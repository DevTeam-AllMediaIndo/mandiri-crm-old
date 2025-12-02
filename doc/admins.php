<?php if(!in_array($user1['ADM_LEVEL'], [1])) die("<script>alert('anda tidak punya akses ke halaman ini'); location.href = '/home.php?page=dashboard';</script>"); ?>
<?php 
/** Create Admin */
if(isset($_POST['submit-create-admin'])) {
    if(!in_array($user1['ADM_LEVEL'], [1])) {
        die("<script>alert('Anda tidak punya akses ke halaman ini'); location.href = '/home.php?page=dashboard';</script>");
    }

    $post = [
        'adm_name'  => "Fullname",
        'adm_user'  => "Username",
        'adm_pass'  => "Password",
        'adm_phone' => "Phone",
        'adm_level' => "Level",
    ];

    foreach($post as $key => $po) {
        if(!in_array($key, ["adm_level"])){
            if(empty($_POST[ $key ])) {
                die("<script>alert('".$po." field is required'); location.href = 'home.php?page=$login_page';</script>");
            }
        }else if(!isset($_POST[ $key ])){
            die("<script>alert('".$po." field is required'); location.href = 'home.php?page=$login_page';</script>");
        }
    }

    $adm_fullname   = form_input($_POST['adm_name']);
    $adm_username   = form_input($_POST['adm_user']);
    $adm_password   = form_input($_POST['adm_pass']);
    $adm_phone      = form_input($_POST['adm_phone']);
    $adm_level      = form_input($_POST['adm_level']);
    $adm_token      = form_input(($_POST['adm_token'] ?? "-"));

    try {
        $sql_check_username = mysqli_query($db, "SELECT ADM_USER FROM tb_admin WHERE LOWER(ADM_USER) = LOWER('".$adm_username."') LIMIT 1");
        if($sql_check_username && mysqli_num_rows($sql_check_username) == 0) {
            $check_password = validation_password($adm_password);
            if($check_password === TRUE) {
                if(in_array($adm_level, [0,1,2,3,4,5,6,7,8,9])) {
                    $insert     = mysqli_query($db, "
                        INSERT INTO tb_admin SET
                        ADM_ID      = (SELECT (MAX(tb2.ADM_ID) + 1) FROM tb_admin as tb2),
                        ADM_USER    = '".$adm_username."',
                        ADM_NAME    = '".$adm_fullname."',
                        ADM_PASS    = '".$adm_password."',
                        ADM_PHONE   = '".$adm_phone."',
                        ADM_TOKEN   = '".$adm_token."',
                        ADM_IP      = '".$ip_visitors."',
                        ADM_LEVEL   = ".$adm_level.",
                        ADM_LEVEL1  = ".$adm_level.",
                        ADM_STS     = -1,
                        ADM_VISIBLE = -1,
                        ADM_TIMESTAMP   = '".date('Y-m-d H:i:s')."'
                    ");

                    if($insert && mysqli_affected_rows($db)) {
                        insert_log($user1['ADM_ID'], "Menambahkan admin baru: ". $adm_username);
                        die("<script>alert('Success create admin'); location.href = 'home.php?page=$login_page'; </script>");
                    }

                    insert_log($user1['ADM_ID'], "Gagal menyimpan data");
                    die("<script>alert('Gagal menyimpan data'); location.href = 'home.php?page=$login_page'; </script>");

                } else {
                    insert_log($user1['ADM_ID'], "Invalid level");
                    die("<script>alert('Invalid level'); location.href = 'home.php?page=$login_page'; </script>");
                }
            } else {
                insert_log($user1['ADM_ID'], ($check_password ?? "Invalid Password"));
                die("<script>alert('".($check_password ?? "Invalid Password")."'); location.href = 'home.php?page=$login_page'; </script>");
            } 
        } else {
            insert_log($user1['ADM_ID'], "Invalid Username");
            die("<script>alert('Invalid Username'); location.href = 'home.php?page=$login_page'; </script>");
        } 

    } catch(Exception $e) {
        insert_log($user1['ADM_ID'], $e->getMessage());
        die("<script>alert(`".$e->getMessage()."`); location.href = 'home.php?page=$login_page'; </script>");
    }
}

/** Submit Edit */
if(isset($_POST['submit-edit-admin'])) {
    if(!in_array($user1['ADM_LEVEL'], [1])) {
        insert_log($user1['ADM_ID'], "Anda tidak punya akses ke halaman ini");
        die("<script>alert('Anda tidak punya akses ke halaman ini'); location.href = '/home.php?page=dashboard';</script>");
    }

    $post = [
        'edit-fullname'  => "Fullname",
        'edit-username'  => "Username",
        'edit-password'  => "Password",
        'edit-phone' => "Phone",
        'edit-level' => "Level",
    ];

    foreach($post as $key => $po) {
        if(empty($_POST[ $key ])) {
            insert_log($user1['ADM_ID'], "$po field is requried");
            die("<script>alert('".$po." field is required'); location.href = 'home.php?page=$login_page';</script>");
        }
    }

    $adm_id         = form_input($_POST['submit-edit-admin']); 
    $adm_fullname   = form_input($_POST['edit-fullname']);
    $adm_username   = form_input($_POST['edit-username']);
    $adm_password   = form_input($_POST['edit-password']);
    $adm_phone      = form_input($_POST['edit-phone']);
    $adm_level      = form_input($_POST['edit-level']);
    $adm_token      = form_input(($_POST['edit-token'] ?? "-"));

    try {
        $sql_check_username = mysqli_query($db, "
            SELECT 
                ADM_USER 
            FROM tb_admin 
            WHERE LOWER(ADM_USER) = LOWER('".$adm_username."') 
            AND MD5(MD5(ADM_ID)) != '".$adm_id."'
            LIMIT 1
        ");
        if($sql_check_username && mysqli_num_rows($sql_check_username) == 0) {
            $check_password = validation_password($adm_password);
            if($check_password === TRUE) {
                if(in_array($adm_level, [1,2,3,4,5,6,7,8,9])) {
                    $update     = mysqli_query($db, "
                        UPDATE tb_admin SET
                            ADM_USER    = '".$adm_username."',
                            ADM_NAME    = '".$adm_fullname."',
                            ADM_PASS    = '".$adm_password."',
                            ADM_PHONE   = '".$adm_phone."',
                            ADM_TOKEN   = '".$adm_token."',
                            ADM_IP      = '".$ip_visitors."',
                            ADM_LEVEL   = ".$adm_level."
                        WHERE MD5(MD5(ADM_ID)) = '".$adm_id."'
                    ");

                    if($update && mysqli_affected_rows($db)) {
                        insert_log($user1['ADM_ID'], "memperbarui data admin: ". $adm_username);
                        die("<script>alert('Berhasil Memperbarui data admin'); location.href = 'home.php?page=$login_page'; </script>");
                    }

                    insert_log($user1['ADM_ID'], "Gagal menyimpan data");
                    die("<script>alert('Gagal menyimpan data'); location.href = 'home.php?page=$login_page'; </script>");

                } else {
                    insert_log($user1['ADM_ID'], "Invalid level");
                    die("<script>alert('Invalid level'); location.href = 'home.php?page=$login_page'; </script>");
                }
            } else {
                insert_log($user1['ADM_ID'], ($check_password ?? "Invalid Password"));
                die("<script>alert('".($check_password ?? "Invalid Password")."'); location.href = 'home.php?page=$login_page'; </script>");
            } 
        } else {
            insert_log($user1['ADM_ID'], "Invalid Username");
            die("<script>alert('Invalid Username'); location.href = 'home.php?page=$login_page'; </script>");
        } 

    } catch(Exception $e) {
        insert_log($user1['ADM_ID'], $e->getMessage());
        die("<script>alert(`".$e->getMessage()."`); location.href = 'home.php?page=$login_page'; </script>");
    }
}

/** ubah status ke nonactive */
if(isset($_GET['nonactive']) && !empty($_GET['nonactive'])) {
    $admin_id = form_input($_GET['nonactive']);

    $sql_get_admin = mysqli_query($db, "SELECT ADM_USER FROM tb_admin WHERE MD5(MD5(ADM_ID)) = '$admin_id' LIMIT 1");
    if(!$sql_get_admin || !mysqli_num_rows($sql_get_admin)) {
        die("<script>alert('Invalid Admin ID'); location.href = 'home.php?page=$login_page'; </script>");
    }

    $admins = mysqli_fetch_assoc($sql_get_admin);
    $update = mysqli_query($db, "UPDATE tb_admin SET ADM_STS = 1 WHERE MD5(MD5(ADM_ID)) = '$admin_id'");
    if($update && mysqli_affected_rows($db)) {
        insert_log($user1['ADM_ID'], "Memperbarui status admin {$admins['ADM_USER']} ke nonactive");
        die("<script>alert('Berhasil memperbarui status admin {$admins['ADM_USER']} ke nonactive'); location.href = 'home.php?page=$login_page'; </script>");
    }

    die("<script>alert('Gagal memperbarui admin'); location.href = 'home.php?page=$login_page'; </script>");
}

/** ubah status ke active */
if(isset($_GET['active']) && !empty($_GET['active'])) {
    $admin_id = form_input($_GET['active']);

    $sql_get_admin = mysqli_query($db, "SELECT ADM_USER FROM tb_admin WHERE MD5(MD5(ADM_ID)) = '$admin_id' LIMIT 1");
    if(!$sql_get_admin || !mysqli_num_rows($sql_get_admin)) {
        die("<script>alert('Invalid Admin ID'); location.href = 'home.php?page=$login_page'; </script>");
    }

    $admins = mysqli_fetch_assoc($sql_get_admin);
    $update = mysqli_query($db, "UPDATE tb_admin SET ADM_STS = -1 WHERE MD5(MD5(ADM_ID)) = '$admin_id'");
    if($update && mysqli_affected_rows($db)) {
        insert_log($user1['ADM_ID'], "Memperbarui status admin {$admins['ADM_USER']} ke active");
        die("<script>alert('Berhasil memperbarui status admin {$admins['ADM_USER']} ke active'); location.href = 'home.php?page=$login_page'; </script>");
    }

    die("<script>alert('Gagal memperbarui status admin'); location.href = 'home.php?page=$login_page'; </script>");
}

/** ubah visible ke 0 */
if(isset($_GET['delete']) && !empty($_GET['delete'])) {
    $admin_id = form_input($_GET['delete']);

    $sql_get_admin = mysqli_query($db, "SELECT ADM_USER FROM tb_admin WHERE MD5(MD5(ADM_ID)) = '$admin_id' LIMIT 1");
    if(!$sql_get_admin || !mysqli_num_rows($sql_get_admin)) {
        die("<script>alert('Invalid Admin ID'); location.href = 'home.php?page=$login_page'; </script>");
    }

    $admins = mysqli_fetch_assoc($sql_get_admin);
    $update = mysqli_query($db, "UPDATE tb_admin SET ADM_STS = 1, ADM_VISIBLE = 0 WHERE MD5(MD5(ADM_ID)) = '$admin_id'");
    if($update && mysqli_affected_rows($db)) {
        insert_log($user1['ADM_ID'], "Menghapus Admin {$admins['ADM_USER']} ");
        die("<script>alert('Berhasil Menghapus Admin {$admins['ADM_USER']}'); location.href = 'home.php?page=$login_page'; </script>");
    }

    die("<script>alert('Gagal menghapus admin'); location.href = 'home.php?page=$login_page'; </script>");
}
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item active" aria-current="page">Create Admins</li>
    </ol>
</nav>

<div class="card mt-3">
    <div class="card-header">
        <h5 class="card-title">Form Create Admin</h5>
    </div>
    <div class="card-body">
        <form action="" method="post">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <div class="form-group">
                        <label for="adm_name" class="form-label">Fullname</label>
                        <input type="text" name="adm_name" id="adm_name" class="form-control" required placeholder="Fullname">
                    </div>
                </div>
    
                <div class="col-md-4 mb-2">
                    <div class="form-group">
                        <label for="adm_user" class="form-label">Username</label>
                        <input type="text" name="adm_user" id="adm_user" class="form-control" required placeholder="Username">
                    </div>
                </div>
    
                <div class="col-md-4 mb-2">
                    <div class="form-group">
                        <label for="adm_pass" class="form-label">Password</label>
                        <input type="password" name="adm_pass" id="adm_pass" class="form-control" required placeholder="Password">
                    </div>
                </div>
    
                <div class="col-md-4 mb-2">
                    <div class="form-group">
                        <label for="adm_phone" class="form-label">Phone</label>
                        <input type="number" name="adm_phone" id="adm_phone" class="form-control" required placeholder="Phone">
                    </div>
                </div>
    
                <div class="col-md-4 mb-2">
                    <div class="form-group">
                        &nbsp;
                    </div>
                </div>
    
                <div class="col-md-4 mb-2">
                    <div class="form-group">
                        <label for="adm_level" class="form-label">Level</label>
                        <select name="adm_level" id="adm_level" class="form-control">
                            <option value="">Select</option>
                            <option value="0">Supervisor</option>
                            <option value="1">All Access</option>
                            <option value="2">WPB Jadwal Temu</option>
                            <option value="3">WPB Verifikator</option>
                            <option value="4">Dealer</option>
                            <option value="5">RND</option>
                            <option value="6">Settlement</option>
                            <option value="7">Accounting</option>
                            <option value="8">UKK APUPPTLN</option>
                            <option value="9">Complient</option>
                        </select>
                    </div>
                </div>
    
                <div class="col-md-12">
                    <button type="submit" name="submit-create-admin" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h5 class="card-title">List Admins</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered" width="100%" id="table-admins">
                <thead>
                    <tr>
                        <th>DateTime</th>
                        <th>User</th>
                        <th>Password</th>
                        <th>Password Change DateTime</th>
                        <th>Next Change Password DateTime</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th class="text-center">#</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalEditAdmin" aria-labelledby="label-modalEditAdmin">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" aria-label="label-modalEditAdmin">Edit Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label for="edit-fullname" class="form-label">Fullname</label>
                                <input type="text" class="form-control" id="edit-fullname" name="edit-fullname" placeholder="Fullname" required>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="form-group">
                                <label for="edit-username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="edit-username" name="edit-username" placeholder="Username" required>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="form-group">
                                <label for="edit-password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="edit-password" name="edit-password" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="form-group">
                                <label for="edit-phone" class="form-label">Phone</label>
                                <input type="number" class="form-control" id="edit-phone" name="edit-phone" placeholder="Phone" required>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="form-group">
                                <label for="edit-level" class="form-label">Level</label>
                                <select name="edit-level" id="edit-level" class="form-control">
                                    <option value="">Select</option>
                                    <option value="1">All Access</option>
                                    <option value="2">WPB Jadwal Temu</option>
                                    <option value="3">WPB Verifikator</option>
                                    <option value="4">Dealer</option>
                                    <option value="5">RND</option>
                                    <option value="6">Settlement</option>
                                    <option value="7">Accounting</option>
                                    <option value="8">UKK APUPPTLN</option>
                                    <option value="9">Complient</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label for="edit-token" class="form-label">Token (optional)</label>
                                <input type="text" class="form-control" id="edit-token" name="edit-token" placeholder="Token">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="submit-edit-admin" id="submit-edit-admin" value="">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $('#table-admins').DataTable( {
            dom: 'Blfrtip',
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "doc/<?php echo $login_page ?>_ajax.php",
                "contentType": "application/json",
                "type": "GET",
                "data": {
                    "usr" : "<?php echo md5(md5($user1["ADM_ID"])) ?>"
                }
            },
            "drawCallback": function( settings ) {
                $('.show-password').on('click', function(btn) {
                    let button = $(btn.currentTarget)
                    let text = prompt('Masukkan password anda');
                    
                    if(text.length) {
                        $.ajax({
                            url: 'ajax/show_password.php',
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                user: "<?php echo md5(md5($user1["ADM_ID"])) ?>",
                                target: $(button).data('id'),
                                password: text
                            }
                        })
                        .done(function(resp) {
                            if(!resp.success) {
                                alert(resp.message)
                                return false
                            }

                            let oldContent = $(button).text()
                            $(button).html(atob(resp.message))
                            setTimeout(() => {
                                $(button).html(oldContent)
                            }, 10000);
                        })
                    }
                })

                $('.btn-edit').on('click', (btn) => {
                    let theButton = $(btn.currentTarget)
                    $('#edit-fullname').val( $(theButton).data('name') )
                    $('#edit-username').val( $(theButton).data('user') )
                    $('#edit-phone').val( $(theButton).data('phone') )
                    $('#edit-level').val( $(theButton).data('level') )
                    $('#submit-edit-admin').val( $(theButton).data('id') )
                    
                    $('#modalEditAdmin').modal('show');
                });
            },
            "deferRender": true,
            "lengthMenu": [[50, 75, 100, -1], [50, 75, 100, "<?= $setting_small ?>"]],
            "scrollX": true,
            "order": [[ 0, "desc" ]]
        });
    })
</script>
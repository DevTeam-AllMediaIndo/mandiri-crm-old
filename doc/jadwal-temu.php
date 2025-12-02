<?php
    if(isset($_GET['action'])){
        if(isset($_GET['id'])){
            $action = mysqli_real_escape_string($db, strip_tags(addslashes($_GET["action"])));
            $id = mysqli_real_escape_string($db, strip_tags(addslashes($_GET["id"])));
            
            $SQL_QUERY = mysqli_query($db, '
                SELECT tb_schedule.ID_SCHD
                FROM tb_schedule
                WHERE MD5(MD5(tb_schedule.ID_SCHD)) = "'.$id.'"
                LIMIT 1
            ');
            if(mysqli_num_rows($SQL_QUERY) > 0) {
                $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
                if($action == 'accept'){
                    $EXEC_SQL = mysqli_query($db, '
                        UPDATE tb_schedule SET
                        tb_schedule.SCHD_STS = -1
                        WHERE tb_schedule.ID_SCHD = '.$RESULT_QUERY['ID_SCHD'].'
                        AND tb_schedule.SCHD_STS = 0
                    ') or die (mysqli_error($db));
                    die ("<script>alert('success ".$action."');location.href = 'home.php?page=".$login_page."'</script>");
                } else if($action == 'reject'){
                    $EXEC_SQL = mysqli_query($db, '
                        UPDATE tb_schedule SET
                        tb_schedule.SCHD_STS = 1
                        WHERE tb_schedule.ID_SCHD = '.$RESULT_QUERY['ID_SCHD'].'
                        AND tb_schedule.SCHD_STS = 0
                    ') or die (mysqli_error($db));
                    die ("<script>alert('success ".$action."');location.href = 'home.php?page=".$login_page."'</script>");
                } else { die ("<script>alert('action unknown');location.href = 'home.php?page=".$login_page."'</script>"); }
            } else { die ("<script>alert('record not found');location.href = 'home.php?page=".$login_page."'</script>"); }
        }
    }

    if(isset($_POST['submit_apply'])){
        if(isset($_POST['id_apply'])){
            if(isset($_POST['ans1'])){
                if(isset($_POST['ans2'])){
                    $id_apply = mysqli_real_escape_string($db, strip_tags(addslashes($_POST["id_apply"])));
                    $ans1 = mysqli_real_escape_string($db, strip_tags(addslashes($_POST["ans1"])));
                    $ans2 = mysqli_real_escape_string($db, strip_tags(addslashes($_POST["ans2"])));
                    
                    $SQL_QUERY = mysqli_query($db, '
                        SELECT 
                            tb_schedule.ID_SCHD,
                            tb_schedule.SCHD_ID
                        FROM tb_schedule
                        WHERE MD5(MD5(tb_schedule.ID_SCHD)) = "'.$id_apply.'"
                        LIMIT 1
                    ');
                    if(mysqli_num_rows($SQL_QUERY) > 0) {
                        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
                        $EXEC_SQL = mysqli_query($db, '
                            UPDATE tb_schedule SET
                            tb_schedule.SCHD_STS = -1
                            WHERE tb_schedule.ID_SCHD = '.$RESULT_QUERY['ID_SCHD'].'
                            AND tb_schedule.SCHD_STS = 0
                        ') or die (mysqli_error($db));

                        $EXEC_SQL = mysqli_query($db, '
                            INSERT INTO tb_scheduledetail SET
                            tb_scheduledetail.ID_SCHD = '.$RESULT_QUERY['ID_SCHD'].',
                            tb_scheduledetail.SCHDT_MBR = '.$RESULT_QUERY['SCHD_ID'].',
                            tb_scheduledetail.SCHDT_ANS1 = "'.$ans1.'",
                            tb_scheduledetail.SCHDT_ANS2 = "'.$ans2.'",
                            tb_scheduledetail.SCHDT_DATETIME = "'.date("Y-m-d H:i:s").'"
                        ') or die (mysqli_error($db));
                        die ("<script>alert('success');location.href = 'home.php?page=".$login_page."'</script>");
                    } else { die ("<script>alert('action unknown');location.href = 'home.php?page=".$login_page."'</script>"); }
                } else { die ("<script>alert('action unknown');location.href = 'home.php?page=".$login_page."'</script>"); }
            } else { die ("<script>alert('action unknown');location.href = 'home.php?page=".$login_page."'</script>"); }
        } else { die ("<script>alert('action unknown');location.href = 'home.php?page=".$login_page."'</script>"); }
    };
    if(isset($_POST['submit_reject'])){
        if(isset($_POST['reason_text'])){
            if(isset($_POST['reason_id'])){
                $reason_text = mysqli_real_escape_string($db, strip_tags(addslashes($_POST["reason_text"])));
                $reason_id = mysqli_real_escape_string($db, strip_tags(addslashes($_POST["reason_id"])));
                
                $EXEC_SQL = mysqli_query($db, '
                    UPDATE tb_schedule SET
                    tb_schedule.SCHD_STS = 1,
                    tb_schedule.SCHD_REASON = "'.$reason_text.'"
                    WHERE tb_schedule.ID_SCHD = '.$reason_id.'
                    AND tb_schedule.SCHD_STS = 0
                ') or die (mysqli_error($db));
                die ("<script>location.href = 'home.php?page=".$login_page."'</script>");
            };
        };
    }
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Jadwal Temu</li>
    </ol>
</nav>
<div class="card mt-3">
    <div class="card-header font-weight-bold">Jadwal yang belum di konfirmasi</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Datetime</th>
                        <th style="vertical-align: middle" class="text-center">Nama</th>
                        <th style="vertical-align: middle" class="text-center">Nomor telepon</th>
                        <!-- <th style="vertical-align: middle" class="text-center">Nomor Demoacc</th>
                        <th style="vertical-align: middle" class="text-center">Alamat</th> -->
                        <th style="vertical-align: middle" class="text-center">Email</th>
                        <!-- <th style="vertical-align: middle" class="text-center">Jenis identitas</th> -->
                        <!-- <th style="vertical-align: middle" class="text-center">No.identitas</th> -->
                        <th style="vertical-align: middle" class="text-center">Tanggal Lahir</th>
                        <th style="vertical-align: middle" class="text-center">Tanggal temu</th>
                        <th style="vertical-align: middle" class="text-center">Jam temu</th>
                        <th style="vertical-align: middle" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                                            
                        $SQL_QUERY = mysqli_query($db, '
                            SELECT
                                tb_schedule.ID_SCHD,
                                tb_schedule.SCHD_DATETIME,
                                tb_member.MBR_NAME,
                                tb_member.MBR_PHONE,
                                tb_schedule.SCHD_DEMO,
                                tb_member.MBR_ADDRESS,
                                tb_member.MBR_EMAIL,
                                tb_member.MBR_TYPE_IDT,
                                tb_member.MBR_NO_IDT,
                                DATE(tb_member.MBR_TGLLAHIR) AS MBR_TGLLAHIR,
                                tb_schedule.SCHD_DEVICE,
                                IF(tb_schedule.SCHD_STS = -1, "Accept",
                                    IF(tb_schedule.SCHD_STS = 1, "Reject", "Unknown")
                                ) AS SCHD_STS,
                                tb_schedule.SCHD_TANGGAL,
                                tb_schedule.SCHD_JAM,
                                tb_schedule.SCHD_DETAIL
                            FROM tb_schedule
                            JOIN tb_member
                            ON (tb_member.MBR_ID = tb_schedule.SCHD_ID)
                            WHERE tb_schedule.SCHD_STS = 0
                            ORDER BY tb_schedule.SCHD_DATETIME DESC
                        ');
                        if(mysqli_num_rows($SQL_QUERY) > 0){
                            while($RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY)){
                                if($RESULT_QUERY['SCHD_DETAIL'] == 0){
                                    $SCHD_DETAIL_CSS = 'font-weight: bold;';
                                } else {
                                    $SCHD_DETAIL_CSS = 'font-weight: normal;';
                                }
                    ?>
                    <tr style="<?php echo $SCHD_DETAIL_CSS; ?>">
                        <td style="vertical-align:middle;"><div class='text-center'><?php echo $RESULT_QUERY['SCHD_DATETIME']?></div></td>
                        <td style="vertical-align:middle;"><?php echo $RESULT_QUERY['MBR_NAME']?></td>
                        <td style="vertical-align:middle;"><?php echo $RESULT_QUERY['MBR_PHONE']?></td>
                        <!-- <td style="vertical-align:middle;"><?php echo $RESULT_QUERY['SCHD_DEMO']?></td> -->
                        <!-- <td style="vertical-align:middle;"><?php echo $RESULT_QUERY['MBR_ADDRESS']?></td> -->
                        <td style="vertical-align:middle;"><?php echo $RESULT_QUERY['MBR_EMAIL']?></td>
                        <!-- <td style="vertical-align:middle;"><?php echo $RESULT_QUERY['MBR_TYPE_IDT']?></td> -->
                        <!-- <td style="vertical-align:middle;"><?php echo $RESULT_QUERY['MBR_NO_IDT']?></td> -->
                        <td style="vertical-align:middle;" class="text-center"><?php echo $RESULT_QUERY['MBR_TGLLAHIR']?></td>
                        <td style="vertical-align:middle;" class="text-center"><?php echo $RESULT_QUERY['SCHD_TANGGAL']?></td>
                        <td style="vertical-align:middle;"><?php echo $RESULT_QUERY['SCHD_JAM']?></td>
                        <td style="vertical-align:middle;" class="text-center">
                            <!-- <a class="btn btn-success btn-sm" href="home.php?page=jadwal-temu&action=accept&id=<?php echo md5(md5($RESULT_QUERY['ID_SCHD'])) ?>">Apply</a> -->
                            <!-- <button class="btn btn-info btn-sm userinfo" data-id="<?php echo md5(md5($RESULT_QUERY['ID_SCHD'])) ?>">Apply</button>&nbsp;|&nbsp; -->
                            <?php if($user1["ADM_LEVEL"] != 0){?>
                                <a href="home.php?page=doc_g1&x=<?php echo md5(md5($RESULT_QUERY['ID_SCHD']))?>" class="btn btn-info btn-sm userinfo">Detail</a>
                            <?php }?>
                            <!-- <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal_reject<?php echo md5(md5($RESULT_QUERY['ID_SCHD'])) ?>" >Reject</button> -->
                            <!--- <a class="btn btn-danger btn-sm" href="home.php?page=jadwal-temu&action=reject&id=<?php echo md5(md5($RESULT_QUERY['ID_SCHD'])) ?>">Reject</a> -->
                        </td>
                    </tr>
                    <div class="modal fade" id="modal_reject<?php echo md5(md5($RESULT_QUERY['ID_SCHD'])) ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <form method="post">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Reject</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Keterangan Reject</label>
                                            <input type="text" class="form-control" name="reason_text" required>
                                            <input type="hidden" class="form-control" value="<?php echo $RESULT_QUERY['ID_SCHD'] ?>" name="reason_id" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="submit_reject" class="btn btn-danger">Reject</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php }; } else { ?>
                        <tr><td colspan="12" class="text-center">No data available in table</td></tr>
                    <?php }; ?>
                    <!-- <div class="modal fade" id="modal_apply" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <form method="post">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Apply</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id_apply" id="id_apply" value="" required>
                                        <div class="form-group">
                                            <label>Pertanyaan 1</label>
                                            <input type="text" class="form-control" name="ans1" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Pertanyaan 2</label>
                                            <input type="text" class="form-control" name="ans2" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="submit_apply" class="btn btn-success">Apply</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div> -->
                    <script>
                        $(document).ready(function(){
                            $('.userinfo').click(function(){                            
                            var userid = $(this).data('id');
                                // AJAX request
                                $.ajax({
                                    url: 'doc/jadwal-temu_ajax1.php',
                                    type: 'post',
                                    data: {userid: userid},
                                    success: function(response){
                                        document.getElementById("id_apply").value = userid;
                                        $('#modal_apply').modal('show'); 
                                    }
                                });
                            });
                        });
                    </script>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card mt-3">
    <div class="card-header font-weight-bold">Status confirmasi jadwal temu</div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Datetime</th>
                        <th style="vertical-align: middle" class="text-center">Nama</th>
                        <th style="vertical-align: middle" class="text-center">Nomor telepon</th>
                        <!-- <th style="vertical-align: middle" class="text-center">Nomor Demoacc</th> -->
                        <!-- <th style="vertical-align: middle" class="text-center">Alamat</th> -->
                        <th style="vertical-align: middle" class="text-center">Email</th>
                        <!-- <th style="vertical-align: middle" class="text-center">Jenis identitas</th> -->
                        <!-- <th style="vertical-align: middle" class="text-center">No.identitas</th> -->
                        <th style="vertical-align: middle" class="text-center">Tanggal Lahir</th>
                        <th style="vertical-align: middle" class="text-center">Tanggal temu</th>
                        <th style="vertical-align: middle" class="text-center">Jam temu</th>
                        <th style="vertical-align: middle" class="text-center">Status</th>
                        <th style="vertical-align: middle" class="text-center">Keterangan</th>
                        <th style="vertical-align: middle" class="text-center">Detail</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#table').DataTable( {
            dom: 'Blfrtip',
            "processing": true,
            "serverSide": true,
            "ajax": "doc/<?php echo $login_page ?>_ajax.php",
            "deferRender": true,
            "lengthMenu": [[50, 75, 100, -1], [50, 75, 100, "<?= $setting_small ?>"]],
            "scrollX": true,
            "order": [[ 0, "desc" ]],
            "drawCallback": function(tbl){
                pageReload();
            }
        } );
    } );
</script>
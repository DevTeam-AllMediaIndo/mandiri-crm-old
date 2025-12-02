<?php
    if(isset($_POST['submit_apply'])){
        if(isset($_POST['ib_name'])){
            if(isset($_POST['ib_code'])){
                if(isset($_POST['ib_city'])){

                    $ib_name = form_input($_POST['ib_name']);
                    $ib_code = form_input($_POST['ib_code']);
                    $ib_city = form_input($_POST['ib_city']);

                    
                    $SRCH_SQL2 = mysqli_query($db,'
                        SELECT
                            tb_ib.IB_CITY
                        FROM tb_ib
                        WHERE tb_ib.IB_CITY = "'.$ib_city.'"
                        LIMIT 1
                    ') or die(mysqli_error($db));
                    if(mysqli_num_rows($SRCH_SQL2) < 1){
                        
                        $EXEC_SQL = mysqli_query($db,'
                            INSERT INTO tb_ib SET
                            tb_ib.IB_ID = UNIX_TIMESTAMP(NOW())+(SELECT IFNULL(MAX(tb1.ID_IB),0) FROM tb_ib tb1),
                            tb_ib.IB_NAME = "'.$ib_name.'",
                            tb_ib.IB_CODE = "'.$ib_code.'",
                            tb_ib.IB_CITY = "'.$ib_city.'",
                            tb_ib.IB_STS = -1,
                            tb_ib.IB_DATETIME = "'.date("Y-m-d H:i:s").'",
                            tb_ib.IB_TIMESTAMP = "'.date("Y-m-d H:i:s").'"
                        ') or die(mysqli_error($db));
                        die("<script>alert('Success Insert Data');location.href='home.php?page=ib'</script>");

                    }else{ die("<script>alert('City Ini Telah Terdaftar');location.href='home.php?page=ib'</script>"); };
                    
                }else{die("<script>alert('No code');location.href='home.php?page=ib'</script>");};
            }else{die("<script>alert('No code');location.href='home.php?page=ib'</script>");};
        }else{die("<script>alert('No name');location.href='home.php?page=ib'</script>");};
    }
    if(isset($_POST['submit_update'])){
        if(isset($_POST['ib_x_update'])){
            if(isset($_POST['ib_name_update'])){
                if(isset($_POST['ib_code_update'])){
                    if(isset($_POST['ib_city_update'])){

                        $ib_x_update = form_input($_POST['ib_x_update']);
                        $ib_name_update = form_input($_POST['ib_name_update']);
                        $ib_code_update = form_input($_POST['ib_code_update']);
                        $ib_city_update = form_input($_POST['ib_city_update']);

                        $SRCH_SQL1 = mysqli_query($db,'
                            SELECT
                                tb_ib.IB_CODE
                            FROM tb_ib
                            WHERE tb_ib.IB_CODE = "'.$ib_code_update.'"
                            LIMIT 1
                        ') or die(mysqli_error($db));
                        $SRCH_SQL2 = mysqli_query($db,'
                            SELECT
                                tb_ib.IB_CITY
                            FROM tb_ib
                            WHERE tb_ib.IB_CITY = "'.$ib_city_update.'"
                            LIMIT 1
                        ') or die(mysqli_error($db));
                        if(mysqli_num_rows($SRCH_SQL2) < 1){
                            
                            $EXEC_SQL = mysqli_query($db,'
                                UPDATE tb_ib SET
                                    tb_ib.IB_NAME = "'.$ib_name_update.'",
                                    tb_ib.IB_CODE = "'.$ib_code_update.'",
                                    tb_ib.IB_CITY = "'.$ib_city_update.'",
                                    tb_ib.IB_TIMESTAMP = "'.date("Y-m-d H:i:s").'"
                                WHERE MD5(MD5(tb_ib.ID_IB)) = "'.$ib_x_update.'"
                            ') or die(mysqli_error($db));
                            die("<script>alert('Success Update Data');location.href='home.php?page=ib'</script>");

                        }else{ die("<script>alert('City Ini Telah Terdaftar');location.href='home.php?page=ib'</script>"); };
                    }else{die("<script>alert('No code');location.href='home.php?page=ib'</script>");};
                }else{die("<script>alert('No code');location.href='home.php?page=ib'</script>");};
            }else{die("<script>alert('No name');location.href='home.php?page=ib'</script>");};
        }else{die("<script>alert('No Data');location.href='home.php?page=ib'</script>");};
    }

?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item active" aria-current="page">IB</li>
        <?php if($user1["ADM_LEVEL"] != 0){ ?>
            <button data-target="#modal_insert" data-toggle="modal" class="btn btn-sm btn-success plus float-right text-white" style="margin-left: auto;">Tambahkan Data <i class="fa fa-plus" aria-hidden="true"></i></button>
            <div class="modal fade" id="modal_insert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <form method="post">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Form Penambahan IB</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- <input type="hidden" name="id_apply" id="id_apply" value="" required> -->
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" class="form-control text-center" id="apply_name" name="ib_name" required autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <label>Kode IB</label>
                                            <input type="text" class="form-control text-center" id="apply_code" name="ib_code" required autocomplete="off">
                                        </div>
                                        <div class="col-6">
                                            <label>City</label>
                                            <select class="form-control text-center" name="ib_city" required>
                                                <option value="">Silahkan pilih IB</option>
                                                <?php
                                                    $SCRH_QUER = mysqli_query($db,'
                                                        SELECT
                                                            MT4_USERS.CITY
                                                        FROM MT4_USERS
                                                        WHERE MT4_USERS.CITY <> ""
                                                        GROUP BY MT4_USERS.CITY
                                                    ');
                                                    if(mysqli_num_rows($SCRH_QUER) > 0){
                                                        while($RESULT_SRCH = mysqli_fetch_assoc($SCRH_QUER)){
                                                ?>
                                                    <option value="<?php echo $RESULT_SRCH["CITY"]; ?>"><?php echo $RESULT_SRCH["CITY"]; ?></option>
                                                <?php
                                                
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="submit_apply" class="btn btn-success">Insert</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <?php }?>
    </ol>
</nav>
<div class="card mt-3">
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Date Time</th>
                        <th style="vertical-align: middle" class="text-center">Name</th>
                        <th style="vertical-align: middle" class="text-center">IB Code</th>
                        <th style="vertical-align: middle" class="text-center">City</th>
                        <th style="vertical-align: middle" class="text-center">Date Update</th>
                        <th style="vertical-align: middle" class="text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<?php
    $SQL_QUERY = mysqli_query($db,'
        SELECT
            tb_ib.IB_DATETIME,
            tb_ib.IB_NAME,
            tb_ib.IB_CODE,
            tb_ib.IB_CITY,
            tb_ib.IB_TIMESTAMP,
            MD5(MD5(tb_ib.ID_IB)) AS ID_IB  
        FROM tb_ib
        WHERE tb_ib.IB_STS = -1
    ') or die(mysqli_erro($db));
    if(mysqli_num_rows($SQL_QUERY) > 0 && $user1["ADM_LEVEL"] != 0){
        while($RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY)){

    
?>
    <div class="modal fade" id="modal_update<?php echo $RESULT_QUERY["ID_IB"]?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form method="post">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Form Update Data IB</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- <input type="hidden" name="id_apply" id="id_apply" value="" required> -->
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" value="<?php echo $RESULT_QUERY["IB_NAME"]?>" class="form-control text-center" id="update_name" name="ib_name_update" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label>Kode IB</label>
                                    <input type="text" value="<?php echo $RESULT_QUERY["IB_CODE"]?>" class="form-control text-center" id="update_code" name="ib_code_update" required autocomplete="off">
                                </div>
                                <div class="col-6">
                                    <label>City</label>
                                    <input type="text" value="<?php echo $RESULT_QUERY["IB_CITY"]?>" class="form-control text-center" id="update_city" name="ib_city_update" required autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" value="<?php echo $RESULT_QUERY["ID_IB"]?>" name="ib_x_update">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit_update" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php
  };
};
?>

<script>
    $(document).ready(function() {
        $('#table').DataTable( {
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
            "deferRender": true,
            "lengthMenu": [[50, 75, 100, -1], [50, 75, 100, "<?= $setting_small ?>"]],
            "scrollX": true,
            "order": [[ 0, "asc" ]]
        } );
    } );
    
    // $(document).ready(function(){
    //     loadData();
    // });

    // function loadData(){
    //     $.get('doc/<?php echo $login_page ?>_ajax.php',function(data){
    //         $('.updateData').click(function(e){
    //             e.preventDefault();
    //             $('[name=ib_name_update]').val($(this).attr('gavl_nama'));
    //             $('[name=ib_code_update]').val($(this).attr('gavl_code'));
    //             $('[name=ib_city_update]').val($(this).attr('gavl_city'));
    //             $('[name=ib_x_update]').val($(this).attr('gavl_x'));
    //         });
    //     })
    // };
    
</script>
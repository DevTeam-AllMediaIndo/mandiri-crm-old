<?php
    if(isset($_GET['x'])){
        $x = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_GET['x']))));
        
        if(isset($_GET['action'])){
            $action = mysqli_real_escape_string($db, stripslashes(strip_tags($_GET["action"])));
    
            if($action == 'confirm'){
                $id = mysqli_real_escape_string($db, stripslashes(strip_tags($_GET["x"])));
                
                $SQL_QUERY = mysqli_query($db, '
                    SELECT ACC_WPCHECK
                    FROM tb_racc
                    WHERE MD5(MD5(ID_ACC)) = "'.$id.'"
                    LIMIT 1
                ');
                if(mysqli_num_rows($SQL_QUERY) > 0) {
                    $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
                    if($RESULT_QUERY['ACC_WPCHECK'] == 0){
                        $ACC_WPCHECK = 1;
                        $EXEC_SQL = mysqli_query($db, '
                            UPDATE tb_racc SET
                            tb_racc.ACC_WPCHECK = "'.$ACC_WPCHECK.'"
                            WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id.'"
                            AND tb_racc.ACC_WPCHECK = "'.$RESULT_QUERY['ACC_WPCHECK'].'"
                        ') or die (mysqli_error($db));
                        die ("<script>alert('success change status user');location.href = 'home.php?page=member_wp'</script>");
                    } else {
                        $ACC_WPCHECK = 0;
                        $EXEC_SQL = mysqli_query($db, '
                            UPDATE tb_racc SET
                            tb_racc.ACC_WPCHECK = "'.$ACC_WPCHECK.'"
                            WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id.'"
                            AND tb_racc.ACC_WPCHECK = "'.$RESULT_QUERY['ACC_WPCHECK'].'"
                        ') or die (mysqli_error($db));
                        die ("<script>alert('success change status user');location.href = 'home.php?page=member_wp'</script>");
                    };
                } else { die ("<script>alert('User Unknown');location.href = 'home.php?page=".$login_page."'</script>"); };
            } else { die ("<script>alert('Please Try Again1');location.href = 'home.php?page=".$login_page."'</script>"); };
        } else { die ("<script>alert('Please Try Again2');location.href = 'home.php?page=".$login_page."'</script>");};
    };
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Member</a></li>
        <li class="breadcrumb-item active" aria-current="page">WP Confirm</li>
    </ol>
</nav>
<div class="card">
    <div class="card-header font-weight-bold">Daftar Real Account</div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Date Reg</th>
                        <th style="vertical-align: middle" class="text-center">Tipe</th>
                        <th style="vertical-align: middle" class="text-center">Produk</th>
                        <th style="vertical-align: middle" class="text-center">Nama</th>
                        <th style="vertical-align: middle" class="text-center">Email</th>
                        <th style="vertical-align: middle" class="text-center">Rate</th>
                        <th style="vertical-align: middle" class="text-center">#</th>
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
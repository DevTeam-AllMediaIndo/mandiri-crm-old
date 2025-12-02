<?php
    if(isset($_POST['submit_reply'])){
        if(isset($_POST['x'])){
            if(isset($_POST['reply'])){
                $x = form_input($_POST['x']);
                $reply = form_input($_POST['reply']);

                $EXEC_SQL = mysqli_query($db,'
                    UPDATE tb_ticket SET
                        tb_ticket.TCKT_KONTEN_ADM = "'.$reply.'",
                        tb_ticket.TCKT_ADM = '.$user1['ADM_ID'].',
                        tb_ticket.TCKT_DATETIME_ADM = "'.date('Y-m-d H:i:s').'"
                    WHERE MD5(MD5(tb_ticket.ID_TCKT)) = "'.$x.'"
                ') or die(mysqli_error($db));
                die("<script>alert('Success Reply');location.href = 'home.php?page=ticket'</script>");

            }else{ die("<script>alert('No Reply');location.href = 'home.php?page=ticket'</script>"); };
        }else{ die("<script>alert('No Data');location.href = 'home.php?page=ticket'</script>"); };
    }
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ticket</li>
    </ol>
</nav>
<div class="card">
    <div class="card-header font-weight-bold">Pending Answer Ticket</div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="pending_table" class="table table-striped table-hover table-bordered" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Date</th>
                        <th style="vertical-align: middle" class="text-center">Name</th>
                        <th style="vertical-align: middle" class="text-center">Email</th>
                        <th style="vertical-align: middle" class="text-center">Ticket</th>
                        <th style="vertical-align: middle" class="text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $SQL_QUERY = mysqli_query($db,'
                        SELECT
                            tb_ticket.TCKT_DATETIME_MBR,
                            tb_member.MBR_NAME,
                            tb_member.MBR_EMAIL,
                            tb_ticket.TCKT_KONTEN_MBR,
                            MD5(MD5(tb_ticket.ID_TCKT)) AS ID_TCKT 
                        FROM tb_ticket
                        JOIN tb_member
                        ON (tb_ticket.TCKT_MBR = tb_member.MBR_ID)
                        WHERE tb_ticket.TCKT_KONTEN_ADM IS NULL
                        ') or die(mysqli_error($db));
                        if(mysqli_num_rows($SQL_QUERY) > 0){
                            while($RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY)){          
                    ?>
                        <tr>
                            <td><?php echo $RESULT_QUERY['TCKT_DATETIME_MBR'] ?></td>
                            <td><?php echo $RESULT_QUERY['MBR_NAME'] ?></td>
                            <td><?php echo $RESULT_QUERY['MBR_EMAIL'] ?></td>
                            <td><?php echo  str_replace('\r\n','<br>',$RESULT_QUERY['TCKT_KONTEN_MBR'])?></td>
                            <td>
                                <div class="text-center"><button data-target="#modal_insert" data-toggle="modal" class="btn btn-sm btn-success text-white">Reply</button></div>
                            </td>
                        </tr>
                    <?php
                            };
                        };
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
    $SQL_QUERY = mysqli_query($db,'
        SELECT
            tb_ticket.TCKT_DATETIME_MBR,
            tb_member.MBR_NAME,
            tb_member.MBR_EMAIL,
            tb_ticket.TCKT_KONTEN_MBR,
            MD5(MD5(tb_ticket.ID_TCKT)) AS ID_TCKT 
        FROM tb_ticket
        JOIN tb_member
        ON (tb_ticket.TCKT_MBR = tb_member.MBR_ID)
        WHERE tb_ticket.TCKT_KONTEN_ADM = 0
    ') or die(mysqli_error($db));
    if(mysqli_num_rows($SQL_QUERY) > 0){
        while($RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY)){          
?>
<div class="modal fade" id="modal_insert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="post">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Reply Complaint</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- <input type="hidden" name="id_apply" id="id_apply" value="" required> -->
                    <div class="form-group">
                        <label>Nama Nasabah</label>
                        <input type="text" class="form-control text-center" id="apply_name" value="<?php echo $RESULT_QUERY['MBR_NAME'] ?>" name="name" required autocomplete="off" disabled>
                    </div>
                    <div class="form-group">
                        <label>Complaint</label>
                        <textarea class="form-control text-center" rows="3" disabled><?php echo $RESULT_QUERY['TCKT_KONTEN_MBR'] ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Reply</label>
                        <textarea class="form-control" rows="3" id="<?php echo $RESULT_QUERY['ID_TCKT'] ?>" name="reply" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="x" value="<? echo $RESULT_QUERY['ID_TCKT'] ?>">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit_reply" class="btn btn-success">Reply</button>
                </div>
            </div>
        </div>
    </form>
</div>
<?php
        };
    };
?>
<div class="card mt-2">
    <div class="card-header font-weight-bold">Success Answer Ticket</div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center" rowspan="2">Member Date Time</th>
                        <th style="vertical-align: middle" class="text-center" colspan="4">Nasabah</th>
                        <th style="vertical-align: middle" class="text-center" colspan="4">Admin</th>
                        <th style="vertical-align: middle" class="text-center" rowspan="2">Admin Date Time</th>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Username</th>
                        <th style="vertical-align: middle" class="text-center">Name</th>
                        <th style="vertical-align: middle" class="text-center">Email</th>
                        <th style="vertical-align: middle" class="text-center">Complaint</th>
                        <th style="vertical-align: middle" class="text-center">Reply</th>
                        <th style="vertical-align: middle" class="text-center">Status</th>
                        <th style="vertical-align: middle" class="text-center">Name</th>
                        <th style="vertical-align: middle" class="text-center">Username</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<!-- <script>
    $(document).ready(function() {
        $('#table').DataTable( {
            dom: 'Blfrtip',
            "processing": true,
            "serverSide": true,
            "ajax": "doc/<?php echo $login_page ?>_ajax.php",
            "deferRender": true,
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "scrollX": true,
            "order": [[ 0, "desc" ]]
        } );
    } );
</script> -->
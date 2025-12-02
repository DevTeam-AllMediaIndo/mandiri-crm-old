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
                        <th style="vertical-align: middle" class="text-center">No. Account</th>
                        <th style="vertical-align: middle" class="text-center">Picture</th>
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
                                tb_racc.LOGIN,
                                tb_ticket.TCKT_FILE,
                                MD5(MD5(tb_ticket.ID_TCKT)) AS ID_TCKT 
                            FROM tb_ticket
                            JOIN tb_member ON (tb_ticket.TCKT_MBR = tb_member.MBR_ID)
                            LEFT JOIN (
                                SELECT 
                                    ACC_MBR,
                                    GROUP_CONCAT(tb_racc.ACC_LOGIN SEPARATOR ",") as LOGIN
                                FROM tb_racc 
                                WHERE ACC_LOGIN != 0
                                AND ACC_WPCHECK = 6
                                AND ACC_DERE = 1
                                GROUP BY ACC_MBR
                            ) as tb_racc ON (tb_racc.ACC_MBR = tb_member.MBR_ID)
                            WHERE tb_ticket.TCKT_KONTEN_ADM IS NULL
                            ORDER BY tb_ticket.TCKT_DATETIME_MBR DESC
                        ') or die(mysqli_error($db));
                        if(mysqli_num_rows($SQL_QUERY) > 0){
                            while($RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY)){          
                    ?>
                        <tr>
                            <td><?php echo $RESULT_QUERY['TCKT_DATETIME_MBR'] ?></td>
                            <td><?php echo $RESULT_QUERY['MBR_NAME'] ?></td>
                            <td><?php echo $RESULT_QUERY['MBR_EMAIL'] ?></td>
                            <td><?php echo  str_replace('\r\n','<br>',$RESULT_QUERY['TCKT_KONTEN_MBR']);?></td>
                            <td><?php echo $RESULT_QUERY['LOGIN'] ?></td>
                            <?php //if($user1["ADM_LEVEL"] == 3 || $user1["ADM_LEVEL"] == 1){ ?>
                            <?php if(in_array($user1["ADM_LEVEL"], [1, 3, 9])){ ?>
                                <td>
                                    <?php if($RESULT_QUERY['TCKT_FILE'] > "0"){?>
                                        <div class="text-center">
                                            <a target="_blank" href="https://allmediaindo-2.s3.ap-southeast-1.amazonaws.com/mandirifx/<?php echo $RESULT_QUERY['TCKT_FILE']?>">
                                                Open
                                            </a>
                                        </div>
                                    <?php };?>
                                </td>
                                <td>
                                    <?php if($user1["ADM_LEVEL"] != 0){ ?>
                                        <div class="text-center"><button data-target="#modal_insert<?php echo $RESULT_QUERY['ID_TCKT']?>" data-toggle="modal" class="btn btn-sm btn-success text-white">Reply</button></div>
                                    <?php }?>
                                </td>
                            <?php }else{?>
                                <td></td>
                                <td></td>
                            <?php };?>
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
            tb_ticket.TCKT_LOGIN,
            MD5(MD5(tb_ticket.ID_TCKT)) AS ID_TCKT,
            IFNULL((
                SELECT MD5(MD5(tb_racc.ID_ACC))
                FROM tb_racc
                WHERE tb_racc.ACC_LOGIN = "0"
                AND tb_racc.ACC_DERE = 1
                AND tb_racc.ACC_WPCHECK <> 6
                AND tb_racc.ACC_MBR = tb_ticket.TCKT_MBR
            ), "0") AS ID_ACC
        FROM tb_ticket
        JOIN tb_member ON (tb_ticket.TCKT_MBR = tb_member.MBR_ID)
        WHERE tb_ticket.TCKT_KONTEN_ADM IS NULL
    ') or die(mysqli_error($db));
    if(mysqli_num_rows($SQL_QUERY) > 0 && $user1["ADM_LEVEL"] != 0){
        while($RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY)){          
?>
<div class="modal fade" id="modal_insert<?php echo $RESULT_QUERY['ID_TCKT'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <input type="text" class="form-control" id="apply_name" value="<?php echo $RESULT_QUERY['MBR_NAME'] ?>" name="name" required autocomplete="off" disabled>
                    </div>
                    <div class="form-group">
                        <label>Nomer Account</label>
                        <input type="number" class="form-control text-center" id="apply_name" value="<?php echo $RESULT_QUERY['TCKT_LOGIN'] ?>" autocomplete="off" disabled readonly>
                    </div>
                    <div class="form-group">
                        <label>Complaint</label>
                        <textarea class="form-control" rows="3" disabled><?php echo  str_replace('\r\n', PHP_EOL ,$RESULT_QUERY['TCKT_KONTEN_MBR']);?> <?php if(strlen($RESULT_QUERY['TCKT_LOGIN']) > 3){ echo $RESULT_QUERY['TCKT_LOGIN']; } ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Reply</label>
                        <textarea class="form-control" rows="3" id="<?php echo $RESULT_QUERY['ID_TCKT'] ?>" name="reply" required></textarea>
                    </div>
                    <a href="home.php?page=member_realacc_edit&id=<?php echo $RESULT_QUERY['ID_ACC'] ?>">Rubah data pendaftaran</a>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="x" value="<?php echo $RESULT_QUERY['ID_TCKT'] ?>">
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
                        <th style="vertical-align: middle" class="text-center">No.Account</th>
                        <th style="vertical-align: middle" class="text-center">Picture</th>
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
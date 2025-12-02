<?php
include_once('../vendor/autoload.php');
use Slim\Csrf\Guard;
use Slim\Psr7\Factory\ResponseFactory;
$responseFactory = new ResponseFactory(); // Note that you will need to import
$guard = new Guard($responseFactory);

$csrfNameKey = $guard->getTokenNameKey();
$csrfValueKey = $guard->getTokenValueKey();

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Verificator ============================
        if(isset($_POST['accept_1']) && isset($_POST['id']) && isset($_POST['note'])){
            $id = form_input($_POST['id']);
            $note = form_input($_POST['note']);

            mysqli_query($db, '
                UPDATE tb_dpwd SET
                tb_dpwd.DPWD_STSACC = -1,
                tb_dpwd.DPWD_NOTE1 = "'.$note.'",
                tb_dpwd.DPWD_DATETIME1 = "'.date('Y-m-d H:i:s').'"
                WHERE MD5(MD5(tb_dpwd.ID_DPWD)) = "'.$id.'"
                AND tb_dpwd.DPWD_TYPE = 1
                AND tb_dpwd.DPWD_STSACC = 0
            ') or die ("<script>alert('Please try again, or contact support.');location.href = 'home.php?page=".$login_page."'</script>");
            
            $SQL_QUERY = mysqli_query($db,'SELECT tb_dpwd.DPWD_MBR FROM tb_dpwd WHERE MD5(MD5(tb_dpwd.ID_DPWD)) = "'.$id.'" LIMIT 1');
            if(mysqli_num_rows($SQL_QUERY) > 0){
                $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
                insert_log($RESULT_QUERY['DPWD_MBR'], 'accept Deposit Verificator');
            };
            die ("<script>alert('success accept Verificator');location.href = 'home.php?page=".$login_page."'</script>");
        };
        if(isset($_POST['reject_1']) && isset($_POST['id']) && isset($_POST['note'])){
            $id = form_input($_POST['id']);
            $note = form_input($_POST['note']);

            mysqli_query($db, '
                UPDATE tb_dpwd SET
                tb_dpwd.DPWD_STSACC = 1,
                tb_dpwd.DPWD_STSVER = 1,
                tb_dpwd.DPWD_STS = 1,
                tb_dpwd.DPWD_NOTE1 = "'.$note.'",
                tb_dpwd.DPWD_DATETIME1 = "'.date('Y-m-d H:i:s').'"
                WHERE MD5(MD5(tb_dpwd.ID_DPWD)) = "'.$id.'"
                AND tb_dpwd.DPWD_TYPE = 1
                AND tb_dpwd.DPWD_STSACC = 0
            ') or die ("<script>alert('Please try again, or contact support.');location.href = 'home.php?page=".$login_page."'</script>");
            
            $SQL_QUERY = mysqli_query($db,'SELECT tb_dpwd.DPWD_MBR FROM tb_dpwd WHERE MD5(MD5(tb_dpwd.ID_DPWD)) = "'.$id.'" LIMIT 1');
            if(mysqli_num_rows($SQL_QUERY) > 0){
                $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
                insert_log($RESULT_QUERY['DPWD_MBR'], 'reject Deposit Verificator');
            };
            die ("<script>alert('success reject Verificator');location.href = 'home.php?page=".$login_page."'</script>");
        };
        
        //Finance ============================
        if(isset($_POST['accept_2']) && isset($_POST['id']) && isset($_POST['note'])){
            $id = form_input($_POST['id']);
            $note = form_input($_POST['note']);

            mysqli_query($db, '
                UPDATE tb_dpwd SET
                tb_dpwd.DPWD_STSVER = -1,
                tb_dpwd.DPWD_NOTE1 = "'.$note.'",
                tb_dpwd.DPWD_DATETIME2 = "'.date('Y-m-d H:i:s').'"
                WHERE MD5(MD5(tb_dpwd.ID_DPWD)) = "'.$id.'"
                AND tb_dpwd.DPWD_TYPE = 1
                AND tb_dpwd.DPWD_STSVER = 0
            ') or die ("<script>alert('Please try again, or contact support.');location.href = 'home.php?page=".$login_page."'</script>");
            
            $SQL_QUERY = mysqli_query($db,'SELECT tb_dpwd.DPWD_MBR FROM tb_dpwd WHERE MD5(MD5(tb_dpwd.ID_DPWD)) = "'.$id.'" LIMIT 1');
            if(mysqli_num_rows($SQL_QUERY) > 0){
                $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
                insert_log($RESULT_QUERY['DPWD_MBR'], 'accept Deposit Finance');
            };
            die ("<script>alert('success accept Finance');location.href = 'home.php?page=".$login_page."'</script>");
        };
        if(isset($_POST['reject_2']) && isset($_POST['id']) && isset($_POST['note'])){
            $id = form_input($_POST['id']);
            $note = form_input($_POST['note']);

            mysqli_query($db, '
                UPDATE tb_dpwd SET
                tb_dpwd.DPWD_STSVER = 1,
                tb_dpwd.DPWD_STS = 1,
                tb_dpwd.DPWD_NOTE1 = "'.$note.'",
                tb_dpwd.DPWD_DATETIME2 = "'.date('Y-m-d H:i:s').'"
                WHERE MD5(MD5(tb_dpwd.ID_DPWD)) = "'.$id.'"
                AND tb_dpwd.DPWD_TYPE = 1
                AND tb_dpwd.DPWD_STSVER = 0
            ') or die ("<script>alert('Please try again, or contact support.');location.href = 'home.php?page=".$login_page."'</script>");
            
            $SQL_QUERY = mysqli_query($db,'SELECT tb_dpwd.DPWD_MBR FROM tb_dpwd WHERE MD5(MD5(tb_dpwd.ID_DPWD)) = "'.$id.'" LIMIT 1');
            if(mysqli_num_rows($SQL_QUERY) > 0){
                $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
                insert_log($RESULT_QUERY['DPWD_MBR'], 'reject Deposit Finance');
            };
            die ("<script>alert('success reject Finance');location.href = 'home.php?page=".$login_page."'</script>");
        };
        
        //Authorization ============================
        if(isset($_POST['accept_3']) && isset($_POST['id']) && isset($_POST['note']) && isset($_POST['voucher'])){
            $id = form_input($_POST['id']);
            $note = form_input($_POST['note']);
            $voucher = form_input($_POST['voucher']);

            mysqli_query($db, '
                UPDATE tb_dpwd SET
                tb_dpwd.DPWD_STS = -1,
                tb_dpwd.DPWD_NOTE1 = "'.$note.'",
                tb_dpwd.DPWD_VOUCHER = "IBF/D/M/'.$voucher.'",
                tb_dpwd.DPWD_TIMESTAMP = "'.date('Y-m-d H:i:s').'"
                WHERE MD5(MD5(tb_dpwd.ID_DPWD)) = "'.$id.'"
                AND tb_dpwd.DPWD_TYPE = 1
                AND tb_dpwd.DPWD_STS = 0
            ') or die ("<script>alert('Please try again, or contact support.');location.href = 'home.php?page=".$login_page."'</script>");
            
            $SQL_QUERY = mysqli_query($db,'SELECT tb_dpwd.DPWD_MBR FROM tb_dpwd WHERE MD5(MD5(tb_dpwd.ID_DPWD)) = "'.$id.'" LIMIT 1');
            if(mysqli_num_rows($SQL_QUERY) > 0){
                $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
                insert_log($RESULT_QUERY['DPWD_MBR'], 'accept Deposit Authorization');
            };
            die ("<script>alert('success accept Authorization');location.href = 'home.php?page=".$login_page."'</script>");
        };

        if(isset($_POST['reject_3']) && isset($_POST['id']) && isset($_POST['note'])){
            $id = form_input($_POST['id']);
            $note = form_input($_POST['note']);

            mysqli_query($db, '
                UPDATE tb_dpwd SET
                tb_dpwd.DPWD_STS = 1,
                tb_dpwd.DPWD_NOTE1 = "'.$note.'",
                tb_dpwd.DPWD_TIMESTAMP = "'.date('Y-m-d H:i:s').'"
                WHERE MD5(MD5(tb_dpwd.ID_DPWD)) = "'.$id.'"
                AND tb_dpwd.DPWD_TYPE = 1
                AND tb_dpwd.DPWD_STS = 0
            ') or die ("<script>alert('Please try again, or contact support.');location.href = 'home.php?page=".$login_page."'</script>");
            
            $SQL_QUERY = mysqli_query($db,'SELECT tb_dpwd.DPWD_MBR FROM tb_dpwd WHERE MD5(MD5(tb_dpwd.ID_DPWD)) = "'.$id.'" LIMIT 1');
            if(mysqli_num_rows($SQL_QUERY) > 0){
                $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
                insert_log($RESULT_QUERY['DPWD_MBR'], 'reject Deposit Authorization');
            };
            die ("<script>alert('success reject Authorization');location.href = 'home.php?page=".$login_page."'</script>");
        };
    }
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Transaction</a></li>
        <li class="breadcrumb-item active" aria-current="page">Deposit</li>
    </ol>
</nav>
<div class="card mb-3">
    <div class="card-header font-weight-bold">Verificator</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Date</th>
                        <th style="vertical-align: middle" class="text-center">Name</th>
                        <th style="vertical-align: middle" class="text-center">Email</th>
                        <th style="vertical-align: middle" class="text-center">Phone</th>
                        <th style="vertical-align: middle" class="text-center">Login</th>
                        <th style="vertical-align: middle" class="text-center">Amount</th>
                        <th style="vertical-align: middle" class="text-center">Pic</th>
                        <th style="vertical-align: middle" class="text-center" width="1%">#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $SQL_QUERY = mysqli_query($db, '
                            SELECT
                                tb_dpwd.ID_DPWD,
                                MD5(MD5(tb_dpwd.ID_DPWD)) AS ID_DPWD_HASH,
                                tb_dpwd.DPWD_DATETIME,
                                tb_racc.ACC_F_APP_PRIBADI_NAMA AS MBR_NAME,
                                tb_member.MBR_EMAIL,
                                tb_racc.ACC_F_APP_PRIBADI_HP,
                                tb_racc.ACC_LOGIN,
                                tb_dpwd.DPWD_AMOUNT,
                                tb_dpwd.DPWD_BANKSRC,
                                tb_dpwd.DPWD_PIC,
                                tb_racc.ACC_PRODUCT,
                                tb_racc.ACC_F_APP_BK_1_NAMA,
                                tb_racc.ACC_F_APP_BK_1_ACC,
                                tb_racc.ACC_F_APP_BK_2_NAMA,
                                tb_racc.ACC_F_APP_BK_2_ACC,
                                tb_racc.ACC_TYPEACC,
                                tb_bankadm.BKADM_CURR,
                                tb_bankadm.BKADM_NAME,
                                tb_bankadm.BKADM_ACCOUNT
                            FROM tb_member
                            JOIN tb_racc
                            JOIN tb_dpwd
                            JOIN tb_bankadm
                            ON(tb_member.MBR_ID = tb_racc.ACC_MBR
                            AND tb_dpwd.DPWD_MBR = tb_member.MBR_ID
                            AND tb_dpwd.DPWD_LOGIN = tb_racc.ID_ACC
                            AND tb_dpwd.DPWD_BANK = tb_bankadm.ID_BKADM)
                            WHERE tb_dpwd.DPWD_STS = 0
                            AND tb_dpwd.DPWD_STSACC = 0
                            AND tb_dpwd.DPWD_STSVER = 0
                            AND tb_dpwd.DPWD_TYPE = 1
                            ORDER BY tb_dpwd.DPWD_DATETIME DESC
                        ');
                        if ($SQL_QUERY && mysqli_num_rows($SQL_QUERY) > 0) {
                            while($RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY)){
                    ?>
                    <tr>
                        <td style="vertical-align: middle" class="text-center"><?php echo $RESULT_QUERY['DPWD_DATETIME'] ?></td>
                        <td style="vertical-align: middle"><?php echo $RESULT_QUERY['MBR_NAME'] ?></td>
                        <td style="vertical-align: middle"><?php echo $RESULT_QUERY['MBR_EMAIL'] ?></td>
                        <td style="vertical-align: middle"><?php echo $RESULT_QUERY['ACC_F_APP_PRIBADI_HP'] ?></td>
                        <td style="vertical-align: middle"><?php echo $RESULT_QUERY['ACC_LOGIN'] ?></td>
                        <td style="vertical-align: middle" class="text-right"><?php echo number_format($RESULT_QUERY['DPWD_AMOUNT'], 0) ?></td>
                        <td style="vertical-align: middle" class="text-center"><a target='_blank' href='https://allmediaindo-2.s3.ap-southeast-1.amazonaws.com/ibftrader/<?php echo $RESULT_QUERY['DPWD_PIC'] ?>'>Open</a></td>
                        <?php if($user1["ADM_LEVEL"] == 3 || $user1["ADM_LEVEL"] == 1){?>
                            <td style="vertical-align: middle; white-space: nowrap;">
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal<?php echo $RESULT_QUERY['ID_DPWD'] ?>">Detail</button>
                                <div id="myModal<?php echo $RESULT_QUERY['ID_DPWD'] ?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post">
                                                <div class="modal-body">
                                                    <table class="table table-striped table-hover table-borderless">
                                                        <tr>
                                                            <td>Login</td>
                                                            <td><?php echo $RESULT_QUERY['ACC_LOGIN'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Name</td>
                                                            <td><?php echo $RESULT_QUERY['MBR_NAME'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Email</td>
                                                            <td><?php echo $RESULT_QUERY['MBR_EMAIL'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Type</td>
                                                            <td><?php echo $RESULT_QUERY['ACC_PRODUCT'].' / '.$RESULT_QUERY['ACC_TYPEACC'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Amount</td>
                                                            <td>Rp <?php echo number_format($RESULT_QUERY['DPWD_AMOUNT'], 0) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bank Source <?php if($RESULT_QUERY['DPWD_BANKSRC'] == 1){ echo '(Selected)';}?></td>
                                                            <td><?php echo $RESULT_QUERY['ACC_F_APP_BK_1_NAMA'].' / '.$RESULT_QUERY['ACC_F_APP_BK_1_ACC'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bank Source 2 <?php if($RESULT_QUERY['DPWD_BANKSRC'] == 2){ echo '(Selected)';}?></td>
                                                            <td><?php echo $RESULT_QUERY['ACC_F_APP_BK_2_NAMA'].' / '.$RESULT_QUERY['ACC_F_APP_BK_2_ACC'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bank Destination</td>
                                                            <td><?php echo $RESULT_QUERY['BKADM_CURR'].' / '.$RESULT_QUERY['BKADM_NAME'].' / '.$RESULT_QUERY['BKADM_ACCOUNT'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="vertical-align: middle;">Note</td>
                                                            <td>
                                                                <input type="hidden" class="form-control" autocomplete="off" name="id" value="<?php echo $RESULT_QUERY['ID_DPWD_HASH'] ?>" required readonly>
                                                                <input type="text" class="form-control" autocomplete="off" name="note" value="-" required>

                                                                <?php $keyPair = $guard->generateToken(); $csrfName = $guard->getTokenName(); $csrfValue = $guard->getTokenValue(); ?>
                                                                <input type="hidden" name="<?php echo $csrfNameKey ?>" value="<?php echo $csrfName ?>">
                                                                <input type="hidden" name="<?php echo $csrfValueKey ?>" value="<?php echo $csrfValue ?>">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success" name="accept_1">Accept</button>
                                                    <button type="submit" class="btn btn-danger"  name="reject_1">Reject</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        <?php }else{?>
                            <td></td>
                        <?php };?>
                    </tr>
                    <?php };} else { echo '<tr><td colspan="8" class="text-center">No data available in table</td></tr>'; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header font-weight-bold">Finance</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Date</th>
                        <th style="vertical-align: middle" class="text-center">Name</th>
                        <th style="vertical-align: middle" class="text-center">Email</th>
                        <th style="vertical-align: middle" class="text-center">Login</th>
                        <th style="vertical-align: middle" class="text-center">Amount</th>
                        <th style="vertical-align: middle" class="text-center">Pic</th>
                        <th style="vertical-align: middle" class="text-center" width="1%">#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $SQL_QUERY = mysqli_query($db, '
                            SELECT
                                tb_dpwd.ID_DPWD,
                                MD5(MD5(tb_dpwd.ID_DPWD)) AS ID_DPWD_HASH,
                                tb_dpwd.DPWD_DATETIME,
                                tb_racc.ACC_F_APP_PRIBADI_NAMA AS MBR_NAME,
                                tb_member.MBR_EMAIL,
                                tb_racc.ACC_LOGIN,
                                tb_dpwd.DPWD_AMOUNT,
                                tb_dpwd.DPWD_BANKSRC,
                                tb_dpwd.DPWD_PIC,
                                tb_racc.ACC_PRODUCT,
                                tb_racc.ACC_F_APP_BK_1_NAMA,
                                tb_racc.ACC_F_APP_BK_1_ACC,
                                tb_racc.ACC_F_APP_BK_2_NAMA,
                                tb_racc.ACC_F_APP_BK_2_ACC,
                                tb_racc.ACC_TYPEACC,
                                tb_bankadm.BKADM_CURR,
                                tb_bankadm.BKADM_NAME,
                                tb_bankadm.BKADM_ACCOUNT
                            FROM tb_member
                            JOIN tb_racc
                            JOIN tb_dpwd
                            JOIN tb_bankadm
                            ON(tb_member.MBR_ID = tb_racc.ACC_MBR
                            AND tb_dpwd.DPWD_MBR = tb_member.MBR_ID
                            AND tb_dpwd.DPWD_LOGIN = tb_racc.ID_ACC
                            AND tb_dpwd.DPWD_BANK = tb_bankadm.ID_BKADM)
                            WHERE tb_dpwd.DPWD_STS = 0
                            AND tb_dpwd.DPWD_STSACC = -1
                            AND tb_dpwd.DPWD_STSVER = 0
                            AND tb_dpwd.DPWD_TYPE = 1
                            ORDER BY tb_dpwd.DPWD_DATETIME DESC
                        ');
                        if ($SQL_QUERY && mysqli_num_rows($SQL_QUERY) > 0) {
                            while($RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY)){
                    ?>
                    <tr>
                        <td style="vertical-align: middle" class="text-center"><?php echo $RESULT_QUERY['DPWD_DATETIME'] ?></td>
                        <td style="vertical-align: middle"><?php echo $RESULT_QUERY['MBR_NAME'] ?></td>
                        <td style="vertical-align: middle"><?php echo $RESULT_QUERY['MBR_EMAIL'] ?></td>
                        <td style="vertical-align: middle"><?php echo $RESULT_QUERY['ACC_LOGIN'] ?></td>
                        <td style="vertical-align: middle" class="text-right"><?php echo number_format($RESULT_QUERY['DPWD_AMOUNT'], 0) ?></td>
                        <td style="vertical-align: middle" class="text-center"><a target='_blank' href='https://allmediaindo-2.s3.ap-southeast-1.amazonaws.com/ibftrader/<?php echo $RESULT_QUERY['DPWD_PIC'] ?>'>Open</a></td>
                        <?php if($user1["ADM_LEVEL"] == 7 || $user1["ADM_LEVEL"] == 1){?>
                            <td style="vertical-align: middle; white-space: nowrap;">
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal<?php echo $RESULT_QUERY['ID_DPWD'] ?>">Detail</button>
                                <div id="myModal<?php echo $RESULT_QUERY['ID_DPWD'] ?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post">
                                                <div class="modal-body">
                                                    <table class="table table-striped table-hover table-borderless">
                                                        <tr>
                                                            <td>Login</td>
                                                            <td><?php echo $RESULT_QUERY['ACC_LOGIN'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Name</td>
                                                            <td><?php echo $RESULT_QUERY['MBR_NAME'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Email</td>
                                                            <td><?php echo $RESULT_QUERY['MBR_EMAIL'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Type</td>
                                                            <td><?php echo $RESULT_QUERY['ACC_PRODUCT'].' / '.$RESULT_QUERY['ACC_TYPEACC'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Amount</td>
                                                            <td>Rp <?php echo number_format($RESULT_QUERY['DPWD_AMOUNT'], 0) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bank Source <?php if($RESULT_QUERY['DPWD_BANKSRC'] == 1){ echo '(Selected)';}?></td>
                                                            <td><?php echo $RESULT_QUERY['ACC_F_APP_BK_1_NAMA'].' / '.$RESULT_QUERY['ACC_F_APP_BK_1_ACC'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bank Source 2 <?php if($RESULT_QUERY['DPWD_BANKSRC'] == 2){ echo '(Selected)';}?></td>
                                                            <td><?php echo $RESULT_QUERY['ACC_F_APP_BK_2_NAMA'].' / '.$RESULT_QUERY['ACC_F_APP_BK_2_ACC'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bank Destination</td>
                                                            <td><?php echo $RESULT_QUERY['BKADM_CURR'].' / '.$RESULT_QUERY['BKADM_NAME'].' / '.$RESULT_QUERY['BKADM_ACCOUNT'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="vertical-align: middle;">Note</td>
                                                            <td>
                                                                <input type="hidden" class="form-control" autocomplete="off" name="id" value="<?php echo $RESULT_QUERY['ID_DPWD_HASH'] ?>" required readonly>
                                                                <input type="text" class="form-control" autocomplete="off" name="note" value="-" required>
                                                                
                                                                <?php $keyPair = $guard->generateToken(); $csrfName = $guard->getTokenName(); $csrfValue = $guard->getTokenValue(); ?>
                                                                <input type="hidden" name="<?php echo $csrfNameKey ?>" value="<?php echo $csrfName ?>">
                                                                <input type="hidden" name="<?php echo $csrfValueKey ?>" value="<?php echo $csrfValue ?>">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success" name="accept_2">Accept</button>
                                                    <button type="submit" class="btn btn-danger"  name="reject_2">Reject</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        <?php }else{?>
                            <td></td>
                        <?php };?>
                    </tr>
                    <?php };} else { echo '<tr><td colspan="7" class="text-center">No data available in table</td></tr>'; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header font-weight-bold">Authorization</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Date</th>
                        <th style="vertical-align: middle" class="text-center">Name</th>
                        <th style="vertical-align: middle" class="text-center">Email</th>
                        <th style="vertical-align: middle" class="text-center">Login</th>
                        <th style="vertical-align: middle" class="text-center">Amount</th>
                        <th style="vertical-align: middle" class="text-center">Pic</th>
                        <th style="vertical-align: middle" class="text-center" width="1%">#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $SQL_QUERY = mysqli_query($db, '
                            SELECT
                                tb_dpwd.ID_DPWD,
                                MD5(MD5(tb_dpwd.ID_DPWD)) AS ID_DPWD_HASH,
                                tb_dpwd.DPWD_DATETIME,
                                tb_racc.ACC_F_APP_PRIBADI_NAMA AS MBR_NAME,
                                tb_member.MBR_EMAIL,
                                tb_racc.ACC_LOGIN,
                                tb_dpwd.DPWD_AMOUNT,
                                tb_dpwd.DPWD_BANKSRC,
                                tb_dpwd.DPWD_PIC,
                                tb_racc.ACC_PRODUCT,
                                tb_racc.ACC_F_APP_BK_1_NAMA,
                                tb_racc.ACC_F_APP_BK_1_ACC,
                                tb_racc.ACC_F_APP_BK_2_NAMA,
                                tb_racc.ACC_F_APP_BK_2_ACC,
                                tb_racc.ACC_TYPEACC,
                                tb_bankadm.BKADM_CURR,
                                tb_bankadm.BKADM_NAME,
                                tb_bankadm.BKADM_ACCOUNT
                            FROM tb_member
                            JOIN tb_racc
                            JOIN tb_dpwd
                            JOIN tb_bankadm
                            ON(tb_member.MBR_ID = tb_racc.ACC_MBR
                            AND tb_dpwd.DPWD_MBR = tb_member.MBR_ID
                            AND tb_dpwd.DPWD_LOGIN = tb_racc.ID_ACC
                            AND tb_dpwd.DPWD_BANK = tb_bankadm.ID_BKADM)
                            WHERE tb_dpwd.DPWD_STS = 0
                            AND tb_dpwd.DPWD_STSACC = -1
                            AND tb_dpwd.DPWD_STSVER = -1
                            AND tb_dpwd.DPWD_TYPE = 1
                            ORDER BY tb_dpwd.DPWD_DATETIME DESC
                        ');
                        if ($SQL_QUERY && mysqli_num_rows($SQL_QUERY) > 0) {
                            while($RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY)){
                    ?>
                    <tr>
                        <td style="vertical-align: middle" class="text-center"><?php echo $RESULT_QUERY['DPWD_DATETIME'] ?></td>
                        <td style="vertical-align: middle"><?php echo $RESULT_QUERY['MBR_NAME'] ?></td>
                        <td style="vertical-align: middle"><?php echo $RESULT_QUERY['MBR_EMAIL'] ?></td>
                        <td style="vertical-align: middle"><?php echo $RESULT_QUERY['ACC_LOGIN'] ?></td>
                        <td style="vertical-align: middle" class="text-right"><?php echo number_format($RESULT_QUERY['DPWD_AMOUNT'], 0) ?></td>
                        <td style="vertical-align: middle" class="text-center"><a target='_blank' href='https://allmediaindo-2.s3.ap-southeast-1.amazonaws.com/ibftrader/<?php echo $RESULT_QUERY['DPWD_PIC'] ?>'>Open</a></td>
                        <?php if($user1["ADM_LEVEL"] == 4 || $user1["ADM_LEVEL"] == 6 || $user1["ADM_LEVEL"] == 1){?>
                            <td style="vertical-align: middle; white-space: nowrap;">
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal<?php echo $RESULT_QUERY['ID_DPWD'] ?>">Detail</button>
                                <div id="myModal<?php echo $RESULT_QUERY['ID_DPWD'] ?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post">
                                                <div class="modal-body">
                                                    <table class="table table-striped table-hover table-borderless">
                                                        <tr>
                                                            <td>Login</td>
                                                            <td><?php echo $RESULT_QUERY['ACC_LOGIN'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Name</td>
                                                            <td><?php echo $RESULT_QUERY['MBR_NAME'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Email</td>
                                                            <td><?php echo $RESULT_QUERY['MBR_EMAIL'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Type</td>
                                                            <td><?php echo $RESULT_QUERY['ACC_PRODUCT'].' / '.$RESULT_QUERY['ACC_TYPEACC'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Amount</td>
                                                            <td><?php echo number_format($RESULT_QUERY['DPWD_AMOUNT'], 0) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bank Source <?php if($RESULT_QUERY['DPWD_BANKSRC'] == 1){ echo '(Selected)';}?></td>
                                                            <td><?php echo $RESULT_QUERY['ACC_F_APP_BK_1_NAMA'].' / '.$RESULT_QUERY['ACC_F_APP_BK_1_ACC'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bank Source 2 <?php if($RESULT_QUERY['DPWD_BANKSRC'] == 2){ echo '(Selected)';}?></td>
                                                            <td><?php echo $RESULT_QUERY['ACC_F_APP_BK_2_NAMA'].' / '.$RESULT_QUERY['ACC_F_APP_BK_2_ACC'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bank Destination</td>
                                                            <td><?php echo $RESULT_QUERY['BKADM_CURR'].' / '.$RESULT_QUERY['BKADM_NAME'].' / '.$RESULT_QUERY['BKADM_ACCOUNT'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="vertical-align:middle;">Voucher</td>
                                                            <td>
                                                                <input type="hidden" name="id" value="<?php echo $RESULT_QUERY['ID_DPWD_HASH'] ?>" readonly required>
                                                                <input type="text" name="voucher" value="-" autocomplete="off" class="form-control" required>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="vertical-align: middle;">Note</td>
                                                            <td>
                                                                <input type="text" class="form-control" autocomplete="off" name="note" value="-" required>
                                                                
                                                                <?php $keyPair = $guard->generateToken(); $csrfName = $guard->getTokenName(); $csrfValue = $guard->getTokenValue(); ?>
                                                                <input type="hidden" name="<?php echo $csrfNameKey ?>" value="<?php echo $csrfName ?>">
                                                                <input type="hidden" name="<?php echo $csrfValueKey ?>" value="<?php echo $csrfValue ?>">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" name="accept_3" class="btn btn-success">Accept</button>
                                                    <button type="submit" name="reject_3" class="btn btn-danger">Reject</button>
                                                </div>
                                            </form>
                                        </div>
                                    
                                    </div>
                                </div>
                            </td>
                        <?php }else{?>
                            <td></td>
                        <?php };?>
                    </tr>
                    <?php };} else { echo '<tr><td colspan="7" class="text-center">No data available in table</td></tr>'; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header font-weight-bold">History</div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table_history" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Date</th>
                        <th style="vertical-align: middle" class="text-center">Name</th>
                        <th style="vertical-align: middle" class="text-center">Email</th>
                        <th style="vertical-align: middle" class="text-center">Login</th>
                        <th style="vertical-align: middle" class="text-center">Amount</th>
                        <th style="vertical-align: middle" class="text-center">Pic</th>
                        <th style="vertical-align: middle" class="text-center">Voucher</th>
                        <th style="vertical-align: middle" class="text-center">Note</th>
                        <th style="vertical-align: middle" class="text-center">Ver.</th>
                        <th style="vertical-align: middle" class="text-center">Fin.</th>
                        <th style="vertical-align: middle" class="text-center">Auth.</th>
                        <th style="vertical-align: middle" class="text-center">#</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#table_history').DataTable( {
            dom: 'Blfrtip',
            "processing": true,
            "serverSide": true,
            "ajax": "doc/<?php echo $login_page ?>_history_ajax.php",
            "deferRender": true,
            "lengthMenu": [[50, 75, 100, -1], [50, 75, 100, "Semua"]],
            "scrollX": true,
            "order": [[ 0, "desc" ]]
        } );
    } );
</script>
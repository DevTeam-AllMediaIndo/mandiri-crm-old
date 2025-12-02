<?php
    if($user1["ADM_LEVEL"] == 6 || $user1["ADM_LEVEL"] == 1){
    $x = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_GET['x']))));

    $SQL_QUERY = mysqli_query($db,"
        SELECT
            tb_dpwd.ID_DPWD,
            tb_racc.ACC_TYPE,
            tb_racc.ACC_RATE,
            tb_racc.ID_ACC,
            tb_member.MBR_NAME,
            tb_member.MBR_ID,
            tb_dpwd.DPWD_AMOUNT
        FROM tb_racc
        JOIN tb_member
        JOIN tb_dpwd
        ON(tb_racc.ACC_MBR = tb_member.MBR_ID
        AND tb_dpwd.DPWD_MBR = tb_member.MBR_ID
        AND tb_dpwd.DPWD_RACC = tb_racc.ID_ACC)
        WHERE MD5(MD5(tb_racc.ID_ACC)) = '".$x."'
        AND tb_dpwd.DPWD_STS = -1
        AND tb_dpwd.DPWD_STSACC = 0
        AND tb_dpwd.DPWD_NOTE = 'Deposit New Account'
    ");
    if(mysqli_num_rows($SQL_QUERY) > 0){
        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);

        if(isset($_POST['reject'])){

            $WP_UPDATE = mysqli_query($db,'
                UPDATE tb_dpwd SET
                tb_dpwd.DPWD_STS = 0
                WHERE tb_dpwd.ID_DPWD = '.$RESULT_QUERY['ID_DPWD'].'
            ') or die(mysqli_error($db));
            $WP_UPDATE = mysqli_query($db,'
                UPDATE tb_racc SET
                tb_racc.ACC_WPCHECK = 2,
                tb_racc.ACC_WPCHECK_DATE = (CURRENT_TIMESTAMP)
                WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$x.'"
            ') or die(mysqli_error($db));
            insert_log($RESULT_QUERY['MBR_ID'], 'reject WP CHeck');
            die("<script>location.href = 'home.php?page=member_realacc'</script>");
        };
        if(isset($_POST['accept'])){
            if(isset($_POST['login'])){
                if(isset($_POST['forex'])){
                    if(isset($_POST['loco'])){
                        if(isset($_POST['jpk50'])){
                            if(isset($_POST['jpk30'])){
                                if(isset($_POST['hkk50'])){
                                    if(isset($_POST['krj35'])){
                                        if(isset($_POST['margin'])){
                                            $login = form_input($_POST['login']);
                                            $forex = form_input($_POST['forex']);
                                            $loco = form_input($_POST['loco']);
                                            $jpk50 = form_input($_POST['jpk50']);
                                            $jpk30 = form_input($_POST['jpk30']);
                                            $hkk50 = form_input($_POST['hkk50']);
                                            $krj35 = form_input($_POST['krj35']);
                                            $margin = form_input($_POST['margin']);

                                            $INSERT_SQL = mysqli_query($db,'
                                                INSERT INTO tb_acccond SET
                                                tb_acccond.ACCCND_MBR = '.$RESULT_QUERY['MBR_ID'].',
                                                tb_acccond.ACCCND_ACC = '.$RESULT_QUERY['ID_ACC'].',
                                                tb_acccond.ACCCND_AMOUNTMARGIN = '.$RESULT_QUERY['DPWD_AMOUNT'].',
                                                tb_acccond.ACCCND_CASH_FOREX = '.$forex.',
                                                tb_acccond.ACCCND_CASH_LOCO = '.$loco.',
                                                tb_acccond.ACCCND_CASH_JPK50 = '.$jpk50.',
                                                tb_acccond.ACCCND_CASH_JPK30 = '.$jpk30.',
                                                tb_acccond.ACCCND_CASH_HK50 = '.$hkk50.',
                                                tb_acccond.ACCCND_CASH_KRJ35 = '.$krj35.',
                                                tb_acccond.ACCCND_LOGIN = "'.$login.'",
                                                tb_acccond.ACCCND_DATEMARGIN = (CURRENT_TIMESTAMP)
                                            ') or die(mysqli_error($db));

                                            $WP_UPDATE = mysqli_query($db,'
                                                UPDATE tb_racc SET
                                                    tb_racc.ACC_WPCHECK = 4,
                                                    tb_racc.ACC_WPCHECK_DATE = (CURRENT_TIMESTAMP)
                                                WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$x.'"
                                            ') or die(mysqli_error($db));
                                            insert_log($RESULT_QUERY['MBR_ID'], 'Accept WP CHeck');
                                            die("<script>location.href = 'home.php?page=member_realacc'</script>");

                                        } else { die("<script>alert('No margin');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check'</>"); };
                                    } else { die("<script>alert('No krj35');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check'</script>"); };
                                } else { die("<script>alert('No hkk50');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check'</script>"); };
                            } else { die("<script>alert('No jpk30');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check'</script>"); };
                        } else { die("<script>alert('No jpk50');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check'</script>"); };
                    } else { die("<script>alert('No loco');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check'</script>"); };
                } else { die("<script>alert('No forex');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check'</script>"); };
            } else { die("<script>alert('No login');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check'</script>"); };
        }
    };
        
?>
<form method="post">
    <div class="card mt-3">
        <div class="card-header font-weight-bold">
            ACCOUNT CONDITION 
            <?php
                if($RESULT_QUERY['ACC_TYPE'] == '' ||$RESULT_QUERY['ACC_TYPE'] == '-') {
                    echo '-';
                } else {
                    if($RESULT_QUERY['ACC_TYPE'] == 1) {
                        echo 'SPA';
                    } else if($RESULT_QUERY['ACC_TYPE'] == 2) {
                        echo 'KOMODITI';
                    } else {
                        echo 'Unknown';
                    }
                };
            ?>
            <div><span><i style="font-size: smaller; color: dimgray;">Comisson Charge</i></span></div>
        </div>
        <script type="text/javascript">
            function calc_rate(){
                var x = document.getElementById('forex').value;
                var y = x * 2;
                var z = y;
                if( z > 0){
                    document.getElementById('forex2').value = (z).toFixed(2);
                    document.getElementById('forex3').value = (z).toFixed(2);
                } else {
                    console.log('Lek Ngoding Seng Nggenah Titik Poo');
                };
            };

            function calc_rate2(){
                var x = document.getElementById('loco').value;
                var y = x * 4;
                var z = y;
                if( z > 0){
                    document.getElementById('loco2').value = (z).toFixed(2);
                    document.getElementById('loco3').value = (z).toFixed(2);
                } else {
                    console.log('Lek Ngoding Seng Nggenah Titik Poo');
                };
            };

            function calc_rate3(){
                var x = document.getElementById('jpk').value;
                var y = x * 2;
                var z = y;
                if( z > 0){
                    document.getElementById('jpk2').value = (z).toFixed(2);
                    document.getElementById('jpk3').value = (z).toFixed(2);
                } else {
                    console.log('Lek Ngoding Seng Nggenah Titik Poo');
                };
            };

            function calc_rate4(){
                var x = document.getElementById('jpkk').value;
                var y = x * 2;
                var z = y;
                if( z > 0){
                    document.getElementById('jpkk2').value = (z).toFixed(2);
                    document.getElementById('jpkk3').value = (z).toFixed(2);
                } else {
                    console.log('Lek Ngoding Seng Nggenah Titik Poo');
                };
            };

            function calc_rate4(){
                var x = document.getElementById('jpkk').value;
                var y = x * 2;
                var z = y;
                if( z > 0){
                    document.getElementById('jpkk2').value = (z).toFixed(2);
                    document.getElementById('jpkk3').value = (z).toFixed(2);
                } else {
                    console.log('Lek Ngoding Seng Nggenah Titik Poo');
                };
            };

            function calc_rate5(){
                var x = document.getElementById('hkk').value;
                var y = x * 2;
                var z = y;
                if( z > 0){
                    document.getElementById('hkk2').value = (z).toFixed(2);
                    document.getElementById('hkk3').value = (z).toFixed(2);
                } else {
                    console.log('Lek Ngoding Seng Nggenah Titik Poo');
                };
            };

            function calc_rate6(){
                var x = document.getElementById('krj').value;
                var y = x * 2;
                var z = y;
                if( z > 0){
                    document.getElementById('krj2').value = (z).toFixed(2);
                    document.getElementById('krj3').value = (z).toFixed(2);
                } else {
                    console.log('Lek Ngoding Seng Nggenah Titik Poo');
                };
            };


        </script>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-2" style="margin-block: auto;">Nama</div>
                <div class="col-sm-10"><input type="text" class="form-control text-center" placeholder="" readonly name="nama" value="<?php echo $RESULT_QUERY['MBR_NAME']; ?>"></div>
                <div class="col-sm-2 mt-2" style="margin-block: auto;">Login</div>
                <div class="col-sm-10 mt-2"><input type="text" class="form-control text-center" placeholder="" name="login" autocomplete="off" required></div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-2" style="margin-block: auto;">Forex</div>
                <div class="col-sm-4">
                    <select class="form-control text-center" name="forex" required>
                        <option value="0">Pilih Nilai</option>
                        <option value="10">10</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="col-sm-2 text-left" style="margin-block: auto;"><i class="fa fa-tag" aria-hidden="true"></i>JPK50</div>
                <div class="col-sm-4">
                    <select class="form-control text-center" name="jpk50" required>
                        <option value="0">Pilih Nilai</option>
                        <option value="10">10</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-sm-2" style="margin-block: auto;">Locco London</div>
                <div class="col-sm-4">
                    <select class="form-control text-center" name="loco" required>
                        <option value="0">Pilih Nilai</option>
                        <option value="10">10</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="col-sm-2 text-left" style="margin-block: auto;"><i class="fa fa-tag" aria-hidden="true"></i>JPK30</div>
                <div class="col-sm-4">
                    <select class="form-control text-center" name="jpk30" required>
                        <option value="0">Pilih Nilai</option>
                        <option value="10">10</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-sm-2" style="margin-block: auto;">&nbsp;</div>
                <div class="col-sm-4">&nbsp;</div>
                <div class="col-sm-2 text-left" style="margin-block: auto;"><i class="fa fa-tag" aria-hidden="true"></i>HKK50/HKJ50</div>
                <div class="col-sm-4">
                    <select class="form-control text-center" name="hkk50" required>
                        <option value="0">Pilih Nilai</option>
                        <option value="10">10</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-sm-2" style="margin-block: auto;">&nbsp;</div>
                <div class="col-sm-4">&nbsp;</div>
                <div class="col-sm-2 text-left" style="margin-block: auto;"><i class="fa fa-tag" aria-hidden="true"></i>KRJ35</div>
                <div class="col-sm-4">
                    <select class="form-control text-center" name="krj35" required>
                        <option value="0">Pilih Nilai</option>
                        <option value="10">10</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-3" style="margin-block: auto;">Nilai Margin</div>
                <div class="col-sm-1 text-left" style="margin-block: auto;">IDR</div>
                <div class="col-sm-3" style="margin-block: auto;"><input type="text" class="form-control text-center" name="margin" value="<?php echo number_format($RESULT_QUERY['DPWD_AMOUNT'], 0) ?>" readonly required></div>
                <div class="col-sm-1 text-left" style="margin-block: auto;">Fixed Rate</div>
                <div class="col-sm-3" style="margin-block: auto;">
                    <select name="" id="" class="form-control text-center" required>
                        <option value="10.000" <?php if($RESULT_QUERY['ACC_RATE'] == '10000'){echo 'selected';}?>>10.000</option>
                        <option value="12.000" <?php if($RESULT_QUERY['ACC_RATE'] == '12000'){echo 'selected';}?>>12.000</option>
                        <option value="14.000" <?php if($RESULT_QUERY['ACC_RATE'] == '14000'){echo 'selected';}?>>14.000</option>
                        <option value="Custom" <?php if($RESULT_QUERY['ACC_RATE'] == '0'){echo 'selected';}?>>Custom</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" name="accept" class="btn btn-primary">Submit</button>
            <button type="submit" name="reject" class="btn btn-danger">cancel</button>
        </div>
    </div>
</form>
<?php 
    } else{
        die("<script>alert('Kepada ".$user1["ADM_NAME"].", anda tidak ada akses ke halaman ini');location.href = 'home.php?page=member_realacc'</script>");
    };
?>
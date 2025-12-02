<?php
    if($user1["ADM_LEVEL"] == 6 || $user1["ADM_LEVEL"] == 1){
    $x = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_GET['x']))));

    $SQL_QUERY = mysqli_query($db,"
        SELECT
            tb_dpwd.ID_DPWD,
            tb_racc.ACC_TYPE,
            tb_racc.ACC_PRODUCT,
            tb_racc.ACC_RATE,
            tb_racc.ACC_CURR,
            tb_racc.ID_ACC,
            tb_racc.ACC_F_APP_BK_1_NAMA,
            tb_racc.ACC_F_APP_BK_1_CBNG,
            tb_racc.ACC_F_APP_BK_1_ACC,
            tb_racc.ACC_F_APP_BK_1_JENIS,
            tb_racc.ACC_F_APP_BK_2_NAMA,
            tb_racc.ACC_F_APP_BK_2_CBNG,
            tb_racc.ACC_F_APP_BK_2_ACC,
            tb_racc.ACC_F_APP_BK_2_JENIS,
            tb_racc.ACC_F_APP_PRIBADI_NAMA AS MBR_NAME,
            tb_member.MBR_EMAIL,
            tb_racc.ACC_F_APP_PRIBADI_HP AS MBR_PHONE,
            tb_member.MBR_IB_CODE,
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
        $MBR_IB_CODE = $RESULT_QUERY["MBR_IB_CODE"];
        $ID_DPWD = $RESULT_QUERY["ID_DPWD"];
        if($RESULT_QUERY['ACC_RATE'] == 0){ $rate = 10000;}else{$rate = $RESULT_QUERY['ACC_RATE'];}
        if($RESULT_QUERY['ACC_RATE'] == 0){
            $curr_idr = 0;
            $curr = number_format($RESULT_QUERY['DPWD_AMOUNT'], 2);
            $curr_lg = 'USD';
            $curr_ag = '$';
        }else{
            $curr_idr = number_format($RESULT_QUERY['DPWD_AMOUNT'], 0);
            $curr = number_format($RESULT_QUERY['DPWD_AMOUNT'], 0);
            $curr_lg = 'IDR';
            $curr_ag = 'Rp';
        }
        if(isset($_POST['reject'])){
            if(isset($_POST['note'])){
                $note = form_input($_POST['note']);
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
                $INSERT_NOTE = mysqli_query($db,'
                    INSERT INTO tb_note SET
                    tb_note.NOTE_MBR = '.$RESULT_QUERY['MBR_ID'].',
                    tb_note.NOTE_RACC = '.$RESULT_QUERY['ID_ACC'].',
                    tb_note.NOTE_DPWD = '.$RESULT_QUERY['ID_DPWD'].',
                    tb_note.NOTE_TYPE = "WP CHECK REJECT",
                    tb_note.NOTE_NOTE = "'.$note.'",
                    tb_note.NOTE_DATETIME = "'.date('Y-m-d H:i:s').'"
                ') or die(mysqli_error($db));
                
                // Message Telegram
                $mesg = 'Notif : ACCOUNT CONDITION Ditolak'.
                PHP_EOL.'Date : '.date("Y-m-d").
                PHP_EOL.'Time : '.date("H:i:s");
                // PHP_EOL.'======== Informasi ACCOUNT CONDITION =========='.
                // PHP_EOL.'Nama : '.$RESULT_QUERY['MBR_NAME'].
                // PHP_EOL.'Email : '.$RESULT_QUERY['MBR_EMAIL'].
                // PHP_EOL.'Status : Ditolak'.
                // PHP_EOL.'Alasan Ditolak : '.$note.
                // PHP_EOL.'By : '.$user1['ADM_NAME'].'';

                // Message Telegram
                $mesg_othr = 'Notif : ACCOUNT CONDITION Ditolak'.
                PHP_EOL.'Date : '.date("Y-m-d").
                PHP_EOL.'Time : '.date("H:i:s").
                PHP_EOL.'==============================================='.
                PHP_EOL.'                      Informasi ACCOUNT CONDITION'.
                PHP_EOL.'==============================================='.
                PHP_EOL.'Nama : '.$RESULT_QUERY['MBR_NAME'].
                PHP_EOL.'Email : '.$RESULT_QUERY['MBR_EMAIL'].
                PHP_EOL.'Status : Ditolak'.
                PHP_EOL.'Alasan Ditolak : '.$note.
                PHP_EOL.'By : '.$user1['ADM_NAME'].'';

                $request_params_wpb = [
                    'chat_id' => $chat_id,
                    'text' => $mesg
                ];
                http_request('https://api.telegram.org/bot'.$token1.'/sendMessage?'.http_build_query($request_params_wpb));

                $request_params_stlmnt = [
                    'chat_id' => $chat_id_stllmnt,
                    'text' => $mesg
                ];
                http_request('https://api.telegram.org/bot'.$token_stllmnt.'/sendMessage?'.http_build_query($request_params_stlmnt));

                $request_params_all = [
                    'chat_id' => $chat_id_all,
                    'text' => $mesg
                ];
                http_request('https://api.telegram.org/bot'.$token_all.'/sendMessage?'.http_build_query($request_params_all));

                $request_params_othr = [
                    'chat_id' => $chat_id_othr,
                    'text' => $mesg_othr
                ];
                http_request('https://api.telegram.org/bot'.$token_othr.'/sendMessage?'.http_build_query($request_params_othr));
                insert_log($RESULT_QUERY['MBR_ID'], 'reject WP CHeck');
                die("<script>location.href = 'home.php?page=member_realacc'</script>");
            };
        };
        if(isset($_POST['accept'])){
            // die(print_r($_POST));

            if(isset($_POST['login'])){
                if(isset($_POST['margin'])){
                    if(isset($_POST['note'])){
                        if(isset($_POST['voucher'])){
                            if(isset($_POST['ib'])){
                                $login = form_input($_POST['login']);
                                $margin = form_input($_POST['margin']);
                                $note = form_input($_POST['note']);
                                $voucher = form_input($_POST['voucher']);
                                $ib = form_input($_POST['ib']);
                                // if($RESULT_QUERY['ACC_PRODUCT'] == 'Forex dan Gold'){
                                if($RESULT_QUERY['ACC_PRODUCT'] == '1'){
                                    if(isset($_POST['forex'])){
                                        if(isset($_POST['loco'])){
                                            $forex = form_input($_POST['forex']);
                                            $loco = form_input($_POST['loco']);

                                            mysqli_query($db,'
                                                INSERT INTO tb_acccond SET
                                                tb_acccond.ACCCND_MBR = '.$RESULT_QUERY['MBR_ID'].',
                                                tb_acccond.ACCCND_ACC = '.$RESULT_QUERY['ID_ACC'].',
                                                tb_acccond.ACCCND_AMOUNTMARGIN = '.$RESULT_QUERY['DPWD_AMOUNT'].',
                                                tb_acccond.ACCCND_CASH_FOREX = '.$forex.',
                                                tb_acccond.ACCCND_CASH_LOCO = '.$loco.',
                                                tb_acccond.ACCCND_LOGIN = "'.$login.'",
                                                tb_acccond.ACCCND_IB = '.$ib.',
                                                tb_acccond.ACCCND_DATEMARGIN = "'.date('Y-m-d H:i:s').'"
                                            ') or die(mysqli_error($db).' - 1');

                                            // echo 'INSERT INTO tb_acccond SET
                                            // tb_acccond.ACCCND_MBR = '.$RESULT_QUERY['MBR_ID'].',
                                            // tb_acccond.ACCCND_ACC = '.$RESULT_QUERY['ID_ACC'].',
                                            // tb_acccond.ACCCND_AMOUNTMARGIN = '.$RESULT_QUERY['DPWD_AMOUNT'].',
                                            // tb_acccond.ACCCND_CASH_FOREX = '.$forex.',
                                            // tb_acccond.ACCCND_CASH_LOCO = '.$loco.',
                                            // tb_acccond.ACCCND_LOGIN = "'.$login.'",
                                            // tb_acccond.ACCCND_DATEMARGIN = (CURRENT_TIMESTAMP)<br>';

                                            mysqli_query($db, '
                                                UPDATE tb_dpwd SET
                                                tb_dpwd.DPWD_VOUCHER = "'.$voucher.'",
                                                tb_dpwd.DPWD_STS = -1,
                                                tb_dpwd.DPWD_DATETIME = "'.date('Y-m-d H:i:s').'"
                                                WHERE tb_dpwd.ID_DPWD = '.$RESULT_QUERY['ID_DPWD'].'
                                            ') or die (mysqli_error($db).' - 2');
                                            
                                            // echo 'UPDATE tb_dpwd SET
                                            // tb_dpwd.DPWD_VOUCHER = "'.$voucher.'",
                                            // tb_dpwd.DPWD_STS = -1,
                                            // tb_dpwd.DPWD_DATETIME = "'.date('Y-m-d H:i:s').'"
                                            // WHERE tb_dpwd.ID_DPWD = "'.$RESULT_QUERY['ID_DPWD'].'<br>';
                                            
                                        } else { die("<script>alert('No loco');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check1'</script>"); };
                                    } else { die("<script>alert('No forex');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check1'</script>"); };
                                } else if($RESULT_QUERY['ACC_PRODUCT'] == 'Kontrak Berjangka Crued Oil' || $RESULT_QUERY['ACC_PRODUCT'] == 'Kontrak Gulir Valuta Asing/GO FOREX'){
                                    if(isset($_POST['jpk50'])){
                                        if(isset($_POST['jpk30'])){
                                            if(isset($_POST['hkk50'])){
                                                if(isset($_POST['krj35'])){
                                                    $jpk50 = form_input($_POST['jpk50']);
                                                    $jpk30 = form_input($_POST['jpk30']);
                                                    $hkk50 = form_input($_POST['hkk50']);
                                                    $krj35 = form_input($_POST['krj35']);
                                    
                                                    mysqli_query($db,'
                                                        INSERT INTO tb_acccond SET
                                                        tb_acccond.ACCCND_MBR = '.$RESULT_QUERY['MBR_ID'].',
                                                        tb_acccond.ACCCND_ACC = '.$RESULT_QUERY['ID_ACC'].',
                                                        tb_acccond.ACCCND_AMOUNTMARGIN = '.$RESULT_QUERY['DPWD_AMOUNT'].',
                                                        tb_acccond.ACCCND_CASH_JPK50 = '.$jpk50.',
                                                        tb_acccond.ACCCND_CASH_JPK30 = '.$jpk30.',
                                                        tb_acccond.ACCCND_CASH_HK50 = '.$hkk50.',
                                                        tb_acccond.ACCCND_CASH_KRJ35 = '.$krj35.',
                                                        tb_acccond.ACCCND_LOGIN = "'.$login.'",
                                                        tb_acccond.ACCCND_IB = '.$ib.',
                                                        tb_acccond.ACCCND_DATEMARGIN = "'.date('Y-m-d H:i:s').'"
                                                    ') or die(mysqli_error($db));
                                                    
                                            // echo 'INSERT INTO tb_acccond SET
                                            // tb_acccond.ACCCND_MBR = '.$RESULT_QUERY['MBR_ID'].',
                                            // tb_acccond.ACCCND_ACC = '.$RESULT_QUERY['ID_ACC'].',
                                            // tb_acccond.ACCCND_AMOUNTMARGIN = '.$RESULT_QUERY['DPWD_AMOUNT'].',
                                            // tb_acccond.ACCCND_CASH_JPK50 = '.$jpk50.',
                                            // tb_acccond.ACCCND_CASH_JPK30 = '.$jpk30.',
                                            // tb_acccond.ACCCND_CASH_HK50 = '.$hkk50.',
                                            // tb_acccond.ACCCND_CASH_KRJ35 = '.$krj35.',
                                            // tb_acccond.ACCCND_LOGIN = "'.$login.'",
                                            // tb_acccond.ACCCND_DATEMARGIN = (CURRENT_TIMESTAMP)<br>';
                                                    
                                                    mysqli_query($db, '
                                                        UPDATE tb_dpwd SET
                                                        tb_dpwd.DPWD_VOUCHER = "'.$voucher.'",
                                                        tb_dpwd.DPWD_STS = -1,
                                                        tb_dpwd.DPWD_DATETIME = "'.date('Y-m-d H:i:s').'"
                                                        WHERE tb_dpwd.ID_DPWD = '.$RESULT_QUERY['ID_DPWD'].'
                                                    ') or die (mysqli_error($db));
                                                    
                                                    // echo 'UPDATE tb_dpwd SET
                                                    // tb_dpwd.DPWD_VOUCHER = "'.$voucher.'",
                                                    // tb_dpwd.DPWD_STS = -1,
                                                    // tb_dpwd.DPWD_DATETIME = "'.date('Y-m-d H:i:s').'"
                                                    // WHERE tb_dpwd.ID_DPWD = '.$RESULT_QUERY['ID_DPWD'].'<br>';
                                    
                                                } else { die("<script>alert('No krj35');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check1'</script>"); };
                                            } else { die("<script>alert('No hkk50');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check1'</script>"); };
                                        } else { die("<script>alert('No jpk30');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check1'</script>"); };
                                    } else { die("<script>alert('No jpk50');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check1'</script>"); };
                                };
                                
                                                    
                                // echo '
                                // INSERT INTO tb_note SET
                                // tb_note.NOTE_MBR = '.$RESULT_QUERY['MBR_ID'].',
                                // tb_note.NOTE_RACC = '.$RESULT_QUERY['ID_ACC'].',
                                // tb_note.NOTE_DPWD = '.$RESULT_QUERY['ID_DPWD'].',
                                // tb_note.NOTE_ACCDN = (SELECT ID_ACCCND FROM tb_acccond WHERE ACCCND_MBR = '.$RESULT_QUERY['MBR_ID'].' AND ACCCND_ACC = '.$RESULT_QUERY['ID_ACC'].' AND ACCCND_LOGIN = '.$login.' ORDER BY ID_ACCCND DESC LIMIT 1),
                                // tb_note.NOTE_TYPE = "WP CHECK ACCEPT",
                                // tb_note.NOTE_NOTE = "'.$note.'",
                                // tb_note.NOTE_DATETIME = "'.date('Y-m-d H:i:s').'"<br>';

                                $SQL_NOTE = mysqli_query($db,'SELECT ID_ACCCND FROM tb_acccond WHERE ACCCND_MBR = '.$RESULT_QUERY['MBR_ID'].' AND ACCCND_ACC = '.$RESULT_QUERY['ID_ACC'].' AND ACCCND_LOGIN = '.$login.' ORDER BY ID_ACCCND DESC LIMIT 1');
                                if(mysqli_num_rows($SQL_NOTE) > 0){
                                    $RSLT_NOTE = mysqli_fetch_assoc($SQL_NOTE);
                                }
                                
                                $INSERT_NOTE = mysqli_query($db,'
                                    INSERT INTO tb_note SET
                                    tb_note.NOTE_MBR = '.$RESULT_QUERY['MBR_ID'].',
                                    tb_note.NOTE_RACC = '.$RESULT_QUERY['ID_ACC'].',
                                    tb_note.NOTE_DPWD = '.$RESULT_QUERY['ID_DPWD'].',
                                    tb_note.NOTE_ACCDN = '.$RSLT_NOTE["ID_ACCCND"].',
                                    tb_note.NOTE_TYPE = "WP CHECK ACCEPT",
                                    tb_note.NOTE_NOTE = "'.$note.'",
                                    tb_note.NOTE_DATETIME = "'.date('Y-m-d H:i:s').'"
                                ') or die(mysqli_error($db).' - 3<br>'.'SELECT ID_ACCCND FROM tb_acccond WHERE ACCCND_MBR = '.$RESULT_QUERY['MBR_ID'].' AND ACCCND_ACC = '.$RESULT_QUERY['ID_ACC'].' AND ACCCND_LOGIN = '.$login.' ORDER BY ID_ACCCND DESC LIMIT 1');
                
                                $WP_UPDATE = mysqli_query($db,'
                                    UPDATE tb_racc SET
                                        tb_racc.ACC_WPCHECK = 4,
                                        tb_racc.ACC_WPCHECK_DATE = (CURRENT_TIMESTAMP)
                                    WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$x.'"
                                ') or die(mysqli_error($db).' - 4');
                                // echo 'UPDATE tb_racc SET
                                //     tb_racc.ACC_WPCHECK = 4,
                                //     tb_racc.ACC_WPCHECK_DATE = (CURRENT_TIMESTAMP)
                                // WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$x.'"<br>';
                
                                // Message Telegram
                                $mesg = 'Notif : ACCOUNT CONDITION Diterima'.
                                PHP_EOL.'Date : '.date("Y-m-d").
                                PHP_EOL.'Time : '.date("H:i:s");
                                // PHP_EOL.'======== Informasi ACCOUNT CONDITION =========='.
                                // PHP_EOL.'Nama : '.$RESULT_QUERY['MBR_NAME'].
                                // PHP_EOL.'Email : '.$RESULT_QUERY['MBR_EMAIL'].
                                // PHP_EOL.'Voucher : '.$voucher.
                                // PHP_EOL.'Login : '.$login.
                                // PHP_EOL.'Margin : '.$curr_ag.' '.$curr.
                                // PHP_EOL.'Rate : '.$RESULT_QUERY['ACC_RATE'].
                                // PHP_EOL.'Status : Diterima'.
                                // PHP_EOL.'Catatan : '.$note.
                                // PHP_EOL.'By : '.$user1['ADM_NAME'].'';

                                // Message Telegram
                                $mesg_othr = 'Notif : ACCOUNT CONDITION Diterima'.
                                PHP_EOL.'Date : '.date("Y-m-d").
                                PHP_EOL.'Time : '.date("H:i:s").
                                PHP_EOL.'==============================================='.
                                PHP_EOL.'                      Informasi ACCOUNT CONDITION'.
                                PHP_EOL.'==============================================='.
                                PHP_EOL.'Nama : '.$RESULT_QUERY['MBR_NAME'].
                                PHP_EOL.'Email : '.$RESULT_QUERY['MBR_EMAIL'].
                                PHP_EOL.'Voucher : '.$voucher.
                                PHP_EOL.'Login : '.$login.
                                PHP_EOL.'Margin : '.$curr_ag.' '.$curr.
                                PHP_EOL.'Rate : '.$RESULT_QUERY['ACC_RATE'].
                                PHP_EOL.'Status : Diterima'.
                                PHP_EOL.'Catatan : '.$note.
                                PHP_EOL.'By : '.$user1['ADM_NAME'].'';

                                $request_params_accounting = [
                                    'chat_id' => $chat_id_accounnting,
                                    'text' => $mesg
                                ];
                                http_request('https://api.telegram.org/bot'.$token_accounnting.'/sendMessage?'.http_build_query($request_params_accounting));

                                $request_params_stlmnt = [
                                    'chat_id' => $chat_id_stllmnt,
                                    'text' => $mesg
                                ];
                                http_request('https://api.telegram.org/bot'.$token_stllmnt.'/sendMessage?'.http_build_query($request_params_stlmnt));

                                $request_params_all = [
                                    'chat_id' => $chat_id_all,
                                    'text' => $mesg
                                ];
                                http_request('https://api.telegram.org/bot'.$token_all.'/sendMessage?'.http_build_query($request_params_all));

                                $request_params_othr = [
                                    'chat_id' => $chat_id_othr,
                                    'text' => $mesg_othr
                                ];
                                http_request('https://api.telegram.org/bot'.$token_othr.'/sendMessage?'.http_build_query($request_params_othr));

                                insert_log($RESULT_QUERY['MBR_ID'], 'Accept WP CHeck');
                                die("<script>location.href = 'home.php?page=member_realacc'</script>");

                            } else { die("<script>alert('No ib');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check1'</script>"); };
                        } else { die("<script>alert('No voucher');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check1'</script>"); };
                    } else { die("<script>alert('No note');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check1'</script>"); };
                } else { die("<script>alert('No margin');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check1'</script>"); };
            } else { die("<script>alert('No login');location.href = 'home.php?page=member_realacc_detail&action=detail&x=".$x."&sub_page=wp_check1'</script>"); };
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
                <div class="col-sm-2" style="margin-block: auto;">Voucher Number</div>
                <div class="col-sm-10" style="margin-block: auto;"><input type="text" class="form-control" name="voucher" value="-" required></div>
                <div class="col-sm-2" style="margin-block: auto;">Nama</div>
                <div class="col-sm-10"><input type="text" class="form-control text-center" readonly value="<?php echo $RESULT_QUERY['MBR_NAME']; ?>"></div>
                <div class="col-sm-2" style="margin-block: auto;">Email</div>
                <div class="col-sm-10" style="margin-block: auto;"><input type="text" class="form-control text-center" readonly value="<?php echo $RESULT_QUERY['MBR_EMAIL']; ?>"></div>
                <div class="col-sm-2" style="margin-block: auto;">No.Telp</div>
                <div class="col-sm-10"><input type="text" class="form-control text-center" readonly value="<?php echo $RESULT_QUERY['MBR_PHONE']; ?>"></div>
            </div>
            <hr>

            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-sm-2" style="margin-block: auto;">Bank 1 Name</div>
                        <div class="col-sm-10 mt-2"><input type="text" class="form-control text-center" readonly value="<?php echo $RESULT_QUERY['ACC_F_APP_BK_1_NAMA']; ?>"></div>
                        <div class="col-sm-2" style="margin-block: auto;">Bank 1 Account</div>
                        <div class="col-sm-10 mt-2"><input type="text" class="form-control text-center" readonly value="<?php echo $RESULT_QUERY['ACC_F_APP_BK_1_ACC']; ?>"></div>
                        <div class="col-sm-2" style="margin-block: auto;">Bank 1 Cabang</div>
                        <div class="col-sm-10 mt-2"><input type="text" class="form-control text-center" readonly value="<?php echo $RESULT_QUERY['ACC_F_APP_BK_1_CBNG']; ?>"></div>
                    </div>    
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-sm-2" style="margin-block: auto;">Bank 2 Name</div>
                        <div class="col-sm-10 mt-2"><input type="text" class="form-control text-center" readonly value="<?php echo $RESULT_QUERY['ACC_F_APP_BK_2_NAMA']; ?>"></div>
                        <div class="col-sm-2" style="margin-block: auto;">Bank 2 Account</div>
                        <div class="col-sm-10 mt-2"><input type="text" class="form-control text-center" readonly value="<?php echo $RESULT_QUERY['ACC_F_APP_BK_2_ACC']; ?>"></div>
                        <div class="col-sm-2" style="margin-block: auto;">Bank 2 Cabang</div>
                        <div class="col-sm-10 mt-2"><input type="text" class="form-control text-center" readonly value="<?php echo $RESULT_QUERY['ACC_F_APP_BK_2_CBNG']; ?>"></div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-2 mt-2" style="margin-block: auto;">Login</div>
                <div class="col-sm-10 mt-2"><input type="number" class="form-control text-center" placeholder="" name="login" autocomplete="off" required></div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-2" style="margin-block: auto; visibility : <?php if($RESULT_QUERY['ACC_PRODUCT'] == '1'){ echo 'visible';}else{echo 'hidden';}?>;" id="forexdiv1">Forex</div>
                <div class="col-sm-4" id="forexinpt1" style="visibility : <?php if($RESULT_QUERY['ACC_PRODUCT'] == '1'){ echo 'visible';}else{echo 'hidden';}?>;">
                    <select class="form-control text-center" name="forex" <?php if($RESULT_QUERY['ACC_PRODUCT'] == '1'){ echo 'required';}?>>
                        <option value="0">Pilih Nilai</option>
                        <option value="10">10</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="col-sm-2 text-left" style="margin-block: auto; visibility : <?php if($RESULT_QUERY['ACC_PRODUCT'] == 'Index Asia (Gulir)' || $RESULT_QUERY['ACC_PRODUCT'] == 'Kontrak Gulir Valuta Asing/GO FOREX'){ echo 'visible';}else{echo 'hidden';}?>;" id="indexdiv1"><i class="fa fa-tag" aria-hidden="true" ></i>JPK50</div>
                <div class="col-sm-4" id="indexinpt" id="indexinpt1" style="visibility : <?php if($RESULT_QUERY['ACC_PRODUCT'] == 'Index Asia (Gulir)' || $RESULT_QUERY['ACC_PRODUCT'] == 'Kontrak Gulir Valuta Asing/GO FOREX'){ echo 'visible';}else{echo 'hidden';}?>;">
                    <select class="form-control text-center" name="jpk50" <?php if($RESULT_QUERY['ACC_PRODUCT'] == 'Index Asia (Gulir)' || $RESULT_QUERY['ACC_PRODUCT'] == 'Kontrak Gulir Valuta Asing/GO FOREX'){ echo 'required';}?>>
                        <option value="0">Pilih Nilai</option>
                        <option value="10">10</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-sm-2" style="margin-block: auto; visibility : <?php if($RESULT_QUERY['ACC_PRODUCT'] == '1'){ echo 'visible';}else{echo 'hidden';}?>;" id="forexdiv2">Locco London</div>
                <div class="col-sm-4" id="forexinpt2" style="visibility : <?php if($RESULT_QUERY['ACC_PRODUCT'] == '1'){ echo 'visible';}else{echo 'hidden';}?>;">
                    <select class="form-control text-center" name="loco" <?php if($RESULT_QUERY['ACC_PRODUCT'] == '1'){ echo 'required';}?>>
                        <option value="0">Pilih Nilai</option>
                        <option value="10">10</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="col-sm-2 text-left" style="margin-block: auto; visibility : <?php if($RESULT_QUERY['ACC_PRODUCT'] == 'Index Asia (Gulir)' || $RESULT_QUERY['ACC_PRODUCT'] == 'Kontrak Gulir Valuta Asing/GO FOREX'){ echo 'visible';}else{echo 'hidden';}?>;" id="indexdiv2"><i class="fa fa-tag" aria-hidden="true" ></i>JPK30</div>
                <div class="col-sm-4" id="indexinpt2" style="visibility : <?php if($RESULT_QUERY['ACC_PRODUCT'] == 'Index Asia (Gulir)' || $RESULT_QUERY['ACC_PRODUCT'] == 'Kontrak Gulir Valuta Asing/GO FOREX'){ echo 'visible';}else{echo 'hidden';}?>;">
                    <select class="form-control text-center" name="jpk30" <?php if($RESULT_QUERY['ACC_PRODUCT'] == 'Index Asia (Gulir)' || $RESULT_QUERY['ACC_PRODUCT'] == 'Kontrak Gulir Valuta Asing/GO FOREX'){ echo 'required';}?>>
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
                <div class="col-sm-2 text-left" style="margin-block: auto; visibility : <?php if($RESULT_QUERY['ACC_PRODUCT'] == 'Index Asia (Gulir)' || $RESULT_QUERY['ACC_PRODUCT'] == 'Kontrak Gulir Valuta Asing/GO FOREX'){ echo 'visible';}else{echo 'hidden';}?>;" id="indexdiv3"><i class="fa fa-tag" aria-hidden="true" ></i>HKK50/HKJ50</div>
                <div class="col-sm-4" id="indexinpt3" style="visibility : <?php if($RESULT_QUERY['ACC_PRODUCT'] == 'Index Asia (Gulir)' || $RESULT_QUERY['ACC_PRODUCT'] == 'Kontrak Gulir Valuta Asing/GO FOREX'){ echo 'visible';}else{echo 'hidden';}?>;">
                    <select class="form-control text-center" name="hkk50" <?php if($RESULT_QUERY['ACC_PRODUCT'] == 'Index Asia (Gulir)' || $RESULT_QUERY['ACC_PRODUCT'] == 'Kontrak Gulir Valuta Asing/GO FOREX'){ echo 'required';}?>>
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
                <div class="col-sm-2 text-left" style="margin-block: auto; visibility : <?php if($RESULT_QUERY['ACC_PRODUCT'] == 'Index Asia (Gulir)' || $RESULT_QUERY['ACC_PRODUCT'] == 'Kontrak Gulir Valuta Asing/GO FOREX'){ echo 'visible';}else{echo 'hidden';}?>;" id="indexdiv4"><i class="fa fa-tag" aria-hidden="true" ></i>KRJ35</div>
                <div class="col-sm-4" id="indexinpt4" style="visibility : <?php if($RESULT_QUERY['ACC_PRODUCT'] == 'Index Asia (Gulir)' || $RESULT_QUERY['ACC_PRODUCT'] == 'Kontrak Gulir Valuta Asing/GO FOREX'){ echo 'visible';}else{echo 'hidden';}?>;">
                    <select class="form-control text-center" name="krj35" <?php if($RESULT_QUERY['ACC_PRODUCT'] == 'Index Asia (Gulir)' || $RESULT_QUERY['ACC_PRODUCT'] == 'Kontrak Gulir Valuta Asing/GO FOREX'){ echo 'required';}?>>
                        <option value="0">Pilih Nilai</option>
                        <option value="10">10</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-2" style="margin-block: auto;">Nilai Margin</div>
                <div class="col-sm-1 text-left" style="margin-block: auto;"><?php echo $RESULT_QUERY['ACC_CURR'] ?></div>
                <div class="col-sm-2" style="margin-block: auto;">
                    <input type="number" class="form-control text-center" name="margin" 
                        value="<?php 
                            echo $RESULT_QUERY['DPWD_AMOUNT']; 
                        ?>" 
                        id="inp_idr"
                        onkeyup="bagii()"
                        required
                    >
                </div>
                <div class="col-sm-2 text-left" style="margin-block: auto;">KURS JISDORS YANG BERLAKU</div>
                <div class="col-sm-3" style="margin-block: auto;">
                    <!-- <select name="" id="" class="form-control text-center" required>
                        <option value="10.000" <?php //if($RESULT_QUERY['ACC_RATE'] == '10000'){echo 'selected';}?>>10.000</option>
                        <option value="12.000" <?php //if($RESULT_QUERY['ACC_RATE'] == '12000'){echo 'selected';}?>>12.000</option>
                        <option value="14.000" <?php //if($RESULT_QUERY['ACC_RATE'] == '14000'){echo 'selected';}?>>14.000</option>
                        <option value="Custom" <?php //if($RESULT_QUERY['ACC_RATE'] == '0'){echo 'selected';}?>>Custom</option>
                    </select> -->
                    <input type="number" class="form-control text-center" name="acc_rate_kurs" id="inp_kurs" onkeyup="bagii()" value="<?php echo $RESULT_QUERY['ACC_RATE']; ?>">
                </div>
                <div class="col-sm-2">
                    <input type="text" class="form-control text-center" id="hasil_bagi" value="0" disabled>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3" style="margin-block: auto;">Introducing Broker</div>
                <div class="col-md-9">
                    <select name="ib" id="" class="form-control" required>
                        <option disabled selected value>Pilih Salah Satu</option>
                        <?php
                            $SQL_QUERY = mysqli_query($db,'
                                SELECT
                                    tb_ib.IB_ID,
                                    tb_ib.IB_NAME,
                                    tb_ib.IB_CODE,
                                    tb_ib.IB_CITY
                                FROM tb_ib
                                WHERE tb_ib.IB_STS = -1
                                ORDER BY tb_ib.IB_NAME ASC
                            ') or die(mysqli_error($db));
                            if(mysqli_num_rows($SQL_QUERY)){
                                while($RESULT_QUERY2 = mysqli_fetch_assoc($SQL_QUERY)){
                        ?>
                        <option value="<?php echo $RESULT_QUERY2['IB_ID'] ?>" <?php if($MBR_IB_CODE == $RESULT_QUERY2['IB_CODE']) { echo 'selected'; }; ?>>
                            <?php echo $RESULT_QUERY2['IB_NAME'] ?>-<?php echo $RESULT_QUERY2['IB_CODE'] ?>-<?php echo $RESULT_QUERY2['IB_CITY'] ?>
                        </option>
                        <?php
                                };
                            };
                        ?>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-1" style="margin-block: auto;">Note:</div>
                <div class="col-sm-11 text-left" style="margin-block: auto;">
                    <input type="text" class="form-control text-center" name="note" value=" " required>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" name="accept" class="btn btn-success">Accept</button>
            <button type="submit" name="reject" class="btn btn-danger">Reject</button>
        </div>
    </div>
    <script>
        function bagii() {
            let inpHasil = document.getElementById('hasil_bagi');
            let inpKurs  = document.getElementById('inp_kurs');
            let inpIdr   = document.getElementById('inp_idr');
            let hasil    = 0;

            hasil = Number(inpIdr.value) / Number(inpKurs.value);
            if(typeof hasil === "number" && hasil !== Infinity) {
                if(hasil > 0) {
                    inpHasil.value = hasil.toFixed(2);
                }else {
                    inpHasil.value = 0;
                }
            } else {
                inpHasil.value = 0;
            }
        }
        // var f = 'Forex dan Gold';
        // var 
        //     idxd1 = document.getElementById('indexdiv1'),
        //     idxd2 = document.getElementById('indexdiv2'),
        //     idxd3 = document.getElementById('indexdiv3'),
        //     idxd4 = document.getElementById('indexdiv4'),

        //     idxi1 = document.getElementById('indexinpt1'),
        //     idxi2 = document.getElementById('indexinpt2'),
        //     idxi3 = document.getElementById('indexinpt3'),
        //     idxi4 = document.getElementById('indexinpt4'),

        //     frxd1 = document.getElementById('forexdiv'),
        //     frxd2 = document.getElementById('forexdiv'),
        //     frxi1 = document.getElementById('forexinpt'),
        //     frxi2 = document.getElementById('forexinpt')
        // ;
        // if(f == "<?php //echo $RESULT_QUERY['ACC_PRODUCT']?>"){
        //     idxd1.style.visibility = 'hidden';
        //     idxd2.style.visibility = 'hidden';
        //     idxd3.style.visibility = 'hidden';
        //     idxd4.style.visibility = 'hidden';

        //     idxi1.style.visibility = 'hidden';
        //     idxi2.style.visibility = 'hidden';
        //     idxi3.style.visibility = 'hidden';
        //     idxi4.style.visibility = 'hidden';
            
        //     frxd1.style.visibility = 'visible';
        //     frxi2.style.visibility = 'visible';
        // }else{
        //     idxd1.style.visibility = 'visible';
        //     idxd2.style.visibility = 'visible';
        //     idxd3.style.visibility = 'visible';
        //     idxd4.style.visibility = 'visible';

        //     idxi1.style.visibility = 'visible';
        //     idxi2.style.visibility = 'visible';
        //     idxi3.style.visibility = 'visible';
        //     idxi4.style.visibility = 'visible';
            
        //     frxd1.style.visibility = 'hidden';
        //     frxi2.style.visibility = 'hidden';
        // };
    </script>
</form>
<?php 
    } else{
        die("<script>alert('Kepada ".$user1["ADM_NAME"].", anda tidak ada akses ke halaman ini');location.href = 'home.php?page=member_realacc'</script>");
    };
?>
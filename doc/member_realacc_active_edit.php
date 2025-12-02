
<?php

require '../vendor/autoload.php';
use Aws\S3\S3Client;
if($user1["ADM_LEVEL"] == 3 || $user1["ADM_LEVEL"] == 1){
        $region = 'ap-southeast-1';
        $bucketName = 'allmediaindo-2';
        $folder = 'ccftrader';
        $IAM_KEY = 'AKIASPLPQWHJMMXY2KPR';
        $IAM_SECRET = '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';

        $s3 = new Aws\S3\S3Client([
            'region'  => $region,
            'version' => 'latest',
            'credentials' => [
                'key'    => $IAM_KEY,
                'secret' => $IAM_SECRET,
            ]
        ]);	

        if(isset($_GET['id'])){
            $id = form_input($_GET['id']);
            $SQL_QUERY = mysqli_query($db, '
                SELECT
                    tb_racc.ACC_MBR,

                    tb_racc.ACC_LOGIN,

                    tb_racc.ACC_F_APP_PRIBADI_NAMA,
                    tb_racc.ACC_F_APP_PRIBADI_TMPTLHR,
                    DATE(tb_racc.ACC_F_APP_PRIBADI_TGLLHR) AS ACC_F_APP_PRIBADI_TGLLHR,
                    tb_racc.ACC_F_APP_PRIBADI_NPWP,
                    tb_racc.ACC_F_APP_PRIBADI_TYPEID,
                    tb_racc.ACC_F_APP_PRIBADI_ID,
                    tb_racc.ACC_F_APP_PRIBADI_KELAMIN,
                    tb_racc.ACC_F_APP_PRIBADI_STSRMH,
                    tb_racc.ACC_F_APP_PRIBADI_HP,
                    tb_racc.ACC_F_APP_PRIBADI_FAX,
                    tb_racc.ACC_F_APP_PRIBADI_TLP,
                    tb_racc.ACC_F_APP_PRIBADI_ZIP,
                    tb_racc.ACC_F_APP_PRIBADI_ALAMAT,
                    tb_racc.ACC_F_APP_PRIBADI_NAMAISTRI,
                    tb_racc.ACC_F_APP_PRIBADI_STSKAWIN,
                    tb_racc.ACC_F_APP_PRIBADI_IBU,
                    
                    tb_racc.ACC_F_APP_PENGINVT,
                    tb_racc.ACC_F_APP_TUJUANBUKA,
                    tb_racc.ACC_F_APP_KEKYAN,

                    tb_racc.ACC_F_APP_DRRT_NAMA,
                    tb_racc.ACC_F_APP_DRRT_ALAMAT,
                    tb_racc.ACC_F_APP_DRRT_ZIP,
                    tb_racc.ACC_F_APP_DRRT_TLP,
                    tb_racc.ACC_F_APP_DRRT_HUB,

                    tb_racc.ACC_F_APP_KRJ_TYPE,
                    tb_racc.ACC_F_APP_KRJ_NAMA,
                    tb_racc.ACC_F_APP_KRJ_BDNG,
                    tb_racc.ACC_F_APP_KRJ_JBTN,
                    tb_racc.ACC_F_APP_KRJ_LAMA,
                    tb_racc.ACC_F_APP_KRJ_LAMASBLM,
                    tb_racc.ACC_F_APP_KRJ_ALAMAT,
                    tb_racc.ACC_F_APP_KRJ_ZIP,
                    tb_racc.ACC_F_APP_KRJ_TLP,
                    tb_racc.ACC_F_APP_KRJ_FAX,

                    tb_racc.ACC_F_APP_KEKYAN_RMHLKS,
                    tb_racc.ACC_F_APP_KEKYAN_NJOP,
                    tb_racc.ACC_F_APP_KEKYAN_DPST,
                    tb_racc.ACC_F_APP_KEKYAN_NILAI,
                    tb_racc.ACC_F_APP_KEKYAN_LAIN,

                    tb_racc.ACC_F_APP_BK_1_NAMA,
                    tb_racc.ACC_F_APP_BK_1_CBNG,
                    tb_racc.ACC_F_APP_BK_1_ACC,
                    tb_racc.ACC_F_APP_BK_1_TLP,
                    tb_racc.ACC_F_APP_BK_1_JENIS,
                    tb_racc.ACC_F_APP_BK_2_NAMA,
                    tb_racc.ACC_F_APP_BK_2_CBNG,
                    tb_racc.ACC_F_APP_BK_2_ACC,
                    tb_racc.ACC_F_APP_BK_2_TLP,
                    tb_racc.ACC_F_APP_BK_2_JENIS,
                    
                    tb_racc.ACC_F_APP_FILE_IMG,
                    tb_racc.ACC_F_APP_FILE_IMG2,
                    tb_racc.ACC_F_APP_FILE_FOTO,
                    tb_racc.ACC_F_APP_FILE_ID
                FROM tb_racc
                WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id.'"
                AND tb_racc.ACC_LOGIN <> "0"
                AND tb_racc.ACC_WPCHECK = 6
            ');
            if ($SQL_QUERY && mysqli_num_rows($SQL_QUERY) > 0) {
                $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);

                if(isset($_POST['submit_pribadi'])){
                    if(isset($_POST['ACC_F_APP_PRIBADI_NAMA']) ||
                    isset($_POST['ACC_F_APP_PRIBADI_TMPTLHR']) ||
                    isset($_POST['ACC_F_APP_PRIBADI_TGLLHR']) ||
                    isset($_POST['ACC_F_APP_PRIBADI_TYPEID']) ||
                    isset($_POST['ACC_F_APP_PRIBADI_ID']) ||
                    isset($_POST['ACC_F_APP_PRIBADI_KELAMIN']) ||
                    isset($_POST['ACC_F_APP_PRIBADI_IBU']) ||
                    isset($_POST['ACC_F_APP_PRIBADI_STSKAWIN']) ||
                    isset($_POST['ACC_F_APP_PRIBADI_NAMAISTRI']) ||
                    isset($_POST['ACC_F_APP_PRIBADI_ALAMAT']) ||
                    isset($_POST['ACC_F_APP_PRIBADI_ZIP']) ||
                    isset($_POST['ACC_F_APP_PRIBADI_TLP']) ||
                    isset($_POST['ACC_F_APP_PRIBADI_TLP']) ||
                    isset($_POST['ACC_F_APP_PRIBADI_HP']) ||
                    isset($_POST['ACC_F_APP_PRIBADI_STSRMH']) ||
                    isset($_POST['ACC_F_APP_PRIBADI_NPWP'])){
                        $ACC_F_APP_PRIBADI_NAMA         = form_input($_POST['ACC_F_APP_PRIBADI_NAMA']);
                        $ACC_F_APP_PRIBADI_TMPTLHR      = form_input($_POST['ACC_F_APP_PRIBADI_TMPTLHR']);
                        $ACC_F_APP_PRIBADI_TGLLHR       = form_input($_POST['ACC_F_APP_PRIBADI_TGLLHR']);
                        $ACC_F_APP_PRIBADI_TYPEID       = form_input($_POST['ACC_F_APP_PRIBADI_TYPEID']);
                        $ACC_F_APP_PRIBADI_ID           = form_input($_POST['ACC_F_APP_PRIBADI_ID']);
                        $ACC_F_APP_PRIBADI_KELAMIN      = form_input($_POST['ACC_F_APP_PRIBADI_KELAMIN']);
                        $ACC_F_APP_PRIBADI_IBU          = form_input($_POST['ACC_F_APP_PRIBADI_IBU']);
                        $ACC_F_APP_PRIBADI_STSKAWIN     = form_input($_POST['ACC_F_APP_PRIBADI_STSKAWIN']);
                        $ACC_F_APP_PRIBADI_NAMAISTRI    = form_input($_POST['ACC_F_APP_PRIBADI_NAMAISTRI']);
                        $ACC_F_APP_PRIBADI_ALAMAT       = form_input($_POST['ACC_F_APP_PRIBADI_ALAMAT']);
                        $ACC_F_APP_PRIBADI_ZIP          = form_input($_POST['ACC_F_APP_PRIBADI_ZIP']);
                        $ACC_F_APP_PRIBADI_TLP          = form_input($_POST['ACC_F_APP_PRIBADI_TLP']);
                        $ACC_F_APP_PRIBADI_HP           = form_input($_POST['ACC_F_APP_PRIBADI_HP']);
                        $ACC_F_APP_PRIBADI_STSRMH       = form_input($_POST['ACC_F_APP_PRIBADI_STSRMH']);
                        $ACC_F_APP_PRIBADI_NPWP         = form_input($_POST['ACC_F_APP_PRIBADI_NPWP']);

                        mysqli_query($db,'
                            UPDATE tb_racc SET
                            tb_racc.ACC_F_APP_PRIBADI_NAMA      =   "'.$ACC_F_APP_PRIBADI_NAMA.'",
                            tb_racc.ACC_F_APP_PRIBADI_TMPTLHR   =   "'.$ACC_F_APP_PRIBADI_TMPTLHR.'",
                            tb_racc.ACC_F_APP_PRIBADI_TGLLHR    =   "'.$ACC_F_APP_PRIBADI_TGLLHR.'",
                            tb_racc.ACC_F_APP_PRIBADI_TYPEID    =   "'.$ACC_F_APP_PRIBADI_TYPEID.'",
                            tb_racc.ACC_F_APP_PRIBADI_ID        =   "'.$ACC_F_APP_PRIBADI_ID.'",
                            tb_racc.ACC_F_APP_PRIBADI_KELAMIN   =   "'.$ACC_F_APP_PRIBADI_KELAMIN.'",
                            tb_racc.ACC_F_APP_PRIBADI_IBU       =   "'.$ACC_F_APP_PRIBADI_IBU.'",
                            tb_racc.ACC_F_APP_PRIBADI_STSKAWIN  =   "'.$ACC_F_APP_PRIBADI_STSKAWIN.'",
                            tb_racc.ACC_F_APP_PRIBADI_NAMAISTRI =   "'.$ACC_F_APP_PRIBADI_NAMAISTRI.'",
                            tb_racc.ACC_F_APP_PRIBADI_ALAMAT    =   "'.$ACC_F_APP_PRIBADI_ALAMAT.'",
                            tb_racc.ACC_F_APP_PRIBADI_ZIP       =   '.$ACC_F_APP_PRIBADI_ZIP.',
                            tb_racc.ACC_F_APP_PRIBADI_TLP       =   "'.$ACC_F_APP_PRIBADI_TLP.'",
                            tb_racc.ACC_F_APP_PRIBADI_HP        =   "'.$ACC_F_APP_PRIBADI_HP.'",
                            tb_racc.ACC_F_APP_PRIBADI_STSRMH    =   "'.$ACC_F_APP_PRIBADI_STSRMH.'",
                            tb_racc.ACC_F_APP_PRIBADI_NPWP      =   "'.$ACC_F_APP_PRIBADI_NPWP.'"
                            WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id.'"
                            AND tb_racc.ACC_LOGIN = "'.$RESULT_QUERY['ACC_LOGIN'].'"
                            AND tb_racc.ACC_WPCHECK = 6
                        ') or die(mysqli_error($db));
                        insert_log($RESULT_QUERY['ACC_MBR'], 'Mengedit Data Pribadi Pada Login'.$RESULT_QUERY['ACC_LOGIN']);
                        die("<script>alert('Success Update Data');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>");
                    }
                }
                if(isset($_POST['submit_darurat'])){
                    if(isset($_POST['ACC_F_APP_DRRT_NAMA']) ||
                    isset($_POST['ACC_F_APP_DRRT_ALAMAT']) ||
                    isset($_POST['ACC_F_APP_DRRT_ZIP']) ||
                    isset($_POST['ACC_F_APP_DRRT_HUB']) ||
                    isset($_POST['ACC_F_APP_DRRT_TLP'])){
                        $ACC_F_APP_DRRT_NAMA    = form_input($_POST['ACC_F_APP_DRRT_NAMA']);
                        $ACC_F_APP_DRRT_ALAMAT  = form_input($_POST['ACC_F_APP_DRRT_ALAMAT']);
                        $ACC_F_APP_DRRT_ZIP     = form_input($_POST['ACC_F_APP_DRRT_ZIP']);
                        $ACC_F_APP_DRRT_TLP     = form_input($_POST['ACC_F_APP_DRRT_TLP']);
                        $ACC_F_APP_DRRT_HUB     = form_input($_POST['ACC_F_APP_DRRT_HUB']);
                        
                        $EXEC_SQL = mysqli_query($db,'
                            UPDATE tb_racc SET
                                tb_racc.ACC_F_APP_DRRT_NAMA     =   "'.$ACC_F_APP_DRRT_NAMA.'",
                                tb_racc.ACC_F_APP_DRRT_ALAMAT   =   "'.$ACC_F_APP_DRRT_ALAMAT.'",
                                tb_racc.ACC_F_APP_DRRT_ZIP      =   '.$ACC_F_APP_DRRT_ZIP.',
                                tb_racc.ACC_F_APP_DRRT_TLP      =   "'.$ACC_F_APP_DRRT_TLP.'",
                                tb_racc.ACC_F_APP_DRRT_HUB      =   "'.$ACC_F_APP_DRRT_HUB.'"
                            WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id.'"
                            AND tb_racc.ACC_LOGIN = "'.$RESULT_QUERY['ACC_LOGIN'].'"
                            AND tb_racc.ACC_WPCHECK = 6
                        ') or die(mysqli_error($db));
                        insert_log($RESULT_QUERY['ACC_MBR'], 'Mengedit Nama Darurat Pada Login'.$RESULT_QUERY['ACC_LOGIN']);
                        die("<script>alert('Success Update Data');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>");
                    }
                }
                if(isset($_POST['submit_kerja'])){
                    if(isset($_POST['ACC_F_APP_KRJ_TYPE']) ||
                    isset($_POST['ACC_F_APP_KRJ_NAMA']) ||
                    isset($_POST['ACC_F_APP_KRJ_BDNG']) ||
                    isset($_POST['ACC_F_APP_KRJ_JBTN']) ||
                    isset($_POST['ACC_F_APP_KRJ_LAMA']) ||
                    isset($_POST['ACC_F_APP_KRJ_LAMASBLM']) ||
                    isset($_POST['ACC_F_APP_KRJ_ALAMAT']) ||
                    isset($_POST['ACC_F_APP_KRJ_ZIP']) ||
                    isset($_POST['ACC_F_APP_KRJ_TLP']) ||
                    isset($_POST['ACC_F_APP_KRJ_FAX'])){
                        $ACC_F_APP_KRJ_TYPE     = form_input($_POST['ACC_F_APP_KRJ_TYPE']);
                        $ACC_F_APP_KRJ_NAMA     = form_input($_POST['ACC_F_APP_KRJ_NAMA']);
                        $ACC_F_APP_KRJ_BDNG     = form_input($_POST['ACC_F_APP_KRJ_BDNG']);
                        $ACC_F_APP_KRJ_JBTN     = form_input($_POST['ACC_F_APP_KRJ_JBTN']);
                        $ACC_F_APP_KRJ_LAMA     = form_input($_POST['ACC_F_APP_KRJ_LAMA']);
                        $ACC_F_APP_KRJ_LAMASBLM = form_input($_POST['ACC_F_APP_KRJ_LAMASBLM']);
                        $ACC_F_APP_KRJ_ALAMAT   = form_input($_POST['ACC_F_APP_KRJ_ALAMAT']);
                        $ACC_F_APP_KRJ_ZIP      = form_input($_POST['ACC_F_APP_KRJ_ZIP']);
                        $ACC_F_APP_KRJ_TLP      = form_input($_POST['ACC_F_APP_KRJ_TLP']);
                        $ACC_F_APP_KRJ_FAX      = form_input($_POST['ACC_F_APP_KRJ_FAX']);

                        $EXEC_SQL = mysqli_query($db,'
                            UPDATE tb_racc SET
                                tb_racc.ACC_F_APP_KRJ_TYPE      =   "'.$ACC_F_APP_KRJ_TYPE.'",
                                tb_racc.ACC_F_APP_KRJ_NAMA      =   "'.$ACC_F_APP_KRJ_NAMA.'",
                                tb_racc.ACC_F_APP_KRJ_BDNG      =   "'.$ACC_F_APP_KRJ_BDNG.'",
                                tb_racc.ACC_F_APP_KRJ_JBTN      =   "'.$ACC_F_APP_KRJ_JBTN.'",
                                tb_racc.ACC_F_APP_KRJ_LAMA      =   "'.$ACC_F_APP_KRJ_LAMA.'",
                                tb_racc.ACC_F_APP_KRJ_LAMASBLM  =   "'.$ACC_F_APP_KRJ_LAMASBLM.'",
                                tb_racc.ACC_F_APP_KRJ_ALAMAT    =   "'.$ACC_F_APP_KRJ_ALAMAT.'",
                                tb_racc.ACC_F_APP_KRJ_ZIP       =   "'.$ACC_F_APP_KRJ_ZIP.'",
                                tb_racc.ACC_F_APP_KRJ_TLP       =   "'.$ACC_F_APP_KRJ_TLP.'",
                                tb_racc.ACC_F_APP_KRJ_FAX       =   "'.$ACC_F_APP_KRJ_FAX.'"
                            WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id.'"
                            AND tb_racc.ACC_LOGIN = "'.$RESULT_QUERY['ACC_LOGIN'].'"
                            AND tb_racc.ACC_WPCHECK = 6
                        ') or die(mysqli_error($db));
                        insert_log($RESULT_QUERY['ACC_MBR'], 'Mengedit Pekerjaan Pada Login'.$RESULT_QUERY['ACC_LOGIN']);
                        die("<script>alert('Success Update Data');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>");
                    }
                }
                if(isset($_POST['submit_bank'])){
                    if(isset($_POST['ACC_F_APP_BK_1_NAMA']) ||
                    isset($_POST['ACC_F_APP_BK_1_CBNG']) ||
                    isset($_POST['ACC_F_APP_BK_1_ACC']) ||
                    isset($_POST['ACC_F_APP_BK_1_TLP']) ||
                    isset($_POST['ACC_F_APP_BK_1_JENIS'])){
                        $ACC_F_APP_BK_1_NAMA    = form_input($_POST['ACC_F_APP_BK_1_NAMA']);
                        $ACC_F_APP_BK_1_CBNG    = form_input($_POST['ACC_F_APP_BK_1_CBNG']);
                        $ACC_F_APP_BK_1_ACC     = form_input($_POST['ACC_F_APP_BK_1_ACC']);
                        $ACC_F_APP_BK_1_TLP     = form_input($_POST['ACC_F_APP_BK_1_TLP']);
                        $ACC_F_APP_BK_1_JENIS   = form_input($_POST['ACC_F_APP_BK_1_JENIS']);
                        
                        if(isset($_POST['ACC_F_APP_BK_2_NAMA'])){
                            $ACC_F_APP_BK_2_NAMA = form_input($_POST['ACC_F_APP_BK_2_NAMA']);
                        } else { $ACC_F_APP_BK_2_NAMA = ''; };

                        if(isset($_POST['ACC_F_APP_BK_2_CBNG'])){
                            $ACC_F_APP_BK_2_CBNG = form_input($_POST['ACC_F_APP_BK_2_CBNG']);
                        } else { $ACC_F_APP_BK_2_CBNG = ''; };

                        if(isset($_POST['ACC_F_APP_BK_2_ACC'])){
                            $ACC_F_APP_BK_2_ACC = form_input($_POST['ACC_F_APP_BK_2_ACC']);
                        } else { $ACC_F_APP_BK_2_ACC = ''; };

                        if(isset($_POST['ACC_F_APP_BK_2_TLP'])){
                            $ACC_F_APP_BK_2_TLP = form_input($_POST['ACC_F_APP_BK_2_TLP']);
                        } else { $ACC_F_APP_BK_2_TLP = ''; };
                        
                        if(isset($_POST['ACC_F_APP_BK_2_JENIS'])){
                            $ACC_F_APP_BK_2_JENIS = form_input($_POST['ACC_F_APP_BK_2_JENIS']);
                        } else { $ACC_F_APP_BK_2_JENIS = ''; };

                        mysqli_query($db,'
                            UPDATE tb_racc SET
                            tb_racc.ACC_F_APP_BK_1_NAMA     = "'.$ACC_F_APP_BK_1_NAMA.'",
                            tb_racc.ACC_F_APP_BK_1_CBNG     = "'.$ACC_F_APP_BK_1_CBNG.'",
                            tb_racc.ACC_F_APP_BK_1_ACC      = "'.$ACC_F_APP_BK_1_ACC.'",
                            tb_racc.ACC_F_APP_BK_1_TLP      = "'.$ACC_F_APP_BK_1_TLP.'",
                            tb_racc.ACC_F_APP_BK_1_JENIS    = "'.$ACC_F_APP_BK_1_JENIS.'",
                            tb_racc.ACC_F_APP_BK_2_NAMA     = "'.$ACC_F_APP_BK_2_NAMA.'",
                            tb_racc.ACC_F_APP_BK_2_CBNG     = "'.$ACC_F_APP_BK_2_CBNG.'",
                            tb_racc.ACC_F_APP_BK_2_ACC      = "'.$ACC_F_APP_BK_2_ACC.'",
                            tb_racc.ACC_F_APP_BK_2_TLP      = "'.$ACC_F_APP_BK_2_TLP.'",
                            tb_racc.ACC_F_APP_BK_2_JENIS    = "'.$ACC_F_APP_BK_2_JENIS.'"
                            WHERE MD5(MD5(tb_racc.ID_ACC))  = "'.$id.'"
                            AND tb_racc.ACC_LOGIN = "'.$RESULT_QUERY['ACC_LOGIN'].'"
                            AND tb_racc.ACC_WPCHECK = 6
                        ') or die(mysqli_error($db));
                        insert_log($RESULT_QUERY['ACC_MBR'], 'Mengedit Bank Pada Login'.$RESULT_QUERY['ACC_LOGIN']);
                        die("<script>alert('Success Update Data');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>");
                        
                    }
                }
                if(isset($_POST['submit_kekayaan'])){
                    if(isset($_POST['ACC_F_APP_KEKYAN_RMHLKS']) ||
                    isset($_POST['ACC_F_APP_KEKYAN_NJOP']) ||
                    isset($_POST['ACC_F_APP_KEKYAN_DPST']) ||
                    isset($_POST['ACC_F_APP_KEKYAN_NILAI']) ||
                    isset($_POST['ACC_F_APP_KEKYAN_LAIN'])){
                        $ACC_F_APP_KEKYAN_RMHLKS = form_input($_POST['ACC_F_APP_KEKYAN_RMHLKS']);
                        $ACC_F_APP_KEKYAN_NJOP = form_input($_POST['ACC_F_APP_KEKYAN_NJOP']);
                        $ACC_F_APP_KEKYAN_DPST = form_input($_POST['ACC_F_APP_KEKYAN_DPST']);
                        $ACC_F_APP_KEKYAN_NILAI = form_input($_POST['ACC_F_APP_KEKYAN_NILAI']);
                        $ACC_F_APP_KEKYAN_LAIN = form_input($_POST['ACC_F_APP_KEKYAN_LAIN']);
                        
                        mysqli_query($db,'
                            UPDATE tb_racc SET
                            tb_racc.ACC_F_APP_KEKYAN_RMHLKS =   "'.$ACC_F_APP_KEKYAN_RMHLKS.'",
                            tb_racc.ACC_F_APP_KEKYAN_NJOP   =   "'.$ACC_F_APP_KEKYAN_NJOP.'",
                            tb_racc.ACC_F_APP_KEKYAN_DPST   =   "'.$ACC_F_APP_KEKYAN_DPST.'",
                            tb_racc.ACC_F_APP_KEKYAN_NILAI  =   "'.$ACC_F_APP_KEKYAN_NILAI.'",
                            tb_racc.ACC_F_APP_KEKYAN_LAIN   =   "'.$ACC_F_APP_KEKYAN_LAIN.'"
                            WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id.'"
                            AND tb_racc.ACC_LOGIN = "'.$RESULT_QUERY['ACC_LOGIN'].'"
                            AND tb_racc.ACC_WPCHECK = 6
                        ') or die(mysqli_error($db));
                        insert_log($RESULT_QUERY['ACC_MBR'], 'Mengedit Kekayaan Pada Login'.$RESULT_QUERY['ACC_LOGIN']);
                        die("<script>alert('Success Update Data');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>");
                    }
                }
                if(isset($_POST['submit_other'])){
                    if(isset($_POST['ACC_F_APP_TUJUANBUKA']) ||
                    isset($_POST['ACC_F_APP_KEKYAN']) ||
                    isset($_POST['ACC_F_APP_PENGINVT'])){
                        $ACC_F_APP_TUJUANBUKA = form_input($_POST['ACC_F_APP_TUJUANBUKA']);
                        $ACC_F_APP_PENGINVT = form_input($_POST['ACC_F_APP_PENGINVT']);
                        $ACC_F_APP_KEKYAN = form_input($_POST['ACC_F_APP_KEKYAN']);
                        
                        mysqli_query($db,'
                            UPDATE tb_racc SET
                            tb_racc.ACC_F_APP_TUJUANBUKA    =   "'.$ACC_F_APP_TUJUANBUKA.'",
                            tb_racc.ACC_F_APP_PENGINVT      =   "'.$ACC_F_APP_PENGINVT.'",
                            tb_racc.ACC_F_APP_KEKYAN        =   "'.$ACC_F_APP_KEKYAN.'"
                            WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id.'"
                            AND tb_racc.ACC_LOGIN = "'.$RESULT_QUERY['ACC_LOGIN'].'"
                            AND tb_racc.ACC_WPCHECK = 6
                        ') or die(mysqli_error($db));
                        insert_log($RESULT_QUERY['ACC_MBR'], 'Mengedit Tujuan Pembukaan Rekening Pada Login'.$RESULT_QUERY['ACC_LOGIN']);
                        die("<script>alert('Success Update Data');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>");
                    }
                }
                if(isset($_POST['submit_picture'])){
                    $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png");
                    if(isset($_FILES["s5_6_doc1"]) && $_FILES["s5_6_doc1"]["error"] == 0){
                        
                        $s5_6_doc1_name = $_FILES["s5_6_doc1"]["name"];
                        $s5_6_doc1_type = $_FILES["s5_6_doc1"]["type"];
                        $s5_6_doc1_size = $_FILES["s5_6_doc1"]["size"];
                        
                        $s5_6_doc1_ext = pathinfo($s5_6_doc1_name, PATHINFO_EXTENSION);
                        
                        if($s5_6_doc1_size < 5000000) {
                            if(array_key_exists($s5_6_doc1_ext, $allowed)){
                                if(in_array($s5_6_doc1_type, $allowed)){
                                    $s5_6_doc1_new = 'doc1_'.$RESULT_QUERY['ACC_MBR'].'_'.round(microtime(true)).'.'.$s5_6_doc1_ext;
                                    if(move_uploaded_file($_FILES["s5_6_doc1"]["tmp_name"], "upload/" . $s5_6_doc1_new)){
                                        
                                        $s5_6_doc1_Path = 'upload/'. $s5_6_doc1_new;
                                        $s5_6_doc1_key = basename($s5_6_doc1_Path);

                                        try {
                                            $result = $s3->putObject([
                                                'Bucket' => $bucketName,
                                                'Key'    => $folder.'/'.$s5_6_doc1_key,
                                                'Body'   => fopen($s5_6_doc1_Path, 'r'),
                                                'ACL'    => 'public-read', // make file 'public'
                                            ]);
                                            mysqli_query($db, '
                                                UPDATE tb_racc SET
                                                tb_racc.ACC_F_APP_FILE_IMG = "'.$s5_6_doc1_new.'"
                                                WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id.'"
                                            ') or die (mysqli_error($db));
                                            insert_log($RESULT_QUERY['ACC_MBR'], 'Mengedit Dokumen Pendukung 1 Pada Login'.$RESULT_QUERY['ACC_LOGIN']);
                                            unlink($s5_6_doc1_Path);
                                            $s5_6_doc1_sts = -1;
                                        } catch (Aws\S3\Exception\S3Exception $e) {
                                            die("<script>alert('Error Uploading');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>");
                                        }
                                    } else { die("<script>alert('Failed Upload');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>"); }
                                } else { die("<script>alert('check type image 1');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>"); }
                            } else { die("<script>alert('check type image 2');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>"); }
                        } else { die("<script>alert('check file size');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>"); }
                    };
                    if(isset($_FILES["s5_6_doc2"]) && $_FILES["s5_6_doc2"]["error"] == 0){
                            
                        $s5_6_doc2_name = $_FILES["s5_6_doc2"]["name"];
                        $s5_6_doc2_type = $_FILES["s5_6_doc2"]["type"];
                        $s5_6_doc2_size = $_FILES["s5_6_doc2"]["size"];
                        
                        $s5_6_doc2_ext = pathinfo($s5_6_doc2_name, PATHINFO_EXTENSION);
                        
                        if($s5_6_doc2_size < 5000000) {
                            if(array_key_exists($s5_6_doc2_ext, $allowed)){
                                if(in_array($s5_6_doc2_type, $allowed)){
                                    $s5_6_doc2_new = 'doc2_'.$RESULT_QUERY['ACC_MBR'].'_'.round(microtime(true)).'.'.$s5_6_doc2_ext;
                                    if(move_uploaded_file($_FILES["s5_6_doc2"]["tmp_name"], "upload/" . $s5_6_doc2_new)){
                                        
                                        $s5_6_doc2_Path = 'upload/'. $s5_6_doc2_new;
                                        $s5_6_doc2_key = basename($s5_6_doc2_Path);

                                        try {
                                            $result = $s3->putObject([
                                                'Bucket' => $bucketName,
                                                'Key'    => $folder.'/'.$s5_6_doc2_key,
                                                'Body'   => fopen($s5_6_doc2_Path, 'r'),
                                                'ACL'    => 'public-read', // make file 'public'
                                            ]);
                                            mysqli_query($db, '
                                                UPDATE tb_racc SET
                                                tb_racc.ACC_F_APP_FILE_IMG2 = "'.$s5_6_doc2_new.'"
                                                WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id.'"
                                            ') or die (mysqli_error($db));
                                            insert_log($RESULT_QUERY['ACC_MBR'], 'Mengedit Dokumen Pendukung 2 Pada Login'.$RESULT_QUERY['ACC_LOGIN']);
                                            unlink($s5_6_doc2_Path);
                                            $s5_6_doc2_sts = -1;
                                        } catch (Aws\S3\Exception\S3Exception $e) {
                                            die("<script>alert('Error Uploading');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>");
                                        }
                                    } else { die("<script>alert('.Failed Upload');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>"); }
                                } else { die("<script>alert('.check type image 1');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>"); }
                            } else { die("<script>alert('.check type image 2');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>"); }
                        } else { die("<script>alert('.check file size');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>"); }
                    };
                    if(isset($_FILES["s5_6_fotoself"]) && $_FILES["s5_6_fotoself"]["error"] == 0){
                        
                        $s5_6_fotoself_name = $_FILES["s5_6_fotoself"]["name"];
                        $s5_6_fotoself_type = $_FILES["s5_6_fotoself"]["type"];
                        $s5_6_fotoself_size = $_FILES["s5_6_fotoself"]["size"];
                        
                        $s5_6_fotoself_ext = pathinfo($s5_6_fotoself_name, PATHINFO_EXTENSION);
                        
                        if($s5_6_fotoself_size < 5000000) {
                            if(array_key_exists($s5_6_fotoself_ext, $allowed)){
                                if(in_array($s5_6_fotoself_type, $allowed)){
                                    $s5_6_fotoself_new = 'self_'.$RESULT_QUERY['ACC_MBR'].'_'.round(microtime(true)).'.'.$s5_6_fotoself_ext;
                                    if(move_uploaded_file($_FILES["s5_6_fotoself"]["tmp_name"], "upload/" . $s5_6_fotoself_new)){
                                        
                                        $s5_6_fotoself_Path = 'upload/'. $s5_6_fotoself_new;
                                        $s5_6_fotoself_key = basename($s5_6_fotoself_Path);

                                        try {
                                            $result = $s3->putObject([
                                                'Bucket' => $bucketName,
                                                'Key'    => $folder.'/'.$s5_6_fotoself_key,
                                                'Body'   => fopen($s5_6_fotoself_Path, 'r'),
                                                'ACL'    => 'public-read', // make file 'public'
                                            ]);
                                            mysqli_query($db, '
                                                UPDATE tb_racc SET
                                                tb_racc.ACC_F_APP_FILE_FOTO = "'.$s5_6_fotoself_new.'"
                                                WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id.'"
                                            ') or die (mysqli_error($db));
                                            insert_log($RESULT_QUERY['ACC_MBR'], 'Mengedit Dokumen Pendukung 2 Pada Login'.$RESULT_QUERY['ACC_LOGIN']);
                                            unlink($s5_6_fotoself_Path);
                                            $s5_6_fotoself_sts = -1;
                                        } catch (Aws\S3\Exception\S3Exception $e) {
                                            die("<script>alert('Error Uploading');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>");
                                        }
                                    } else { die("<script>alert('.Failed Upload');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>"); }
                                } else { die("<script>alert('.check type image 1');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>"); }
                            } else { die("<script>alert('.check type image 2');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>"); }
                        } else { die("<script>alert('.check file size');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>"); }
                    };
                    if(isset($_FILES["s5_6_fotoid"]) && $_FILES["s5_6_fotoid"]["error"] == 0){
                        
                        $s5_6_fotoid_name = $_FILES["s5_6_fotoid"]["name"];
                        $s5_6_fotoid_type = $_FILES["s5_6_fotoid"]["type"];
                        $s5_6_fotoid_size = $_FILES["s5_6_fotoid"]["size"];
                        
                        $s5_6_fotoid_ext = pathinfo($s5_6_fotoid_name, PATHINFO_EXTENSION);
                        
                        if($s5_6_fotoid_size < 5000000) {
                            if(array_key_exists($s5_6_fotoid_ext, $allowed)){
                                if(in_array($s5_6_fotoid_type, $allowed)){
                                    $s5_6_fotoid_new = 'id_'.$RESULT_QUERY['ACC_MBR'].'_'.round(microtime(true)).'.'.$s5_6_fotoid_ext;
                                    if(move_uploaded_file($_FILES["s5_6_fotoid"]["tmp_name"], "upload/" . $s5_6_fotoid_new)){
                                        
                                        $s5_6_fotoid_Path = 'upload/'. $s5_6_fotoid_new;
                                        $s5_6_fotoid_key = basename($s5_6_fotoid_Path);

                                        try {
                                            $result = $s3->putObject([
                                                'Bucket' => $bucketName,
                                                'Key'    => $folder.'/'.$s5_6_fotoid_key,
                                                'Body'   => fopen($s5_6_fotoid_Path, 'r'),
                                                'ACL'    => 'public-read', // make file 'public'
                                            ]);
                                            mysqli_query($db, '
                                                UPDATE tb_racc SET
                                                tb_racc.ACC_F_APP_FILE_ID = "'.$s5_6_fotoid_new.'"
                                                WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id.'"
                                            ') or die (mysqli_error($db));
                                            insert_log($RESULT_QUERY['ACC_MBR'], 'Mengedit Identitas Pada Login'.$RESULT_QUERY['ACC_LOGIN']);
                                            unlink($s5_6_fotoid_Path);
                                            $s5_6_fotoid_sts = -1;
                                        } catch (Aws\S3\Exception\S3Exception $e) {
                                            die("<script>alert('Error Uploading');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>");
                                        }
                                    } else { die("<script>alert('.Failed Upload');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>"); }
                                } else { die("<script>alert('.check type image 1');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>"); }
                            } else { die("<script>alert('.check type image 2');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>"); }
                        } else { die("<script>alert('.check file size');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>"); }
                    };
                    
                    die("<script>alert('Success Update Data');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>");
                };
            } else { die("<script>alert('data not allow to edit / account already active');location.href = 'home.php?page=member_active'</script>"); }
        } else { die("<script>alert('please check link again');location.href = 'home.php?page=member_realacc_active_edit&id=".$id."'</script>"); }
?>
<!-- ================================================================ -->
<ul id="tabs" class="nav nav-tabs nav-fill">
    <li class="nav-item"><a href="#profile_pribadi" data-target="#profile_pribadi" data-toggle="tab" class="nav-link active"><strong>Profile Pribadi <?php echo '('.$RESULT_QUERY['ACC_LOGIN'].')'?></strong></a></li>
    <li class="nav-item"><a href="#other" data-target="#other" data-toggle="tab" class="nav-link "><strong>Other</strong></a></li>
    <li class="nav-item"><a href="#kontak_darurat" data-target="#kontak_darurat" data-toggle="tab" class="nav-link "><strong>Kontak Darurat</strong></a></li>
    <li class="nav-item"><a href="#kerja" data-target="#kerja" data-toggle="tab" class="nav-link"><strong>Kerja</strong></a></li>
    <li class="nav-item"><a href="#kekayaan" data-target="#kekayaan" data-toggle="tab" class="nav-link"><strong>Kekayaan</strong></a></li>
    <li class="nav-item"><a href="#bank" data-target="#bank" data-toggle="tab" class="nav-link"><strong>Bank</strong></a></li>
    <li class="nav-item"><a href="#picture" data-target="#picture" data-toggle="tab" class="nav-link"><strong>Picture</strong></a></li>
</ul>
<div id="tabsContent" class="tab-content">
    <div id="profile_pribadi" class="tab-pane fade active show">
        <div class="card mb-3">
            <form method="post">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="ACC_F_APP_PRIBADI_NAMA" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_PRIBADI_NAMA']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tempat Lahir</label>
                                <input type="text" name="ACC_F_APP_PRIBADI_TMPTLHR" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_PRIBADI_TMPTLHR']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Lahir</label>
                                <input type="date" name="ACC_F_APP_PRIBADI_TGLLHR" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_PRIBADI_TGLLHR']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>NPWP</label>
                                <input type="text" name="ACC_F_APP_PRIBADI_NPWP" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_PRIBADI_NPWP']; ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Type ID</label>
                                <select class="form-control" name="ACC_F_APP_PRIBADI_TYPEID" required>
                                    <option value="KTP" <?php if($RESULT_QUERY['ACC_F_APP_PRIBADI_TYPEID'] == 'KTP'){ echo 'selected';}?>>KTP</option>
                                    <option value="Passport" <?php if($RESULT_QUERY['ACC_F_APP_PRIBADI_TYPEID'] == 'Passport'){ echo 'selected';}?>>Passport</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>ID Number</label>
                                <input type="text" name="ACC_F_APP_PRIBADI_ID" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_PRIBADI_ID']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <select class="form-control" name="ACC_F_APP_PRIBADI_KELAMIN">
                                    <option <?php if($RESULT_QUERY['ACC_F_APP_PRIBADI_KELAMIN'] == 'Laki-laki'){ echo 'selected';}?> value="Laki-laki">Laki-laki</option>
                                    <option <?php if($RESULT_QUERY['ACC_F_APP_PRIBADI_KELAMIN'] == 'Perempuan'){ echo 'selected';}?> value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ibu Kandung</label>
                                <input type="text" name="ACC_F_APP_PRIBADI_IBU" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_PRIBADI_IBU']; ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status Perkawinan</label>
                                <select class="form-control" name="ACC_F_APP_PRIBADI_STSKAWIN">
                                    <option <?php if($RESULT_QUERY['ACC_F_APP_PRIBADI_STSKAWIN'] == 'Tidak Kawin'){ echo 'selected';}?> value="Tidak Kawin">Tidak Kawin</option>
                                    <option <?php if($RESULT_QUERY['ACC_F_APP_PRIBADI_STSKAWIN'] == 'Kawin'){ echo 'selected';}?> value="Kawin">Kawin</option>
                                    <option <?php if($RESULT_QUERY['ACC_F_APP_PRIBADI_STSKAWIN'] == 'Janda'){ echo 'selected';}?> value="Janda">Janda</option>
                                    <option <?php if($RESULT_QUERY['ACC_F_APP_PRIBADI_STSKAWIN'] == 'Duda'){ echo 'selected';}?> value="Duda">Duda</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nama Suami/Istri</label>
                                <input type="text" name="ACC_F_APP_PRIBADI_NAMAISTRI" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_PRIBADI_NAMAISTRI']; ?>" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Alamat</label>
                                <input type="text" name="ACC_F_APP_PRIBADI_ALAMAT" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_PRIBADI_ALAMAT']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Kode Pos</label>
                                <input type="number" name="ACC_F_APP_PRIBADI_ZIP" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_PRIBADI_ZIP']; ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nomor Telephone</label>
                                <input type="text" name="ACC_F_APP_PRIBADI_TLP" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_PRIBADI_TLP']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nomor Faksimili</label>
                                <input type="text" name="ACC_F_APP_PRIBADI_FAX" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_PRIBADI_FAX']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nomor Handphone</label>
                                <input type="text" name="ACC_F_APP_PRIBADI_HP" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_PRIBADI_HP']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status Kepemilikan Rumah</label>
                                <select class="form-control" name="ACC_F_APP_PRIBADI_STSRMH" required>
                                    <option <?php if($RESULT_QUERY['ACC_F_APP_PRIBADI_STSRMH'] == 'Pribadi'){ echo 'selected';}?> value="Pribadi">Pribadi</option>
                                    <option <?php if($RESULT_QUERY['ACC_F_APP_PRIBADI_STSRMH'] == 'Keluarga'){ echo 'selected';}?> value="Keluarga">Keluarga</option>
                                    <option <?php if($RESULT_QUERY['ACC_F_APP_PRIBADI_STSRMH'] == 'Sewa'){ echo 'selected';}?> value="Sewa">Sewa</option>
                                    <option <?php if($RESULT_QUERY['ACC_F_APP_PRIBADI_STSRMH'] == 'Lainnya'){ echo 'selected';}?> value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" name="submit_pribadi">Edit Data</button>
                </div>
            </form>
        </div>
    </div>
    <div id="other" class="tab-pane fade">
        <div class="card mb-3">
            <form method="post">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tujuan Pembukaan Rekening</label>
                                <select class="form-control" name="ACC_F_APP_TUJUANBUKA" required>
                                    <option value="Lindungi nilai" <?php if($RESULT_QUERY['ACC_F_APP_TUJUANBUKA'] == 'Lindungi nilai'){ echo 'selected';}?>>Lindungi nilai</option>
                                    <option value="Gain" <?php if($RESULT_QUERY['ACC_F_APP_TUJUANBUKA'] == 'Gain'){ echo 'selected';}?>>Gain</option>
                                    <option value="Spekulasi" <?php if($RESULT_QUERY['ACC_F_APP_TUJUANBUKA'] == 'Spekulasi'){ echo 'selected';}?>>Spekulasi</option>
                                    <option value="Lainnya" <?php if($RESULT_QUERY['ACC_F_APP_TUJUANBUKA'] == 'Lainnya'){ echo 'selected';}?>>Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pengalaman Investasi</label>
                                <select class="form-control" name="ACC_F_APP_PENGINVT" required>
                                    <option value="Ya" <?php if($RESULT_QUERY['ACC_F_APP_PENGINVT'] == 'Ya'){ echo 'selected';}?>>Ya</option>
                                    <option value="Tidak" <?php if($RESULT_QUERY['ACC_F_APP_PENGINVT'] == 'Tidak'){ echo 'selected';}?>>Tidak</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>DAFTAR KEKAYAAN /thn</label>
                                <select class="form-control" name="ACC_F_APP_KEKYAN" required>
                                    <option value="Antara 100-250 juta" <?php if($RESULT_QUERY['ACC_F_APP_KEKYAN'] == 'Antara 100-250 juta'){ echo 'selected';}?>>Antara 100-250 juta</option>
                                    <option value="Antara 250-500 juta" <?php if($RESULT_QUERY['ACC_F_APP_KEKYAN'] == 'Antara 250-500 juta'){ echo 'selected';}?>>Antara 250-500 juta</option>
                                    <option value="Diatas 500 juta rupiah" <?php if($RESULT_QUERY['ACC_F_APP_KEKYAN'] == 'Diatas 500 juta rupiah'){ echo 'selected';}?>>Diatas 500 juta rupiah</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" name="submit_other">Edit Data</button>
                </div>
            </form>
        </div>
    </div>
    <div id="kontak_darurat" class="tab-pane fade ">
        <div class="card mb-3">
            <form method="post">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="ACC_F_APP_DRRT_NAMA" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_DRRT_NAMA']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Alamat</label>
                                <input type="text" name="ACC_F_APP_DRRT_ALAMAT" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_DRRT_ALAMAT']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Kode Pos</label>
                                <input type="number" name="ACC_F_APP_DRRT_ZIP" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_DRRT_ZIP']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Tlp</label>
                                <input type="text" name="ACC_F_APP_DRRT_TLP" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_DRRT_TLP']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Hubungan</label>
                                <input type="text" name="ACC_F_APP_DRRT_HUB" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_DRRT_HUB']; ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" name="submit_darurat">Edit Data</button>
                </div>
            </form>
        </div>
    </div>
    <div id="kerja" class="tab-pane fade">
        <div class="card">
            <form method="post">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Kerja Type</label>
                                <input type="text" name="ACC_F_APP_KRJ_TYPE" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_KRJ_TYPE']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nama Perusahaan</label>
                                <input type="text" name="ACC_F_APP_KRJ_NAMA" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_KRJ_NAMA']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Bidang Pekerjaan</label>
                                <input type="text" name="ACC_F_APP_KRJ_BDNG" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_KRJ_BDNG']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Jabatan</label>
                                <input type="text" name="ACC_F_APP_KRJ_JBTN" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_KRJ_JBTN']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>No Tlp</label>
                                <input type="text" name="ACC_F_APP_KRJ_TLP" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_KRJ_TLP']; ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Lama Bekerja</label>
                                <input type="text" name="ACC_F_APP_KRJ_LAMA" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_KRJ_LAMA']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Kantor sebelumnya</label>
                                <input type="text" name="ACC_F_APP_KRJ_LAMASBLM" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_KRJ_LAMASBLM']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Alamat Tempat Kerja</label>
                                <input type="text" name="ACC_F_APP_KRJ_ALAMAT" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_KRJ_ALAMAT']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Kode POS </label>
                                <input type="text" name="ACC_F_APP_KRJ_ZIP" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_KRJ_ZIP']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fax</label>
                                <input type="text" name="ACC_F_APP_KRJ_FAX" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_KRJ_FAX']; ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" name="submit_kerja">Edit Data</button>
                </div>
            </form>
        </div>
    </div>
    <div id="kekayaan" class="tab-pane fade">
        <div class="card">
            <form method="post">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Rumah Lokasi</label>
                                <input type="text" name="ACC_F_APP_KEKYAN_RMHLKS" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_KEKYAN_RMHLKS']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nilai NJOP</label>
                                <input type="text" name="ACC_F_APP_KEKYAN_NJOP" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_KEKYAN_NJOP']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Deposit Bank</label>
                                <input type="text" name="ACC_F_APP_KEKYAN_DPST" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_KEKYAN_DPST']; ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah</label>
                                <input type="text" name="ACC_F_APP_KEKYAN_NILAI" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_KEKYAN_NILAI']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Kekayaan Lainnya</label>
                                <select class="form-control" name="ACC_F_APP_KEKYAN_LAIN" required>
                                    <option <?php if($RESULT_QUERY["ACC_F_APP_KEKYAN_LAIN"] == 'Antara Rp. 100 - 250 juta'){ echo 'selected';}?> value="Antara Rp. 100 - 250 juta">Antara Rp. 100 - 250 juta</option>
                                    <option <?php if($RESULT_QUERY["ACC_F_APP_KEKYAN_LAIN"] == 'Antara Rp. 250 - 500 juta'){ echo 'selected';}?> value="Antara Rp. 250 - 500 juta">Antara Rp. 250 - 500 juta</option>
                                    <option <?php if($RESULT_QUERY["ACC_F_APP_KEKYAN_LAIN"] == 'Di atas Rp. 500 juta'){ echo 'selected';}?> value="Di atas Rp. 500 juta">Di atas Rp. 500 juta</option>
                                    <option <?php if($RESULT_QUERY["ACC_F_APP_KEKYAN_LAIN"] == 'Tidak ada'){ echo 'selected';}?> value="Tidak ada">Tidak ada</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" name="submit_kekayaan">Edit Data</button>
                </div>
            </form>
        </div>
    </div>
    <div id="bank" class="tab-pane fade">
        <div class="card">
            <form method="post">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Bank Nama</label>
                                <select class="form-control custom-select" name="ACC_F_APP_BK_1_NAMA" required>
                                    <option disabled selected value>Nama Bank</option>
                                    <?php
                                        $SQL_BANK = mysqli_query($db, '
                                            SELECT tb_banklist.BANKLST_NAME
                                            FROM tb_banklist
                                        ');
                                        if ($SQL_BANK && mysqli_num_rows($SQL_BANK) > 0) {
                                            while($RESULT_BANK = mysqli_fetch_assoc($SQL_BANK)){
                                    ?>
                                        <option <?php if($RESULT_QUERY['ACC_F_APP_BK_1_NAMA'] == $RESULT_BANK['BANKLST_NAME']){ echo 'selected'; } ?> value="<?php echo $RESULT_BANK['BANKLST_NAME'] ?>"><?php echo $RESULT_BANK['BANKLST_NAME'] ?></option>
                                    <?php };}; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cabang Bank</label>
                                <input type="text" name="ACC_F_APP_BK_1_CBNG" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_BK_1_CBNG']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Nomor Rekening</label>
                                <input type="text" name="ACC_F_APP_BK_1_ACC" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_BK_1_ACC']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Nomor Telephone</label>
                                <input type="number" name="ACC_F_APP_BK_1_TLP" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_BK_1_TLP']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Jenis Bank</label>
                                <select class="form-control" name="ACC_F_APP_BK_1_JENIS" required>
                                    <option disabled selected value>Jenis Bank</option>
                                    <option <?php if($RESULT_QUERY["ACC_F_APP_BK_1_JENIS"] == 'Giro'){echo 'selected';}?> value="Giro">Giro</option>
                                    <option <?php if($RESULT_QUERY["ACC_F_APP_BK_1_JENIS"] == 'Tabungan'){echo 'selected';}?> value="Tabungan">Tabungan</option>
                                    <option <?php if($RESULT_QUERY["ACC_F_APP_BK_1_JENIS"] == 'Lainya'){echo 'selected';}?> value="Lainya">Lainya</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Bank Nama 2</label>
                                <select class="form-control custom-select" name="ACC_F_APP_BK_2_NAMA" >
                                    <option disabled selected value>Nama Bank</option>
                                    <?php
                                        $SQL_BANK2 = mysqli_query($db, '
                                            SELECT tb_banklist.BANKLST_NAME
                                            FROM tb_banklist
                                        ');
                                        if ($SQL_BANK2 && mysqli_num_rows($SQL_BANK2) > 0) {
                                            while($RESULT_BANK2 = mysqli_fetch_assoc($SQL_BANK2)){
                                    ?>
                                        <option <?php if($RESULT_QUERY['ACC_F_APP_BK_2_NAMA'] == $RESULT_BANK2['BANKLST_NAME']){ echo 'selected'; } ?> value="<?php echo $RESULT_BANK2['BANKLST_NAME'] ?>"><?php echo $RESULT_BANK2['BANKLST_NAME'] ?></option>
                                    <?php };}; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cabang Bank 2</label>
                                <input type="text" name="ACC_F_APP_BK_2_CBNG" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_BK_2_CBNG']; ?>" >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Nomor Rekening 2</label>
                                <input type="text" name="ACC_F_APP_BK_2_ACC" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_BK_2_ACC']; ?>" >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Nomor Telephone 2</label>
                                <input type="number" name="ACC_F_APP_BK_2_TLP" class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_BK_2_TLP']; ?>" >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Jenis Bank 2</label>
                                <select class="form-control" name="ACC_F_APP_BK_2_JENIS" >
                                    <option disabled selected value>Jenis Bank</option>
                                    <option <?php if($RESULT_QUERY["ACC_F_APP_BK_2_JENIS"] == 'Giro'){echo 'selected';}?> value="Giro">Giro</option>
                                    <option <?php if($RESULT_QUERY["ACC_F_APP_BK_2_JENIS"] == 'Tabungan'){echo 'selected';}?> value="Tabungan">Tabungan</option>
                                    <option <?php if($RESULT_QUERY["ACC_F_APP_BK_2_JENIS"] == 'Lainya'){echo 'selected';}?> value="Lainya">Lainya</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" name="submit_bank">Edit Data</button>
                </div>
            </form>
        </div>
    </div>
    <div id="picture" class="tab-pane fade">
        <div class="card">
            <form method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_IMG']; ?>" width="100%"><br>
                                <label>Dokument Pendukung</label>
                                <input type="file" accept=".png, .jpg, .jpeg" name="s5_6_doc1" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_IMG2']; ?>" width="100%"><br>
                                <label>Dokument Pendukung Lainnya</label>
                                <input type="file" accept=".png, .jpg, .jpeg" name="s5_6_doc2" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_FOTO']; ?>" width="100%"><br>
                                <label>Foto Terbaru</label>
                                <input type="file" accept=".png, .jpg, .jpeg" name="s5_6_fotoself" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_ID']; ?>" width="100%"><br>
                                <label>KTP/Passpor</label>
                                <input type="file" accept=".png, .jpg, .jpeg" name="s5_6_fotoid" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" name="submit_picture">Edit Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php }else{ die("<script>alert('Anda Tidak Memiliki Akses Ke Halaman Ini');location.href = 'home.php?page=member_active'</script>"); };?>
<!-- ================================================================ -->


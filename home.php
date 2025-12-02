<?php
session_start();
include_once('setting.php');

if (isset($_GET["page"])) {
    $login_page = htmlentities(str_replace('%', '', str_replace(' ', '_', stripslashes(($_GET['page'])))), ENT_QUOTES, 'WINDOWS-1252');
    $page_title = ucwords(strtolower(str_replace('-', ' ', $login_page)));
} else {
    die("<script>location.href ='" . $site_url . "'</script>");
};

if(isset($_GET['sub_page'])){
    $sub_page = $_GET['sub_page'];
}

if(isset($_COOKIE['login_adm_mail_id'])){
    if (isset($_COOKIE['login_adm_pass_id'])){
        if (isset($_COOKIE['login_adm_uxbc_id'])) {
            $login_mail_id = $_COOKIE['login_adm_mail_id'];
            $login_pass_id = $_COOKIE['login_adm_pass_id'];
            $login_uxbc_id = $_COOKIE['login_adm_uxbc_id'];
            $ids = substr($login_uxbc_id, 7, 32);

            $sql_sytax = "
                SELECT *
                FROM tb_admin 
                WHERE MD5(MD5(ADM_ID)) = '" . $ids . "'
                AND MD5(MD5(ADM_USER)) = '" . $login_mail_id . "' 
                AND MD5(MD5(ADM_PASS)) = '" . $login_pass_id . "'
                AND ADM_STS = -1
                LIMIT 1
            ";
            $sql_user = mysqli_query($db, $sql_sytax);
            if ($sql_user) {
                $user1 = mysqli_fetch_assoc($sql_user);
                if ($login_mail_id != "") {
                    if ($login_pass_id != "") {
                        if ($login_uxbc_id != "") {
                            if ($login_page != "") {
                                if (md5(md5($user1["ADM_ID"])) == $ids) {
                                    if (md5(md5($user1["ADM_USER"])) == $login_mail_id) {
                                        if (md5(md5($user1["ADM_PASS"])) == $login_pass_id) {
                                            if ($user1["ADM_STS"] == "-1") {

                                                //push_notification('delete');
                                                function new_msqrde($lvl){
                                                    global $db;
                                                    $RET_VAL = [];
                                                    $MASK_CTG = [
                                                        // 2 => [
                                                        //     "ACC_F_APP_BK_1_ACC",
                                                        //     "ACC_F_APP_BK_2_ACC"
                                                        // ],
                                                        4 => [
                                                            "MBR_PHONE",
                                                            "ACC_F_APP_PRIBADI_ID",
                                                            "ACC_F_APP_PRIBADI_NPWP",
                                                            "ACC_F_APP_PRIBADI_TLP",
                                                            "ACC_F_APP_PRIBADI_HP",
                                                            "ACC_F_APP_BK_1_ACC",
                                                            "ACC_F_APP_BK_2_ACC",
                                                            "ACC_F_APP_BK_1_TLP",
                                                            "ACC_F_APP_BK_2_TLP"
                                                        ],
                                                        5 => [
                                                            "MBR_NAME",
                                                            "MBR_EMAIL",
                                                            "MBR_PHONE",
                                                            "ACC_F_APP_PRIBADI_NAMA",
                                                            "ACC_F_APP_PRIBADI_ID",
                                                            "ACC_F_APP_PRIBADI_NPWP",
                                                            "ACC_F_APP_PRIBADI_TLP",
                                                            "ACC_F_APP_PRIBADI_HP",
                                                            "ACC_F_APP_BK_1_ACC",
                                                            "ACC_F_APP_BK_2_ACC",
                                                            "ACC_F_APP_BK_1_TLP",
                                                            "ACC_F_APP_BK_2_TLP"
                                                        ],
                                                        6 => [
                                                            "MBR_PHONE",
                                                            "ACC_F_APP_PRIBADI_ID",
                                                            "ACC_F_APP_PRIBADI_NPWP",
                                                            "ACC_F_APP_PRIBADI_TLP",
                                                            "ACC_F_APP_PRIBADI_HP",
                                                            "ACC_F_APP_BK_1_ACC",
                                                            "ACC_F_APP_BK_2_ACC",
                                                            "ACC_F_APP_BK_1_TLP",
                                                            "ACC_F_APP_BK_2_TLP"
                                                        ],
                                                        7 => [
                                                            "MBR_EMAIL",
                                                            "MBR_PHONE",
                                                            "ACC_F_APP_PRIBADI_ID",
                                                            "ACC_F_APP_PRIBADI_NPWP",
                                                            "ACC_F_APP_PRIBADI_TLP",
                                                            "ACC_F_APP_PRIBADI_HP",
                                                            "ACC_F_APP_BK_1_TLP",
                                                            "ACC_F_APP_BK_2_TLP"
                                                        ]
                                                    ];
                                                    if(isset($MASK_CTG[$lvl])){
                                                        $query = '
                                                            SELECT
                                                                '.implode(', ', $MASK_CTG[$lvl]).'
                                                            FROM tb_member
                                                            LEFT JOIN tb_racc
                                                            ON(tb_member.MBR_ID = tb_racc.ACC_MBR)
                                                        ';
                                                        $SQL_TBL = mysqli_query($db, $query);
                                                        if($SQL_TBL && mysqli_num_rows($SQL_TBL) > 0){
                                                            foreach (mysqli_fetch_all($SQL_TBL, MYSQLI_ASSOC) as $TBVAL) {
                                                                foreach ($TBVAL as $key => $val){
                                                                    if(!empty($val)){
                                                                        $RET_VAL[] = base64_encode(trim($val));
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    return (count($RET_VAL)) ? json_encode(array_values(array_unique($RET_VAL))) : '[]';
                                                }

                                                if(strtotime(date("Y-m-d H:i:s")) >= strtotime(date("Y-m-d H:i:s", strtotime($user1["ADM_CHANGEPASS_TIMESTAMP"]." +90 days"))) && $login_page != 'changepassword'){
                                                    die("<script>alert('Please change your password!.');location.href = 'home.php?page=changepassword'</script>");
                                                }
                                                
                                                setcookie('login_adm_mail_id', $login_mail_id, time() + (86400 * 1), '/'); // 86400 = 1 day
                                                setcookie('login_adm_pass_id', $login_pass_id, time() + (86400 * 1), '/'); // 86400 = 1 day
                                                setcookie('login_adm_uxbc_id', $login_uxbc_id, time() + (86400 * 1), '/'); // 86400 = 1 day
                                            } else { die("<script>alert('please login.12');location.href = './'</script>"); };
                                        } else { die("<script>alert('please login.11');location.href = './'</script>"); };
                                    } else { die("<script>alert('please login.10');location.href = './'</script>"); };
                                } else { die("<script>alert('please login.9');location.href = './'</script>"); };
                            } else { die("<script>alert('please login.8');location.href = './'</script>"); };
                        } else { die("<script>alert('please login.7');location.href = './'</script>"); };
                    } else { die("<script>alert('please login.6');location.href = './'</script>"); };
                } else { die("<script>alert('please login.5');location.href = './'</script>"); };
            } else { die("<script>alert('please login.4');location.href = './'</script>"); };
        } else { die("<script>alert('please login.3');location.href = './'</script>"); };
    } else { die(print_r($_COOKIE)); };
} else { die("<script>alert('please login.1');location.href = './'</script>"); };
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>CRM - <?php echo $setting_alias; ?></title>
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="57x57" href="assets/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="https://cdn.allmediaindo.com/bootstrap-4.1.3/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.allmediaindo.com/bootstrap-4.1.3/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.allmediaindo.com/DataTables/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.allmediaindo.com/DataTables/datatables.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.ckeditor.com/4.20.0/standard-all/ckeditor.js"></script>
    
    <style>
        table.dataTable thead .sorting,
        table.dataTable thead .sorting_asc,
        table.dataTable thead .sorting_desc,
        table.dataTable thead .sorting_asc_disabled,
        table.dataTable thead .sorting_desc_disabled {
            cursor: pointer;
            *cursor: hand;
            background-repeat: no-repeat;
            background-position: center right;
            border: 1px solid #dddddd;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid #dddddd;
        }

        div.dt-buttons {
            position: relative;
            float: right;
        }

        .dataTables_wrapper .dataTables_filter input {
            margin-left: 0.5em;
            margin-right: 0.5em;
            border-radius: .25rem;
            padding: .275rem .75rem;
            border: 1px solid #ced4da;
        }
    </style>
</head>

<body style="background-color: #f8f9fa;font-size:.8125rem;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#"><img src="assets/favicon/favicon-32x32.png" style="height:35px;"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?php if($login_page == 'dashboard'){ echo 'active'; } ?>"><a class="nav-link" href="home.php?page=dashboard">Dashboard <span class="sr-only">(current)</span></a></li>
                <?php if($user1["ADM_LEVEL"] == 1 || $user1["ADM_LEVEL"] == 0){ ?>
                    <li class="nav-item dropdown 
                        <?php 
                            if($login_page == 'member_user' ||
                                $login_page == 'member_realacc' || 
                                $login_page == 'member_active' || 
                                $login_page == 'member_realacc_detail' || 
                                $login_page == 'member_wp' || 
                                $login_page == 'member_demoacc' || 
                                $login_page == 'member_pending_account'|| 
                                $login_page == 'member_user_detail'|| 
                                $login_page == 'member_wp_detail'
                            ){ echo 'active'; } 
                        ?>">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Member</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item <?php if($login_page == 'member_user' || $login_page == 'member_user_detail'){ echo 'active'; } ?>" href="home.php?page=member_user">User</a>
                            <a class="dropdown-item <?php if($login_page == 'member_realacc' || $login_page == 'member_realacc_detail' && $sub_page == 'document'){ echo 'active'; } ?>" href="home.php?page=member_realacc">Progress Real Account</a>
                            <a class="dropdown-item <?php if($login_page == 'member_active' || $login_page == 'member_realacc_detail' && ($sub_page == 'wp_verification1' || $sub_page == 'temporary_detail' || $sub_page == 'client_deposit1' || $sub_page == 'wp_check1' || $sub_page == 'dealer1')){ echo 'active'; } ?>" href="home.php?page=member_active">Active Real Account</a>
                            <a class="dropdown-item <?php if($login_page == 'member_demoacc'){ echo 'active'; } ?>" href="home.php?page=member_demoacc">Demo Account</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown <?php if($login_page == 'meta_accountreal' || $login_page == 'meta_accountdemo' || $login_page == 'meta_orderopen' || $login_page == 'meta_orderclose'){ echo 'active'; } ?>">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">MetaTrader</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item <?php if($login_page == 'meta_accountreal'){ echo 'active'; } ?>" href="home.php?page=meta_accountreal">Account Real</a>
                            <a class="dropdown-item <?php if($login_page == 'meta_accountdemo'){ echo 'active'; } ?>" href="home.php?page=meta_accountdemo">Account Demo</a>
                            <a class="dropdown-item <?php if($login_page == 'meta_orderopen'){ echo 'active'; } ?>" href="home.php?page=meta_orderopen">Open Order</a>
                            <a class="dropdown-item <?php if($login_page == 'meta_orderclose'){ echo 'active'; } ?>" href="home.php?page=meta_orderclose">Close Order</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown <?php if($login_page == 'trans_deposit2' || $login_page == 'trans_withdrawal2'){ echo 'active'; } ?>">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Transaction</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item <?php if($login_page == 'trans_deposit2'){ echo 'active'; } ?>" href="home.php?page=trans_deposit2">Top Up</a>
                            <a class="dropdown-item <?php if($login_page == 'trans_withdrawal2'){ echo 'active'; } ?>" href="home.php?page=trans_withdrawal2">Withdrawal</a>
                            <a class="dropdown-item <?php if($login_page == 'trans_rate'){ echo 'active'; } ?>" href="home.php?page=trans_rate">Rate</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown <?php if($login_page == 'blog' || $login_page == 'gallery'){ echo 'active'; } ?>">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Blog</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item <?php if($login_page == 'blog'){ echo 'active'; } ?>" href="home.php?page=blog">Upload Berita</a>
                            <a class="dropdown-item <?php if($login_page == 'gallery'){ echo 'active'; } ?>" href="home.php?page=gallery">Gallery</a>
                        </div>
                    </li>
                    </li>
                    <li class="nav-item <?php if($login_page == 'jadwal-temu' || $login_page == 'doc_g1'){ echo 'active'; } ?>"><a class="nav-link" href="home.php?page=jadwal-temu">Jadwal temu</a></li>
                    <li class="nav-item <?php if($login_page == 'ib'){ echo 'active'; } ?>"><a class="nav-link" href="home.php?page=ib">IB</a></li>
                    <li class="nav-item <?php if($login_page == 'ticket'){ echo 'active'; } ?>"><a class="nav-link" href="home.php?page=ticket">Ticket</a></li>
                    <li class="nav-item dropdown <?= in_array($login_page, ['log-error', 'log-admin', 'log-client', 'log-create-account'])? "active" : ""; ?>">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownLog" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Log</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownLog">
                            <a class="dropdown-item <?= ($login_page == 'log-error')? "active" : "" ?>" href="home.php?page=log-error">Log Error</a>
                            <a class="dropdown-item <?= ($login_page == 'log-admin')? "active" : "" ?>" href="home.php?page=log-admin">Log Admin</a>
                            <a class="dropdown-item <?= ($login_page == 'log-client')? "active" : "" ?>" href="home.php?page=log-client">Log Client</a>
                            <!-- <a class="dropdown-item <?= ($login_page == 'log-create-account')? "active" : "" ?>" href="home.php?page=log-create-account">Log Create Account</a> -->
                        </div>
                    </li>
                    <li class="nav-item dropdown <?php if($login_page == 'apu_evnas' || $login_page == 'apu_penrisk' || $login_page == 'apu_evcannas' || $login_page == 'apu_evcannasdtl' || $login_page == 'apu_evnasdtl' || $login_page == 'apu_edd' || $login_page == 'apu_eddadtl' || $login_page == 'apu_eddhstry' || $login_page == 'apu_eddhstrydtl'){ echo 'active'; } ?>">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">APUPPT</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item <?php if($login_page == 'apu_penrisk'){ echo 'active'; } ?>" href="home.php?page=apu_penrisk">Penilaian Risiko</a>
                            <a class="dropdown-item <?php if($login_page == 'apu_evcannas' || $login_page == 'apu_evcannasdtl'){ echo 'active'; } ?>" href="home.php?page=apu_evcannas">Evaluasi Calon Nasabah</a>
                            <a class="dropdown-item <?php if($login_page == 'apu_evnas' || $login_page == 'apu_evnasdtl'){ echo 'active'; } ?>" href="home.php?page=apu_evnas">Evaluasi Nasabah</a>
                            <a class="dropdown-item <?php if($login_page == 'apu_edd' || $login_page == 'apu_eddadtl' || $login_page == 'apu_eddhstry' || $login_page == 'apu_eddhstrydtl'){ echo 'active'; } ?>" href="home.php?page=apu_edd">Enhanced Due Dilligence (EDD)</a>
                        </div>
                    </li>
                <?php }; ?>
                <?php if($user1["ADM_LEVEL"] == 2){ ?>
                    <li class="nav-item <?php if($login_page == 'jadwal-temu' || $login_page == 'doc_g1'){ echo 'active'; } ?>"><a class="nav-link" href="home.php?page=jadwal-temu">Jadwal temu</a></li>
                <?php }; ?>
                <?php if($user1["ADM_LEVEL"] == 5){ ?>
                    <li class="nav-item <?php if($login_page == 'blog'){ echo 'active'; } ?>"><a class="nav-link" href="home.php?page=blog">Blog</a></li>
                <?php }; ?>
                <?php if($user1["ADM_LEVEL"] == 2 ||$user1["ADM_LEVEL"] == 3 || $user1["ADM_LEVEL"] == 4 || $user1["ADM_LEVEL"] == 6 || $user1["ADM_LEVEL"] == 7 || $user1["ADM_LEVEL"] == 8){ ?>
                    <li class="nav-item dropdown <?php if($login_page == 'member_user' || $login_page == 'member_realacc' || $login_page == 'member_active' || $login_page == 'member_realacc_detail' || $login_page == 'member_wp' || $login_page == 'member_demoacc' || $login_page == 'member_pending_account'|| $login_page == 'member_user_detail'|| $login_page == 'member_wp_detail'){ echo 'active'; } ?>">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Member</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item <?php if($login_page == 'member_user' || $login_page == 'member_user_detail'){ echo 'active'; } ?>" href="home.php?page=member_user">User</a>
                            <a class="dropdown-item <?php if($login_page == 'member_realacc' || $login_page == 'member_realacc_detail'){ echo 'active'; } ?>" href="home.php?page=member_realacc">Progress Real Account</a>
                            <a class="dropdown-item <?php if($login_page == 'member_active' || $login_page == 'member_realacc_detail'){ echo 'active'; } ?>" href="home.php?page=member_active">Active Real Account</a>
                            <a class="dropdown-item <?php if($login_page == 'member_demoacc'){ echo 'active'; } ?>" href="home.php?page=member_demoacc">Demo Account</a>
                        </div>
                    </li>
                    <?php if($user1["ADM_LEVEL"] == 3 || $user1["ADM_LEVEL"] == 4){ ?>
                        <li class="nav-item dropdown <?php if($login_page == 'trans_deposit2' || $login_page == 'trans_withdrawal2'){ echo 'active'; } ?>">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Transaction</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item <?php if($login_page == 'trans_deposit2'){ echo 'active'; } ?>" href="home.php?page=trans_deposit2">Deposit</a>
                                <a class="dropdown-item <?php if($login_page == 'trans_withdrawal2'){ echo 'active'; } ?>" href="home.php?page=trans_withdrawal2">Withdrawal</a>
                            </div>
                        </li>
                    <?php }; ?>
                    <?php if($user1["ADM_LEVEL"] == 6){ ?>
                        <li class="nav-item <?php if($login_page == 'ib'){ echo 'active'; } ?>"><a class="nav-link" href="home.php?page=ib">IB</a></li>
                        <li class="nav-item dropdown <?php if($login_page == 'trans_deposit2' || $login_page == 'trans_withdrawal2'){ echo 'active'; } ?>">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Transaction</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item <?php if($login_page == 'trans_deposit2'){ echo 'active'; } ?>" href="home.php?page=trans_deposit2">Deposit</a>
                                <a class="dropdown-item <?php if($login_page == 'trans_withdrawal2'){ echo 'active'; } ?>" href="home.php?page=trans_withdrawal2">Withdrawal</a>
                            </div>
                        </li>
                    <?php }; ?>
                    <?php if($user1["ADM_LEVEL"] == 7){ ?>
                        <li class="nav-item dropdown <?php if($login_page == 'trans_deposit2' || $login_page == 'trans_withdrawal2'){ echo 'active'; } ?>">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Transaction</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item <?php if($login_page == 'trans_deposit2'){ echo 'active'; } ?>" href="home.php?page=trans_deposit2">Deposit</a>
                                <a class="dropdown-item <?php if($login_page == 'trans_withdrawal2'){ echo 'active'; } ?>" href="home.php?page=trans_withdrawal2">Withdrawal</a>
                                <a class="dropdown-item <?php if($login_page == 'trans_rate'){ echo 'active'; } ?>" href="home.php?page=trans_rate">Rate</a>
                            </div>
                        </li>
                    <?php }; ?>
                <?php }; ?>
                <?php if($user1["ADM_LEVEL"] == 9 || $user1["ADM_LEVEL"] == 3){ ?>
                    <li class="nav-item <?php if($login_page == 'ticket'){ echo 'active'; } ?>"><a class="nav-link" href="home.php?page=ticket">Ticket</a></li>
                <?php }; ?>
                <?php if($user1["ADM_LEVEL"] == 4 || $user1["ADM_LEVEL"] == 6|| $user1["ADM_LEVEL"] == 7){ ?>
                    <li class="nav-item dropdown <?php if($login_page == 'meta_accountreal' || $login_page == 'meta_accountdemo' || $login_page == 'meta_orderopen' || $login_page == 'meta_orderclose'){ echo 'active'; } ?>">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">MetaTrader</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item <?php if($login_page == 'meta_accountreal'){ echo 'active'; } ?>" href="home.php?page=meta_accountreal">Account Real</a>
                            <a class="dropdown-item <?php if($login_page == 'meta_accountdemo'){ echo 'active'; } ?>" href="home.php?page=meta_accountdemo">Account Demo</a>
                            <a class="dropdown-item <?php if($login_page == 'meta_orderopen'){ echo 'active'; } ?>" href="home.php?page=meta_orderopen">Open Order</a>
                            <a class="dropdown-item <?php if($login_page == 'meta_orderclose'){ echo 'active'; } ?>" href="home.php?page=meta_orderclose">Close Order</a>
                        </div>
                    </li>
                <?php }; ?>
                <li class="nav-item <?php if($login_page == 'changepassword'){ echo 'active'; } ?>"><a class="nav-link" href="home.php?page=changepassword">Profile (<?php echo $user1["ADM_NAME"]?>)</a></li>
                <?php if($user1["ADM_LEVEL"] == 1){ ?>
                    <!-- <li class="nav-item <?php if($login_page == 'dtc'){ echo 'active'; } ?>"><a class="nav-link" href="home.php?page=dtc">DTC</a></li> -->
                    <li class="nav-item <?php if($login_page == 'admins'){ echo 'active'; } ?>"><a class="nav-link" href="home.php?page=admins">Admins</a></li>
                <?php }; ?>
                <?php if(in_array($user1["ADM_LEVEL"], [3, 8])){ ?>
                <li class="nav-item dropdown <?php if($login_page == 'apu_evnas' || $login_page == 'apu_penrisk' || $login_page == 'apu_evcannas' || $login_page == 'apu_evcannasdtl' || $login_page == 'apu_evnasdtl' || $login_page == 'apu_edd' || $login_page == 'apu_eddadtl' || $login_page == 'apu_eddhstry' || $login_page == 'apu_eddhstrydtl'){ echo 'active'; } ?>">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">APUPPT</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php if($user1["ADM_LEVEL"] == 8){?>
                            <a class="dropdown-item <?php if($login_page == 'apu_penrisk'){ echo 'active'; } ?>" href="home.php?page=apu_penrisk">Penilaian Risiko</a>
                            <a class="dropdown-item <?php if($login_page == 'apu_evcannas' || $login_page == 'apu_evcannasdtl'){ echo 'active'; } ?>" href="home.php?page=apu_evcannas">Evaluasi Calon Nasabah</a>
                            <a class="dropdown-item <?php if($login_page == 'apu_evnas' || $login_page == 'apu_evnasdtl'){ echo 'active'; } ?>" href="home.php?page=apu_evnas">Evaluasi Nasabah</a>
                            <a class="dropdown-item <?php if($login_page == 'apu_edd' || $login_page == 'apu_eddadtl' || $login_page == 'apu_eddhstry' || $login_page == 'apu_eddhstrydtl'){ echo 'active'; } ?>" href="home.php?page=apu_edd">Enhanced Due Dilligence (EDD)</a>
                        <?php }else{?>
                            <!-- <a class="dropdown-item <?php if($login_page == 'apu_penrisk'){ echo 'active'; } ?>" href="home.php?page=apu_penrisk">Penilaian Risiko</a> -->
                            <a class="dropdown-item <?php if($login_page == 'apu_evcannas' || $login_page == 'apu_evcannasdtl'){ echo 'active'; } ?>" href="home.php?page=apu_evcannas">Evaluasi Calon Nasabah</a>
                            <a class="dropdown-item <?php if($login_page == 'apu_evnas' || $login_page == 'apu_evnasdtl'){ echo 'active'; } ?>" href="home.php?page=apu_evnas">Evaluasi Nasabah</a>
                            <!-- <a class="dropdown-item <?php if($login_page == 'apu_edd' || $login_page == 'apu_eddadtl' || $login_page == 'apu_eddhstry' || $login_page == 'apu_eddhstrydtl'){ echo 'active'; } ?>" href="home.php?page=apu_edd">Enhanced Due Dilligence (EDD)</a> -->
                        <?php }?>
                    </div>
                </li>
                <?php }; ?>
            </ul>
            <!--
            <span class="navbar-text mr-3">
                Silahkan login atau daftar akun
            </span>
            <a href="#" class="btn btn-outline-success mr-2">Login</a>
            -->
            <a href="./" class="btn btn-outline-danger">Logout</a>

        </div>
    </nav>
    <div class="container-fluid" id="container-fluid-home" style="margin-top:80px;margin-bottom:20px;">
        <?php
        if (file_exists("doc/" . $login_page . ".php")) {
            include "doc/" . $login_page . ".php";
        } else {
            include "doc/404.php";
        };
        ?>
    </div>
    <script>
        function pageReload(){
            function callAgain(){
                function cenNum(num){
                    let half   = ((num.length*35) / 100);
                    let numCen = num.substring(0, half) + star(half);
                    function star(star){
                        let restar = '';
                        for(let i = 0; i<star; i++){
                            restar += '*';
                        }
                        return restar;
                    }
                    return numCen;
                }
                let msqrd = <?php echo new_msqrde($user1["ADM_LEVEL"]); ?>;
                let fm = 0;
                if(msqrd.length){
                    document.getElementById('container-fluid-home').querySelectorAll('*').forEach((elm) => {
                        fm = msqrd.find((ml) => { return elm.innerText.includes(`${atob(ml)}`); });
                        if(fm && (!Array.from(elm.childNodes).map(function(e){ return e.nodeName; }).find((ntx) => { return ntx != '#text'; }))){
                            let re = new RegExp(atob(fm), "g");
                            elm.innerText = elm.innerText.replace(re, function(matched){
                                return cenNum(matched);
                            });
                        }
                    });
                }
            }
            callAgain();
        }
        pageReload();
        $("#position").select2({
            allowClear: true,
            placeholder: 'username'
        });
    </script>
    <script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-app.js"></script>
		<script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-database.js"></script>
		<script>
			const firebaseConfig = {
				apiKey: "AIzaSyBPzDbf1xjp-JARqJNARdCJS3583PXNffk",
				authDomain: "ibftrader-6f87a.firebaseapp.com",
				databaseURL: "https://ibftrader-6f87a-default-rtdb.asia-southeast1.firebasedatabase.app",
				projectId: "ibftrader-6f87a",
				storageBucket: "ibftrader-6f87a.appspot.com",
				messagingSenderId: "814807480492",
				appId: "1:814807480492:web:b6e3d62764808cdb8d4709",
				measurementId: "G-54VJECZC7F"
			};

			firebase.initializeApp(firebaseConfig);
			const db = firebase.database();

			// function play() {
			//     var audio = new Audio('beep.mp3');
			//     audio.play();
			// }
            function play_sound() {
                var audioElement = document.createElement('audio');
                audioElement.setAttribute('src', 'beep3.mp3');
                audioElement.setAttribute('autoplay', 'autoplay');
                audioElement.load();
                audioElement.play();
            }
			firebase.database().ref('notif').on('value',(snap)=>{
			    console.log(snap.numChildren());
				if(snap.numChildren() > 0){
					firebase.database().ref('notif').remove();
					play_sound();
				}
			});

			// let userRef = firebase.database().ref();
			// userRef.remove();
		</script>
</body>

</html>
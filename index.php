<?php
session_start();
include_once('setting.php');
include_once('../vendor/autoload.php');
use Slim\Csrf\Guard;
use Slim\Psr7\Factory\ResponseFactory;
$responseFactory = new ResponseFactory(); // Note that you will need to import
$guard = new Guard($responseFactory);

$csrfNameKey = $guard->getTokenNameKey();
$csrfValueKey = $guard->getTokenValueKey();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
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
        <title>Login <?php echo $setting_alias; ?></title>
        <link rel="stylesheet" href="https://cdn.allmediaindo.com/bootstrap-4.1.3/dist/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.allmediaindo.com/bootstrap-4.1.3/dist/js/bootstrap.min.js"></script>

        <script src="https://www.google.com/recaptcha/api.js?render=6Ldo21waAAAAAGkYeukkJYxOgwxaum-XIhLufKL9"></script>
        <script>
            grecaptcha.ready(function () {
                grecaptcha.execute('6Ldo21waAAAAAGkYeukkJYxOgwxaum-XIhLufKL9', { 
                    action: 'submit_login_<?php echo $setting_name; ?>'
                }).then(function (token) {
                    var recaptchaResponse = document.getElementById('recaptchaResponse');
                    recaptchaResponse.value = token;
                });
            });
        </script>
    </head>
    <body>
        <div class="page-content">
            <div class="container">
                <div class="row mt-5">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <?php
                            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                                if(isset($_POST[$csrfNameKey]) && isset($_POST[$csrfNameKey])){
                                    $csrfName = form_input($_POST[$csrfNameKey]);
                                    $csrfValue = form_input($_POST[$csrfValueKey]);
                                    if($guard->validateToken($csrfName, $csrfValue)){
                                        if(isset($_POST['submit'])) {
                                            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                if(isset($_POST['recaptcha_response'])) {

                                                    // Build POST request:
                                                    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
                                                    $recaptcha_secret = '6Ldo21waAAAAAFRHs3xe68AHjsU-bLBakMbIhDko';
                                                    $recaptcha_response = $_POST['recaptcha_response'];
                                                
                                                    $ch = curl_init();
                                                    curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
                                                    curl_setopt($ch, CURLOPT_POST, 1);
                                                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
                                                        array(
                                                            'secret' => $recaptcha_secret, 
                                                            'response' => $recaptcha_response
                                                        )
                                                    ));
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                    $response = curl_exec($ch);
                                                    curl_close($ch);
                                                    $arrResponse = json_decode($response, true);

                                                    if($arrResponse['success'] == 1){
                                                        if($arrResponse['hostname'] == $setting_domain){
                                                            if($arrResponse['score'] >= 0){
                                                                if($arrResponse['action'] == 'submit_login_'.$setting_name){
                                                                    if(isset($_POST['username'])){
                                                                        if(isset($_POST['password'])){
                                                                            $username = form_input($_POST['username']);
                                                                            $password = form_input($_POST['password']);
                                                                            
                                                                            $SQL_QUERY_USERNAME = mysqli_query($db, '
                                                                                SELECT tb_admin.ADM_USER 
                                                                                FROM tb_admin 
                                                                                WHERE tb_admin.ADM_USER = "'.$username.'" 
                                                                                LIMIT 1
                                                                            ');
                                                                            if($SQL_QUERY_USERNAME && mysqli_num_rows($SQL_QUERY_USERNAME) > 0){
                                                                                $RESULT_USERNAME = mysqli_fetch_assoc($SQL_QUERY_USERNAME);
                                                                                $SQL_QUERY_PASSWORD = mysqli_query($db, '
                                                                                    SELECT tb_admin.ADM_PASS 
                                                                                    FROM tb_admin 
                                                                                    WHERE tb_admin.ADM_USER = "'.$RESULT_USERNAME['ADM_USER'].'" 
                                                                                    AND tb_admin.ADM_PASS = "'.$password.'" 
                                                                                    LIMIT 1
                                                                                ');
                                                                                if($SQL_QUERY_PASSWORD && mysqli_num_rows($SQL_QUERY_PASSWORD) > 0){
                                                                                    $RESULT_PASSWORD = mysqli_fetch_assoc($SQL_QUERY_PASSWORD);
                                                                                    $SQL_QUERY_STATUS = mysqli_query($db, '
                                                                                        SELECT 
                                                                                            tb_admin.ADM_ID,
                                                                                            tb_admin.ADM_USER,
                                                                                            tb_admin.ADM_PASS,
                                                                                            tb_admin.ADM_LEVEL,
                                                                                            tb_admin.ADM_LEVEL1
                                                                                        FROM tb_admin
                                                                                        WHERE tb_admin.ADM_USER = "'.$RESULT_USERNAME['ADM_USER'].'" 
                                                                                        AND tb_admin.ADM_PASS = "'.$RESULT_PASSWORD['ADM_PASS'].'"
                                                                                        AND tb_admin.ADM_STS = -1
                                                                                        LIMIT 1
                                                                                    ');
                                                                                    if($SQL_QUERY_STATUS && mysqli_num_rows($SQL_QUERY_STATUS) > 0){
                                                                                        $RESULT_STATUS = mysqli_fetch_assoc($SQL_QUERY_STATUS);

                                                                                        $ran3 			= rand(1, 1234567890);
                                                                                        $ran4 			= rand(1, 1234567890);
                                                                                        $random_3		= substr($ran3, 0, 2);
                                                                                        $random_4		= substr($ran4, 0, 3);
                                                                                        $random_5	 	= substr(md5(rand()),0,7);
                                                                                        $random_6	 	= substr(md5(rand()),0,5);
                                                                                        $TOKEN  	 	= md5(md5(rand()));

                                                                                        mysqli_query($db,"
                                                                                            UPDATE tb_admin SET
                                                                                            tb_admin.ADM_TOKEN = '".$TOKEN."',
                                                                                            tb_admin.ADM_IP = '".$ip_visitors."'
                                                                                            WHERE ((tb_admin.ADM_ID)) = ".$RESULT_STATUS['ADM_ID']."
                                                                                        ") or die(mysqli_error($db));

                                                                                        mysqli_query($db,"
                                                                                            UPDATE tb_racc SET
                                                                                                tb_racc.ACC_MBR = (tb_racc.ACC_MBR * 10)
                                                                                            WHERE tb_racc.ACC_WPCHECK = -5
                                                                                            AND (UNIX_TIMESTAMP((tb_racc.ACC_WPCHECK_DATE + INTERVAL 1 WEEK)) <= UNIX_TIMESTAMP(NOW() + INTERVAL 7 HOUR))
                                                                                            AND LENGTH(tb_racc.ACC_MBR) = 10
                                                                                        ") or die(mysqli_error($db));

                                                                                        setcookie('login_adm_tken_id', $TOKEN, time() + (86400 / 12), '/'); // 86400 = 1 day
                                                                                        setcookie('login_adm_mail_id', md5(md5($RESULT_STATUS['ADM_USER'])), time() + (86400 / 12), '/'); // 86400 = 1 day
                                                                                        setcookie('login_adm_pass_id', md5(md5($RESULT_STATUS['ADM_PASS'])), time() + (86400 / 12), '/'); // 86400 = 1 day
                                                                                        setcookie('login_adm_uxbc_id', $random_3."".$random_6."".md5(md5($RESULT_STATUS['ADM_ID']))."".$random_4."".$random_5, time() + (86400 / 12), '/'); // 86400 = 1 day

                                                                                        die ("<script>alert('Success Login.');location.href = '".$_SERVER['REQUEST_URI']."home.php?page=dashboard'</script>");
                                                                                    } else { die (mysqli_error($db)); }
                                                                                } else { die ("<script>alert('Check Username / Password.2');location.href = '".$_SERVER['REQUEST_URI']."'</script>"); }
                                                                            } else { die ("<script>alert('Check Username / Password.1');location.href = '".$_SERVER['REQUEST_URI']."'</script>"); }
                                                                        } else { die ("<script>alert('Please try again.9');location.href = '".$_SERVER['REQUEST_URI']."'</script>"); }
                                                                    } else { die ("<script>alert('Please try again.8');location.href = '".$_SERVER['REQUEST_URI']."'</script>"); }
                                                                } else { die ("<script>alert('Please try again.7');location.href = '".$_SERVER['REQUEST_URI']."'</script>"); }
                                                            } else { die ("<script>alert('Please try again.6');location.href = '".$_SERVER['REQUEST_URI']."'</script>"); }
                                                        } else { die ("<script>alert('Please try again.5');location.href = '".$_SERVER['REQUEST_URI']."'</script>"); }
                                                    } else { die ("<script>alert('Please try again.4');location.href = '".$_SERVER['REQUEST_URI']."'</script>"); }
                                                } else { die ("<script>alert('Please try again.3');location.href = '".$_SERVER['REQUEST_URI']."'</script>"); }
                                            } else { die ("<script>alert('Please try again.2');location.href = '".$_SERVER['REQUEST_URI']."'</script>"); }
                                        } else { die ("<script>alert('Please try again.1');location.href = '".$_SERVER['REQUEST_URI']."'</script>"); }
                                    } else { die ("<script>alert('Please try again.1');location.href = '".$_SERVER['REQUEST_URI']."'</script>"); }
                                } else { die ("<script>alert('Please try again.1');location.href = '".$_SERVER['REQUEST_URI']."'</script>"); }
                            }
                        ?>
                        <form method="post">
                            <div class="card">
                                <div class="card-header"><?php echo ucwords('Login '.$setting_alias) ?></div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="username" autocomplete="off" placeholder="Username" required>
                                    </div>
                                    <div class="mb-2">
                                        <input type="password" class="form-control" name="password" autocomplete="off" placeholder="Password" required>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <input type="submit" name="submit" class="btn btn-primary" value="submit">
                                </div>
                            </div>
                            <?php
                                $keyPair = $guard->generateToken();

                                $csrfName = $guard->getTokenName();
                                $csrfValue = $guard->getTokenValue();
                            ?>
                            <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                            <input type="hidden" name="<?php echo $csrfNameKey ?>" value="<?php echo $csrfName ?>">
                            <input type="hidden" name="<?php echo $csrfValueKey ?>" value="<?php echo $csrfValue ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<?php
    date_default_timezone_set("Asia/Jakarta");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

   
    /** Error Log */
    $old_error_handler = set_error_handler(function($errno, $errstr, $errfile, $errline) {
        global $db;
        if (!(error_reporting() && $errno)) {
            return false;
        }

        $level  = [
            E_USER_WARNING  => "WARNING",
            E_USER_ERROR    => "ERROR",
            E_USER_NOTICE   => "NOTICE",
            E_USER_DEPRECATED   => "DEPRECATED",
        ];

        $datetime = date("Y-m-d H:i:s");
        $errorLevel = $level[ $errno ] ?? "Basic Error";
        $sqlInsert = $db->prepare("INSERT INTO tb_log_error (level, message, file, line, datetime) VALUES (?, ?, ?, ?, ?)");
        $sqlInsert->bind_param("sssis", $errorLevel, $errstr, $errfile, $errline, $datetime);
        $sqlInsert->execute();
        $sqlInsert->close();
    });

    
    /*===========AWS==========*/
    $region = 'ap-southeast-1';
    $bucketName = 'allmediaindo-2';
    $folder = 'mandirifx';
    $IAM_KEY = 'AKIASPLPQWHJGPL7K3MB';
    $IAM_SECRET = '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';
    $awsUrl     = "https://{$bucketName}.s3.{$region}.amazonaws.com/$folder";
    /*========================*/
    
    //==============TGM WPB====================
    $chat_id = -1001952742842;
    $token1 = '6670979075:AAGlmpRBd9D2zKbDlFVH0lqtg2lvqwrD8uw';
    //=========================================

    //==============TGM Accounting====================
    $chat_id_accounnting = -1001947079593;
    $token_accounnting = '6123067412:AAFLMlmuKHtKqwSZ9-o4yet0knkxaKTy2vM';
    //================================================

    //==============TGM Settlement====================
    $chat_id_stllmnt = -1001915148008;
    $token_stllmnt = '6679719803:AAFuqTKUEB8F4QfkKj3WL-chxKQtcaKpeQc';
    //================================================

    //==============TGM Dealer====================
    $chat_id_dlr = -1001980031308;
    $token_dlr = '6368539542:AAEOeij8GrESnnKai6BMuHGgZsH64f7fGrI';
    //============================================

    //==============TGM All====================
    $chat_id_all = -1001928370692;
    $token_all = '6563902697:AAFF_sJHx_Ua0BuxVTYhrqrEr73We6RmM7Y';
    //=========================================

    //==============TGM Other====================
    $chat_id_othr = -1001639843792;
    $token_othr = '6563902697:AAFF_sJHx_Ua0BuxVTYhrqrEr73We6RmM7Y';
    //=========================================

    $meta_server        = "103.145.82.20:443";    
    $setting_pdf_logo   = 'logo-mif-fixhitam.png';

    $ARR = (explode("/",$_SERVER["SCRIPT_NAME"]));
    $control_name = $ARR[1];
    
	$setting_alias = 'MIF Trader';
	$setting_name = 'techcrm';
	$setting_ext = 'net';
	$setting_protokol = 'https';
	$setting_domain = 'control.'.$setting_name.'.'.$setting_ext;

    $web_name_full       = 'PT. Mandiri Investindo Futures';
    $web_name_short      = 'MIF';
    $web_name_short_comp = 'PT.MIF';
    $web_name            = 'MIF Trader';

    $setting_front_web_link = 'www.mandirifx.co.id';
    $setting_central_office_address = 'Gedung Graha HSBC lt.9 Jl. Basuki Rahmat 58-60  Surabaya ,  Kelurahan Tegalsari, Kecamatan Tegalsari - Jawa Timur Kode Pos 60262';
    // $setting_playstore_link = 'https://play.google.com/store/apps/details?id=com.allmediaindo.ibftrader&hl=en';
    $setting_office_number = ' ( 031 ) - 33601175';
    $setting_email_support_name = 'support@mandirifx.co.id';
    $setting_email_support_password = 'Support@mandirifx.co.id123';
    $setting_email_logo_linksrc = 'https://mobilemandiri.techcrm.net/assets/img/logo-mif-fixhitam.png';
    $setting_email_host_api = 'smtp.hostinger.com';
    $setting_email_port_api = 465;
    $setting_email_port_encrypt = 'tls';
    $setting_number_phone = '( 031 ) - 33601175';
    $setting_fax_number = '( 031 ) - 33601175';
    $setting_email_pgdu = 'pengaduan@mandirifx.co.id';
    $setting_email_cs = $setting_email_pgdu;
    $setting_email_sp       = 'support@mandirifx.co.id';
    $setting_email_wp       = 'wakilpialang@mandirifx.co.id';
    $setting_insta_link = 'https://www.instagram.com/ibf.trader/?hl=0';
    $setting_facebook_link = 'https://www.facebook.com/profile.php?id=0';
    $setting_linkedin_link = 'https://www.linkedin.com/company/0';
    $setting_facebook_linksrc = 'https://mobileibftraders.techcrm.net/assets/img/sosmed/fb.png';
    $setting_insta_linksrc = 'https://mobileibftraders.techcrm.net/assets/img/sosmed/ig.png';
    $setting_linkedin_linksrc = 'https://mobileibftraders.techcrm.net/assets/img/sosmed/linkedin.png';
    $setting_small            = (true) ? 'All' : 'Semua';

    $db_host = '45.76.176.106:1224';
    $db_user = 'root';
    $db_pass = 'Masuk@1224';

    $db_dbse = 'db_mndrfx';
    $db = mysqli_connect($db_host, $db_user, $db_pass, $db_dbse);
    
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];
    if(filter_var($client, FILTER_VALIDATE_IP)){ $ip_visitors = $client;
    } else if(filter_var($forward, FILTER_VALIDATE_IP)){ $ip_visitors = $forward;
    } else { $ip_visitors = $remote; };

    
    include_once 'module/MobileDetect.class.php'; 
    include_once 'module/PDFMerger.php'; 
    
    $setting_desc           = 'The Ultimate Gateway to Trading Success.';
    $setting_title          = 'PT. Mandiri Investindo Futures';
    $setting_site_name      = 'MIF Trader';

    $url_mobile             = 'https://mobileibf.techcrm.net';
    $url_web                = 'https://ibftrader.allmediaindo.com';
    $url_web_cabinet        = 'https://ibftrader.allmediaindo.com/cabinet';


    

    function form_input($input_form){
        global $db;
        return htmlspecialchars(trim(addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($input_form))))));
    }

    function http_request($url){
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = curl_exec($ch); 
        curl_close($ch);
        return $output;
    }

    function curl_get_contents($url){
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
    
        $data = curl_exec($ch);
        curl_close($ch);
    
        return $data;
    }

    function checkmobile_device(){
        global $useragent;
        $mobile_agents = '!(tablet|pad|mobile|phone|symbian|android|ipod|ios|blackberry|webos)!i';
        if (preg_match($mobile_agents, ($_SERVER['HTTP_USER_AGENT'] ?? "Web"))) {
            $a1 = substr($useragent, strpos(strtolower($useragent), 'android'), strlen($useragent));
            $a2 = strpos(strtolower($a1), 'build');
            $a3 = substr($a1, 0, $a2).';';
            $a4 = explode(';', $a3);
            $out['mobile'] = 'yes';
            $out['type'] = $a4[0];
            $out['name'] = $a4[1];
        } else {
            $out['mobile'] = 'no';
            $out['type'] = 'unknown';
            $out['name'] = 'unknown';
        }
        return $out;
    };
    $agent_var_type = checkmobile_device()['type'];
    $agent_var_name = checkmobile_device()['name'];

    $mdetect = new MobileDetect(); 
    $device_detect = '';
    $os_detect = '';
    if($mdetect->isMobile()){ 
        if($mdetect->isTablet()){ 
            $device_detect = 'Tablet'; 
        } else { 
            $device_detect = 'Mobile'; 
        } 
         
        if($mdetect->isiOS()){ 
            $os_detect = 'IOS'; 
        } else if($mdetect->isAndroidOS()){ 
            $os_detect = $agent_var_type;
        };
    } else { 
        $device_detect = 'Desktop'; 
    };
    $device_and_os = $device_detect.' '.$os_detect;

    function insert_log($id_log, $message_log){
        global $db;
        global $ip_visitors;
        global $device_and_os;
        global $agent_var_name;
        global $user1;
        mysqli_query($db, "
            INSERT INTO tb_log SET
            tb_log.LOG_MBR = ".$id_log.",
            tb_log.LOG_ADM = ".$user1['ADM_ID'].",
            tb_log.LOG_MESSAGE = '".$message_log."',
            tb_log.LOG_IP = '".$ip_visitors."',
            tb_log.LOG_DEVICE = '".$device_and_os."',
            tb_log.LOG_DEVICENAME = '".$agent_var_name."',
            tb_log.LOG_DATETIME = '".date("Y-m-d H:i:s")."'
        ") or die ("<script>alert('Please try again, or contact support');location.href = 'Javascript:history.back(1)'</script>");
    }

    function OnlineToBase64($image){
        $imageData = base64_encode(curl_get_contents($image));
        $mime_types = array(
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'odt' => 'application/vnd.oasis.opendocument.text ',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'gif' => 'image/gif',
            'jpg' => 'image/jpg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'bmp' => 'image/bmp',
            'svg' => 'image/svg'
        );
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        
        if (array_key_exists($ext, $mime_types)) {
            $a = $mime_types[$ext];
        }
        return 'data: '.$a.';base64,'.$imageData;
    }

    function LocalToBase64($image){
        $imageData = base64_encode(file_get_contents($image));
        $mime_types = array(
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'odt' => 'application/vnd.oasis.opendocument.text ',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'gif' => 'image/gif',
            'jpg' => 'image/jpg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'bmp' => 'image/bmp',
            'svg' => 'image/svg'
        );
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        
        if (array_key_exists($ext, $mime_types)) {
            $a = $mime_types[$ext];
        }
        return 'data: '.$a.';base64,'.$imageData;
    }
    
    function remoteFileExists($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        $result = curl_exec($curl);
        $ret = false;
        if ($result !== false) {
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  
            if ($statusCode == 200) {
                $ret = true;   
            }
        }
        curl_close($curl);
        return $ret;
    }

    function mt4api_connect_post($path, $data){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-mt4.techcrm.net/w63otmcz/'.$path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            //CURLOPT_POSTFIELDS => 'name=alfi&group=demoindex-MMI&password=Python1234&investor=Python1234&country=Indonesia&state=Jawa%20Timur&email=allmediaindo%40gmail.com&city=Surabaya&comment=Example%20comment&leverage=100&zip_code=43199&phone=0812233&address=Example%20Address&enable=1&enable_change_password=1&phone_password=PhonePassword&login=10066377',
            CURLOPT_HTTPHEADER => array(
                'key: e14fec4b7b578c067fd0521da1d73926',
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        return json_decode($response, true);
        curl_close($curl);
    };

    function mt4api_connect_get($path){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-mt4.techcrm.net/w63otmcz/'.$path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'key: e14fec4b7b578c067fd0521da1d73926'
            ),
        ));

        $response = curl_exec($curl);
        return json_decode($response, true);
        curl_close($curl);
    };

    // function mt5api_demo_connect($mt_v, $cmd, $syntax){
    //     $curl = curl_init();
    //     curl_setopt_array($curl, array(
    //       CURLOPT_URL => 'https://api-mt5.techcrm.net/v3/'.$mt_v.'/'.$cmd.'?'.$syntax,
    //       CURLOPT_RETURNTRANSFER => true,
    //       CURLOPT_ENCODING => '',
    //       CURLOPT_MAXREDIRS => 10,
    //       CURLOPT_TIMEOUT => 0,
    //       CURLOPT_FOLLOWLOCATION => true,
    //       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //       CURLOPT_CUSTOMREQUEST => 'GET',
    //       CURLOPT_HTTPHEADER => array(
    //         'key: c6dfb4e6aa140451bf53392ccaf1777a'
    //       ),
    //     ));
    //     $response = curl_exec($curl);
    //     curl_close($curl);
    //     return $response;
        
    // }

    function mt5api_demonew_connect($cmd, $syntax){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api-mt5.techcrm.net/v5-manager/'.$cmd.'?'.$syntax,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'key: 04977358-a193-4b3a-b808-adc2fa9084ef'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    function mt5api_demo_connect($mt_v, $cmd, $syntax){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api-mt5.techcrm.net/v5-manager/'.$cmd.'?'.$syntax,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'key: fca4da74-ce45-4317-8245-27226eacb467'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
        
    }
    
    function push_notification($action){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://firebase.techcrm.net/ibftrader/'.$action,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'key: 1d73adee8d0d3d502e88cc1b847b09cf'
            ),
        ));
        
        $response = curl_exec($curl);
        return json_decode($response, true);
        curl_close($curl);
    
        function http_request($url){
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            $output = curl_exec($ch); 
            curl_close($ch);
            return $output;
        }
    };



    function mt5api_connect($command, $params = []){
        $curl = curl_init();
        $params['id'] = "fca4da74-ce45-4317-8245-27226eacb467";

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://45.76.163.26:5000/{$command}?".http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15000,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    function validation_password($input) {
        $character  = "abcdefghijklmnopqrstuvwxyz";
        $numeric    = "1234567890";
        $min_length = 8;
    
        $return     = [
            'upper'     => 0,
            'lower'     => 0,
            'numeric'   => 0
        ];
    
        // Validate Length
        if(strlen($input) < $min_length) {
            return  "Password must be at least {$min_length} characters";
        }
    
        // Validate Character
        foreach(str_split($character) as $char) {
            //Uppercase 
            if($return['upper'] == 0) {
                if(strpos($input, strtoupper($char)) !== FALSE) {
                    $return['upper'] += 1;
                } 
            }
    
            //Lowercase
            if($return['lower'] == 0) {
                if(strpos($input, strtolower($char)) !== FALSE) {
                    $return['lower'] += 1;
                }
            }
        }
    
        // Validate Numeric
        foreach(str_split($numeric) as $num) {
            if($return['numeric'] == 0) {
                if(strpos($input, $num) !== FALSE) {
                    $return['numeric'] += 1;
                }
            }
        }
    
        if($return['upper'] == 0) {
            return  "Password must contain at least one upper case letter.";
        }
    
        if($return['lower'] == 0) {
            return  "Password must contain at least one lower case letter.";
        }
    
        if($return['numeric'] == 0) {
            return  "Password must contain at least one number.";
        }
    
        if(preg_match('/[^a-zA-Z0-9]/', $input) <= 0) {
            return  "Password must contain symbols.";
        }
    
        return true;
    }
<!-- template.php -->
 
<?php
date_default_timezone_set("Asia/Jakarta");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../../setting.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contoh Template</title>
    <meta name="viewport" content="width=device-width, minimum-scale=1,0, maximum-scale=1.0">
    <style>
        body { font-family: "Times New Roman", serif; margin-top: 150px; }
        header { position: fixed; top: 0px; left: 0; right: 0; height: 50px;}
        .titik_dua {vertical-align: top; text-align:right;width:1%;}
        .content {vertical-align: top;}
        .page-break { page-break-before: always; }
        .judul { border:1px solid black;text-align:center;background-color:#efefef;padding:5px 0px;margin-bottom:10px; }
        .text-center { text-align:center; }
        .text-justify { text-align:justify; }
        .text-right { text-align:right; }
    </style>
</head>
<body>

    <header>
        <table style="width:100%">
            <tr>
                <td width="47%" style="vertical-align: middle; "><img src="<?php echo "https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/".$setting_pdf_logo ?>" width="100%"></td>
                <td width="6%">&nbsp;</td>
                <td width="47%" style="text-align:right; vertical-align: top; ">
                    <small>
                        <h3><?php echo $web_name_full ?></h3>
                        <?php echo $setting_central_office_address ?>
                    </small>
                </td>
            </tr>
        </table>
        <hr>
    </header>
    <div class="content">
        <?php
            if(isset($_GET['content'])){
                $content = $_GET['content'];
                include $content.'.php';
            } else {
                echo "<p>Tidak ada konten yang diterima.</p>";
            }
        ?>
    </div>
</body>
</html>

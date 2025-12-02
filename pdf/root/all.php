<?php
    date_default_timezone_set("Asia/Jakarta");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../../setting.php';
    require_once 'vendor/autoload.php';

    
    use Dompdf\Dompdf;
    use Dompdf\Options;
    $new_options = new Options();
    $new_options->set('isRemoteEnabled', true);
    $new_options->set('isHtml5ParserEnabled', true);
    $new_dompdf = new Dompdf($new_options);

    function DOMinnerHTML(DOMNode $element) 
    { 
        $innerHTML = ""; 
        $children  = $element->childNodes;

        foreach ($children as $child) 
        { 
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML; 
    } 

    $pdfotpt = form_input($_GET["x"]);
    $F_NAME = [
        "02-profil-perusahaaan-pialang-berjangka.php",
        "03.pernyataan-telah-melakukan-simulasi.php",
        "04.pernyataan-pengalaman-transaksi.php",
        "disclosure-statement-01.php",
        "05.aplikasi-pembukaan-rekening.php",
        "disclosure-statement-02.php",
        "06.dokumen-pemberitahuan-adanya-resiko.php",
        "disclosure-statement-03.php",
        "07.perjanjian-pemberian-amanat.php",
        "08.trading-rules.php",
        "09.pernyataan-bertanggung-jawab-atas-kode-transaksi.php",
        "14.formulir-pernyataan-nasabah.php",
        // "disclosure-statement-all.php",
        "verifikasikelengkapan.php",
        "13.bukti-konfirmasi-penerimaan-nasabah.php"
    ];
    $HTML    = [];
    $str_hml = '';

    foreach($F_NAME as $fl_name){
        if(file_exists($fl_name)){
            require_once($fl_name);
            if(!empty($htmls)){
                $HTML[] = $htmls;
            }
        }
    }
    
    $brns = 1;
    foreach($HTML as $html){
        $loadDOM = new DOMDocument();
        $loadDOM->loadHTML($html, LIBXML_NOERROR);
        if($brns == (count($HTML))){
            $str_hml .= DOMinnerHTML($loadDOM->getElementsByTagName('body')->item(0));
        }else{
            $str_hml .= DOMinnerHTML($loadDOM->getElementsByTagName('body')->item(0)).'<div class="page-break"></div>';
        }
        $brns++;
    }

    $str_srch = '
        <table style="width:100%">
            <tr>
                <td width="47%" style="vertical-align: middle; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/".$setting_pdf_logo."")).'" width="100%"></td>
                <td width="6%">&nbsp;</td>
                <td width="47%" style="text-align:right; vertical-align: top; ">
                    <p>
                        <h3>'.$web_name_full.'</h3>
                        '.$setting_central_office_address.'
                    </p>
                </td>
            </tr>
        </table>
    ';

    $str_rplc = '
        <header>
            <table style="width:100%">
                <tr>
                    <td width="47%" style="vertical-align: middle; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/".$setting_pdf_logo."")).'" width="100%"></td>
                    <td width="6%">&nbsp;</td>
                    <td width="47%" style="text-align:right; vertical-align: top; ">
                        <small>
                            <h3>'.$web_name_full.'</h3>
                            '.$setting_central_office_address.'
                        </small>
                    </td>
                </tr>
            </table>
            <hr>
        </header>
    ';
    
    if(!empty($str_hml)){
        $new_dompdf->loadHtml('
            <!DOCTYPE html>
            <html>
                <head>
                    <meta charset="utf-8">
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
                    
                    <style>
                        ol {
                            list-style: none;
                            margin-left: 0;
                            padding-left: 0;
                        }
                        li {
                            display: block;
                            margin-bottom: .5em;
                            margin-left: 2.5em;
                        }
                        li::before {
                            display: inline-block;
                            content: attr(seq);
                            width: 2em;
                            margin-left: -2em;
                        }
                    </style>
                </head>
                <body>
                '.str_replace($str_srch, 'Test Replace' ,$str_hml).'
                </body>
            </html>
        ');
        $new_dompdf->setPaper('A4', 'portrait');
        $new_dompdf->render();
        $new_dompdf->stream("".$web_name_full." - All-documents.pdf",array("Attachment"=>0));
        exit(0);
    }

    // echo '<pre>';
    // print_r(htmlspecialchars(
    //     '
    //         <!DOCTYPE html>
    //         <html>
    //             <head>
    //                 <meta charset="utf-8">
    //                 <meta name="viewport" content="width=device-width, minimum-scale=1,0, maximum-scale=1.0">
    //                 <style>
    //                     body { font-family: "Times New Roman", serif; margin-top: 150px; }
    //                     header { position: fixed; top: 0px; left: 0; right: 0; height: 50px;}
    //                     .titik_dua {vertical-align: top; text-align:right;width:1%;}
    //                     .content {vertical-align: top;}
    //                     .page-break { page-break-before: always; }
    //                     .judul { border:1px solid black;text-align:center;background-color:#efefef;padding:5px 0px;margin-bottom:10px; }
    //                     .text-center { text-align:center; }
    //                     .text-justify { text-align:justify; }
    //                     .text-right { text-align:right; }
    //                 </style>
                    
    //                 <style>
    //                     ol {
    //                         list-style: none;
    //                         margin-left: 0;
    //                         padding-left: 0;
    //                     }
    //                     li {
    //                         display: block;
    //                         margin-bottom: .5em;
    //                         margin-left: 2.5em;
    //                     }
    //                     li::before {
    //                         display: inline-block;
    //                         content: attr(seq);
    //                         width: 2em;
    //                         margin-left: -2em;
    //                     }
    //                 </style>
    //             </head>
    //             <body>
    //             '.str_replace($str_srch, 'Test Replace' ,$str_hml).'
    //             </body>
    //         </html>
    //     '
    // ));
    // echo '</pre>';
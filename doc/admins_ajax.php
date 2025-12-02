<?php
require_once('setting.php');
$usr = (isset($_GET["usr"])) ? $_GET["usr"] : 0;
$dt->query('
    SELECT
        ADM_TIMESTAMP,
        ADM_USER,
        ADM_PASS,
        DATE(ADM_CHANGEPASS_TIMESTAMP) AS CPASSD,
        (DATE(ADM_CHANGEPASS_TIMESTAMP) + INTERVAL 90 DAY) AS CPASSE,
        ADM_NAME,
        ADM_PHONE,
        ADM_LEVEL,
        ADM_STS,
        ADM_ID
    FROM tb_admin
    WHERE ADM_VISIBLE = -1
');

$dt->edit('ADM_LEVEL', function ($data) {
    switch ($data['ADM_LEVEL']) {
        case 0:
            return "Supervisor";
        case 1:
            return "All Access";
        case 2:
            return "WPB Jadwal Temu";
        case 3:
            return "WPB Verifikator";
        case 4:
            return "Dealer";
        case 5:
            return "RND";
        case 6:
            return "Settlement";
        case 7:
            return "Accounting";
        case 8:
            return "UKK APUPPTLN";
        case 9:
            return "Complient";
    }
});

$dt->edit('ADM_PASS', function($data) {
    return "<a href='javascript:void(0)' data-id='".md5(md5($data['ADM_ID']))."' class='show-password'><i>click to show</i></a>";
});

$dt->edit('ADM_STS', function($data) {
    switch($data['ADM_STS']) {
        case 0: return "<span class='badge bg-warning'>Pending</span>";
        case -1: return "<span class='badge bg-success'>Active</span>";
        case 1: return "<span class='badge bg-danger'>Inactive</span>";
    }
});

$dt->edit('ADM_ID', function ($data) {
    $content = "<div class='text-center'>";
    $content .= "
        <a 
        data-id='".md5(md5($data['ADM_ID']))."'
        data-name='".$data['ADM_NAME']."'
        data-user='".$data['ADM_USER']."'
        data-phone='".$data['ADM_PHONE']."'
        data-level='".$data['ADM_LEVEL']."'
        class='btn btn-sm btn-warning btn-edit' 
        href='javascript:void(0)' 
        data-bs-toggle='modal' 
        data-bs-target='#modalEditAdmin'>Edit</a>
    ";

    if($data['ADM_STS'] == -1) {
        $content .= "<a href='/m7p4jvq4/home.php?page=admins&nonactive=".md5(md5($data['ADM_ID']))."' class='btn btn-primary btn-sm'>Nonactive</a>";
    }
    else {
        $content .= "<a href='/m7p4jvq4/home.php?page=admins&active=".md5(md5($data['ADM_ID']))."' class='btn btn-primary btn-sm'>Active</a>";
    }

    $content .= "<a href='/m7p4jvq4/home.php?page=admins&delete=".md5(md5($data['ADM_ID']))."' class='btn btn-danger btn-sm'>Hapus</a>";
    $content .= "</div>";

    return $content;
});

echo $dt->generate()->toJson(); // same as 'echo $dt->generate()';
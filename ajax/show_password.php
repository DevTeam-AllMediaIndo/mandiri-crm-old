<?php
require_once('../setting.php');

$user       = form_input($_POST['user'] ?? "-");
$target     = form_input($_POST['target'] ?? "-");
$password   = form_input($_POST['password'] ?? "-");

try {
    $sql_get_admin = mysqli_query($db, "SELECT ADM_PASS FROM tb_admin WHERE MD5(MD5(ADM_ID)) = '$user' LIMIT 1");
    if(!$sql_get_admin) {
        exit(json_encode([
            'success'   => false,
            'message'   => "Invalid User"
        ]));
    }


    $admin = mysqli_fetch_assoc($sql_get_admin);
    if(base64_encode($password) !== base64_encode($admin['ADM_PASS'])) {
        exit(json_encode([
            'success'   => false,
            'message'   => "Invalid Password"
        ]));
    }

    $sql_get_target = mysqli_query($db, "SELECT ADM_PASS FROM tb_admin WHERE MD5(MD5(ADM_ID)) = '$target' LIMIT 1");
    if(!$sql_get_target || !mysqli_num_rows($sql_get_target)) {
        exit(json_encode([
            'success'   => false,
            'message'   => "Invalid Target"
        ]));
    }

    exit(json_encode([
        'success'   => true,
        'message'   => base64_encode(mysqli_fetch_assoc($sql_get_target)['ADM_PASS'])
    ]));

} catch (Exception $e) {
    exit(json_encode([
        'success'   => false,
        'message'   => $e->getMessage()
    ]));
}
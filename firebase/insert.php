<?php
date_default_timezone_set("Asia/Jakarta");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function sendPushNotification($fcm_token, $title, $message, $id = null,$action = null) {  
     
    $url = "https://fcm.googleapis.com/fcm/send";            
    $header = [
        'authorization: key=AAAAeyWtnvQ:APA91bH-cpYFViHderkotXvJYn6-hFuxmZyQ9TStsg8W41NJNHJcO78aOAanPOYorlNxF7m9kHA-bpV1K0vkzgNnT5_s7-gqKTynHb5fJEgNeW_4oh1OGVBH028V39eVrfFjYBjumc0z',
        'content-type: application/json'
    ];    
 
    $notification = [
        'title' =>$title,
        'body' => $message
    ];
    $extraNotificationData = ["message" => $notification,"id" =>$id,'action'=>$action];
 
    $fcmNotification = [
        'to'        => $fcm_token,
        'notification' => $notification,
        'data' => $extraNotificationData
    ];
 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
 
    $result = curl_exec($ch);    
    curl_close($ch);
 
    return $result;
}

sendPushNotification("528913112820","Ini Title","Ini Isi",123456,"notif");


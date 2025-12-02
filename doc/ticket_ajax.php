<?php
    require_once('setting.php');

    //$dt->query('SELECT ID_TRD, TRD_MBR, TRD_PRICE FROM tb_trade');
    $dt->query('
        SELECT
            tb_ticket.TCKT_DATETIME_MBR,
            tb_member.MBR_USER,
            tb_member.MBR_NAME,
            tb_member.MBR_EMAIL,
            tb_ticket.TCKT_KONTEN_MBR,
            tb_racc.LOGIN,
            tb_ticket.TCKT_FILE,
            tb_ticket.TCKT_KONTEN_ADM,
            IF(tb_admin.ADM_LEVEL = 1, "Admin", "Admin") AS ADMIN,
            tb_admin.ADM_NAME,
            tb_admin.ADM_USER,
            tb_ticket.TCKT_DATETIME_ADM
        FROM tb_ticket
        JOIN tb_member
        JOIN tb_admin ON (tb_ticket.TCKT_MBR = tb_member.MBR_ID AND tb_ticket.TCKT_ADM = tb_admin.ADM_ID)
        LEFT JOIN (
            SELECT 
                ACC_MBR,
                GROUP_CONCAT(tb_racc.ACC_LOGIN SEPARATOR ",") as LOGIN
            FROM tb_racc 
            WHERE ACC_LOGIN != 0
            AND ACC_WPCHECK = 6
            AND ACC_DERE = 1
            GROUP BY ACC_MBR
        ) as tb_racc ON (tb_racc.ACC_MBR = tb_member.MBR_ID)
        WHERE TCKT_KONTEN_ADM <> "0"
        ORDER BY tb_ticket.ID_TCKT DESC
    ');
    $dt->edit('TCKT_KONTEN_ADM', function($data){
        return str_replace('\r\n','<br>',$data['TCKT_KONTEN_ADM']);
    });
    $dt->edit('TCKT_FILE', function($data){
        if(strlen($data['TCKT_FILE']) > 0){
            return '<a target="_blank" href="https://allmediaindo-2.s3.ap-southeast-1.amazonaws.com/mandirifx/'.$data['TCKT_FILE'].'">Open</a>';
        }else{
            return '';
        }
    });

    echo $dt->generate()->toJson(); // same as 'echo $dt->generate()';
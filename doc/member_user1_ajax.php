<?php
    require_once('setting.php');

    //$dt->query('SELECT ID_TRD, TRD_MBR, TRD_PRICE FROM tb_trade');
    $dt->query('
        SELECT
            tb_member.MBR_DATETIME,
            tb_member.MBR_ID,
            tb_member.MBR_NAME,
            tb_member.MBR_EMAIL,
            tb_member.MBR_PASS,
            IF(tb_member.MBR_STS = -1, "Active", "Email Not Confirm") AS MBR_STS,
            MD5(MD5(tb_member.MBR_ID)) AS MBR_ID_HASH
        FROM tb_member
    ');
    
    $dt->edit('MBR_DATETIME', function($data){
        return "<div class='text-center'>".$data['MBR_DATETIME']."</div>";
    });
    $dt->edit('MBR_ID_HASH', function($data){
        return "<div class='text-center'><a href='home.php?page=member_user_detail&x=".$data['MBR_ID_HASH']."' class='btn btn-sm btn-info'>Detail</a></div>";
    });

    $dt->edit('MBR_STS', function($data){
        if($data['MBR_STS'] == 'Active'){
            return "
                <div class='text-center'>
                    <span class='badge badge-success'>Active</span>
                </div>
            ";
        } else {
            return "
                <div class='text-center'>
                    <span class='badge badge-warning'>Email Not Confirm</span>
                </div>
            ";
        }
    });

    echo $dt->generate()->toJson(); // same as 'echo $dt->generate()';
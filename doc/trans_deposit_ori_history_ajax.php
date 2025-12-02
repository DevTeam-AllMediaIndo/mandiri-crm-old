<?php
    require_once('setting.php');

    

    //$dt->query('SELECT ID_TRD, TRD_MBR, TRD_PRICE FROM tb_trade');
    $dt->query('
        SELECT
            tb_dpwd.ID_DPWD,
            tb_dpwd.DPWD_DATETIME,
            tb_dpwd.DPWD_DEVICE,
            tb_member.MBR_USER,
            tb_member.MBR_NAME,
            tb_racc.ACC_LOGIN,
            tb_dpwd.DPWD_AMOUNT,
            tb_dpwd.DPWD_PIC,
            IF(tb_dpwd.DPWD_STS = -1, "Accept",
                IF(tb_dpwd.DPWD_STS = 1, "Reject", "Unknown")
            ) AS DPWD_STS
        FROM tb_member
        JOIN tb_racc
        JOIN tb_dpwd
        ON(tb_member.MBR_ID = tb_racc.ACC_MBR
        AND tb_dpwd.DPWD_MBR = tb_member.MBR_ID
        AND tb_dpwd.DPWD_LOGIN = tb_racc.ID_ACC)
        WHERE tb_dpwd.DPWD_STS <> 0
        AND tb_dpwd.DPWD_TYPE = 1
    ');
    $dt->hide('ID_DPWD');
    $dt->hide('DPWD_DEVICE');
    $dt->edit('DPWD_DATETIME', function($data){
        return "<div class='text-center'>".$data['DPWD_DATETIME']."</div>";
    });
    $dt->edit('DPWD_AMOUNT', function($data){
        return "<div class='text-right'>".number_format($data['DPWD_AMOUNT'], 0)."</div>";
    });
    $dt->edit('DPWD_PIC', function($data){
        $region = 'ap-southeast-1';
        $bucketName = 'allmediaindo-2';
        $folder = 'ibftrader';
        $IAM_KEY = 'AKIASPLPQWHJMMXY2KPR';
        $IAM_SECRET = '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';
        if($data['DPWD_DEVICE'] == 'Web'){
            return "<div class='text-center'><a target='_blank' href='https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/".$data['DPWD_PIC']."'>Pic</a></div>";
        } else {
            return "<div class='text-center'><a target='_blank' href='https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/".$data['DPWD_PIC']."'>Pic</a></div>";
        }
    });
    // $dt->add('action', function($data){
    //     if($data['DPWD_STS'] == 'Accept'){ 
    //             return "<div class='text-center'>
    //                 <a href='pdf/root/trans_deposit_detail.php?x=".md5(md5($data['ID_DPWD']))."' class=''>Open</a>
    //             </div>
    //         ";
    //     }
    // });

    echo $dt->generate()->toJson(); // same as 'echo $dt->generate()';
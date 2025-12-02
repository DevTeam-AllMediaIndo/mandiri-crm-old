<?php
require_once('setting.php');

//$dt->query('SELECT ID_TRD, TRD_MBR, TRD_PRICE FROM tb_trade');
$dt->query('
    SELECT
        tl.LOG_DATETIME,
        tm.MBR_EMAIL,
        tl.LOG_MESSAGE,
        tl.LOG_IP
    FROM tb_log tl
    JOIN tb_member tm ON (tm.MBR_ID = tl.LOG_MBR)
    WHERE tl.LOG_MBR IS NOT NULL
    AND tl.LOG_ADM IS NULL
');

$dt->edit('LOG_DATETIME', function($data){ return "<div class='text-center'>".$data['LOG_DATETIME']."</div>"; });

echo $dt->generate()->toJson(); // same as 'echo $dt->generate()';
<?php
require_once('setting.php');

//$dt->query('SELECT ID_TRD, TRD_MBR, TRD_PRICE FROM tb_trade');
$dt->query('
    SELECT
        tle.datetime,
        tle.level,
        tle.message
    FROM tb_log_error tle
');

$dt->edit('datetime', function($data){ return "<div class='text-center'>".$data['datetime']."</div>"; });

echo $dt->generate()->toJson(); // same as 'echo $dt->generate()';
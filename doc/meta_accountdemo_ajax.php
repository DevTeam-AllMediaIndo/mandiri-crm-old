<?php
    require_once('setting.php');

    //$dt->query('SELECT ID_TRD, TRD_MBR, TRD_PRICE FROM tb_trade');
    $dt->query('
        SELECT
            MT4_USERS_DEMO1.REGDATE,
            MT4_USERS_DEMO1.LOGIN,
            MT4_USERS_DEMO1.NAME,
            MT4_USERS_DEMO1.EMAIL,
            MT4_USERS_DEMO1.CITY,
            CONCAT("1:", MT4_USERS_DEMO1.LEVERAGE) AS LEVERAGE,
            MT4_USERS_DEMO1.BALANCE,
            MT4_USERS_DEMO1.EQUITY,
            MT4_USERS_DEMO1.MARGIN_FREE
        FROM MT4_USERS_DEMO1
    ');
    $dt->edit('REGDATE', function($data){
        return "<div class='text-center'>".$data['REGDATE']."</div>";
    });
    $dt->edit('BALANCE', function($data){
        return "<div class='text-right'>".number_format($data['BALANCE'], 2)."</div>";
    });
    $dt->edit('EQUITY', function($data){
        return "<div class='text-right'>".number_format($data['EQUITY'], 2)."</div>";
    });
    $dt->edit('MARGIN_FREE', function($data){
        return "<div class='text-right'>".number_format($data['MARGIN_FREE'], 2)."</div>";
    });

    echo $dt->generate()->toJson(); // same as 'echo $dt->generate()';
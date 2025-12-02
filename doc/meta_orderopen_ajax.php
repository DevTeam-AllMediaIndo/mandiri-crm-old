<?php
    require_once('setting.php');

    //$dt->query('SELECT ID_TRD, TRD_MBR, TRD_PRICE FROM tb_trade');
    $dt->query('
        SELECT
            MT4_TRADES.TICKET,
            MT4_TRADES.LOGIN,
            MT4_TRADES.OPEN_TIME,
            MT4_TRADES.SYMBOL,
            (MT4_TRADES.VOLUME / 100) AS VOLUME,
            MT4_TRADES.OPEN_PRICE,
            MT4_TRADES.SWAPS,
            MT4_TRADES.COMMISSION,
            MT4_PRICES.DIGITS
        FROM MT4_TRADES
        JOIN MT4_PRICES
        ON(MT4_PRICES.SYMBOL = MT4_TRADES.SYMBOL)
        WHERE DATE(MT4_TRADES.CLOSE_TIME) = DATE("1970-01-01")
        AND (MT4_TRADES.CMD = 0 OR MT4_TRADES.CMD = 1)
        AND MT4_TRADES.LOGIN NOT LIKE "6%"
    ');
    $dt->hide('DIGITS');
    $dt->edit('OPEN_TIME', function($data){
        return "<div class='text-center'>".$data['OPEN_TIME']."</div>";
    });
    $dt->edit('OPEN_PRICE', function($data){
        return "<div class='text-right'>".number_format($data['OPEN_PRICE'], $data['DIGITS'])."</div>";
    });
    $dt->edit('VOLUME', function($data){
        return "<div class='text-right'>".number_format($data['VOLUME'], 2)."</div>";
    });
    $dt->edit('SWAPS', function($data){
        return "<div class='text-right'>".number_format($data['SWAPS'], 2)."</div>";
    });
    $dt->edit('COMMISSION', function($data){
        return "<div class='text-right'>".number_format($data['COMMISSION'], 2)."</div>";
    });

    echo $dt->generate()->toJson(); // same as 'echo $dt->generate()';
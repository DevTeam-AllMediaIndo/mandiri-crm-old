<?php
    require_once('setting.php');
    $dt->query('
        SELECT
            tb_rate.RATE_DATE,
            tb_rate.RATE_AMMOUNT,
            MD5(MD5(tb_rate.ID_RATE)) AS ID_RATE
        FROM tb_rate
    ');
    $dt->edit('RATE_DATE', function($data){
        return '<div class="text-center">'.$data["RATE_DATE"].'</div>';
    });
    $dt->edit('RATE_AMMOUNT', function($data){
        return '<div class="text-end">Rp. '.number_format($data["RATE_AMMOUNT"], 2).'</div>';
    });
    $dt->edit('ID_RATE', function($data){
        return '
            <div class="text-center">
                <button type="button" data-target="#modal_insert" data-toggle="modal" data-amt="Rp. '.number_format($data["RATE_AMMOUNT"], 2, ",", ".").'" class="btn btn-info edt" value="'.$data["ID_RATE"].'">Edit</button>
            </div>
        ';
    });
    echo $dt->generate()->toJson();

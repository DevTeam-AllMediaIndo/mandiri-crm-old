<?php
    require_once('setting.php');
    $usr = (isset($_GET["usr"])) ? $_GET["usr"] : 0;
    //$dt->query('SELECT ID_TRD, TRD_MBR, TRD_PRICE FROM tb_trade');
    $dt->query('
        SELECT
            tb_ib.IB_DATETIME,
            tb_ib.IB_NAME,
            tb_ib.IB_CODE,
            tb_ib.IB_CITY,
            tb_ib.IB_TIMESTAMP,
            MD5(MD5(tb_ib.ID_IB)) AS ID_IB,
            IFNULL(
                (
                    SELECT 
                        tb_admin.ADM_LEVEL 
                    FROM tb_admin 
                    WHERE MD5(MD5(tb_admin.ADM_ID)) = "'.$usr.'" 
                    LIMIT 1
                )
            ,0) AS ADM_LEVEL
        FROM tb_ib
        WHERE tb_ib.IB_STS = -1
    ');
    $dt->hide('ADM_LEVEL');
    $dt->edit('IB_DATETIME', function($data){ return "<div class='text-center'>".$data['IB_DATETIME']."</div>"; });
    $dt->edit('ID_IB', function($data){
        if($data["ADM_LEVEL"] != 0){
            return "
                <div class='text-center'>
                    <button data-target='#modal_update".$data['ID_IB']."' gavl_x='".$data['ID_IB']."' gavl_city='".$data['IB_CITY']."' gavl_code='".$data['IB_CODE']."' gavl_nama='".$data['IB_NAME']."' data-toggle='modal' class='updateData btn btn-sm btn-primary text-white'>
                        Update Data <i class='fa fa-pencil' aria-hidden='true'></i>
                    </button>
                </div>
            ";
        }
    });


    echo $dt->generate()->toJson(); // same as 'echo $dt->generate()';
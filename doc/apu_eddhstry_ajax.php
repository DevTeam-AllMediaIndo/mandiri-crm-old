<?php
    include_once('setting.php');
    if(isset($_GET["x"])){
        $x = $_GET["x"];
        $dt->query('
            SELECT
                tb_apuppt_edd.ADD_DATTIME,
                tb_racc.ACC_F_APP_PRIBADI_NAMA,
                tb_racc.ACC_F_APP_PRIBADI_ID,
                (
                    SELECT
                        tb_range_edd.EDD_LV
                    FROM tb_range_edd
                    WHERE tb_range_edd.ID_EDD = tb_apuppt_edd.ADD_VAL1
                    LIMIT 1
                ) AS ADD_VAL1,
                (
                    SELECT
                        tb_range_edd.EDD_LV
                    FROM tb_range_edd
                    WHERE tb_range_edd.ID_EDD = tb_apuppt_edd.ADD_VAL2
                    LIMIT 1
                ) AS ADD_VAL2,
                (
                    SELECT
                        tb_range_edd.EDD_LV
                    FROM tb_range_edd
                    WHERE tb_range_edd.ID_EDD = tb_apuppt_edd.ADD_VAL3
                    LIMIT 1
                ) AS ADD_VAL3,
                tb_apuppt_edd.ADD_ARF,
                MD5(MD5(MD5(tb_apuppt_edd.ID_ADD))) AS ID_ADD
            FROM tb_racc
            JOIN tb_apuppt_edd ON(tb_racc.ACC_MBR = tb_apuppt_edd.ADD_MBR)
            WHERE tb_racc.ACC_DERE = 1
            AND MD5(MD5(tb_racc.ID_ACC)) = "'.$x.'"
            AND (tb_racc.ACC_LOGIN IS NOT NULL AND tb_racc.ACC_LOGIN != "0")
            GROUP BY tb_apuppt_edd.ID_ADD
        ');
        $ARR_CLR = [
            "Rendah"   => "success",
            "Menengah" => "warning",
            "Tinggi"   => "danger"
        ];
        $dt->edit('ADD_VAL1', function($data){
            global $ARR_CLR;
            return '
                <div class="text-center">
                    <span class="badge bg-'.$ARR_CLR["$data[ADD_VAL1]"].' h-50 d-inline-block bg-opacity-15 text-white" style="font-size: 12px;">'.$data["ADD_VAL1"].'</span>
                </div>
            ';
        });
        $dt->edit('ADD_VAL2', function($data){
            global $ARR_CLR;
            return '
                <div class="text-center">
                    <span class="badge bg-'.$ARR_CLR["$data[ADD_VAL2]"].' h-50 d-inline-block bg-opacity-15 text-white" style="font-size: 12px;">'.$data["ADD_VAL2"].'</span>
                </div>
            ';
        });
        $dt->edit('ADD_VAL3', function($data){
            global $ARR_CLR;
            return '
                <div class="text-center">
                    <span class="badge bg-'.$ARR_CLR["$data[ADD_VAL3]"].' h-50 d-inline-block bg-opacity-15 text-white" style="font-size: 12px;">'.$data["ADD_VAL3"].'</span>
                </div>
            ';
        });
        $dt->edit('ID_ADD', function($data){
            return '
                <div>
                    <a href="home.php?page=apu_eddhstrydtl&x='.$data["ID_ADD"].'" class="btn btn-sm btn-primary">Detail</a>
                </div>
            ';
        });
        $dt->edit('ADD_ARF', function($data){
            return '
                <div class="text-center">
                    '.$data["ADD_ARF"].'
                </div>
            ';
        });
        
        
        echo $dt->generate()->toJson();
    }
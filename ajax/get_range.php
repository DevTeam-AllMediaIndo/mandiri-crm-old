<?php
    include_once('../setting.php');
    if(isset($_GET["val"])){
        $val = form_input($_GET["val"]);
        $ARRE = [];
        $SQL_VW = mysqli_query($db,'
            SELECT
                tb_range.RNG_MIN,
                tb_range.RNG_MAX,
                tb_range.RNG_LEVEL
            FROM tb_range
            WHERE tb_range.RNG_TYPE = 2
        ') or die(mysqli_error($db));
        if($SQL_VW && mysqli_num_rows($SQL_VW) > 0){
            while($RSLT_VW = mysqli_fetch_assoc($SQL_VW)){
                if($val >= $RSLT_VW["RNG_MIN"] && $val <= (($RSLT_VW["RNG_MAX"] == -1) ? INF : $RSLT_VW["RNG_MAX"])){
                    $ARRE[] = $RSLT_VW["RNG_LEVEL"];       
                }
            }
        }
        echo json_encode($ARRE,true);
    }
    if(isset($_GET["val2"])){
        $val = ($_GET["val2"]);
        $ARRE = [];
            $SQL_VW = mysqli_query($db,'
                SELECT
                    tb_range.RNG_MIN,
                    tb_range.RNG_MAX,
                    tb_range.RNG_LEVEL
                FROM tb_range
                WHERE tb_range.RNG_TYPE = 2
            ') or die(mysqli_error($db));
            if($SQL_VW && mysqli_num_rows($SQL_VW) > 0){
                while($RSLT_VW = mysqli_fetch_assoc($SQL_VW)){
                    if($val >= $RSLT_VW["RNG_MIN"] && $val <= (($RSLT_VW["RNG_MAX"] == -1) ? INF : $RSLT_VW["RNG_MAX"])){
                        $ARRE[] = $RSLT_VW["RNG_LEVEL"];       
                    }
                }
            }
        echo json_encode($ARRE,true);
    }
<?php
    include_once('../setting.php');
    if(isset($_POST['userid'])){
        $userid = mysqli_real_escape_string($db, strip_tags(addslashes($_POST["userid"])));
        
        $SQL_QUERY = mysqli_query($db, '
            SELECT tb_schedule.ID_SCHD
            FROM tb_schedule
            WHERE MD5(MD5(tb_schedule.ID_SCHD)) = "'.$userid.'"
            LIMIT 1
        ');
        if(mysqli_num_rows($SQL_QUERY) > 0) {
            $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
            
            $EXEC_SQL = mysqli_query($db, '
                UPDATE tb_schedule SET
                tb_schedule.SCHD_DETAIL = -1
                WHERE tb_schedule.ID_SCHD = '.$RESULT_QUERY['ID_SCHD'].'
                AND tb_schedule.SCHD_DETAIL = 0
            ') or die (mysqli_error($db));
        } else { die ("<script>alert('record not found 2');location.href = 'home.php?page=jadwal-temu'</script>"); }
    } else { die ("<script>alert('record not found 1');location.href = 'home.php?page=jadwal-temu'</script>"); }
    exit;
?>
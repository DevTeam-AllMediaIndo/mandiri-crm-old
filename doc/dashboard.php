
<?php
    
    $SQL_QUERY = mysqli_query($db, '

        SELECT
        IFNULL((
            SELECT COUNT(tb_member.MBR_ID)
            FROM tb_member
            WHERE tb_member.MBR_STS = -1
        ), 0) AS TOTAL_USER,
        IFNULL((
            SELECT COUNT(tb_racc.ID_ACC)
            FROM tb_racc
            WHERE tb_racc.ACC_DERE = 1
            AND tb_racc.ACC_LOGIN <> "0"
            AND tb_racc.ACC_WPCHECK = 6
        ), 0) AS TOTAL_ACTIVE_REAL,
        IFNULL((
            SELECT COUNT(tb_racc.ID_ACC)
            FROM tb_racc
            WHERE tb_racc.ACC_DERE = 1
            AND tb_racc.ACC_LOGIN = "0"
            AND tb_racc.ACC_WPCHECK < 6
        ), 0) AS TOTAL_UNACTIVE_REAL,
        IFNULL((
            SELECT COUNT(tb_schedule.ID_SCHD)
            FROM tb_schedule
            JOIN tb_member
            ON(tb_member.MBR_ID = tb_schedule.SCHD_ID)
            WHERE tb_schedule.SCHD_STS = 0
        ), 0) AS TOTAL_PENDING_JADWALTEMU
    ');
    if(mysqli_num_rows($SQL_QUERY) > 0){
        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
        $TOTAL_USER = $RESULT_QUERY['TOTAL_USER'];
        $TOTAL_ACTIVE_REAL = $RESULT_QUERY['TOTAL_ACTIVE_REAL'];
        $TOTAL_UNACTIVE_REAL = $RESULT_QUERY['TOTAL_UNACTIVE_REAL'];
        $TOTAL_PENDING_JADWALTEMU = $RESULT_QUERY['TOTAL_PENDING_JADWALTEMU'];
    } else {
        $TOTAL_USER = 0;
        $TOTAL_ACTIVE_REAL = 0;
        $TOTAL_UNACTIVE_REAL = 0;
        $TOTAL_PENDING_JADWALTEMU = 0;
    }
?>
<div class="row">
    <div class="col-xl-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="lead">Total User</div>
                <h2 class="card-title"><?php echo number_format($TOTAL_USER, 0) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-xl-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="lead">Active Real Account</div>
                <h2 class="card-title"><?php echo number_format($TOTAL_ACTIVE_REAL, 0) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-xl-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="lead">Un-active Real Account</div>
                <h2 class="card-title"><?php echo number_format($TOTAL_UNACTIVE_REAL, 0) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-xl-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="lead">Pending Jadwal Temu</div>
                <h2 class="card-title"><?php echo number_format($TOTAL_PENDING_JADWALTEMU, 0) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-xl-6 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="lead">Pending Top-Up</div>
                <h2 class="card-title"><?php echo number_format(0,0) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-xl-6 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="lead">Pending Withdrawal</div>
                <h2 class="card-title"><?php echo number_format(0,0) ?></h2>
            </div>
        </div>
    </div>
</div>
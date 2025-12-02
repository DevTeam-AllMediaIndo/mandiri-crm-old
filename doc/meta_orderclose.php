<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">MetaTrader</a></li>
        <li class="breadcrumb-item active" aria-current="page">Order Close</li>
    </ol>
</nav>
<div class="card mt-3">
    <div class="card-header font-weight-bold">Order Close</div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Deal</th>
                        <th style="vertical-align: middle" class="text-center">Login</th>
                        <th style="vertical-align: middle" class="text-center">Time</th>
                        <th style="vertical-align: middle" class="text-center">Symbol</th>
                        <th style="vertical-align: middle" class="text-center">Volume</th>
                        <th style="vertical-align: middle" class="text-center">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_get_account_real = mysqli_query($db, "
                        SELECT 
                            ACC_DATETIME,
                            ACC_F_APP_PRIBADI_NAMA,
                            ACC_LOGIN,
                            MBR_EMAIL,
                            MBR_CITY
                        FROM tb_racc 
                        JOIN tb_member ON (tb_member.MBR_ID = tb_racc.ACC_MBR)
                        WHERE ACC_LOGIN != 0
                        AND (ACC_DERE = 2 OR (ACC_DERE = 1 AND ACC_WPCHECK = 6))
                    ");

                    if ($sql_get_account_real && mysqli_num_rows($sql_get_account_real) != 0) :
                        $racc_real      = mysqli_fetch_all($sql_get_account_real, MYSQLI_ASSOC);
                        $list_login     = array_column($racc_real, 'ACC_LOGIN');
                        $params         = [
                            'logins'    => implode(",", $list_login),
                            'date_From' => implode("T", [date("Y-m-d", strtotime("-1 week")), "00:00:00"]),
                            'date_To'   => implode("T", [date("Y-m-d", strtotime("+1 day")), "00:00:00"])
                        ];

                        $HistoryRequestList  = mt5api_connect("HistoryRequestList", $params);
                        $history = json_decode($HistoryRequestList);
                    ?>

                        <?php if ($history->status == "success" && is_array($history->message)) : ?>
                            <?php foreach ($history->message as $order) : ?>
                                <tr>
                                    <td><?= $order->order ?></td>
                                    <td><?= $order->login ?></td>
                                    <td><?= date("Y-m-d H:i:s", strtotime($order->openTime)); ?></td>
                                    <td><?= $order->symbol ?></td>
                                    <td class="text-right"><?= ($order->volume / 10000) ?></td>
                                    <td class="text-right"><?= $order->openPrice ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#table').DataTable( {
            dom: 'Blfrtip',
            "processing": true,
            "deferRender": true,
            "lengthMenu": [[50, 75, 100, -1], [50, 75, 100, "<?= $setting_small ?>"]],
            "scrollX": true,
            "order": [[ 0, "desc" ]],
            "drawCallback": function(tbl){
                pageReload();
            }
        } );
    } );
</script>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">MetaTrader</a></li>
        <li class="breadcrumb-item active" aria-current="page">Account Demo</li>
    </ol>
</nav>
<div class="card mt-3">
    <div class="card-header font-weight-bold">Account Demo</div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <!-- <th style="vertical-align: middle" class="text-center">Date Reg</th> -->
                        <th style="vertical-align: middle" class="text-center">Login</th>
                        <!-- <th style="vertical-align: middle" class="text-center">Name</th> -->
                        <th style="vertical-align: middle" class="text-center">Email</th>
                        <th style="vertical-align: middle" class="text-center">City</th>
                        <th style="vertical-align: middle" class="text-center">Leverage</th>
                        <th style="vertical-align: middle" class="text-center">Balance</th>
                        <th style="vertical-align: middle" class="text-center">Equity Previous Day</th>
                        <th style="vertical-align: middle" class="text-center">Free Margin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $getAccounts = mt5api_demo_connect(51, "Accounts", ""); 
                    $accounts = json_decode($getAccounts, true);
                    ?>
                    <?php if(is_array($accounts) && array_key_exists("message", $accounts) && is_array($accounts['message'])) : ?>
                        <?php foreach($accounts['message'] as $acc) : ?>
                            <?php if(array_key_exists("Login", $acc) && $acc['Group'] == "demo\\forex-hedge-usd-MIF") : ?>
                                <tr>
                                    <td><?= $acc['Login'] ?? "-" ?></td>
                                    <td><?= $acc['EMail'] ?? "-" ?></td>
                                    <td><?= $acc['City']  ?? "-" ?></td>
                                    <td><?= $acc['Leverage'] ?? "-" ?></td>
                                    <td width="15%" class="text-right"><?= number_format(round($acc['Balance'], 2), 2) ?? "-" ?></td>
                                    <td width="15%" class="text-right"><?= number_format(round($acc['EquityPrevDay'], 2), 2) ?? "-" ?></td>
                                    <td width="15%" class="text-right"><?= number_format(round($acc['FreeMargin'], 2), 2) ?? "-" ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
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
            "lengthMenu": [[50, 75, 100, -1], [50, 75, 100, "<?= $setting_small ?>"]],
            "scrollX": true,
            "order": [[ 0, "desc" ]],
            "drawCallback": function(tbl){
                pageReload();
            }
        } );
    } );
</script>
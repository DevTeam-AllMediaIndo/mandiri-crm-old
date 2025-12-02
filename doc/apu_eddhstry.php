<?php
    if(isset($_GET["x"])){
        $x = form_input($_GET["x"]);
?>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">APUPPT</a></li>
            <li class="breadcrumb-item"><a href="#">EDD</a></li>
            <li class="breadcrumb-item active" aria-current="page">History EDD</li>
    </ol>
</nav>
<div class="card mt-3">
    <div class="card-header font-weight-bold">History EDD</div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Date Time</th>
                        <th style="vertical-align: middle" class="text-center">Nama</th>
                        <th style="vertical-align: middle" class="text-center">NIK</th>
                        <th style="vertical-align: middle" class="text-center">Risiko Jumlah Rekening Transaksi Milik Nasabah</th>
                        <th style="vertical-align: middle" class="text-center">Risiko Total Deposit (Top-up) per hari</th>
                        <th style="vertical-align: middle" class="text-center">Risiko Total Equity Nasabah</th>
                        <th style="vertical-align: middle" class="text-center">Analisa Dan Rekomendasi, Faktor Lainnya</th>
                        <th style="vertical-align: middle" class="text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#table').DataTable({
            dom: 'Blfrtip',
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url"         : "doc/<?php echo $login_page ?>_ajax.php",
                "contentType" : "application/json",
                "type"        : "GET",
                "data"        : {
                    "x" : "<?php echo $x; ?>"
                }
            },
            "deferRender": true,
            "lengthMenu": [[50, 75, 100, -1], [50, 75, 100, "<?= $setting_small ?>"]],
            "scrollX": true,
            "order": [[ 0, "desc" ]],
            "drawCallback": function(tbl){
                pageReload();
            }
        });
    });
</script>
<?php
    }
?>
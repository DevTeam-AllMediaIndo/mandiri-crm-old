<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">APUPPT</a></li>
        <li class="breadcrumb-item active" aria-current="page">Evaluasi Calon Nasabah</li>
    </ol>
</nav>
<div class="card mt-3">
    <div class="card-header font-weight-bold">
        Calon Nasabah Yang Belum Di Evaluasi
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Date Time</th>
                        <th style="vertical-align: middle" class="text-center">Nama</th>
                        <th style="vertical-align: middle" class="text-center">NIK</th>
                        <th style="vertical-align: middle" class="text-center">Tanggal Lahir</th>
                        <th style="vertical-align: middle" class="text-center">Email</th>
                        <th style="vertical-align: middle" class="text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="card mt-3">
    <div class="card-header font-weight-bold">
        History Evaluasi Calon Nasabah
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table2" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Date Time</th>
                        <th style="vertical-align: middle" class="text-center">Nama</th>
                        <th style="vertical-align: middle" class="text-center">NIK</th>
                        <th style="vertical-align: middle" class="text-center">Tanggal Lahir</th>
                        <th style="vertical-align: middle" class="text-center">Email</th>
                        <th style="vertical-align: middle" class="text-center">Tingkat Risiko</th>
                        <th style="vertical-align: middle" class="text-center">Konfirmasi APUPPT</th>
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
                "type"        : "GET" ,
                "data"        : {
                    "adm" : '<?= $ids ?>'
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
        $('#table2').DataTable({
            dom: 'Blfrtip',
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url"         : "doc/<?php echo $login_page ?>_ajax.php",
                "contentType" : "application/json",
                "type"        : "GET" ,
                "data"        : {
                    "scndt" : 1
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
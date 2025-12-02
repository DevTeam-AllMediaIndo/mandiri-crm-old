<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">APUPPT</a></li>
        <li class="breadcrumb-item"><a href="#">Evaluasi Nasabah</a></li>
    </ol>
</nav>
<div class="card mt-3">
    <div class="card-header font-weight-bold">Evaluasi Calon Nasabah Lanjutan</div>
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
                        <th style="vertical-align: middle" class="text-center">Login</th>
                        <th style="vertical-align: middle" class="text-center">Konfirmasi APUPPT</th>
                        <th style="vertical-align: middle" class="text-center">Tingkat Risiko</th>
                        <th style="vertical-align: middle" class="text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="card mt-3">
    <div class="card-header font-weight-bold">Status Nasabah Dan Hasil Evaluasi</div>
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
                        <th style="vertical-align: middle" class="text-center">Login</th>
                        <th style="vertical-align: middle" class="text-center">Status</th>
                        <th style="vertical-align: middle" class="text-center">Tingkat Risiko</th>
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
                    "table1" : true
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
                "type"        : "GET",
                "data"        : {
                    "table1" : false
                }
            },
            "deferRender": true,
            "lengthMenu": [[50, 75, 100, -1], [50, 75, 100, "<?= $setting_small ?>"]],
            "scrollX": true,
            "order": [[ 6, "asc" ]],
            "drawCallback": function(tbl){
                pageReload();
            }
        });
        
    });
</script>
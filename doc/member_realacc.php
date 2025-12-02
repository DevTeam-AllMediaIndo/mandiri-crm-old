<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Member</a></li>
        <li class="breadcrumb-item active" aria-current="page">Progress Real Account</li>
    </ol>
</nav>
<div class="card">
    <div class="card-header font-weight-bold">Progress Real Account</div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Date Reg</th>
                        <th style="vertical-align: middle" class="text-center">Login</th>
                        <th style="vertical-align: middle" class="text-center">Nama</th>
                        <th style="vertical-align: middle" class="text-center">Email</th>
                        <th style="vertical-align: middle" class="text-center">Type Acc</th>
                        <th style="vertical-align: middle" class="text-center">Product</th>
                        <th style="vertical-align: middle" class="text-center">Rate</th>
                        <th style="vertical-align: middle" class="text-center">IB Code</th>
                        <th style="vertical-align: middle" class="text-center">Status</th>
                        <th style="vertical-align: middle" class="text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#table').DataTable( {
            dom: 'Blfrtip',
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "doc/<?php echo $login_page ?>_ajax.php",
                "contentType": "application/json",
                "type": "GET",
                "data": {
                    "usr" : "<?php echo md5(md5($user1["ADM_ID"])) ?>"
                }
            },
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
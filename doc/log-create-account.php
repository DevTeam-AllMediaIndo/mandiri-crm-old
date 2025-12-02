<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Log</a></li>
        <li class="breadcrumb-item active" aria-current="page">Log Create Account</li>
    </ol>
</nav>
<div class="card">
    <div class="card-header font-weight-bold">Log Create Account</div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th width="15%" style="vertical-align: middle" class="text-center">Date Time</th>
                        <th width="20%" style="vertical-align: middle" class="text-center">Client</th>
                        <th style="vertical-align: middle" class="text-center">Message</th>
                        <th width="10%" style="vertical-align: middle" class="text-center">Link</th>
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
            "ajax": "doc/<?php echo $login_page ?>_ajax.php",
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
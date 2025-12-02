<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Member</a></li>
        <li class="breadcrumb-item active" aria-current="page">Demo Account</li>
    </ol>
</nav>
<div class="card">
    <div class="card-header font-weight-bold">Daftar Real Account</div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Date Reg</th>
                        <th style="vertical-align: middle" class="text-center">Login</th>
                        <th style="vertical-align: middle" class="text-center">Nama</th>
                        <th style="vertical-align: middle" class="text-center">Email</th>
                        <th style="vertical-align: middle" class="text-center">Master</th>
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
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "scrollX": true,
            "order": [[ 0, "desc" ]],
            "drawCallback": function(tbl){
                pageReload();
            }
        } );
    } );
</script>
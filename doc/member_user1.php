<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Member</a></li>
        <li class="breadcrumb-item active" aria-current="page">User</li>
    </ol>
</nav>
<div class="card">
    <div class="card-header font-weight-bold">All Member</div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Date Reg</th>
                        <th style="vertical-align: middle" class="text-center">ID</th>
                        <th style="vertical-align: middle" class="text-center">Name</th>
                        <th style="vertical-align: middle" class="text-center">Email</th>
                        <th style="vertical-align: middle" class="text-center">Password</th>
                        <th style="vertical-align: middle" class="text-center">Status</th>
                        <th style="vertical-align: middle" class="text-center">#</th>
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
            "order": [[ 0, "desc" ]]
        } );
    } );
</script>
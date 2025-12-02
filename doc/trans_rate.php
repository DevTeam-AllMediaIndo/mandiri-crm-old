<?php
    if(isset($_POST["smbt_insrate"])){
        if(isset($_POST["rate"])){
            $rate    = (float)form_input(str_replace(['Rp.','.', ','], ['','', '.'], $_POST["rate"]));
            $INS_SQL = mysqli_query($db, '
                INSERT INTO tb_rate SET
                tb_rate.RATE_AMMOUNT = '.$rate.',
                tb_rate.RATE_DATE    = "'.date("Y-m-d").'"
            ') or die("<script>alert('Err DeBe Ins!');location.href = 'home.php?page=".$login_page."'</script>");
            die("<script>alert('Berhasil Menambahkan Rate Untuk TGL:".date("Y-m-d")."');location.href = 'home.php?page=".$login_page."'</script>");
        }else{ die("<script>alert('Some parameter is missing');location.href = 'home.php?page=".$login_page."'</script>"); }
    }

    if(isset($_POST["submit_apply"])){
        if(isset($_POST["submit_apply"])){
            if(!empty($_POST["ib_name"])){
                $submit_apply = form_input($_POST["submit_apply"]);
                $ib_name      = (float)form_input(str_replace(['Rp.','.', ','], ['','', '.'], $_POST["ib_name"]));
                $SQL_QUER     = mysqli_query($db, '
                    UPDATE tb_rate SET
                        tb_rate.RATE_AMMOUNT = "'.$ib_name.'";
                    WHERE MD5(MD5(tb_rate.ID_RATE)) = "'.$submit_apply.'"
                ') or die("<script>alert('Err DeBe UpeDeTe!');location.href = 'home.php?page=".$login_page."'</script>");
                die("<script>alert('Berhasil Menambahkan Ammount');location.href = 'home.php?page=".$login_page."'</script>");
            }else{ die("<script>alert('Beberapa parameter tidak ditemukan!. 2');location.href = 'home.php?page=".$login_page."'</script>"); }
        }else{ die("<script>alert('Beberapa parameter tidak ditemukan!. 1');location.href = 'home.php?page=".$login_page."'</script>"); }
    }
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Transaction</a></li>
        <li class="breadcrumb-item active" aria-current="page">Rate</li>
    </ol>
</nav>
<div class="row">
    <div class="col-4"></div>
    <div class="col-4">
        <form method="post">
            <div class="card">
                <div class="card-header">Insert Rate Form</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="rate">Rate Untuk <?= date("Y-m-d")?></label>
                        <input type="text" name="rate" id="rate" class="form-control rate" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" name="smbt_insrate">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-4"></div>
</div>
<div class="card mt-3">
    <div class="card-header font-weight-bold">Rate History</div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table_history" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Date</th>
                        <th style="vertical-align: middle" class="text-center">Ammount</th>
                        <th style="vertical-align: middle" class="text-center">#</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_insert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="post">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Update Rate</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Rate</label>
                        <input type="text" class="form-control text-center rate" id="apply_name" name="ib_name" required autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit_apply" name="submit_apply" class="btn btn-success">Insert</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(() => {
        function formatRupiah(angka, prefix = 'Rp. '){
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split   		= number_string.split(','),
            sisa     		= split[0].length % 3,
            rupiah     		= split[0].substr(0, sisa),
            ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if(ribuan){
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }
        function reCall(){
            $('.rate').on('keyup', function(e)  {
                $(this).val(formatRupiah($(this).val()));
            });
            $('.edt').on('click', function(e){
                $('#apply_name').val($(this).data('amt'));
                $('#submit_apply').val($(this).val());
            });
        }

        
        $('#table_history').DataTable({
            dom: 'Blfrtip',
            "processing": true,
            "serverSide": true,
            "ajax": "doc/<?php echo $login_page ?>_ajax.php",
            "deferRender": true,
            "lengthMenu": [[50, 75, 100, -1], [50, 75, 100, "<?= $setting_small ?>"]],
            "scrollX": true,
            "order": [[ 0, "desc" ]],
            "drawCallback": function(tbl){
                reCall();
            }
        });
    });
</script>
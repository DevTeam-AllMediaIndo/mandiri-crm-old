<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">APUPPT</a></li>
        <li class="breadcrumb-item active" aria-current="page">Evaluasi Nasabah</li>
    </ol>
</nav>

<div class="card mt-3">
    <div class="card-header font-weight-bold">
        Regol Baru
        <?php if($user1["ADM_LEVEL"] != 0){ ?>
            <button data-target="#tambah_eval" data-toggle="modal" class="btn btn-sm btn-success ins plus text-white float-right" style="margin-left: auto; justify-content: flex-end;">
                Tambah Data Evaluasi <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            </button>
        <?php }?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Date Time</th>
                        <th style="vertical-align: middle" class="text-center">Name</th>
                        <th style="vertical-align: middle" class="text-center">Email</th>
                        <th style="vertical-align: middle" class="text-center">Account</th>
                        <th style="vertical-align: middle" class="text-center">Konfirmasi APUPPT</th>
                        <th style="vertical-align: middle" class="text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <?php if($user1["ADM_LEVEL"] != 0){ ?>
        <div class="modal fade" id="tambah_eval" role="dialog" aria-labelledby="hdr" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Evaluasi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Email</label>
                            <select class="form-control select2" id="nas" data-dropdown-css-class="select2-danger" style="width: 100%;" data-select2-id="12" aria-hidden="true">
                                <option value disabled selected>Plih Nasabah Untuk di Evaluasi</option>
                                <?php
                                    $SQL_OPT = mysqli_query($db,'
                                        SELECT
                                            tb_member.MBR_NAME,
                                            tb_racc.ACC_LOGIN,
                                            MD5(MD5(tb_racc.ID_ACC)) AS ID_ACC
                                        FROM tb_member
                                        JOIN tb_racc
                                        ON(tb_member.MBR_ID = tb_racc.ACC_MBR)
                                        WHERE tb_racc.ACC_DERE = 1
                                        GROUP BY tb_racc.ACC_LOGIN
                                        ORDER BY tb_member.MBR_NAME
                                    ');
                                    if($SQL_OPT && mysqli_num_rows($SQL_OPT) > 0){
                                        while($RSLT_OPT = mysqli_fetch_assoc($SQL_OPT)){
                                ?>
                                    <option data-select2-id="<?php echo $RSLT_OPT["ID_ACC"] ?>" value="<?php echo $RSLT_OPT["ID_ACC"] ?>">
                                        <?php echo $RSLT_OPT["MBR_NAME"].'('.$RSLT_OPT["ACC_LOGIN"].')' ?>
                                    </option>
                                <?php                   
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a href="#" class="btn btn-success" id="anc">Evaluasi</a>
                    </div>
                </div>
            </div>
        </div>
    <?php }?>
</div>

<script>
    $(document).ready(function() {    
        <?php if($user1["ADM_LEVEL"] != 0){ ?>
            $('.select2').select2({
                placeholder: "please select",
                dropdownParent: $('#tambah_eval'),
                width: '100%',
            });
        <?php }?>
        $('#table').DataTable( {
            dom: 'Blfrtip',
            "processing": true,
            "serverSide": true,
            "ajax": "doc/<?php echo $login_page ?>_ajax1.php",
            "deferRender": true,
            "lengthMenu": [[50, 75, 100, -1], [50, 75, 100, "Semua"]],
            "scrollX": true,
            "order": [[ 0, "desc" ]],
            "drawCallback": function() {
                let apuc = Array.from(document.getElementsByClassName('apuc'));
                apuc.forEach(async function(el){
                    if(el.innerHTML.length > 0){
                        let pattern = /[a-zA-Z]/;
                        if(!pattern.test(el.innerHTML)){
                            el.innerHTML = await conv(el.innerHTML);
                        }
                    }
                });
            }
        });
        <?php if($user1["ADM_LEVEL"] != 0){ ?>
            $('#nas').on('change', function(e){
                document.getElementById('anc').href = `home.php?page=apu_evnasdtl&x=${e.target.value}`
            });
        <?php }?>
        
    } );

    async function conv(val1){
        let txt = '';
        await $.ajax({
            url      : 'ajax/get_range.php',
            type     : 'GET',
            dataType : 'JSON',
            data     : {
                val : val1
            }
        }).done(function(resp){
            txt = resp[0]+'('+val1+')';
        });
        return txt;
    }
</script>
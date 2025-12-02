<?php
    require 'vendor/autoload.php';
    error_reporting(E_ALL);
    try {
        // use PhpOffice\PhpSpreadsheet\Spreadsheet;
        // use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

        $inputFileName = 'assets/ddtot.xls';
        //code...
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $testAgainstFormats = [
            \PhpOffice\PhpSpreadsheet\IOFactory::READER_XLS,
            \PhpOffice\PhpSpreadsheet\IOFactory::READER_HTML,
        ];

        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
        /**  Create a new Reader of the type that has been identified  **/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($inputFileName);
        // print_r($spreadsheet->getActiveSheet()->getHighestRow());
        $i = 1;
        $thead;
        $tbody = [];
        foreach($spreadsheet->getActiveSheet()->getRowIterator() as $KEY1 => $row) {
            // if($i == 5) break;
            $tbody[] = $row;
            foreach ($row->getCellIterator() as $key => $value) {
                $thead = $row->getCellIterator();
            }
            // if($i == 5){ break; }
            $i++;
        }


    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
        throw $e;
    }
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header font-weight-bold">Tabel DTTOT</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table_search" class="table table-striped table-hover table-bordered" width="100%">
                        <thead>
                            <tr>
                                <?php 
                                    $num = 1; 
                                    foreach($tbody as $tbd => $vtbd){ 
                                        if($num == 1){
                                ?>
                                        <th class="text-center">No</th>
                                        <?php 
                                            foreach($vtbd->getCellIterator() as $ths => $vths){       
                                        ?>
                                            <th class="text-center"><?php print_r($vths->getValue("")); ?></th>
                                        <?php 
                                            }
                                        ?>
                                <?php 
                                            break;
                                        }
                                        $num++; 
                                    }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $num = 1; 
                                foreach($tbody as $tbd => $vtbd){ 
                                    if($num != 1){
                            ?>
                                <tr>
                                    <td><?php echo ($num-1); ?></td>
                                    <?php 
                                        foreach($vtbd->getCellIterator() as $ths => $vths){       
                                    ?>
                                        <td><?php  print_r($vths->getValue("")) ?></td>
                                    <?php 
                                        }
                                    ?>
                                </tr>
                            <?php 
                                    }
                                    $num++; 
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#table_search').DataTable({
            dom: 'Blfrtip',
            "processing": true,
            "deferRender": true,
            "lengthMenu": [[10, 25, 50, -1].reverse(), [10, 25, 50, "Kabeh"].reverse()],
            "scrollX": true,
            "order": [[ 0, "asc" ]]
        });
        var nm = Number("<?php echo base64_decode(form_input($_GET["nms"]));  ?>");
        var nk = Number("<?php echo base64_decode(form_input($_GET["nks"]));  ?>");
        let tby = document.querySelectorAll('tbody');
        console.log(tby);
        if(!isNaN(nk)){ 
            tby[0].children[nk].children[2].style.background = 'orange'; 
            tby[0].children[nk].children[2].scrollIntoView();
            setTimeout(function(){
                tby[0].children[nk].children[2].scrollIntoView();
            }, 1000);
        }
        if(!isNaN(nm)){ 
            tby[0].children[nm].children[1].style.background = 'yellow'; 
            tby[0].children[nm].children[2].scrollIntoView();
            setTimeout(function(){
                tby[0].children[nm].children[2].scrollIntoView();
            }, 1500);
        }
    });
</script>
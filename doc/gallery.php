<?php
    require '../vendor/autoload.php';

    use Aws\S3\S3Client;
    
    $s3 = new Aws\S3\S3Client([
        'region'  => $region,
        'version' => 'latest',
        'credentials' => [
            'key'    => $IAM_KEY,
            'secret' => $IAM_SECRET,
        ]
    ]);	
    if(isset($_POST["submit"])){
        $newfilename1 = round(microtime(true));
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png");
        if(isset($_FILES["file_upload"]) && $_FILES["file_upload"]["error"] == 0){
            
        
            $img_name = $_FILES["file_upload"]["name"];
            $img_type = $_FILES["file_upload"]["type"];
            
            $img_ext = pathinfo($img_name, PATHINFO_EXTENSION);
            
            if(array_key_exists($img_ext, $allowed)){
                if(in_array($img_type, $allowed)){
                    $img_new = 'gallery_'.time().'_'.rand(100000000, 999999999).'.'.$img_ext;
                    if(move_uploaded_file($_FILES["file_upload"]["tmp_name"], "upload/" . $img_new)){
                        
                        $img_Path = 'upload/'. $img_new;
                        $img_key = basename($img_Path);

                        try {
                            $result = $s3->putObject([
                                'Bucket' => $bucketName,
                                'Key'    => $folder.'/'.$img_key,
                                'Body'   => fopen($img_Path, 'r'),
                                'ACL'    => 'public-read', // make file 'public'
                            ]);
                            mysqli_query($db, '
                                INSERT INTO tb_gallery SET
                                tb_gallery.GALLERY_NAME = "'.$img_new.'",
                                tb_gallery.GALLERY_DATETIME = "'.date("Y-m-d H:i:s").'"
                            ') or die (mysqli_error($db));
                            unlink($img_Path);
                            die("<script>alert('Success Upload');location.href = 'home.php?page=gallery'</script>");
                        } catch (Aws\S3\Exception\S3Exception $e) {
                            die ("<script>alert('There was an error uploading the file.'); location.href = 'home.php?page=gallery'</>");
                        }
                    } else { die ("<script>alert('File is not uploaded.'); location.href = 'home.php?page=gallery'</script>"); }
                } else { die ("<script>alert('Error: There was a problem uploading your file. Please try again.'); location.href = 'home.php?page=gallery'</script>"); }
            } else { die ("<script>alert('Only *.jpg, *.jpeg, *.png'); location.href = 'home.php?page=gallery'</script>"); }
        };
    }
    if(isset($_GET["hps"])){
        $hps = form_input($_GET["hps"]);
        $EXEC_SQL = mysqli_query($db,'
            DELETE FROM tb_gallery WHERE MD5(MD5(tb_gallery.ID_GALLERY)) = "'.$hps.'"
        ') or die(mysqli_error($db));
        die("<script>alert('Success Delete');location.href = 'home.php?page=gallery'</script>");
    }
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Blog</a></li>
        <li class="breadcrumb-item active" aria-current="page">Gallery</li>
    </ol>
</nav>
<div class="card mt-3">
    <div class="card-header font-weight-bold">Input Blog</div>
    <form method="POST" enctype="multipart/form-data" name="fm" id="fm">
        <div class="card-body">
            <style>
                .uploader {position:relative; overflow:hidden; width:500px; height:350px; background:#FEFEFE; border:5px dashed #e8e8e8;}
                #filePhoto{
                    position:absolute;
                    width:500px;
                    height:350px;
                    top:-50px;
                    left:0;
                    z-index:2;
                    opacity:0;
                    cursor:pointer;
                }
                .uploader img{
                    position:absolute;
                    width:500px;
                    height:350px;
                    top:-1px;
                    left:-1px;
                    z-index:1;
                    border:none;
                }
                p {
                    font-size: 20px;
                    text-align: center;
                }
            </style>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="uploader" onclick="$('#filePhoto').click()">
                                <p style="margin-top: 140px;">
                                    Klik atau drop file bukti mutasi disini
                                </p>
                                <img src=""/>
                                <input type="file" name="file_upload"  id="filePhoto" required>
                            </div>
                            <?php if($user1["ADM_LEVEL"] != 0){ ?>
                                <button type="submit" name="submit" class="btn btn-primary mt-3" required>Insert New</button>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
            <style>
                a.ths:link {
                    color: #000;
                    text-decoration: none;
                    cursor: auto;
                }

                a.ths:visited {
                    color: #000;
                    text-decoration: none;
                    cursor: auto;
                }

                a.ths:hover {
                    text-decoration: underline;
                    cursor: auto;
                }
            </style>
            <div class="row text-center text-lg-start mt-3">
                <?php
                    $SQL_DATA =  mysqli_query($db, '
                        SELECT
                            tb_gallery.ID_GALLERY,
                            tb_gallery.GALLERY_NAME,
                            tb_gallery.GALLERY_DATETIME,
                            tb_gallery.GALLERY_TIMESTAMP
                        FROM tb_gallery
                        ORDER BY tb_gallery.ID_GALLERY DESC
                    ');
                    if(mysqli_num_rows($SQL_DATA) > 0){
                        while($RSLT_DATA = mysqli_fetch_assoc($SQL_DATA)){                 
                ?>
                    <div class="col-3">
                        <a href="javascript:void(0)" class="d-block mb-4 h-100 ths">
                            <img class="img-fluid img-thumbnail gmbr" src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_DATA['GALLERY_NAME']; ?>" alt="">
                            <input type="text" class="form-control" value="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_DATA['GALLERY_NAME']; ?>">
                            <input type="hidden" class="form-control" name="dt" value="<?php echo md5(md5($RSLT_DATA['ID_GALLERY'])); ?>">
                        </a>
                    </div>
                <?php
                        }
                    }
                ?>
            </div>
        </div>
    </form>
</div>
<script>
    var imageLoader = document.getElementById('filePhoto');

    imageLoader.addEventListener('change', handleImage, false);
    function handleImage(e) {
        var reader = new FileReader();
        reader.onload = function (event) {
            
            $('.uploader img').attr('src',event.target.result);
        }
        reader.readAsDataURL(e.target.files[0]);
    }
    let gmbr = document.getElementsByClassName("gmbr");
    console.log(document.forms);
    for (let i = 0; i < gmbr.length; i++) {
        gmbr[i].addEventListener('dblclick', function dc(e){
            const imgSrc = e.target.currentSrc;
            console.log(e);
            if (confirm(`Apa anda yakin akan menghapus ${imgSrc}`) == true) {
                location.href = `${e.target.baseURI}&hps=${e.target.nextElementSibling.nextElementSibling.value}`;
            } else {
                alert('nok');;
            }
        });
    }
</script>

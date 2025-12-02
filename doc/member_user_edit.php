<?php

    require '../vendor/autoload.php';

    use Aws\S3\S3Client;

    if($user1["ADM_LEVEL"] == 3 || $user1["ADM_LEVEL"] == 1){
        if(isset($_GET["x"])){
            $x = $_GET["x"];
            $SQL_QUERY  = mysqli_query($db,'
                SELECT
                *
                FROM tb_member
                WHERE MD5(MD5(tb_member.MBR_ID)) = "'.$x.'"
                LIMIT 1
            ') or die(mysqli_error($db));
            if(mysqli_num_rows($SQL_QUERY) > 0){
                $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
                $tgl = $RESULT_QUERY["MBR_DATETIME"];
            }else{ die("<script>alert('Data Not Found!!!');location.href = 'home.php?page=member_user'</script>"); };
        }
        if(isset($_POST["submit_profile"])){
            if(isset($_POST["fullname"])){
                if(isset($_POST["phone"])){
                    if(isset($_POST["country"])){
                        if(isset($_POST["address"])){
                            if(isset($_POST["city"])){
                                if(isset($_POST["zip"])){
                                    if(isset($_POST["reg_date"])){
                                        $fullname = form_input($_POST["fullname"]);
                                        $phone    = form_input($_POST["phone"]);
                                        $country  = form_input($_POST["country"]);
                                        $address  = form_input($_POST["address"]);
                                        $city     = form_input($_POST["city"]);
                                        $zip      = form_input($_POST["zip"]);
                                        $reg_date = form_input($_POST["reg_date"]);

                                        mysqli_query($db,'
                                            UPDATE tb_member SET
                                                tb_member.MBR_NAME     = "'.$fullname.'",
                                                tb_member.MBR_PHONE    = "'.$phone.'",
                                                tb_member.MBR_COUNTRY  = "'.$country.'",
                                                tb_member.MBR_ADDRESS  = "'.$address.'",
                                                tb_member.MBR_CITY     = "'.$city.'",
                                                tb_member.MBR_ZIP      = "'.$zip.'",
                                                tb_member.MBR_DATETIME = "'.date("Y-m-d H:i:s", strtotime("$reg_date")).'"
                                            WHERE MD5(MD5(tb_member.MBR_ID)) = "'.$x.'"
                                        ') or die(mysqli_error($db));
                                        insert_log($RESULT_QUERY["MBR_ID"], "Mengganti Data Registrasi Member");
                                        die("<script>alert('Success mengganti data user');location.href = 'home.php?page=".$login_page."&x=".$x."'</script>");
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if(isset($_POST["submit_update"])){
            if(isset($_POST["new_mail"])){
                $new_mail = form_input($_POST["new_mail"]);
                $SQL_MAILCHECK = mysqli_query($db, 'SELECT 1 FROM tb_member WHERE tb_member.MBR_EMAIL = "'.$new_mail.'"');
                if(mysqli_num_rows($SQL_MAILCHECK) < 1){
                    if(isset($_FILES["file_bktmail"])){
    
                        $s3 = new Aws\S3\S3Client([
                            'region'  => $region,
                            'version' => 'latest',
                            'credentials' => [
                                'key'    => $IAM_KEY,
                                'secret' => $IAM_SECRET,
                            ]
                        ]);	
    
                        $newfilename1 = round(microtime(true));
                        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png");
                        if(isset($_FILES["file_bktmail"]) && $_FILES["file_bktmail"]["error"] == 0){
                        
                            $img_name = $_FILES["file_bktmail"]["name"];
                            $img_type = $_FILES["file_bktmail"]["type"];
                            
                            $img_ext = pathinfo($img_name, PATHINFO_EXTENSION);
                            
                            if(array_key_exists($img_ext, $allowed)){
                                if(in_array($img_type, $allowed)){
                                    $img_new = 'logmail_'.time().'_'.rand(100000000, 999999999).'.'.$img_ext;
                                    if(move_uploaded_file($_FILES["file_bktmail"]["tmp_name"], "upload/" . $img_new)){
                                        
                                        $img_Path = 'upload/'. $img_new;
                                        $img_key = basename($img_Path);
    
                                        try {
                                            $result = $s3->putObject([
                                                'Bucket' => $bucketName,
                                                'Key'    => $folder.'/'.$img_key,
                                                'Body'   => fopen($img_Path, 'r'),
                                                'ACL'    => 'public-read', // make file 'public'
                                            ]);
                                            mysqli_query($db, "
                                                UPDATE tb_member SET
                                                    tb_member.MBR_EMAIL = '".$new_mail."'
                                                WHERE tb_member.ID_MBR = ".$RESULT_QUERY["ID_MBR"]."
                                            ") or die (mysqli_error($db));
                                            unlink($img_Path);
    
                                            mysqli_query($db, "
                                                INSERT INTO tb_chmail_log SET
                                                tb_chmail_log.CHML_ADM       = ".$user1["ADM_ID"].",
                                                tb_chmail_log.CHML_MBR       = ".$RESULT_QUERY["MBR_ID"].",
                                                tb_chmail_log.CHML_PREV_MAIL = '".$RESULT_QUERY["MBR_EMAIL"]."',
                                                tb_chmail_log.CHML_NEXT_MAIL = '".$new_mail."',
                                                tb_chmail_log.CHML_FILE      = '".$img_new."',
                                                tb_chmail_log.CHML_DATETIME  = '".date("Y-m-d H:i:s")."',
                                                tb_chmail_log.CHML_TIMESTAMP = '".date("Y-m-d H:i:s")."'
                                            ") or die (mysqli_error($db));
                                            insert_log($RESULT_QUERY["MBR_ID"], "Mengganti Email Member");
                                            die("<script>alert('Success Change Email');location.href = 'home.php?page=".$login_page."&x=".$x."'</script>");
                                        } catch (Aws\S3\Exception\S3Exception $e) {
                                            die ("<script>alert('There was an error uploading the file.');location.href = 'home.php?page=".$login_page."&x=".$x."'</script>");
                                        }
                                    } else { die ("<script>alert('File is not uploaded.');location.href = 'home.php?page=".$login_page."&x=".$x."'</script>"); }
                                } else { die ("<script>alert('Error: There was a problem uploading your file. Please try again.');location.href = 'home.php?page=".$login_page."&x=".$x."'</script>"); }
                            } else { die ("<script>alert('Only *.jpg, *.jpeg, *.png');location.href = 'home.php?page=".$login_page."&x=".$x."'</script>"); }
                        };
                    }else{ die("<script>alert('Mohon Isi Pada Input File Bukti Surat Perubahan');location.href = 'home.php?page=".$login_page."&x=".$x."'</script>"); }
                }else{ die("<script>alert('Email Yang Anda Masukan Sudah terdaftar');location.href = 'home.php?page=".$login_page."&x=".$x."'</script>"); }
            }else{ die("<script>alert('Mohon Isi Pada Input Nama Email');location.href = 'home.php?page=".$login_page."&x=".$x."'</script>"); }
        }

?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Member</a></li>
        <li class="breadcrumb-item"><a href="#">All</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail</li>
    </ol>
</nav>

<div class="card mt-3">
    <div class="card-header font-weight-bold">Detail Member</div>
    <form method="post">
        <input type="hidden" name="id" class="form-control" value="<?php echo $RESULT_QUERY['MBR_ID'] ?>" readonly required autocomplete="off">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="fullname">Full Name</label>
                                <input id="fullname" type="text" name="fullname" class="form-control" value="<?php echo $RESULT_QUERY['MBR_NAME'] ?>" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input id="phone" type="text" name="phone" class="form-control" value="<?php echo $RESULT_QUERY['MBR_PHONE'] ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input id="username" type="text" class="form-control" pattern="[a-z0-9]+" minlength="4" value="<?php echo str_replace(" ","",strtolower($RESULT_QUERY['MBR_USER'])) ?>"  autocomplete="off" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label for="email">
                                    Email
                                </label>
                            </div>
                            <div class="col-6 text-right">
                                <a id="mail-btn" href="#" data-target="#modal_update" data-toggle="modal">
                                    Change Email
                                </a>
                            </div>
                        </div>
                        <input id="email" type="email" class="form-control" value="<?php echo $RESULT_QUERY['MBR_EMAIL'] ?>" readonly  autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input id="country" type="text" name="country" class="form-control" value="<?php echo $RESULT_QUERY['MBR_COUNTRY'] ?>"  autocomplete="off">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input id="address" type="text" name="address" class="form-control" value="<?php echo $RESULT_QUERY['MBR_ADDRESS'] ?>"  autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input id="city" type="text" name="city" class="form-control" value="<?php echo $RESULT_QUERY['MBR_CITY'] ?>"  autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="zip">ZIP</label>
                        <input id="zip" type="number" name="zip" class="form-control" value="<?php echo $RESULT_QUERY['MBR_ZIP'] ?>"  autocomplete="off">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="reg_date">Tanggal Registrasi</label>
                        <input id="reg_date" type="date" name="reg_date" max="<?php echo date("Y-m-d") ?>" class="form-control text-center" value="<?php echo date("Y-m-d", strtotime("$tgl")) ?>"  autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" name="submit_profile" class="btn btn-primary pd-x-30 mg-r-5 mg-t-5">Submit</button>
        </div>
    </form>
</div>

<div class="card mt-3">
    <div class="card-header font-weight-bold">Log Penggantian Email</div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-bordered table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Date</th>
                        <th style="vertical-align: middle" class="text-center">E-mail Lama</th>
                        <th style="vertical-align: middle" class="text-center">E-mail Baru</th>
                        <th style="vertical-align: middle" class="text-center">File</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $SQL_CHMLOG = mysqli_query($db, '
                            SELECT
                                tb_chmail_log.CHML_DATETIME,
                                tb_chmail_log.CHML_PREV_MAIL,
                                tb_chmail_log.CHML_NEXT_MAIL,
                                tb_chmail_log.CHML_FILE
                            FROM tb_chmail_log
                            WHERE tb_chmail_log.CHML_MBR = '.$RESULT_QUERY['MBR_ID'].'
                        ');
                        if($SQL_CHMLOG && mysqli_num_rows($SQL_CHMLOG) > 0){
                            while($RSLT_CHMLOG = mysqli_fetch_assoc($SQL_CHMLOG)){
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $RSLT_CHMLOG["CHML_DATETIME"] ?></td>
                            <td><?php echo $RSLT_CHMLOG["CHML_PREV_MAIL"] ?></td>
                            <td><?php echo $RSLT_CHMLOG["CHML_NEXT_MAIL"] ?></td>
                            <td class="text-center"><a href="<?php echo $aws_folder.$RSLT_CHMLOG["CHML_FILE"] ?>" target="_blank">open</a></td>
                        </tr>
                    <?php
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_update" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="post" enctype="multipart/form-data">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Update Email</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12">
                                <label>Email Sekarang</label>
                                <input type="text" class="form-control text-center" id="update_code" autocomplete="off" readonly>
                            </div>
                            <div class="col-12">
                                <label>Email Baru</label>
                                <input type="email" class="form-control text-center" name="new_mail" required autocomplete="off">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <style>
                                    .container2 {
                                        background-color: #ffffff;
                                        /* width: 60%; */
                                        min-width: 27.5em;
                                        padding: 3.12em 1.87em;
                                        position: relative;
                                        transform: translate(-50%, -50%);
                                        left: 50%;
                                        top: 50%;
                                        box-shadow: 0 1.25em 3.43em rgba(0, 0, 0, 0.08);
                                        border-radius: 0.5em;
                                        border: dashed;
                                    }
                                    input[type="file"] {
                                        display: none;
                                    }
                                    .container2 label {
                                        display: block;
                                        position: relative;
                                        background-color: #025bee;
                                        color: #ffffff;
                                        font-size: 1.1em;
                                        text-align: center;
                                        width: 16em;
                                        padding: 1em 0;
                                        border-radius: 0.3em;
                                        margin: 0 auto 1em auto;
                                        cursor: pointer;
                                    }
                                    #image-display {
                                        position: relative;
                                        width: 90%;
                                        margin: 0 auto;
                                        display: flex;
                                        justify-content: space-evenly;
                                        gap: 1.25em;
                                        flex-wrap: wrap;
                                    }
                                    #image-display figure {
                                        width: 45%;
                                    }
                                    #image-display img {
                                        width: 100%;
                                    }
                                    #image-display figcaption {
                                        font-size: 0.8em;
                                        text-align: center;
                                        color: #5a5861;
                                    }
                                    .container2 .active {
                                        border: 0.2em dashed #025bee;
                                    }
                                    #error {
                                        text-align: center;
                                        color: #ff3030;
                                    }
                                </style>
                                <label>Bukti Dokument</label>
                                <div class="container2">
                                    <input type="file" name="file_bktmail" required id="upload-button" accept=".png, .jpg, .jpeg">
                                    <label for="upload-button">
                                        <i class="fa fa-upload" aria-hidden="true"></i>&nbsp; Choose Or Drop Photos
                                    </label>
                                    <div id="error"></div>
                                    <div id="image-display"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit_update" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>
    <script>
        let uploadButton = document.getElementById("upload-button");
        let chosenImage = document.getElementById("chosen-image");
        let fileName = document.getElementById("file-name");
        let container = document.querySelector(".container2");
        let error = document.getElementById("error");
        let imageDisplay = document.getElementById("image-display");

        window.onload = () => {
            error.innerText = "";

            const fileHandler = (file, name, type) => {
                if (type.split("/")[0] !== "image") {
                    //File Type Error
                    error.innerText = "Please upload an image file";
                    return false;
                }
                error.innerText = "";
                let reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onloadend = () => {
                    //image and file name
                    let imageContainer = document.createElement("figure");
                    let img = document.createElement("img");
                    img.src = reader.result;
                    imageContainer.appendChild(img);
                    // imageContainer.innerHTML += `<figcaption>${name}</figcaption>`;
                    imageDisplay.appendChild(imageContainer);
                };
            };

            //Upload Button
            uploadButton.addEventListener("change", () => {
                console.log(1);
                imageDisplay.innerHTML = "";
                Array.from(uploadButton.files).forEach((file) => {
                    fileHandler(file, file.name, file.type);
                });
            });

            container.addEventListener(
                "dragenter",
                (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    container.classList.add("active");
                },false
            );

            container.addEventListener(
                "dragleave",
                (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    container.classList.remove("active");
                },false
            );

            container.addEventListener(
                "dragover",
                (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    container.classList.add("active");
                },false
            );

            container.addEventListener(
                "drop",
                (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    container.classList.remove("active");
                    let draggedData = e.dataTransfer;
                    let files = draggedData.files;
                    imageDisplay.innerHTML = "";
                    Array.from(files).forEach((file) => {
                        fileHandler(file, file.name, file.type);
                    });
                    uploadButton.files = e.dataTransfer.files;
                },false
            );
        };

        document.getElementById('mail-btn').addEventListener('click', function(e){
            document.getElementById('update_code').value = document.getElementById('email').value;
        });
    </script>
<?php };?>
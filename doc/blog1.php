<?php
    require '../vendor/autoload.php';

    use Aws\S3\S3Client;

    if(isset($_GET['action'])){
        if(isset($_GET['action'])){
            $action = mysqli_real_escape_string($db, strip_tags(addslashes($_GET["action"])));
            if($action == 'delete'){
                $id = mysqli_real_escape_string($db, strip_tags(addslashes($_GET["id"])));
                mysqli_query($db, "DELETE FROM tb_blog WHERE MD5(MD5(tb_blog.ID_BLOG)) = '".$id."'") or die (mysqli_error($db));
                die ("<script>alert('success ".$action."');location.href = 'home.php?page=" . $login_page . "'</script>");
            };
        };
    };
    if(isset($_POST['submit'])){
        if(isset($_POST['title'])){
            if(isset($_POST['content'])){
                if(isset($_POST['author'])){
                    if(isset($_POST['type_blog'])){
                        $title = mysqli_real_escape_string($db, strip_tags(addslashes($_POST["title"])));
                        $author = mysqli_real_escape_string($db, strip_tags(addslashes($_POST["author"])));
                        $type_blog = mysqli_real_escape_string($db, strip_tags(addslashes($_POST["type_blog"])));
                        $content = $_POST["content"];
                        
                        // AWS Info
                        $region = 'ap-southeast-1';
                        $bucketName = 'allmediaindo-2';
                        $folder = 'ccftrader';
                        $IAM_KEY = 'AKIASPLPQWHJMMXY2KPR';
                        $IAM_SECRET = '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';
                        
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
                        if(isset($_FILES["img"]) && $_FILES["img"]["error"] == 0){
                        
                            $img_name = $_FILES["img"]["name"];
                            $img_type = $_FILES["img"]["type"];
                            
                            $img_ext = pathinfo($img_name, PATHINFO_EXTENSION);
                            
                            if(array_key_exists($img_ext, $allowed)){
                                if(in_array($img_type, $allowed)){
                                    $img_new = 'blog_'.time().'_'.rand(100000000, 999999999).'.'.$img_ext;
                                    if(move_uploaded_file($_FILES["img"]["tmp_name"], "upload/" . $img_new)){
                                        
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
                                                INSERT INTO tb_blog SET
                                                tb_blog.BLOG_TYPE = '.$type_blog.',
                                                tb_blog.BLOG_TITLE = REPLACE("'.$title.'", "", " "),
                                                tb_blog.BLOG_AUTHOR = "'.$author.'",
                                                tb_blog.BLOG_MESSAGE = "'.$content.'",
                                                tb_blog.BLOG_IMG = "'.$img_new.'",
                                                tb_blog.BLOG_DATETIME = "'.date("Y-m-d H:i:s").'"
                                            ') or die (mysqli_error($db));
                                            unlink($img_Path);
                                            die("<script>alert('Success Upload');location.href = 'home.php?page=blog'</script>");
                                        } catch (Aws\S3\Exception\S3Exception $e) {
                                            die ("<script>location.href = 'home.php?page=blog&notif=".base64_encode('There was an error uploading the file.')."'</>");
                                        }
                                    } else { die ("<script>location.href = 'home.php?page=blog&notif=".base64_encode('File is not uploaded.')."'</script>"); }
                                } else { die ("<script>location.href = 'home.php?page=blog&notif=".base64_encode('Error: There was a problem uploading your file. Please try again.')."'</script>"); }
                            } else { die ("<script>location.href = 'home.php?page=blog&notif=".base64_encode('Only *.jpg, *.jpeg, *.png')."'</script>"); }
                        };
                    };
                };
            };
        };
    }
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item active" aria-current="page">Blog</li>
    </ol>
</nav>


<div class="card mt-3">
    <div class="card-header font-weight-bold">Input Blog</div>
    <form method="POST" enctype="multipart/form-data">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Author</label>
                        <input type="text" class="form-control" name="author" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Type</label>
                            <select class="form-control" name="type_blog" required>
                                <option disabled selected value="">Please Select One</option>
                                <!-- <option value="1">Blog</option>
                                <option value="2">Promo</option>
                                <option value="3">Berita</option> -->
                                <option value="4">Fundamental & technical Analys</option>
                                <option value="5">News Corner</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Picture</label>
                                <input type="file" class="form-control" name="img" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div><label>Content</label></div>
                <textarea name="content"></textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" name="submit" class="btn btn-primary">Insert New</button>
        </div>
    </form>
</div>
<script>
        CKEDITOR.replace( 'content' );
</script>
<div class="card mt-3">
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Date Time</th>
                        <th style="vertical-align: middle" class="text-center">Type</th>
                        <th style="vertical-align: middle" class="text-center">Author</th>
                        <th style="vertical-align: middle" class="text-center">Title</th>
                        <th style="vertical-align: middle" class="text-center">Content</th>
                        <th style="vertical-align: middle" class="text-center"></th>
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
            "lengthMenu": [[50, 75, 100, -1], [50, 75, 100, "Semua"]],
            "scrollX": true,
            "order": [[ 0, "desc" ]]
        } );
    } );
</script>
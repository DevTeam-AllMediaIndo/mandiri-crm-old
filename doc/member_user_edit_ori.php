<?php
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
            };
        }
        if(isset($_POST["submit_profile"])){
            if(isset($_POST["fullname"])){
                if(isset($_POST["phone"])){
                    if(isset($_POST["country"])){
                        if(isset($_POST["address"])){
                            if(isset($_POST["city"])){
                                if(isset($_POST["zip"])){
                                    $fullname = form_input($_POST["fullname"]);
                                    $phone = form_input($_POST["phone"]);
                                    $country = form_input($_POST["country"]);
                                    $address = form_input($_POST["address"]);
                                    $city = form_input($_POST["city"]);
                                    $zip = form_input($_POST["zip"]);

                                    mysqli_query($db,'
                                        UPDATE tb_member SET
                                            tb_member.MBR_NAME = "'.$fullname.'",
                                            tb_member.MBR_PHONE = "'.$phone.'",
                                            tb_member.MBR_COUNTRY = "'.$country.'",
                                            tb_member.MBR_ADDRESS = "'.$address.'",
                                            tb_member.MBR_CITY = "'.$city.'",
                                            tb_member.MBR_ZIP = "'.$zip.'"
                                        WHERE MD5(MD5(tb_member.MBR_ID)) = "'.$x.'"
                                    ') or die(mysqli_error($db));
                                    die("<script>alert('Success mengganti data user');location.href = 'home.php?page=".$login_page."&x=".$x."'</script>");
                                }
                            }
                        }
                    }
                }
            }
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
                        <label for="email">Email</label>
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
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" name="submit_profile" class="btn btn-primary pd-x-30 mg-r-5 mg-t-5">Submit</button>
        </div>
    </form>
</div>
<?php };?>
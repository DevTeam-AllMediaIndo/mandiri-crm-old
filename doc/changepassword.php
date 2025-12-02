<?php

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['submit_password'])){
        if(isset($_POST['pass01'])){
            if(isset($_POST['pass02'])){
                if(isset($_POST['pass03'])){

                    $pass01 = mysqli_real_escape_string($db, stripslashes(strip_tags($_POST["pass01"])));
                    $pass02 = mysqli_real_escape_string($db, stripslashes(strip_tags($_POST["pass02"])));
                    $pass03 = mysqli_real_escape_string($db, stripslashes(strip_tags($_POST["pass03"])));

                    if($pass01 == $user1['ADM_PASS']){
                        if($pass01 != $pass02){
                            if($pass02 == $pass03){
                                if(strlen($pass02) >= 6 ){
                                    $SQL_QUERY = '
                                        UPDATE tb_admin SET
                                            ADM_PASS = "'.$pass02.'",
                                            ADM_CHANGEPASS_TIMESTAMP = "'.date("Y-m-d H:i:s").'"
                                        WHERE ADM_ID = '.$user1['ADM_ID'].'
                                        AND ADM_PASS = "'.$pass01.'"
                                    ';
                                    $EXEC_SQL = mysqli_query($db, $SQL_QUERY) or die ("<script>alert('Please try again, or contact support');location.href ='".$login_page."'</script>");
                                    die ("<script>alert('Success change password, please login again');location.href ='./'</script>");
                                } else { die ("<script>alert('Password min 6 character');location.href ='".$login_page."'</script>"); };
                            } else { die ("<script>alert('please check confirm password');location.href ='".$login_page."'</script>"); };
                        } else { die ("<script>alert('new password must be different from old password');location.href ='".$login_page."'</script>"); };
                    } else { die ("<script>alert('Please check current password');location.href ='".$login_page."'</script>"); };

                };
            };
        };
    };
};
?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Password</li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header">Change Password</div>
                        <form method="post">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Old Password</label>
                                    <input type="text" class="form-control" name="pass01" required autocomplete="off" placeholder="Old Passwod">
                                </div>
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="text" class="form-control" name="pass02" required autocomplete="off" placeholder="New Passwod">
                                </div>
                                <div class="form-group">
                                    <label>Confirm New Password</label>
                                    <input type="text" class="form-control" name="pass03" required autocomplete="off" placeholder="Confirm New Passwod">
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <input type="submit" class="btn btn-primary" name="submit_password">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
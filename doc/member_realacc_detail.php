<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item">Member</li>
        <li class="breadcrumb-item">
            <a  href="<?php $sub_page = $_GET['sub_page'];
                    if($sub_page == 'wp_verification1' || 
                        $sub_page == 'temporary_detail' || 
                        $sub_page == 'client_deposit1' ||
                        $sub_page == 'wp_check1' ||
                        $sub_page == 'dealer1' 
                    ){echo 'home.php?page=member_realacc'; }else{ echo 'home.php?page=member_active';};
                ?>">
                Real Account
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Detail</li>
    </ol>
</nav>
<div class="row">
    <!-- <div class="col-md-2">
        <a href="home.php?page=<?php echo $login_page ?>&x=<?php echo $_GET['x'] ?>&sub_page=wp_verification" class="btn btn-primary btn-block">WP Verification</a>     0   1   
        <a href="home.php?page=<?php echo $login_page ?>&x=<?php echo $_GET['x'] ?>&sub_page=client_deposit" class="btn btn-primary btn-block">Client Deposit</a>       2   3
        <a href="home.php?page=<?php echo $login_page ?>&x=<?php echo $_GET['x'] ?>&sub_page=wp_check" class="btn btn-primary btn-block">WP Check</a>                   3   4
        <a href="home.php?page=<?php echo $login_page ?>&x=<?php echo $_GET['x'] ?>&sub_page=accounting" class="btn btn-primary btn-block">Accounting</a>               4   5
        <a href="home.php?page=<?php echo $login_page ?>&x=<?php echo $_GET['x'] ?>&sub_page=dealer" class="btn btn-primary btn-block">Dealer</a>                       5   6
        <a target="_blank" href="<?php echo 'pdf/root/12.account-condition.php?x='.$_GET['x']; ?>" class="btn btn-primary btn-block">Account Condition</a>
    </div> -->
    <div class="col-md-12">
        <?php 
            if(isset($_GET['sub_page'])){
                $sub_page = $_GET['sub_page'];
                include('doc/realacc/'.$sub_page.'.php');
            }
        ?>
    </div>
</div>
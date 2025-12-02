<?php

date_default_timezone_set("Asia/Jakarta");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    require_once('../../vendor/autoload.php');
    use Ozdemir\Datatables\Datatables;
    use Ozdemir\Datatables\DB\MySQL;

    $config = [ 'host'     => '45.76.176.106',
                'port'     => '1224',
                'username' => 'root',
                'password' => 'Masuk@1224',
                'database' => 'db_mndrfx' ];

    $dt = new Datatables( new MySQL($config) );
?>
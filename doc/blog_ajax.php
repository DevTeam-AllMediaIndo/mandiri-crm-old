<?php
    require_once('setting.php');
    $usr = (isset($_GET["usr"])) ? $_GET["usr"] : 0;
    //$dt->query('SELECT ID_TRD, TRD_MBR, TRD_PRICE FROM tb_trade');
    $dt->query('
        SELECT
            tb_blog.BLOG_DATETIME,
            IF(tb_blog.BLOG_TYPE = 1, "Blog",
                IF(tb_blog.BLOG_TYPE = 3, "Tutorial",
                    IF(tb_blog.BLOG_TYPE = 4, "Berita",
                        IF(tb_blog.BLOG_TYPE = 5, "Buletin", "Unknown")
                    )
                )
            ) AS BLOG_TYPE,
            tb_blog.BLOG_AUTHOR,
            tb_blog.BLOG_TITLE,
            tb_blog.BLOG_MESSAGE,
            MD5(MD5(tb_blog.ID_BLOG)) AS ID_BLOG,
            IFNULL(
                (
                    SELECT 
                        tb_admin.ADM_LEVEL 
                    FROM tb_admin 
                    WHERE MD5(MD5(tb_admin.ADM_ID)) = "'.$usr.'" 
                    LIMIT 1
                )
            ,0) AS ADM_LEVEL
        FROM tb_blog
    ');
    $dt->hide('ADM_LEVEL');
    $dt->edit('BLOG_DATETIME', function($data){
        return "<div class='text-center'>".$data['BLOG_DATETIME']."</div>";
    });
    $dt->edit('BLOG_TITLE', function($data){
        return "<div class='text-left'>".substr(addslashes(strip_tags(stripslashes($data['BLOG_TITLE']))), 0, 50)."</div>";
    });
    $dt->edit('BLOG_MESSAGE', function($data){
        return "<div class='text-left'>".substr(addslashes(strip_tags(stripslashes($data['BLOG_MESSAGE']))), 0, 100)."</div>";
    });
    $dt->edit('ID_BLOG', function($data){
        if($data["ADM_LEVEL"] != 0){
            return "
                <div class='text-center'>
                    <a class='btn btn-danger btn-sm' href='home.php?page=blog&action=delete&id=".$data['ID_BLOG']."'>Delete</a> | 
                    <a class='btn btn-info btn-sm' href='home.php?page=blog&action=detail&id=".$data['ID_BLOG']."'>Detail</a>
                </div>
            ";
        }
    });

    echo $dt->generate()->toJson(); // same as 'echo $dt->generate()';
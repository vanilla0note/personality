<?php
    if( !isset($_SESSION) ) session_start();

    //01.資料庫連線
    require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php"); 
    $link = create_connection() ; 


    //02.取得版本資訊
    $sql_ver = "SELECT * FROM `ver_record` ORDER BY `count` DESC";
    $result_ver = mysqli_query($link, $sql_ver) ; 
    while( $row_ver = mysqli_fetch_assoc($result_ver) ) 
        $rows_ver[] = $row_ver; 
    $year = substr($rows_ver[0]["publish_date"],0,4) ;
    $ver = $rows_ver[0]["ver"] ;

?>

<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">
    <title>喜憨兒基金會 人才培訓分析平台</title>

    <?php include('include/toolkit.php'); ?>

    <link rel="stylesheet" href="css/login.css?<?php echo date("is"); ?>" />

</head>

<body>

    <div id="page-main">

        <div id="member_bot">

            <div id="login_title">喜憨兒基金會 人才培訓分析平台<hr/></div>

            <div id="login_content">
                <div class="login_content_avatar"><img src="img/avatar01.png" />&nbsp;</div>
                <div class="login_content_title"><cc>你是面試人員：</cc></div>
                <div class="login_content_btn">
                    <div class='btn' onclick="exam_tested();"><cc>人格特質測驗</cc></div>
                </div>
                               
                <br /><br /><br />

                <div class="login_content_avatar"><img src="img/avatar02.png" />&nbsp;</div>
                <div class="login_content_title"><cc>你是新進員工：</cc></div>
                <div class="login_content_btn">
                    <div class='btn' onclick="score_tested();"><cc>滿意度評量</cc></div>
                </div>

                <br /><br /><br />

                <div class="login_content_avatar"><img src="img/avatar03.png" />&nbsp;</div>
                <div class="login_content_title"><cc>你是一般員工：</cc></div>
                <div class="login_content_btn">
                    <div class='btn_close' onclick=""><cc>線上課程學習</cc></div>
                </div>

            </div>

            <br /><br /><br />

            <div id="backend">
                <div id="backend_img"><img src="img/admin.png" /></div>
                <div id="backend_text" onclick="backend_login()">管理後台</div>
            </div>
            
            
            
            <div id="eip_cc"><?php echo $year ?> © 喜憨兒基金會 數位發展組(<?php echo $ver ?>)</div>

        </div>

    </div>


    <script src="javascript/login.js?<?php echo date("is"); ?>"></script>

</body>
</html>

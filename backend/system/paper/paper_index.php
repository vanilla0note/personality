<?php
    if( !isset($_SESSION) ) session_start();

    ////// test //////
    //session_destroy();  //刪除全部session
    /////////////////


    //01.資料庫連線
    require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php"); 
    $link = create_connection() ;


    //02.登入權限判定(群組)
    include($_SERVER['DOCUMENT_ROOT'].'/module/auth/auth.php');
    $auth = myauth('system-setting');
    if ( $auth != "true"){
        header("Location:/./");
        exit();
    }

?>


<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>喜憨兒基金會 人才登錄培訓分析平台－作答卷題目維護（後台管理）</title>

        <?php include($_SERVER['DOCUMENT_ROOT'].'/include/toolkit.php'); ?>
        <link rel="stylesheet" href="/./css/backend/bread.css?<?php echo date("is"); ?>" />
        <link rel="stylesheet" href="/./css/backend/system/paper/paper_index.css?<?php echo date("is"); ?>" />

    </head>

    <body>

        <div id="caption">
            <div id="caption_header">喜憨兒基金會 人才登錄培訓分析平台－作答卷題目維護</div>
        </div>

        <div id="content">

            <div id="bread">
                <a href="/./backend/backend_panel.php" target="_parent">人才登錄分析平台</a> > 
                作答卷題目維護 
            </div>

            <br/><br/><br/>

            <div id='result'>

                <div class='personality_all_title'>人格特質測驗</div>
                <div class='personality_title'>試題標題</div> 
                <div class='personality_title2'>題目數</div>
                <div class='personality_title2'>建立日期</div>
                <div class='personality_title2'>啟用狀態</div>

                <div class='personality_txt'>預設題目</div>   
                <div class='personality_txt2'>108</div>
                <div class='personality_txt2'>2023-03-01</div>
                <div class='personality_txt2'><div class='btn_acc_enable'>啟用中</div></div>

                <br/><br /><br />

                <div class='personality_all_title'>員工滿意度調查</div>
                <div class='personality_title'>試題標題</div>
                <div class='personality_title2'>題目數</div>
                <div class='personality_title2'>建立日期</div>
                <div class='personality_title2'>啟用狀態</div>

                <div class='personality_txt'>預設題目</div>
                <div class='personality_txt2'>15</div>
                <div class='personality_txt2'>2024-10-01</div>
                <div class='personality_txt2'><div class='btn_acc_enable'>啟用中</div></div>

            </div>
        </div>

        <br/>

        <!--<script src="/./javascript/backend/system/paper/paper_index.js"></script>-->

    </body>


</html>

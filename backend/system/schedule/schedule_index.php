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
        <title>喜憨兒基金會 人才登錄培訓分析平台－排程管理（後台管理）</title>

        <?php include($_SERVER['DOCUMENT_ROOT'].'/include/toolkit.php'); ?>
        <link rel="stylesheet" href="/./css/backend/bread.css?<?php echo date("is"); ?>" />
        <link rel="stylesheet" href="/./css/backend/system/schedule/schedule_index.css?<?php echo date("is"); ?>" />

    </head>

    <body>

        <div id="caption">
            <div id="caption_header">喜憨兒基金會 人才登錄培訓分析平台－排程管理</div>
        </div>

        <div id="content">

            <div id="bread">
                <a href="/./backend/backend_panel.php" target="_parent">人才登錄分析平台</a> > 
                排程管理 
            </div>

            <br/><br/><br/>

            <div id='result'>

                <div class='personality_title'>排程執行</div> 
                <div class='personality_title2'>執行時間</div>
                <div class='personality_title2'>啟用狀態</div>

                <div class='personality_txt'>試題卷分數自動計算</div>   
                <div class='personality_txt2'>每日 04：45</div>
                <div class='personality_txt2'>
                    <?php
                        $sql = "SELECT * FROM `system-schedule` WHERE `no` = '1'" ;
                        $result = mysqli_query($link, $sql) ;

                        while( $row = mysqli_fetch_assoc($result) )
                            $rows[] = $row;
                    
                        if ( $rows[0]["acc"] == "true")
                            echo "<div class='btn_acc_enable' onclick='schedule_acc_edit()'>已啟用</div>";
                        else if ( $rows[0]["acc"] == "false")
                            echo "<div class='btn_acc_disable' onclick='schedule_acc_edit()'>已關閉</div>";


                    ?>

                </div>

            </div>
        </div>

        <br/>

        <script src="/./javascript/backend/system/schedule/schedule_index.js?<?php echo date("is"); ?>"></script>

    </body>


</html>

<?php
    if( !isset($_SESSION) ) session_start();

    ////// test //////
    //session_destroy();  //刪除全部session
    /////////////////

    //01.讀取session，設定目前要作答的試題卷
    if( !isset($_SESSION['careus_personality_mood_loading_page']) ) header("Location:/./");
    else  $page = $_SESSION['careus_personality_mood_loading_page'] ;     //作答進度頁

    if( !isset($_SESSION['careus_personality_mood_id']) )       header("Location:/./");
    else  $mood_id = $_SESSION['careus_personality_mood_id'] ;            //試題卷編號

    if( !isset($_SESSION['careus_personality_mood_name']) ) header("Location:/./");
    else  $staff_name = $_SESSION['careus_personality_mood_name'] ;  //員工姓名

    if( !isset($_SESSION['careus_personality_mood_workid']) ) header("Location:/./");
    else  $staff_id = $_SESSION['careus_personality_mood_workid'] ;  //員工帳號

    if( !isset($_SESSION['careus_personality_mood_area_str']) ) header("Location:/./");
    else  $staff_area_str = $_SESSION['careus_personality_mood_area_str'] ;  //任職區域(中文)

    if( !isset($_SESSION['careus_personality_mood_dep']) ) header("Location:/./");
    else  $staff_dep = $_SESSION['careus_personality_mood_dep'] ;  //任職部門

    if( !isset($_SESSION['careus_personality_mood_dep_str']) ) header("Location:/./");
    else  $staff_dep_str = $_SESSION['careus_personality_mood_dep_str'] ;  //任職部門(中文)

    if( !isset($_SESSION['careus_personality_mood_job']) )  header("Location:/./");
    else  $staff_job  = $_SESSION['careus_personality_mood_job'] ;   //任職職位

    if( !isset($_SESSION['careus_personality_mood_job_str']) )  header("Location:/./");
    else  $staff_job_str  = $_SESSION['careus_personality_mood_job_str'] ;  //任職職位(中文)

    ////////// test ///////////
    //echo "<br/>";
    //echo "page = ".$page."<br/>";
    //echo "mood_id = ".$mood_id."<br/>";
    //////////////////////////


    //02.比對目前作答進度頁，起始頁應為 0
    if( $page != 0 ) header("Location:../topic_page_error.php?msg=902");


?>

<!DOCTYPE html>
<html>
    <head>

        <meta charset="UTF-8">
        <title>喜憨兒基金會 人才登錄分析平台</title>


        <?php include('../../include/toolkit.php'); ?>

        <link rel="stylesheet" href="../../css/html/mood/topic_starting.css?<?php echo date("is"); ?>" />

    </head>

    <body>


        <div id="caption">

            <div id="caption_header">

                喜憨兒基金會 人才登錄分析平台－員工滿意度調查                                
                <br/>
                <tt>
                    員工姓名：<u><?php echo $staff_name ?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    員工帳號：<u><?php echo $staff_id ?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    任職地區：<u><?php echo $staff_area_str ;?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    任職職務：<u><?php echo $staff_job_str ?></u>
                </tt>
                <br/>
                <tt>試題編號：<?php echo $mood_id?></tt>
                <br/>
                <pp>頁數：<?php echo $page ?>/3</pp>

            </div>

        </div>


        <div id="content">

            <div id="content_description">
                <?php echo $staff_name ?>，您好！<br/>
                歡迎加入喜憨兒基金會的大家庭！<br />
                我們努力營造一個員工滿意的工作環境，感謝您提供的意見！<br /><br />
                所有題目都是<b>必選題</b>，請依<b>這段時間</b>工作的感覺來作答<br/>
                <br/>
            </div> 


            <div id="content_button">

                <div class='btn' onclick="starting();"><cc>我知道了！開始吧！</cc></div>

            </div>

        </div>

        <script src="../../javascript/html/mood/topic_start.js?<?php echo date("is"); ?>"></script>


    </body>


</html>
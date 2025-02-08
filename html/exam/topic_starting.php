<?php
    if( !isset($_SESSION) ) session_start(); 

    ////// test //////
    //session_destroy();  //刪除全部session
    /////////////////

    //01.讀取session，設定目前要作答的試題卷
    if( !isset($_SESSION['careus_personality_exam_loading_page']) ) header("Location:/./");
    else  $page = $_SESSION['careus_personality_exam_loading_page'] ;     //作答進度頁

    if( !isset($_SESSION['careus_personality_exam_id']) )       header("Location:/./");
    else  $exam_id = $_SESSION['careus_personality_exam_id'] ;            //試題卷編號
    
    if( !isset($_SESSION['careus_personality_exam_name']) ) header("Location:/./");
    else  $interview_name = $_SESSION['careus_personality_exam_name'] ;  //作答姓名

    if( !isset($_SESSION['careus_personality_exam_area_str']) ) header("Location:/./");
    else  $interview_area_str = $_SESSION['careus_personality_exam_area_str'] ;  //應徵區域(中文)

    if( !isset($_SESSION['careus_personality_exam_dep']) ) header("Location:/./");
    else  $interview_dep = $_SESSION['careus_personality_exam_dep'] ;  //應徵部門

    if( !isset($_SESSION['careus_personality_exam_dep_str']) ) header("Location:/./");
    else  $interview_dep_str = $_SESSION['careus_personality_exam_dep_str'] ;  //應徵部門(中文)

    if( !isset($_SESSION['careus_personality_exam_job']) )  header("Location:/./");
    else  $interview_job  = $_SESSION['careus_personality_exam_job'] ;   //應徵職位

    if( !isset($_SESSION['careus_personality_exam_job_str']) )  header("Location:/./");
    else  $interview_job_str  = $_SESSION['careus_personality_exam_job_str'] ;  //應徵職位(中文)

    ////////// test ///////////
    //echo "<br/>";      
    //echo "page = ".$page."<br/>";
    //echo "exam_id = ".$exam_id."<br/>";
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

        <link rel="stylesheet" href="../../css/html/exam/topic_starting.css?<?php echo date("is"); ?>" />

    </head>

    <body>


        <div id="caption">

            <div id="caption_header">

                喜憨兒基金會 人才登錄分析平台－人格特質測驗                                
                <br/>
                <tt>
                    受測者：<u><?php echo $interview_name ?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    應徵地區：<u><?php echo $interview_area_str ;?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    應徵職務：<u><?php echo $interview_job_str ?></u>
                </tt>
                <br/>
                <tt>試題編號：<?php echo $exam_id?></tt>
                <br/>
                <pp>頁數：<?php echo $page ?>/11</pp>

            </div>

        </div>


        <div id="content">

            <div id="content_description">                                  
                所有題目都是<b>必選題</b><br/>
                很可能兩個選項都在描述你，請以你<b>第一時間</b>的直覺想法為主<br/>
                <br/>
                不要考慮太多外在情境因素，不要在意你的主管、同事、家人、朋友對你的期望<br/>
                選項沒有優劣之分，請選擇真實的自己，而不是期望的自己<br/>
            </div> 


            <div id="content_button">

                <div class='btn' onclick="starting();"><cc>我知道了！開始吧！</cc></div>

            </div>

        </div>

        <script src="../../javascript/html/exam/topic_start.js?<?php echo date("is"); ?>"></script>


    </body>


</html>
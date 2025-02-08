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

    //02.刪除全部session
    //作答已經結束，取出需要的變數顯示後，刪除全部session
    session_destroy(); 

?>

<!DOCTYPE html>
<html>
    <head>

        <meta charset="UTF-8">
        <title>喜憨兒基金會 人才登錄分析平台</title>


        <?php include('../../include/toolkit.php'); ?>

        <link rel="stylesheet" href="../../css/html/exam/topic_page_B.css?<?php echo date("is"); ?>" />

    </head>

    <body>


        <div id="caption">

            <div id="caption_header">

                喜憨兒基金會 人才登錄分析平台－人格特質測驗                                
                <br/>
                <tt>
                    受測者：<u><?php echo $interview_name ?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    應徵地區：<u><?php echo $interview_area_str ;?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    應徵部門：<u><?php echo $interview_dep_str ;?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    應徵職務：<u><?php echo $interview_job_str ?></u>
                </tt>
                <br/>
                <tt>試題編號：<?php echo $exam_id?></tt>
                <br/>
                <div id="progress">
                    <div id="progress_title"><pp>填寫進度：完成 </pp></div>
                    <div id="progress_bar"></div>
                </div>

            </div>

        </div>


        <div id="content">

            <div id="content_description">                                  
                所有的題目都作答完成了！<br/>
                祝福您求職順利～<br/>
            </div> 


            <div id="content_button">

                <div class='btn' onclick="closing();"><cc>作答完成</cc></div>

            </div>

        </div>

        <script src="../../javascript/html/exam/topic_page_B.js?<?php echo date("is"); ?>"></script>


    </body>


</html>
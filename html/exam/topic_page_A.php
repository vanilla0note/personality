<?php
    if( !isset($_SESSION) ) session_start();

    ////// test //////
    //session_destroy();  //刪除全部session
    /////////////////


    //01.讀取session，設定目前要作答的題目數
    if( !isset($_SESSION['careus_personality_exam_loading_page']) ) header("Location:/./");
    else  $page = $_SESSION['careus_personality_exam_loading_page'] ;                    //作答進度頁

    if( !isset($_SESSION['careus_personality_exam_id']) )       header("Location:/./");
    else  $exam_id = $_SESSION['careus_personality_exam_id'] ;                           //試題卷編號

    if( !isset($_SESSION['careus_personality_exam_name']) ) header("Location:/./");
    else  $interview_name = $_SESSION['careus_personality_exam_name'] ;                 //作答姓名

    if( !isset($_SESSION['careus_personality_exam_area_str']) ) header("Location:/./");
    else  $interview_area_str = $_SESSION['careus_personality_exam_area_str'] ;         //應徵區域(中文)

    if( !isset($_SESSION['careus_personality_exam_dep']) ) header("Location:/./");
    else  $interview_dep = $_SESSION['careus_personality_exam_dep'] ;                   //應徵部門

    if( !isset($_SESSION['careus_personality_exam_dep_str']) ) header("Location:/./");
    else  $interview_dep_str = $_SESSION['careus_personality_exam_dep_str'] ;          //應徵部門(中文)

    if( !isset($_SESSION['careus_personality_exam_group']) ) header("Location:/./");
    else  $interview_group = $_SESSION['careus_personality_exam_group'] ;              //應徵組別

    if( !isset($_SESSION['careus_personality_exam_group_str']) ) header("Location:/./");
    else  $interview_group_str = $_SESSION['careus_personality_exam_group_str'] ;      //應徵組別(中文)

    if( !isset($_SESSION['careus_personality_exam_job']) )  header("Location:/./");
    else  $interview_job  = $_SESSION['careus_personality_exam_job'] ;                 //應徵職位

    if( !isset($_SESSION['careus_personality_exam_job_str']) )  header("Location:/./");
    else  $interview_job_str  = $_SESSION['careus_personality_exam_job_str'] ;         //應徵職位(中文)


    //02.資料庫連線
    require_once('../../conn/conn_user_php7.php');
    $link = create_connection() ;


    //03.設定基本變數
    $sql = "SELECT * FROM `exam` WHERE `exam_id` = '{$exam_id}'";
    $result = mysqli_query($link, $sql) ;

    while( $row = mysqli_fetch_assoc($result) )
        $rows[] = $row;
    //echo "exam_01 = ".$rows[0]["exam_01"] ;

?>

<!DOCTYPE html>
<html>
    <head>

        <meta charset="UTF-8">
        <title>喜憨兒基金會 人才登錄分析平台</title>

        <?php include('../../include/toolkit.php'); ?>

        <link rel="stylesheet" href="../../css/html/exam/topic_page_A.css?<?php echo date("is"); ?>" />

    </head>

    <body onload="progress_bar_update(<?php echo $page*10-15 ?>)">

        <div id="caption">

            <div id="caption_header">

                喜憨兒基金會 人才登錄分析平台－人格特質測驗                                
                <br/>
                <tt>
                    受測者：<u><?php echo $interview_name ?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    應徵地區：<u><?php echo $interview_area_str ;?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    應徵部門：<u><?php echo $interview_dep_str ;?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    應徵組別：<u><?php echo $interview_group_str; ?></u>
                </tt>
                <br/>
                <tt>試題編號：<?php echo $exam_id ?></tt>
                <br/>
                <div id="progress">
                    <div id="progress_title"><pp>填寫進度：<?php echo $page ?>/11 </pp></div>
                    <div id="progress_bar"></div>
                </div>
            </div>

        </div>


        <div id="content" style="<?php if($page==11) echo 'height: 965px;' ?> ">

            <form action="" method="post" name="myForm1" id="myForm1">    
                <?php
                $sql = "SELECT * FROM `system-question-exam` ORDER BY `num` ASC LIMIT ".(($page-1)*10).",10";
                $result = mysqli_query($link, $sql) ;

                while( $row = mysqli_fetch_assoc($result) ){


                    echo "<div class='content_answering'>" ;

                        echo "<div class='content_question'>題目 ".$row['num']."：".$row['question']."</div>" ;

                        echo "<div class='content_button'>";
                            echo "<label for='".$row['id']."-Y'>是</label>" ;
                            echo "<input type='radio' name='".$row['id']."' id='".$row['id']."-Y' value='".$row['id']."-Y'>";
            echo "&nbsp;" ;
                            echo "<label for='".$row['id']."-N'>否</label>" ;
                            echo "<input type='radio' name='".$row['id']."' id='".$row['id']."-N' value='".$row['id']."-N'>";
                        echo "</div>" ;

                    echo "</div>" ;
                }

                ?>

                <!--<div class="content_answering"> 
                    <div class="content_question">題目 1：我常空虛寂寞覺得冷</div>           
                    <div class="content_button">
                        <label for="QA001-Y">是</label>
                        <input type="radio" name="QA001" id="QA001-Y" value="QA001-Y">
                        &nbsp;
                        <label for="QA001-N">否</label>
                        <input type="radio" name="QA001" id="QA001-N" value="QA001-N">
                    </div>
                </div>-->
                <input type='hidden' name='id' value='<?php echo $exam_id ?>'>
            </form>

        </div>

        <div id="page_btn">
            <center>
                <div class='btn' onclick="form_run();"><cc>下一頁</cc></div>
            </center>
        </div>


        <script src="../../javascript/html/exam/topic_page_A.js?<?php echo date("is"); ?>"></script>

    </body>


</html>
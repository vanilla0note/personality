<?php
    if( !isset($_SESSION) ) session_start();

    ////// test //////
    //session_destroy();  //刪除全部session
    /////////////////


    //01.讀取session，設定目前要作答的題目數
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


    //02.資料庫連線
    require_once('../../conn/conn_user_php7.php');
    $link = create_connection() ;


    //03.設定基本變數
    $sql = "SELECT * FROM `mood` WHERE `mood_id` = '{$mood_id}'";
    $result = mysqli_query($link, $sql) ;

    while( $row = mysqli_fetch_assoc($result) )
        $rows[] = $row;
    //echo "mood_01 = ".$rows[0]["mood_01"] ;

?>

<!DOCTYPE html>
<html>
    <head>

        <meta charset="UTF-8">
        <title>喜憨兒基金會 人才登錄分析平台</title>

        <?php include('../../include/toolkit.php'); ?>

        <link rel="stylesheet" href="../../css/html/mood/topic_page_A.css?<?php echo date("is"); ?>" />

    </head>

    <body onload="progress_bar_update(<?php echo ($page*33)-33 ; ?>)">

        <div id="caption">

            <div id="caption_header">

                喜憨兒基金會 人才登錄分析平台－員工滿意度調查
                <br/>
                <tt>
                    員工姓名：<u><?php echo $staff_name ?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    員工帳號：<u><?php echo $staff_id ?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    任職地區：<u><?php echo $staff_area_str; ?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    任職職務：<u><?php echo $staff_job_str ?></u>
                </tt>
                <br/>
                <tt>試題編號：<?php echo $mood_id ?></tt>
                <br/>
                <div id="progress">
                    <div id="progress_title"><pp>填寫進度：<?php echo $page ?>/3 </pp></div>
                    <div id="progress_bar"></div>
                </div>
            </div>

        </div>


        <div id="content" style="<?php if($page==3) echo 'height: 190px;' ?> ">

            <form action="" method="post" name="myForm1" id="myForm1">    
                <?php
                $sql = "SELECT * FROM `system-question-mood` ORDER BY `num` ASC LIMIT ".(($page-1)*7).",7";
                $result = mysqli_query($link, $sql) ;

                while( $row = mysqli_fetch_assoc($result) ){


                    echo "<div class='content_answering'>" ;

                        echo "<div class='content_question'>題目 ".$row['num']."：".$row['question']."</div>" ;

                        echo "<div class='content_button'>";

                            switch ($page){

                                case 1:
                                case 2:
                                default:
                                    echo "<label for='".$row['id']."-1'>非常不同意</label>" ;
                                    echo "<input type='radio' name='".$row['id']."' id='".$row['id']."-1' value='".$row['id']."-1'>";
                                    echo "<label for='".$row['id']."-2'>不同意</label>" ;
                                    echo "<input type='radio' name='".$row['id']."' id='".$row['id']."-2' value='".$row['id']."-2'>";
                                    echo "<label for='".$row['id']."-3'>沒意見</label>" ;
                                    echo "<input type='radio' name='".$row['id']."' id='".$row['id']."-3' value='".$row['id']."-3'>";
                                    echo "<label for='".$row['id']."-4'>同意</label>" ;
                                    echo "<input type='radio' name='".$row['id']."' id='".$row['id']."-4' value='".$row['id']."-4'>";
                                    echo "<label for='".$row['id']."-5'>非常同意</label>" ;
                                    echo "<input type='radio' name='".$row['id']."' id='".$row['id']."-5' value='".$row['id']."-5'>";
                                    break;
                                case 3:
                                    echo "<textarea id='".$row['id']."' name='".$row['id']."' rows='4' cols='50'></textarea>" ;
                                    break;

                            }



                        echo "</div>" ;

                    echo "</div>" ;
                }

                ?>

                <!--<div class="content_answering"> 
                    <div class="content_question">題目 1：我常空虛寂寞覺得冷</div>           
                    <div class="content_button">
                        <fieldset>
                            <label for="CQA001-1">非常不同意</label>
                            <input type="radio" name="CQA001" id="CQA001-1" value="CQA001-1" />
                            <label for="CQA001-2">不同意</label>
                            <input type="radio" name="CQA001" id="CQA001-2" value="CQA001-2" />
                            <label for="CQA001-3">沒意見</label>
                            <input type="radio" name="CQA001" id="CQA001-3" value="CQA001-3" />
                            <label for="CQA001-4">同意</label>
                            <input type="radio" name="CQA001" id="CQA001-4" value="CQA001-4" />
                            <label for="CQA001-5">非常同意</label>
                            <input type="radio" name="CQA001" id="CQA001-5" value="CQA001-5" />
                        </fieldset>
                    </div>
                </div>-->

                <input type='hidden' name='id' value='<?php echo $mood_id ?>'>
            </form>

        </div>

        <div id="page_btn">
            <center>
                <div class='btn' onclick="form_run();"><cc>下一頁</cc></div>
            </center>
        </div>


        <script src="../../javascript/html/mood/topic_page_A.js?<?php echo date("is"); ?>"></script>

    </body>


</html>
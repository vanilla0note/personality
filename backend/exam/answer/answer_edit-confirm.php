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
    $auth = myauth('exam-answer');
    if ( $auth != "true"){
        header("Location:/./");
        exit();
    }


    //03.取得要編輯的試題卷id及所在頁面
    $exam_id  = $_GET["x"];
    $get_page  = $_SESSION['personality_exam_answer_get_page'];

    //////// test /////////
    //echo "<br/><br/><br/><br/>" ;
    //echo "exam_id=".$order_id ;
    ///////////////////////


    //04.登入權限判定(帳號)
    include_once('../answer_edit_view_colony_function.php');
    $colony = answer_edit_view_colony($exam_id);
    if ( $colony != "true"){
        header("Location:/./");
        exit();
    }

    //////// test /////////
    //echo "<br/><br/><br/><br/>" ;
    //echo "colony=".$colony ;
    ///////////////////////


    //05.從資料庫取得要訂單的資料
    $sql  = "SELECT * FROM `exam` WHERE `exam_id` = '{$exam_id}'";           
    $result = mysqli_query($link, $sql) ;
    while( $row = mysqli_fetch_assoc($result) )
        $rows[] = $row; 
 
?>

<!DOCTYPE HTML>
<html>
    <head>

        <meta charset="UTF-8">
        <title>喜憨兒基金會 人才登錄分析平台－人格特質 查詢作答卷（後台管理）</title>

        <?php include($_SERVER['DOCUMENT_ROOT'].'/include/toolkit.php'); ?>
        <link rel="stylesheet" href="/./css/backend/bread.css">
        <link rel="stylesheet" href="/./css/backend/exam/answer/answer_edit-confirm.css?<?php echo date("is"); ?>" />


        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <!-- optional -->
        <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>


    </head>

    <body>
        <div id="caption">
            <div id="caption_header">
                喜憨兒基金會 人才登錄分析平台－人格特質 查詢作答卷（管理後台）
            </div>
        </div>
    

        <div id="content">

            <div id="bread">
                <a href="/./backend/backend_panel.php" target="_parent">人才登錄分析平台</a> > 
                <a href="/./backend/exam/answer/answer_index.php?page=1" target="_parent">查詢作答卷</a> > 
                <?php echo $rows[0]["exam_id"] ?> 
            </div>
           
            <div id="exam">

                <form method="post" action="" name="myForm1">

                    <div id="exam_title_all">作答人基本資料</div>
                    <div class="interview_title3">作答日期</div> 
                    <div class="interview_title2">作答人姓名</div>     
                    <div class="interview_title3">性別</div>                 
                    <div class="interview_title">應徵部門</div> 
                    <div class="interview_title">應徵組別</div>
                    <div class="interview_title">應徵職務</div>

                
                    <div class="interview_txt3"><?php echo substr($rows[0]["start_date"],0,10) ?></div>
                    <div class="interview_txt2">
                        <input id="interview_name" name="interview_name" type="text" value="<?php echo $rows[0]["interview_name"] ?>" style="width:88px;text-align:center;">
                    </div>
                    <div class="interview_txt3">                  
                        <?php                                       
                            switch( $rows[0]["interview_sex"] ){                    
                                case "sex_f": echo "女"; break;
                                case "sex_m": echo "男"; break;
                                case "sex_o": echo "其他";     break;
                                default:      echo "無法辨識"; break;
                            }                                                         
                        ?>                
                    </div>
                    <div class="interview_txt">
                        <?php
                            //應徵部門
                            $sql_temp = "SELECT * FROM `system-department` WHERE `dep_id` = '{$rows[0]["interview_dep"]}'";
                            $result_temp = mysqli_query($link, $sql_temp) ;
                            $num = mysqli_num_rows($result_temp);
                            if ($num > 0 ){
                                while( $row_temp = mysqli_fetch_assoc($result_temp) ) 
                                    $rows_temp[] = $row_temp;       
                                echo $rows_temp[0]["dep_name"] ;
                            }
                            else echo "無法辨識部門" ;                    
                            unset($rows_temp) ;// 清空暫存陣列                                                                         
                        ?>                                                         
                    </div>

                    <div class="interview_txt">
                        <?php
                            //應徵組別
                            $sql_temp = "SELECT * FROM `system-department-group` WHERE `group_id` = '{$rows[0]['interview_group']}'";
                            $result_temp = mysqli_query($link, $sql_temp);
                            $num = mysqli_num_rows($result_temp);
                            if ($num > 0) {
                                while ($row_temp = mysqli_fetch_assoc($result_temp))
                                    $rows_temp[] = $row_temp;
                                echo $rows_temp[0]["group_name"];
                            } else
                                echo "無法辨識組別";
                            unset($rows_temp); // 清空暫存陣列
                        ?>
                    </div>

                    <div class="interview_txt">
                        <?php
                            //應徵職務
                            $sql_temp = "SELECT * FROM `system-department-job` WHERE `job_id` = '{$rows[0]['interview_job']}'";
                            $result_temp = mysqli_query($link, $sql_temp) ;
                            $num = mysqli_num_rows($result_temp);
                            if ($num > 0 ){
                                while( $row_temp = mysqli_fetch_assoc($result_temp) )
                                    $rows_temp[] = $row_temp;
                                echo $rows_temp[0]["job_name"] ;
                            }
                            else echo "無法辨識職位" ;
                            unset($rows_temp) ;// 清空暫存陣列
                        ?>
                    </div>


                    <div class="space">&nbsp;</div>

                    <div id="exam_title_all">答題過程與結果</div>
                    <div class="interview_title2">作答時間</div> 
                    <div class="interview_title">作答時間排名(全部)</div>
                    <div class="interview_title">作答時間排名(部門)</div>
                    <div class="interview_title2">作答總分&nbsp;<img src="/./img/backend/help_icon.png" title="分數越高，個性較為鮮明" /></div>
                    <div class="interview_title2">人格分析類型</div>
                    <div class="interview_title2">人格分析雷達圖</div>

                    <?php 
                        //作答時間
                        if ( $rows[0]['Times_Score'] !== "") 
                            echo "<div class='interview_txt2' onclick=Times_Score('".$rows[0]['exam_id']."')><tt1><u> ".floor( ($rows[0]['Times_Score']/60) )." 分 ".($rows[0]['Times_Score']%60)." 秒</u></tt1></div>" ;                    
                        else 
                            echo "<div class='interview_txt2'>&nbsp;</div>";                                                                                                                   
                    ?>

                    <div class='interview_txt'>
                    <?php
                        //作答時間排名(全部)
                        switch( $rows[0]['state'] ){
                            //試題狀態 分數計算完成才需要排名
                            case "B":                                   
                                $sql_temp = "SELECT * FROM `exam` 
                                             WHERE `state` = 'B' AND `Times_Score` <> '0' 
                                                                 AND `same`        <> 'true' 
                                             ORDER BY `Times_Score` ASC";
                                $result_temp = mysqli_query($link, $sql_temp) ;
                                $num = mysqli_num_rows($result_temp);


                                $ranking = "" ; //排名
                                $i = 1 ;        //比對順序
                                while( $row_temp = mysqli_fetch_assoc($result_temp) ){                        
                                    if ( $rows[0]['exam_id'] == $row_temp['exam_id'])
                                        $ranking = $i ; //作答花費時間 遞增排名
                                    else 
                                        $i++ ;
                                }
                                if ( $ranking != "" ) echo $ranking." / ".$num ;
                                else                  echo "<tt3>不列入排名</tt3>" ;

                                break;

                            case "A":
                            case "Z":
                            default: echo "<tt3>無法排名</tt3>" ;  break;
                        }
                    ?>
                    </div>

                    <div class='interview_txt'>
                    <?php
                        //作答時間排名(部門)
                        switch( $rows[0]['state'] ){                                        
                            //試題狀態 分數計算完成才需要排名
                            case "B": 
                                $sql_temp = "SELECT * FROM `exam` 
                                             WHERE `state` = 'B' AND `Times_Score` <> '0' 
                                                                 AND `same`        <> 'true' 
                                                                 AND `interview_dep` = '{$rows[0]["interview_dep"]}' 
                                             ORDER BY `Times_Score` ASC";
                                $result_temp = mysqli_query($link, $sql_temp) ;
                                $num = mysqli_num_rows($result_temp);
                    
                    
                                $ranking = "" ; //排名
                                $i = 1 ;        //比對順序
                                while( $row_temp = mysqli_fetch_assoc($result_temp) ){                        
                                    if ( $rows[0]['exam_id'] == $row_temp['exam_id'])
                                        $ranking = $i ; //作答花費時間 遞增排名
                                    else 
                                        $i++ ;
                                }
                                if ( $ranking != "" ) echo $ranking." / ".$num ;
                                else                  echo "<tt3>不列入排名</tt3>" ;

                                break; 

                            case "A":
                            case "Z":
                            default:  echo "<tt3>無法排名</tt3>" ;  break;                   
                        }
                    ?>
                    </div>

                    <div class='interview_txt2'>
                        <?php
                        //作答總分
                        $temp_Array = explode(",",$rows[0]["Type_Score"]) ;
                        $temp_total = 0 ;
                        for ( $i=0 ; $i<count($temp_Array) ; $i++ )                    
                            $temp_total = $temp_total + intval($temp_Array[$i]) ;

                        //$temp_percentage = round($temp_total/108*100,3); //計算百分比
                        echo "<i style='font-weight:bold;'>".$temp_total ." / 108</i>" ;
                        ?>
                    </div>

                    <div class="interview_txt2">
                        <?php
                        //人格分析類型
                        switch( $rows[0]['state'] ){
                            case "A":
                            case "Z":
                            default :
                                echo "<div class='btn_close' style='width:90px;'>不能查看</div>";
                                break;
                            case "B":
                                echo "<div style='float:right;padding:0 15px 0 0'><div class='btn' style='width:90px;' onclick=view_personality_type('".$rows[0]['interview_name']."','".$rows[0]['Type_Score']."');>查看</div></div>";
                                break;
                        }
                        ?>                        
                    </div>
                    <div class="interview_txt2">
                        <?php
                        //人格分析雷達圖
                        switch( $rows[0]['state'] ){
                            case "A":
                            case "Z":
                            default :
                                echo "<div class='btn_close' style='width:90px;'>不能查看</div>";
                                break;
                            case "B":
                                echo "<div style='float:right;padding:0 15px 0 0'><div class='btn' style='width:90px;' onclick=view_personality_radar('".$rows[0]['state']."','".$rows[0]['interview_name']."','".$rows[0]['Type_Score']."');>查看</div></div>";
                                break;
                        }
                        ?>
                    </div>

                    <div id="space">&nbsp;</div>

                    <div id="exam_title_all">作答備註及錄取任職資料</div>
                    <div class="interview_title4">重覆答題</div>
                    <div class="interview_title2">作答卷備註</div>                  
                    <div class="interview_title2">員工帳號</div>
                    <div class="interview_title2">任職部門</div>
                    <div class="interview_title2">到職日期</div>
                    <div class="interview_title2">離職日期</div>
                    <div class="interview_title2" style="width:150px">人資備註</div>


                    <div class="interview_txt4">
                        <?php
                        if ($rows[0]['same'] == "true")
                            echo "<input type='checkbox' id='same' name='same' value='true' checked onchange=same_readme(this);>";
                        else if ($rows[0]['same'] == "false")
                            echo "<input type='checkbox' id='same' name='same' value='true'         onchange=same_readme(this);>";
                        ?>
                    </div>
                    <div class="interview_txt2">
                        <?php
                        $str = $rows[0]['remark'];
                        echo "<div class='btn' style='width:70px;margin:0 25px;' onclick=remark_click_show('" . $str . "');>查看</div>";
                        ?>
                    </div>
                    <div class="interview_txt2">
                        <input id="employee_id" name="employee_id" type="text" value="<?php echo $rows[0]["Employee_id"] ?>">
                    </div> 
                    <div class="interview_txt2">
                        <select name='dep'>
                        <?php
                            echo $rows[0]["Dep"];

                            $sql_temp = "SELECT * FROM `system-department` WHERE `acc` = 'true'";
                            $result_temp = mysqli_query($link, $sql_temp) ;

                            while( $row_temp = mysqli_fetch_assoc($result_temp) ){                        

                                //echo $row_temp["dep_id"];

                                if ( $rows[0]["interview_dep"] == $row_temp["dep_id"] )
                                    echo "<option value='".$row_temp["dep_id"]."' selected>".$row_temp["dep_name"]."</option>";                                                                         
                                else
                                    echo "<option value='".$row_temp["dep_id"]."'         >".$row_temp["dep_name"]."</option>";
                            }
                            unset($rows_temp) ;// 清空暫存陣列
                        ?>
                        </select>
                    </div>
                    <div class="interview_txt2">
                        <input id="appointment_date" name="appointment_date" type="text" placeholder=' 請選擇日期' readonly='readonly' >
                    </div>
                    <div class="interview_txt2">
                        <input id="resignation_date" name="resignation_date" type="text" placeholder=' 請選擇日期' readonly='readonly'>
                    </div>
                    <div class="interview_txt2">
                        <textarea id="backend_remark" name="backend_remark"><?php echo $rows[0]["Backend_remark"] ?></textarea>
                    </div>

                    <div class="space">&nbsp;</div>

                    <div style="width:415px;height:40px;float:left;background-color:#FFF;">&nbsp;</div>
                    <div id="exam_title_run" style="background-color:#a5d5f3;">執行指令操作</div>
                    <div style="width:415px;height:200px;float:left;background-color:#FFF;"></div>
                    <div class="interview_run">   
                        <?php
                        switch( $rows[0]['state'] ){
                            case "B":
                                echo "<div style='float:left;padding:15px 0 0 15px'><div class='btn' onclick=output_report('" . $rows[0]['exam_id'] . "');>匯出檔案</div></div>
                                      <div style='float:left;padding:15px 20px 0 0'><div class='btn_close' onclick='';>寄送郵件通知</div></div>
                                      <div style='float:left;padding:15px 0 0 15px'><div class='btn' onclick='update_exam();'>儲存更新資料</div></div>";
                                break;
                            case "A":
                            case "Z":
                            default :
                                echo "<div style='width:200px;float:left;padding:15px 0 0 15px'>&nbsp;</div>";
                                break;
                        }
                        ?>
                        <div style="float:left;padding:15px 20px 0 0"><div class='btn' onclick='self.location=document.referrer;'>不儲存離開</div></div>

                    </div>

                    <input type='hidden' name='exam_id' value='<?php echo $rows[0]["exam_id"] ?>' />
                    <input type='hidden' id='hidden_Appointment_date' value='<?php echo $rows[0]["Appointment_date"] ?>' />
                    <input type='hidden' id='hidden_Resignation_date' value='<?php echo $rows[0]["Resignation_date"] ?>' />

                </form>

            </div>
           
        </div>


        <script src="/./javascript/backend/exam/answer/answer_edit-confirm.js?<?php echo date("is"); ?>"></script>
        <script src="/./javascript/backend/exam/answer/answer_edit-chart.js?<?php echo date("is"); ?>"></script>

    </body>
</html>
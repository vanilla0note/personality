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


    //03.接收看看有沒有分頁數值和查詢語法，來決定輸出
    /* 第一次進來本頁$_SESSION['personality_exam_answer_sql_query']一定沒有；
       重新進入本頁，$_SESSION['personality_exam_answer_sql_query']可能有sql，但$_GET["page"]也不會有值，所以可以確定是重新進入，就不能再帶前次搜尋結果 */

    if ( isset($_GET["page"]) && $_SESSION['personality_exam_answer_sql_query'] != "" ){
        echo("<script>console.log('SESSION有sql值，重覆進入本頁');</script>");
        $personality_exam_answer_sql_flag = true ;


        ////03-01.以下將session值取出，用於重複進入本頁的輸出
        $sql       = $_SESSION['personality_exam_answer_sql_query'] ; //本次查詢的語法（不帶頁數）
        $data_nums = $_SESSION['personality_exam_answer_data_nums'] ; //符合本次查詢資料的總筆數
        $all_pages = $_SESSION['personality_exam_answer_all_pages'] ; //符合本次查詢資料的總頁數
        $filter_content = explode( "@" , $_SESSION['personality_exam_answer_filter_content'] ) ; //本次查詢的篩選器條件
        $page = $_GET["page"] ; //目前所在的頁數
        $_SESSION['personality_exam_answer_get_page'] = $page ;//目前所在的頁數

        //03-02.分頁頁數組合
        //計算前後要顯示的頁數
        $pages_box = 0 ;
        for ( $i = 1; $i <= $all_pages ; $i++ ) 
            if ( $page - 4 < $i && $i < $page + 4 ) $pages_box++  ;
                
        //組合語法 + 頁數
        $sql = $sql." LIMIT ".($page*10-10).",10";

        $result = mysqli_query($link, $sql) ;
        $num = mysqli_num_rows($result) ; //本頁面要顯示的筆數


        //03-03.搜尋器帶入前一次搜尋值
        //前面session已經執行

        //////////// test////////
        //echo "<br/><br/><br/><br/><br/><br/>";
        //echo "sql=".json_encode($sql)."<br/>" ;
        //echo "all_pages=".$all_pages."<br/>" ;
        //echo "page=".$page."<br/>" ;
        //echo "filter_content[0]=".$filter_content[0]."<br/>" ;
        //echo "filter_content[1]=".$filter_content[1]."<br/>" ;
        //echo "filter_content[2]=".$filter_content[2]."<br/>" ;
        //echo "filter_content[3]=".$filter_content[3]."<br/>" ;
        //echo "filter_content[4]=".$filter_content[4]."<br/>" ;
        //echo "filter_content[5]=".$filter_content[5]."<br/>" ;
        /////////////////////////
        
    }
    else{
        echo("<script>console.log('page沒有值及SESSION沒有sql值，第一次或重新進入本頁');</script>");
        $personality_exam_answer_sql_flag = false ;

        $_SESSION['personality_exam_answer_sql_query'] = "" ;  //本次查詢的語法（不帶頁數）
        $_SESSION['personality_exam_answer_data_nums'] = "" ;  //符合本次查詢資料的總筆數
        $_SESSION['personality_exam_answer_all_pages'] = "" ;  //符合本次查詢資料的總頁數
        $_SESSION['personality_exam_answer_filter_content'] = "" ;  //本次查詢的篩選器條件

        //第一次進入本頁，搜尋器給予預設值
        $filter_content[0] = "" ;
        $filter_content[1] = "" ;
        $filter_content[2] = "" ;
        $filter_content[3] = "" ;
        $filter_content[4] = "ALL" ;
        $filter_content[5] = "ALL" ;
    }


    //04.基本設定
    date_default_timezone_set('Asia/Taipei'); 

?>


<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>喜憨兒基金會 人才登錄分析平台－人格特質 查詢作答卷（後台管理）</title>

        <?php include($_SERVER['DOCUMENT_ROOT'].'/include/toolkit.php'); ?>

        <link rel="stylesheet" href="/./css/backend/exam/answer/answer_index.css?<?php echo date("is"); ?>" />
    </head>

    <body>

        <div id="caption">

            <div id="caption_header">

                喜憨兒基金會 人才登錄分析平台－人格特質 查詢作答卷

            </div>

        </div>

        <div id="content">

            <div id="search">

                <b>作答日期：</b>
                &nbsp;<input type='text' id='filter-exam-date01' name='filter-exam-date01' style='width:120px;font-size:16px;' value="<?php echo $filter_content[0] ?>" readonly> ～ 
                &nbsp;<input type='text' id='filter-exam-date02' name='filter-exam-date02' style='width:120px;font-size:16px;' value="<?php echo $filter_content[1] ?>" readonly>
                &nbsp;&nbsp; 

                <b>試題卷編號：</b>
                <input id="filter-exam-id" type="text" value="<?php echo $filter_content[2] ?>" onblur="this.value=this.value.toUpperCase()" placeholder=' 請輸入試題卷編號'>
                &nbsp;&nbsp;<br/>

                <b>作答人姓名：</b>
                <input id="filter-exam-name" type="text" value="<?php echo $filter_content[3] ?>" onblur="this.value=this.value.toUpperCase()" placeholder=' 請輸入查詢姓名'>
                &nbsp;&nbsp;

                <b>應徵區域：</b>
                <select id='filter-exam-area'>                   
                    <?php                       
                    /****************************
                      試題卷 應徵區域代號(第一碼)： 
                      A(台北地區) 
                      H(桃園地區) 
                      J(新竹地區) 
                      D(台南地區) 
                      E(高雄地區
                    *****************************/
                    $arr =array("ALL","全部地區",
                              "A","台北地區",
                              "H","桃園地區",
                              "J","新竹地區",
                              "D","台南地區",
                              "E","高雄地區") ;
                    for ( $i=0 ; $i<=11 ; $i=$i+2){
                        if ( $arr[$i] == $filter_content[4] )
                            echo "<option value='".$arr[$i]."' selected>".$arr[$i+1]."</option>";
                        else
                            echo "<option value='".$arr[$i]."'         >".$arr[$i+1]."</option>";
                    }
                    ?>
                </select>
                &nbsp;&nbsp;<br/>

                <b>應徵部門：</b>
                <select id='filter-exam-dep'>
                    <?php
                        $sql_temp = "SELECT * FROM `system-department` WHERE `acc` = 'true'";
                        $result_temp = mysqli_query($link, $sql_temp) ;

                        $arr =array("ALL","全部部門") ;
                        while( $row_temp = mysqli_fetch_array($result_temp,MYSQL_ASSOC) )
                            array_push($arr,$row_temp['dep_id'],$row_temp['dep_name']);
                                                
                        for ( $i=0 ; $i<=count($arr)-1 ; $i=$i+2){
                            if ( $arr[$i] == $filter_content[5] )
                                echo "<option value='".$arr[$i]."' selected>".$arr[$i+1]."</option>";
                            else
                                echo "<option value='".$arr[$i]."'         >".$arr[$i+1]."</option>";
                        } 
                    ?>
                </select>
                &nbsp;&nbsp;

                <b>試題卷狀態：</b>
                <select id='filter-exam-state'>
                    <?php
                        $arr =array("ALL","全部狀態",
                                      "A","分數未計算",
                                      "B","分數計算完成",
                                      "Z","無效試卷") ;
                        for ( $i=0 ; $i<=6 ; $i=$i+2){
                            if ( $arr[$i] == $filter_content[6] )
                                echo "<option value='".$arr[$i]."' selected>".$arr[$i+1]."</option>";
                            else
                                echo "<option value='".$arr[$i]."'         >".$arr[$i+1]."</option>";
                        }             
                    ?>
                </select>
                &nbsp;&nbsp;<br/>
                <div style="float:right;padding:15px 10px 0 0"><div class='btn' onclick='filter_ready()'>查詢</div></div>
                <div style="float:right;padding:15px 10px 0 0"><div class='btn' onclick='answer_return()'>返回</div></div>
                <div style="float:left;padding:15px 10px 0 0;font-size:17px;color:#808080;">+進階查詢</div>

            </div>

            <br/>

            <!-- 開始顯示搜尋結果：作答卷內容 -->
            <?php
            if ( $personality_exam_answer_sql_flag == true ){
            
                echo "<div id='nums'>符合條件筆數：".$data_nums."</div><br/><br/>";
                echo "<div id='result' style='height:".( 120 + 40 * $num )."px'>" ;


                //試題卷標題                  
                echo "<div class='personality_exam_title'>作答卷編號</div>" ;
                echo "<div class='personality_exam_title2'>作答日期</div>" ;
                echo "<div class='personality_exam_title2'>作答人姓名</div>" ;
                echo "<div class='personality_exam_title2'>應徵區域</div>" ;
                echo "<div class='personality_exam_title2'>應徵部門</div>" ;
                echo "<div class='personality_exam_title2'><tt1 onclick='state_explain()'>狀態</tt1></div>" ;
                echo "<div class='personality_exam_title2'>詳細資料</div>" ;

                //試題卷內容
                while( $row = mysqli_fetch_assoc($result) ){

                    echo "<div class='personality_exam_txt'>".$row['exam_id']."</div>" ; 
                    echo "<div class='personality_exam_txt2'>".substr($row['start_date'],0,10)."</div>" ;
                    echo "<div class='personality_exam_txt2'>".$row['interview_name']."</div>" ;

                    //應徵區域
                    switch(substr($row['exam_id'],0,1)){
                        /****************************
                        試題卷 代號(第一碼)為應徵區域
                        A(台北地區) H(桃園地區) J(新竹地區) D(台南地區) E(高雄地區) 
                        ****************************/
                        case 'A':
                            echo "<div class='personality_exam_txt2'>台北</div>" ; break;
                        case 'H':
                            echo "<div class='personality_exam_txt2'>桃園</div>" ; break;
                        case 'J':
                            echo "<div class='personality_exam_txt2'>新竹</div>" ; break;
                        case 'D':
                            echo "<div class='personality_exam_txt2'>台南</div>" ; break;
                        case 'E':
                            echo "<div class='personality_exam_txt2'>高雄</div>" ; break;
                        default:
                            echo "<div class='personality_exam_txt2'>無法辨識</div>" ; break;            
                    }

                    
                    //應徵部門
                    $sql_temp = "SELECT * FROM `system-department` WHERE `dep_id` = '{$row['interview_dep']}'";
                    $result_temp = mysqli_query($link, $sql_temp) ;
                    $num = mysqli_num_rows($result_temp);
                    if ($num > 0 ){
                        while( $row_temp = mysqli_fetch_assoc($result_temp) ) 
                            $rows_temp[] = $row_temp;       
                        echo "<div class='personality_exam_txt2' style='font-size: 14px;'>".$rows_temp[0]["dep_name"]."</div>" ;
                    }
                    else echo "<div class='personality_exam_txt2' style='color:#F00;'>無法辨識</div>" ;                    
                    unset($rows_temp) ;// 清空暫存陣列

                     
                    //試題卷狀態
                    switch($row['state']){
                        case "A":
                            //目前時間
                            $Now_Times  = date("Y-m-d H:i:s"); 
                            //最後作答時間
                            if      ( $row["now_date"] == "" ) $Last_Times = $Now_Times ; //未有「最後作答時間」是還沒送出第一頁，所以直接等於作答時間
                            else if ( $row["now_date"] != "" ) $Last_Times = $row["now_date"] ;


                            $Usage_Times = strtotime($Now_Times) - strtotime($Last_Times) ; //目前作答已花費的時間

                            ///////test///////
                            //echo $Usage_Times ;
                            /////////////////

                            if ( $row["loading_page"] == 11 )
                                echo "<div class='personality_exam_txt2' onclick=score_calculate('".$row['exam_id']."','T')><tt1>分數未計算</tt1></div>" ; 
                            else if  ( $row["loading_page"] != 11 && $Usage_Times <= 120 )                                
                                echo "<div class='personality_exam_txt2'><tt2>作答中</tt2></div>" ;                             
                            else if  ( $row["loading_page"] != 11 && $Usage_Times > 120 )
                                echo "<div class='personality_exam_txt2' onclick=score_calculate('".$row['exam_id']."','F')><tt1>分數未計算</tt1></div>" ;
                                     
                            break;

                        case "B": echo "<div class='personality_exam_txt2' style='line-height:55px;'>&nbsp;<img src='/./img/backend/accept_icon.png'/></div>" ; break;
                        case "Z": echo "<div class='personality_exam_txt2' style='line-height:55px;'>&nbsp;<img src='/./img/backend/oxygen_icon.png' title='無效試卷'/></div>" ; break;
                        default:  echo "<div class='personality_exam_txt2' style='color:#F00;'>無法辨識</div>" ; break;
                    }
                    
                    //人格類型
                    switch($row['state']){
                        default:
                        case "A":
                            echo "<div class='personality_exam_txt2'>&nbsp;</div>" ;
                            break;

                        case "B": 
                        case "Z": 
                            echo "<div class='personality_exam_txt2'>
                                    <div style='float:right;padding:4px 15px'>
                                        <div class='btn' style='width:70px;height:30px;line-height:30px;font-size:17px;' onclick=answer_paper_edit('".$row['exam_id']."')>檢視</div>
                                    </div>
                                  </div>" ;                       
                        break;
                    }
                }
                echo "</div>" ;

                //分頁內容
                echo "<div id='pages' style='width:".( 140 + 60 * $pages_box )."px;'>" ;

                    echo "<a href=?page=1><div class='pages_box' style='color:#00F'> << </div></a>"; //回到第一頁

                        for ( $i = 1; $i <= $all_pages ; $i++ ) {
                            if ( $page - 4 < $i && $i < $page + 4 ) {
                                if ( $i != $page ) 
                                    echo "<a href=?page=".$i."><div class='pages_box' style='color:#00F'> ".$i." </div></a>" ;  
                                else
                                    echo "<div class='pages_box' style='color:#000;background-color:#f8c476;'> ".$i."</div>" ;
                            }       
                        }

                    echo "<a href=?page=".$all_pages."><div class='pages_box' style='color:#00F'> >> </div></a>"; //到最後一頁

                echo "</div>";
            }
            else if ( $personality_exam_answer_sql_flag == false ){
                echo "<div id='result'>&nbsp;</div>" ;
                echo "<div id='pages'>&nbsp;</div>" ;
            }
            ?>
            <!-- 結束顯示搜尋結果：作答卷內容 -->
        </div>

        <br/>

        <script src="/./javascript/backend/exam/answer/answer_index.js?<?php echo date("is"); ?>"></script>

    </body>


</html>

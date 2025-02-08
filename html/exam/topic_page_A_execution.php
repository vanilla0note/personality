<?php
    if( !isset($_SESSION) ) session_start(); 

    ////// test //////
    //session_destroy();  //刪除全部session
    /////////////////


    //01.資料庫連線 
    require_once('../../conn/conn_user_php7.php'); 
    $link = create_connection() ;


    //02.接收及整理作答題目的答案
    if( !isset($_SESSION['careus_personality_exam_loading_page']) ) header("Location:../topic_page_error.php?msg=901");
    else                                                     
        $page = $_SESSION['careus_personality_exam_loading_page'] ; //當前作答進度頁(共11頁)
    
    if( !isset($_SESSION['careus_personality_exam_id']) )    header("Location:../topic_page_error.php?msg=901");
    else
        $exam_id = $_SESSION['careus_personality_exam_id'] ;       //試題卷編號(存在瀏覽器)
    

    $exam_id_form_check = $_POST["id"] ; //試題卷編號(表單接收值)
    $exam_Cheating_flag = false ; //試題作弊flag true：有作弊(立即中止作答)　false：無作弊

    ////////// test ///////////
    //echo "<br/>";      
    //echo "page = ".$page."<br/>";
    //echo "exam_id = ".$exam_id."<br/>";
    //echo "exam_id_form_check = ".$exam_id_form_check."<br/>";
    //////////////////////////


    //03.與資料庫比對目前作答進度
    $sql = "SELECT * FROM `exam` WHERE `exam_id` = '{$exam_id}'"; 
    $result = mysqli_query($link, $sql) ;
    $row = mysqli_fetch_assoc($result);
    $loading_page = $row['loading_page'] ; //目前已完成的作答進度頁
    $start_date = $row['start_date'] ; //開始作答時間
 
    ////////// test ///////////
    //echo "<br/>";      
    //echo "loading_page = ".$loading_page."<br/>";
    //////////////////////////

    //試題卷編號(存在瀏覽器) 與 試題卷編號(表單接收值) 不一樣 即比對失敗
    //已完成作答進度頁+1 與 目前頁面作答頁面頁數 不一樣 即比對失敗
    //疑似開啟多個視窗，立即中止作答
    if( $exam_id != $exam_id_form_check || $loading_page + 1 != $page ) {
        $exam_Cheating_flag = true ; //試題作弊flag
        header("Location:../topic_page_error.php?msg=902"); 
    }
    else{
        //04.基本變數宣告
        //寫入資料欄位名稱
        $exam_field = Array("exam_ans_1","exam_ans_2","exam_ans_3","exam_ans_4","exam_ans_5","exam_ans_6",
                            "exam_ans_7","exam_ans_8","exam_ans_9","exam_ans_10","exam_ans_11");

        $exam_ans = "" ;    //本頁作答答案
        /* page = 1 ： 題目1  至 題目10
           page = 2 ： 題目11 至 題目20
           page = 3 ： 題目21 至 題目30    
           … 
           page = 11 ： 題目101 至 題目108 

           $i = ($page-1)*10 + 1 ;   //本頁起始作答題數 */

        for( $i = ($page-1)*10 + 1 ; $i <= ($page-1)*10 + 10 && $i <= 108 ; $i++ ){

            $sql = "SELECT * FROM `system-question-exam` WHERE `num` = {$i}"; 
            $result = mysqli_query($link, $sql) ;
            $row = mysqli_fetch_assoc($result);
            $id = $row['id'] ;

            //接收 $_POST[$id] 找不到值，前台表單送出的欄位不一樣  即比對失敗
            //疑似開啟多個視窗，立即中止作答
            if ( !isset($_POST[$id]) ) {
                $exam_Cheating_flag = true ; //試題作弊flag
                header("Location:../topic_page_error.php?msg=902");
            }
            

            ////////// test ///////////
            //echo "<br/>";      
            //echo "id = ".$id."<br/>";
            //echo $id." = ".$_POST[$id]."<br/>";
            //////////////////////////

           
            if ( $exam_ans == "" ) $exam_ans = $_POST[$id] ;
            else                   $exam_ans = $exam_ans.",".$_POST[$id] ;

        }
        ////////// test ///////////
        //echo "exam_ans = ".$exam_ans ;
        //////////////////////////
              
        //時區和時間設定
        date_default_timezone_set('Asia/Taipei'); 
        $date = date("Y-m-d");
        $now_date = date("Y-m-d H:i:s"); //填寫試卷的時間戳記
        $Times_Score = strtotime($now_date) - strtotime($start_date) ; //目前作答已花費的時間

        //05.寫入資料庫
        $sql = "UPDATE `exam` SET `loading_page` = '{$page}' ,
                                  `{$exam_field[$page-1]}` = '{$exam_ans}' ,
                                  `now_date` = '{$now_date}',
                                  `Times_Score` = '{$Times_Score}'

                WHERE  `exam_id` = '{$exam_id}'" ;
        $result = mysqli_query($link, $sql) ;

    
        //06. 變更下一頁作答頁數
        $page = $page + 1 ;
        $_SESSION['careus_personality_exam_loading_page'] = $page ;


        //07. 網頁導向
        if      ( $page <= 11 && $exam_Cheating_flag == false )  {header("Location:topic_page_A.php");}
        else if ( $page > 11  && $exam_Cheating_flag == false )  {header("Location:topic_page_B.php");}

    }




    


?>


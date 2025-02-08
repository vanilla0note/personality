<?php
    /* 查詢作答卷(檢視按鈕)－登入帳號的權限判定service */

    if( !isset($_SESSION) ) session_start();

    //如果是 POST 才會執行
    if ($_SERVER['REQUEST_METHOD'] == "POST") { 

        //01.固定欄位紀錄
        $exam_id = $_POST["myckeck_id_val"];
        $my_id = $_SESSION['careus_personality_id'] ;
        $colony = "false" ;  // true：可檢視　false：不可檢視


        //02.資料庫連線
        require_once( $_SERVER['DOCUMENT_ROOT'].'/conn/conn_user_php7.php'); 
        $link = create_connection() ; 


        //03.取得帳號資料
        $sql = "SELECT * FROM `system-account` WHERE `id` = '{$my_id}'";
        $result = mysqli_query($link, $sql) ;
        while( $row = mysqli_fetch_assoc($result) ) 
            $rows[] = $row;
        $colony_read_title   = $rows[0]["colony_read_title"] ;   //讀取權限類別
        $colony_read_content = $rows[0]["colony_read_content"] ; //讀取權限內容


        //04.判定權限
        switch($colony_read_title){

            case "ALL":
                $colony = "true" ;
                break;

            case "AREA":
                $colony_content_arr = explode( "," , $colony_read_content ) ; //切割成陣列，要比對的權限代號

                for ( $i=0 ; $i<count($colony_content_arr) ; $i++ ){
                    if( substr($exam_id , 0 , 1) == substr($colony_content_arr[$i] , 5 , 1) )  
                        $colony = "true" ; 
                }
                break;
                
            case "DEP":
                $colony_content_arr= explode( "," , $colony_read_content ) ; //切割成陣列，要比對的權限代號

                $sql_temp = "SELECT * FROM `exam` WHERE `exam_id` = '{$exam_id}'";
                $result_temp = mysqli_query($link, $sql_temp) ;
                while( $row_temp = mysqli_fetch_assoc($result_temp) ) 
                    $rows_temp[] = $row_temp;
                $interview_dep   = $rows_temp[0]["interview_dep"] ;   //讀取權限類別

                for ( $i=0 ; $i<count($colony_content_arr) ; $i++ ){
                    if( $interview_dep == $colony_content_arr[$i] )  
                        $colony = "true" ; 
                }
                break;

            default: break;

        }


        //05.回傳資料
        echo json_encode(array(
            'colony' => $colony,
        ));

    }
    else
        header("Location:https://www.c-are-us.org.tw/");

?>
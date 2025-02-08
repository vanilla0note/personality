<?php
    /* 查詢作答卷(檢視內容)－登入帳號的權限判定function */

    if( !isset($_SESSION) ) session_start();


    function answer_edit_view_colony($exam_id){

        //01.固定欄位紀錄

        $my_id = $_SESSION['careus_personality_id'] ;
        $colony = "false" ;  // true：可檢視　false：不可檢視

        ////////test////////
        //echo "exam_id=".$exam_id."<br/>";
        //echo "my_id=".$my_id."<br/>";
        //echo "colony=".$colony."<br/>";
        //echo "<br/>";
        ////////////////////


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

        ////////test////////
        //echo "colony_read_title=".$colony_read_title."<br/>";
        //echo "colony_read_content=".$colony_read_content."<br/>";
        ////////////////////


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


        //05.回傳判定結果
        return $colony;

    }


?>
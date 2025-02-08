<?php
    /* 查詢作答卷－立即計算分數service */

    //如果是 POST 才會執行
    if ($_SERVER['REQUEST_METHOD'] == "POST") { 

        //01.固定欄位紀錄
        header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8
        $exam_id = $_POST["myckeck_id_val"];


        //02.資料庫連線
        require_once( $_SERVER['DOCUMENT_ROOT'].'/conn/conn_user_php7.php'); 
        $link = create_connection() ; 


        //03.載入「計算單一作答卷的分數」副程式，並執行
        include('../../../module/exam/Score_calculate_one_exam.php') ;  
      
        /* 取得計算後的人格類型分數
        
           完整答案 格式：$score_result['A']=>14,$score_result['B']=>12,$score_result['C']=>12....
           不完整答案     $score_result空字串    */

        $score_result = Score_calculate_one_exam( $exam_id ) ; //Score_calculate_one_exam.php


        //04.更新至資料庫
        $type_score = "" ;
        
        if ( $score_result == "" ) $state = "Z" ;
        else{                     
            $state = "B" ;


            for( $i = 65 ; $i<= 73 ; $i++ ){
                if( $i <  73 ) $type_score = $type_score.$score_result[ chr($i) ]."," ;
                if( $i == 73 ) $type_score = $type_score.$score_result[ chr($i) ] ;
            }
        }


        $sql = "UPDATE `exam` SET `Type_Score` = '{$type_score}' ,
                                  `state` = '{$state}'
                              WHERE  `exam_id` = '{$exam_id}'" ;
        $result = mysqli_query($link, $sql) ;


        //05.回傳資料
        echo json_encode(array(
            'type_score' => $type_score,  
        ));

    }
    else
        header("Location:https://www.c-are-us.org.tw/");

?>
<?php
    /* 查詢作答卷－立即計算分數service */

    //如果是 POST 才會執行
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        //01.固定欄位紀錄
        header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8
        $mood_id = $_POST["myckeck_id_val"];


        //02.資料庫連線
        require_once( $_SERVER['DOCUMENT_ROOT'].'/conn/conn_user_php7.php');
        $link = create_connection() ;


        //03.載入「計算單一作答卷的分數」副程式，並執行
        include('../../../module/mood/Score_calculate_one_mood.php') ;

        /* 取得計算後的滿意度分數 */
        $total_score = Score_calculate_one_mood( $mood_id ) ; //Score_calculate_one_mood.php


        //04.更新至資料庫
        if ( $total_score == 0) $state = "Z" ;
        else                    $state = "B" ;

        $sql = "UPDATE `mood` SET `Total_Score` = '{$total_score}' ,
                                  `state` = '{$state}'
                              WHERE  `mood_id` = '{$mood_id}'" ;
        $result = mysqli_query($link, $sql) ;


        //05.回傳資料
        echo json_encode(array(
            //'type_score' => $type_score,
            'mood_id' => $mood_id,
        ));

} else
    header("Location:https://www.c-are-us.org.tw/");

?>
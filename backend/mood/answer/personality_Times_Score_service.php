<?php
    /* 作答花費時間service  */

    //如果是 POST 才會執行
    if ($_SERVER['REQUEST_METHOD'] == "POST") { 

        //01.資料庫連線
        require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php"); 
        $link = create_connection() ; 


        //02.固定欄位紀錄
        header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8
        $id = $_POST["myckeck_id_val"];

        //03.取出需要的資料
        $sql = "SELECT * FROM `mood` WHERE `mood_id`  = '{$id}'" ;
        $result = mysqli_query($link, $sql) ; 
        while( $row = mysqli_fetch_assoc($result) )
            $rows[] = $row;    
        $start_date = $rows[0]["start_date"] ;
        $final_date = $rows[0]["now_date"] ;
        $Times_Score = $rows[0]["Times_Score"] ;

        //04.回傳資料
        echo json_encode(array(
            'start_date' => $start_date, 
            'final_date' => $final_date, 
            'Times_Score' => $Times_Score,
        ));

    }




?>
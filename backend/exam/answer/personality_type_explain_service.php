<?php
    /* 人格分析類型service  */

    //如果是 POST 才會執行
    if ($_SERVER['REQUEST_METHOD'] == "POST") { 

        //01.資料庫連線
        require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php"); 
        $link = create_connection() ; 


        //02.固定欄位紀錄
        header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8
        $id = $_POST["type"];

        //03.取出需要的資料
        $sql = "SELECT * FROM `system-exam_type_explain` WHERE `id`  = '{$id}'" ;
        $result = mysqli_query($link, $sql) ; 
        while( $row = mysqli_fetch_assoc($result) )
            $rows[] = $row;    
        $name = $rows[0]["name"] ;
        $type_describe = $rows[0]["type_describe"] ;
        $hope_work = $rows[0]["hope_work"] ;


        //04.回傳資料
        echo json_encode(array(
            'name' => $name, 
            'type_describe' => $type_describe, 
            'hope_work' => $hope_work, 
        ));


    }





?>
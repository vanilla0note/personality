<?php
    /* 部門職位維護－部門啟用service */


    //如果是 POST 才會執行
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        //01.資料庫連線
        require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php"); 
        $link = create_connection() ; 


        //02.固定欄位紀錄
        header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8
        $dep_id = $_POST["myckeck_dep_id_val"];


        //03.取得要更新的資料
        $sql = "SELECT * FROM `system-department` WHERE `dep_id` = '{$dep_id}'" ;
        $result = mysqli_query($link, $sql) ;
        while( $row = mysqli_fetch_assoc($result) )
            $rows[] = $row; 

        if      ( $rows[0]["acc"] == 'true' ) $acc = "false" ;
        else if ( $rows[0]["acc"] == 'false') $acc = "true"  ;


        //04.新增至資料庫
        $sql = "UPDATE `system-department` SET `acc` = '{$acc}'
                                           WHERE `dep_id` = '{$dep_id}'" ;
        mysqli_query($link, $sql) ;


        //05.回傳資料
        echo json_encode(array()
            );

    }
?>
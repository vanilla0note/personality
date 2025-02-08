<?php
    /* 帳號權限維護－設定「查詢作答卷」權限service */


    //如果是 POST 才會執行
    if ($_SERVER['REQUEST_METHOD'] == "POST") { 

        //01.資料庫連線
        require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php"); 
        $link = create_connection() ; 


        //02.固定欄位紀錄
        header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8

        $id   = $_POST["myckeck_new_account_id_val"];
        $type = $_POST["myckeck_new_account_type_val"];

        date_default_timezone_set('Asia/Taipei');         
        $now_date = date("Y-m-d H:i:s");


        //03.檢查要建立的帳號是否存在
        $sql = "SELECT * FROM `system-account` WHERE `id` = '{$id}'" ;
        $result = mysqli_query($link, $sql) ;
        $num = mysqli_num_rows($result);
        if ($num == 0 ){
                       
            //04.新增至資料庫
            $sql = "INSERT INTO `system-account` (`no`,`id`,`pw`,`acc`,
                                                  `auth`,`colony_read_title`,`colony_read_content`,`type`,
                                                  `login_count`,`login_time`,`now_date`) Values 
                                                 ('', '{$id}','','true',
                                                  'AC00','ALL','ALL','{$type}',
                                                  '0','' ,'{$now_date}')" ;
            mysqli_query($link, $sql) ;
        }


        //05.緩衝時間
        sleep(1);


        //06.回傳資料
        echo json_encode(array()
                );


    }
?>
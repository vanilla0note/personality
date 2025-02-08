<?php
    /* 郵件設定－儲存更新service */


    //如果是 POST 才會執行
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        //01.資料庫連線
        require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php");
        $link = create_connection() ;

        //02.固定欄位紀錄
        header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8
        $mail_main_id = $_POST["myckeck_mail_main_id_val"];
        $mail_main_pw = $_POST["myckeck_mail_main_pw_val"];
        $mail_cc      = $_POST["myckeck_mail_cc_val"];


        //03.執行更新
        $sql = "UPDATE `system-mail` SET `mail_address`  = '{$mail_main_id}',
                                         `mail_password` = '{$mail_main_pw}'
                                         WHERE `count` = '0'" ;
        mysqli_query($link, $sql) ;


        $sql = "UPDATE `system-mail` SET `mail_address`  = '{$mail_cc}'
                                         WHERE `count` = '1'" ;
        mysqli_query($link, $sql) ;


        //04.緩衝時間
        sleep(1);


        //05.回傳資料
        echo json_encode(array()
            );

    }

?>
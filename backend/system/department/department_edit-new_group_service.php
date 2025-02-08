<?php
    /* 部門職位維護－建立職位service */


    //如果是 POST 才會執行
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        //01.資料庫連線
        require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php");
        $link = create_connection() ;


        //02.固定欄位紀錄
        header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8

        $group_name = $_POST["myckeck_new_group_name_val"];
        $dep_id = $_POST["myckeck_dep_id_val"];

        date_default_timezone_set('Asia/Taipei');
        $now_date = date("Y-m-d H:i:s");


        //03.取得一個新的職位id
        $sql = "SELECT * FROM `system-department-group`" ;
        $result = mysqli_query($link, $sql) ;
        $num = mysqli_num_rows($result) ; //搜尋資料總筆數


        $num = $num + 1 ;
        if      ( $num <  10 )                      $group_id = "group0000".$num ;
        else if ( $num >= 10     && $num <  100   ) $group_id = "group000".$num ;
        else if ( $num >= 100    && $num <  1000  ) $group_id = "group00".$num ;
        else if ( $num >= 1000   && $num <  10000 ) $group_id = "groupb0".$num ;
        else if ( $num >= 10000  && $num <= 99999 ) $group_id = "group".$num ;
        else if ( $num >  100000                  ) $group_id = "group-null";


        //04.新增至資料庫
        $sql = "INSERT INTO `system-department-group` (`no`,`group_id`,`dep_id`,`group_name`,`acc`,`now_date`) Values
                                                      ('', '{$group_id}', '{$dep_id}', '{$group_name}' ,'true', '{$now_date}')" ;
        mysqli_query($link, $sql) ;


        //05.緩衝時間
        sleep(1);


        //06.回傳資料
        echo json_encode(array(
            //'job_id' => $job_id,
            'dep_id' => $dep_id,
            //'num' => $num,
        ));

    }

?>
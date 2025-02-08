<?php
    //如果是 POST 才會執行
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        //01.固定欄位紀錄
        header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8
        $dep_id = $_POST["myckeck_exam_dep_data"];
        $group_arrange_results = Array(); //要回傳的結果內容
        $job_arrange_results = Array() ;


        //02.資料庫連線
        require_once( $_SERVER['DOCUMENT_ROOT'].'/conn/conn_user_php7.php');
        $link = create_connection() ;


        //03.依照部門資料，取出需要的組別及職位資料
        $sql = "SELECT * FROM `system-department-group` WHERE `dep_id` = '{$dep_id}' AND `acc` = 'true'" ;
        $result = mysqli_query($link, $sql) ;

        $i = 0 ;
        while( $row = mysqli_fetch_assoc($result) ){
            $group_arrange_results[$i]   = $row['group_id'] ;
            $group_arrange_results[$i+1] = $row['group_name'] ;

            $i = $i + 2 ;
        }


        $sql = "SELECT * FROM `system-department-job` WHERE `dep_id` = '{$dep_id}' AND `acc` = 'true'" ;
        $result = mysqli_query($link, $sql) ;

        $i = 0 ;
        while( $row = mysqli_fetch_assoc($result) ){
            $job_arrange_results[$i]   = $row['job_id'] ;
            $job_arrange_results[$i+1] = $row['job_name'] ;

            $i = $i + 2 ;
        }


        //04.回傳資料
        echo json_encode(array(
            'group_arrange_results' => $group_arrange_results,
            'job_arrange_results' => $job_arrange_results,
        ));

    }
    else
        header("Location:http://www.google.com");

?>
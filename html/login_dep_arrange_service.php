<?php
    //如果是 POST 才會執行
    if ($_SERVER['REQUEST_METHOD'] == "POST") { 

        //01.固定欄位紀錄
        header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8
        $dep_arrange_results = "" ; //要回傳的結果內容


        //02.資料庫連線
        require_once( $_SERVER['DOCUMENT_ROOT'].'/conn/conn_user_php7.php'); 
        $link = create_connection() ; 


        //03.取出需要的部門資料
        $sql = "SELECT * FROM `system-department` WHERE `acc` = 'true'" ;
        $result = mysqli_query($link, $sql) ;

        while( $row = mysqli_fetch_assoc($result) )
            $dep_arrange_results = $dep_arrange_results."<option value='".$row['dep_id']."'>".$row['dep_name']."</option>" ;

                      
        //04.回傳資料
        echo json_encode(array(
            'dep_arrange_results' => $dep_arrange_results,
        ));

    }
    else
        header("Location:http://www.google.com");

?>
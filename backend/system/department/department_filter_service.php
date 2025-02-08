<?php
    //01.資料庫連線
    require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php"); 
    $link = create_connection() ; 


    //02.固定欄位紀錄
    header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8

    $ckeck_filter_system_dep_name = $_POST["myckeck_filter_dep_name_val"];
    $ckeck_filter_system_dep_acc  = $_POST["myckeck_filter_dep_acc_val"];


    //取得現在時間
    date_default_timezone_set('Asia/Taipei');         
    $now_date = date("Y-m-d");


    //03.依照前台所設定的篩選條件，對應各段的查詢語法
    $sql_str1 = "SELECT * FROM `system-department` " ;
    $sql_str2 = "";
    $sql_str3 = " order by `no` ASC";
    $sql_str4 = "";

    //////////// test ////////////
    ////範例
    //$sql = "SELECT * FROM `system-department` WHERE `acc`      LIKE 'true'     AND
    //                                                `dep_name` LIKE '%行政%'         
    //                                          order by `no` ASC"; 
    //$result = mysqli_query($link, $sql) ;  
    //while( $row = mysqli_fetch_assoc($result) )
    //    $rows[] = $row;    
    //$exam_id = $rows[0]["exam_id"] ;
    /////////////////////////////


    //03-A.啟用狀態 $filter_content[1]
    if ( $ckeck_filter_system_dep_acc == "ALL" ) $ckeck_filter_system_dep_acc = '%' ;
    $sql_str2 = $sql_str2."WHERE acc LIKE '{$ckeck_filter_system_dep_acc}' ";

    //03-B.職務類型名稱 $filter_content[0]
    if ( $ckeck_filter_system_dep_name != "" ) 
        $sql_str2 = $sql_str2."AND dep_name LIKE '%{$ckeck_filter_system_dep_name}%' ";


    //04.計算總筆數及分頁
    $sql = "SELECT * FROM `system-department` ".$sql_str2.$sql_str3 ;
    $result = mysqli_query($link, $sql) ;  
    $data_nums = mysqli_num_rows($result); ; //搜尋後資料總筆數
    $limit = 10; //每頁最多顯示資料數量
    $all_pages = ceil($data_nums/$limit); //資料總頁數 取得不小於值的下一個整數
        if ( $all_pages == 0 ) $all_pages = 1 ;  //等於 0 代表沒有資料，直接等於1，點選末頁才不會出錯
    $page = 1 ;  //設定起始頁數
    $sql_str4 = " LIMIT 0,10"; //第 1 頁的語法


    //05.組合查詢語法
    $sql = $sql_str1.$sql_str2.$sql_str3.$sql_str4; 


    //06.在session紀錄sql查詢語法，用於點擊頁數後的查詢
    if( !isset($_SESSION) ) session_start();     
    $_SESSION['personality_system_dep_sql_query'] = $sql_str1.$sql_str2.$sql_str3 ;  //本次查詢的sql語法，語法的頁數不紀錄
    $_SESSION['personality_system_dep_data_nums'] = $data_nums ; //本次查詢的資料總筆數
    $_SESSION['personality_system_dep_all_pages'] = $all_pages ; //本次查詢的資料總頁數
    $_SESSION['personality_system_dep_filter_content'] = $ckeck_filter_system_dep_name . "@" . $ckeck_filter_system_dep_acc ;
                                                          //本次查詢的篩選器條件，要帶入至前台網頁

    //07.回傳資料
    echo json_encode(array(
        'mysql' => $sql, //查詢語法
    ));



















?>
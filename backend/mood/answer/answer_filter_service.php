<?php
    //01.資料庫連線
    require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php");
    $link = create_connection() ;


    //02.固定欄位紀錄
    header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8

    $ckeck_filter_mood_date01 = $_POST["myckeck_filter_mood_date01_val"];
    $ckeck_filter_mood_date02 = $_POST["myckeck_filter_mood_date02_val"];
    $ckeck_filter_mood_id     = $_POST["myckeck_filter_mood_id_val"];
    $ckeck_filter_mood_name  = $_POST["myckeck_filter_mood_name_val"];
    $ckeck_filter_mood_area  = $_POST["myckeck_filter_mood_area_val"];
    $ckeck_filter_mood_dep   = $_POST["myckeck_filter_mood_dep_val"];
    $ckeck_filter_mood_state = $_POST["myckeck_filter_mood_state_val"];


    //取得現在時間
    date_default_timezone_set('Asia/Taipei');
    $now_date = date("Y-m-d");


    //03.依照前台所設定的篩選條件，對應各段的查詢語法
    $sql_str1 = "SELECT * FROM `mood` " ;
    $sql_str2 = "";
    $sql_str3 = " order by num DESC";
    $sql_str4 = "";

    //////////// test ////////////
    ////範例
    //$sql = "SELECT * FROM `mood` WHERE `mood_id`        LIKE 'A%'        AND
    //                                   `staff_dep`  LIKE 'dep0001'   AND
    //                                   `state`          LIKE 'A'         AND
    //                                   `start_date`  >= '2022-08-01 00:00:00' AND now_date <= '2022-08-10 23:59:59' AND
    //                                   `mood_id`        = 'Q2208040451O' AND
    //                                   `staffw_name` = '王大明'
    //                                   order by num DESC";
    //$result = mysqli_query($link, $sql) ;
    //while( $row = mysqli_fetch_assoc($result) )
    //    $rows[] = $row;
    //$mood_id = $rows[0]["mood_id"] ;
    /////////////////////////////

    //A.應徵區域 $filter_content[4]
    if ( $ckeck_filter_mood_area == "ALL" ) $ckeck_filter_mood_area = '%' ;
    $sql_str2 = $sql_str2."WHERE mood_id LIKE '{$ckeck_filter_mood_area}%' ";

    //B.應徵部門 $filter_content[5]
    if ( $ckeck_filter_mood_dep == "ALL" ) $ckeck_filter_mood_dep = '%' ;
    $sql_str2 = $sql_str2."AND staff_dep LIKE '{$ckeck_filter_mood_dep}%' ";

    //C.試題卷狀態 $filter_content[6]
    if ( $ckeck_filter_mood_state == "ALL" ) $ckeck_filter_mood_state = '%' ;
    $sql_str2 = $sql_str2."AND state LIKE '{$ckeck_filter_mood_state}' ";

    //D.作答日期 $filter_content[0] $filter_content[1]
    if ( $ckeck_filter_mood_date01 != "" ) $sql_str2 = $sql_str2."AND start_date >= '{$ckeck_filter_mood_date01}' ";
    if ( $ckeck_filter_mood_date02 != "" ) $sql_str2 = $sql_str2."AND now_date <= '{$ckeck_filter_mood_date02}' ";

    //E.試題卷編號 $filter_content[2]
    if ( $ckeck_filter_mood_id != "") $sql_str2 = $sql_str2."AND mood_id = '{$ckeck_filter_mood_id}' ";

    //F.作答人姓名 $filter_content[3]
    if ( $ckeck_filter_mood_name != "") $sql_str2 = $sql_str2."AND staff_name = '{$ckeck_filter_mood_name}' ";


    //04.計算總筆數及分頁
    $sql = "SELECT * FROM `mood` ".$sql_str2.$sql_str3 ;
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
    $_SESSION['personality_mood_answer_sql_query'] = $sql_str1.$sql_str2.$sql_str3 ;  //本次查詢的sql語法，語法的頁數不紀錄
    $_SESSION['personality_mood_answer_data_nums'] = $data_nums ; //本次查詢的資料總筆數
    $_SESSION['personality_mood_answer_all_pages'] = $all_pages ; //本次查詢的資料總頁數
    $_SESSION['personality_mood_answer_filter_content'] = $ckeck_filter_mood_date01 . "@" . $ckeck_filter_mood_date02 . "@" .
                                                   $ckeck_filter_mood_id ."@" .
                                                   $ckeck_filter_mood_name . "@" .
                                                   $ckeck_filter_mood_area . "@" .
                                                   $ckeck_filter_mood_dep . "@" .
                                                   $ckeck_filter_mood_state  ; //本次查詢的篩選器條件，要帶入至前台網頁


    //07.回傳資料
    echo json_encode(array(
        'mysql' => $sql, //查詢語法
    ));



















?>
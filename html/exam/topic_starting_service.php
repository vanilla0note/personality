<?php
    if( !isset($_SESSION) ) session_start();


    //01.資料庫連線
    require_once($_SERVER['DOCUMENT_ROOT'] .'/conn/conn_user_php7.php');
    $link = create_connection() ;


    //02.接收數值
    header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8
    $exam_id = $_SESSION['careus_personality_exam_id'] ;

    if( !isset($_SESSION['careus_personality_exam_loading_page']) ) header("Location:/./");
    else
        $page = $_SESSION['careus_personality_exam_loading_page'] ;     //作答進度頁


    //03. 變更作答進度頁
    $page = $page + 1 ;
    $_SESSION['careus_personality_exam_loading_page'] = $page ;


    //04.複寫「開始作答時間」
    date_default_timezone_set('Asia/Taipei');
    $start_date = date("Y-m-d H:i:s") ; //開始作答時間

    $sql = "UPDATE `exam` SET `start_date` = '{$start_date}'
            WHERE  `exam_id` = '{$exam_id}'" ;
    $result = mysqli_query($link, $sql) ;


    //05.回傳
    echo json_encode(array(


    ));

?>


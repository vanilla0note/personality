<?php
    if( !isset($_SESSION) ) session_start(); 

    ////// test //////
    //session_destroy();  //刪除全部session
    /////////////////


    //01.接收數值
    header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8
    $page = $_SESSION['careus_personality_exam_loading_page']  ; //題目目前進行的頁數
    $formData = $_POST["myckeck_formData"] ;  //填寫作答內容
    $complete_flag = false ; //填寫作答flag  true：填寫完成  false：填寫未完成


    //02.檢查作答情形
    $formData = explode("&",$formData) ; //將字串依照& 切割成陣列

    //題目目前進行的題數 (page：1～10 每頁做答 10 題；page：11 每頁作答 8 題)(最後一個欄位 帶入試題卷編號)
    if( $page <= 10 ){       
        if      ( count($formData) < 11 )   $complete_flag = false ;
        else if ( count($formData) == 11 )  $complete_flag = true ;
    }
    else if( $page >= 11 ){ 
        if      ( count($formData) < 9 )   $complete_flag = false ;
        else if ( count($formData) == 9 )  $complete_flag = true ;
    }


    //03.回傳
    echo json_encode(array(
        'mypage'  => $page,
        'myformData'  => $formData,

        'mycomplete_flag' => $complete_flag,
    ));

?>


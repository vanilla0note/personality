<?php
    if( !isset($_SESSION) ) session_start(); 

    ////// test //////
    //session_destroy();  //刪除全部session
    /////////////////


    //01.接收數值
    header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8
    $page = $_SESSION['careus_personality_mood_loading_page']  ; //題目目前進行的頁數
    $formData = $_POST["myckeck_formData"] ;  //填寫作答內容
    $complete_flag = false ; //填寫作答flag  true：填寫完成  false：填寫未完成


    //02.檢查作答情形
    $formData = explode("&",$formData) ; //將字串依照& 切割成陣列

    //題目目前進行的題數 (page：1～2 每頁作答 7 題；page：3 每頁作答 1 題)(最後一個欄位 帶入試題卷編號)
    if( $page <= 2 ){       
        if      ( count($formData) < 8 )   $complete_flag = false ;
        else if ( count($formData) == 8 )  $complete_flag = true ;
    }
    else if( $page >= 3 ){ 
        if      ( $formData[0] == "CQA015=")   $complete_flag = false ;
        else if ( $formData[0] != "CQA015=" )  $complete_flag = true ;
    }


    //03.回傳
    echo json_encode(array(
        'mypage'  => $page,
        'myformData'  => $formData,

        'mycomplete_flag' => $complete_flag,
    ));

?>


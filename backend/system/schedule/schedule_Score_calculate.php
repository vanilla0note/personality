<?php
    if( !isset($_SESSION) ) session_start();

    ////// test //////
    //session_destroy();  //刪除全部session
    /////////////////


    //01.資料庫連線
    require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php"); 
    $link = create_connection() ;


    //02.登入權限判定(群組)
    //include($_SERVER['DOCUMENT_ROOT'].'/module/auth/auth.php');
    //$auth = myauth('system-setting');
    //if ( $auth != "true"){
    //    header("Location:/./");
    //    exit();
    //}


    //03.取得啟用狀態
    $sql = "SELECT * FROM `system-schedule` WHERE `name` = 'exam_schedule'" ;
    $result = mysqli_query($link, $sql) ; 
    while( $row = mysqli_fetch_assoc($result) )
        $rows[] = $row;    

    //// test /////
    //echo "acc = ".$rows[0]["acc"]."<br/>";
    //////////////


    include($_SERVER['DOCUMENT_ROOT'].'/module/exam/Score_calculate_all_exam.php');
    if ( $rows[0]["acc"] == "true" ) Score_calculate_all_exam() ;

?>
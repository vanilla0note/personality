<?php
    if( !isset($_SESSION) ) session_start();

    ////// test //////
    //session_destroy();  //�R������session
    /////////////////


    //01.��Ʈw�s�u
    require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php"); 
    $link = create_connection() ;


    //02.�n�J�v���P�w(�s��)
    //include($_SERVER['DOCUMENT_ROOT'].'/module/auth/auth.php');
    //$auth = myauth('system-setting');
    //if ( $auth != "true"){
    //    header("Location:/./");
    //    exit();
    //}


    //03.���o�ҥΪ��A
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
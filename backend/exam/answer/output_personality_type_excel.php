<?php
    if( !isset($_SESSION) ) session_start();

    ////// test //////
    //session_destroy();  //�R������session
    /////////////////


    //01.��Ʈw�s�u
    require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php");
    $link = create_connection() ;


    //02.�n�J�v���P�w(�s��)
    include($_SERVER['DOCUMENT_ROOT'].'/module/auth/auth.php');
    $auth = myauth('exam-answer');
    if ( $auth != "true"){
        header("Location:/./");
        exit();
    }

    //03.���o�n�s�誺���D��id
    $exam_id  = $_GET["x"];


    //04.�q��Ʈw���o�n�@���������
    $sql  = "SELECT `exam_id`,
                    `interview_name`,
                    `interview_sex`,
                    `interview_dep`,
                    `interview_group`,
                    `interview_job`,
                    `start_date`,
                    `now_date`,
                    `Times_Score`,
                    `Type_Score`,
                    `remark`,
                    `state`,
                    `Backend_remark`
             FROM `exam` WHERE `exam_id` = '{$exam_id}'";


    //05.�N SQL �y�k�� Export_personality_type_report �ǰe�A�� Export_personality_type_report �����ɮ�
    include_once('Export_personality_type_report.php');
    exporting_xls($sql); //��X Excel


?>
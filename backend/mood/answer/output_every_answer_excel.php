<?php
    if( !isset($_SESSION) ) session_start();

    ////// test //////
    //session_destroy();  //刪除全部session
    /////////////////


    //01.資料庫連線
    require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php");
    $link = create_connection() ;


    //02.登入權限判定(群組)
    include($_SERVER['DOCUMENT_ROOT'].'/module/auth/auth.php');
    $auth = myauth('exam-answer');
    if ( $auth != "true"){
        header("Location:/./");
        exit();
    }


    //03.取得要編輯的試題卷id
    $mood_id  = $_GET["x"];


    //04.從資料庫取得要作答卷的資料
    $sql  = "SELECT `mood_id`,
                    `staff_name`,
                    `staff_sex`,
                    `staff_dep`,
                    `staff_group`,
                    `staff_job`,
                    `start_date`,
                    `now_date`,
                    `Times_Score`,
                    `remark`,
                    `state`,
                    `Backend_remark`
             FROM `mood` WHERE `mood_id` = '{$mood_id}'";


    //05.將 SQL 語法往 Export_every_answer_report 傳送，由 Export_every_answer_report 產生檔案
    include_once('Export_every_answer_report.php');
    exporting_xls($sql); //輸出 Excel

?>
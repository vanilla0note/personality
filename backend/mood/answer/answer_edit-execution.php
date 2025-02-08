<?php
    if( !isset($_SESSION) ) session_start();

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


    //03.固定欄位記錄
    $staff_name       = $_POST["staff_name"];
    $backend_remark   = $_POST["backend_remark"];

    $mood_id   = $_POST["mood_id"];

    //////test//////
    //echo "staff_name=".$staff_name."<br/>";
    //echo "backend_remark=".$backend_remark."<br/>";
    //echo "<br/>";
    //echo "mood_id=".$mood_id."<br/>";
    //echo "<br/>";
    ////////////////


    //04.寫入資料庫要更新的部門資料
    $sql = "UPDATE `mood` SET `staff_name`     = '{$staff_name}',
                              `Backend_remark` = '{$backend_remark}' 
                          WHERE `mood_id` = '{$mood_id}'" ;

    mysqli_query($link, $sql) ;


    //05.緩衝時間
    sleep(1);
    
                
    //06. 網頁跳轉
    header("Location:answer_index.php?page=1");

?>
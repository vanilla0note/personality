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
    $interview_name = $_POST["interview_name"];
    if ( isset($_POST["same"]) ) $same = "true" ;
    else                         $same = "false" ;

    $employee_id = $_POST["employee_id"];
    $dep         = $_POST["dep"];
    $appointment_date = $_POST["appointment_date"];
    $resignation_date = $_POST["resignation_date"];
    $backend_remark   = $_POST["backend_remark"];

    $exam_id   = $_POST["exam_id"];

    //////test//////
    //echo "interview_name=".$interview_name."<br/>";
    //echo "same=".$same."<br/>";
    //echo "employee_id=".$employee_id."<br/>";
    //echo "dep=".$dep."<br/>";
    //echo "appointment_date=".$appointment_date."<br/>";
    //echo "resignation_date=".$resignation_date."<br/>";
    //echo "backend_remark=".$backend_remark."<br/>";
    //echo "<br/>";
    //echo "exam_id=".$exam_id."<br/>";
    //echo "<br/>";
    ////////////////


    //04.寫入資料庫要更新的部門資料
    $sql = "UPDATE `exam` SET `interview_name`   = '{$interview_name}',
                              `same`             = '{$same}',
                              `Employee_id`      = '{$employee_id}' ,
                              `Dep`              = '{$dep}' ,
                              `Appointment_date` = '{$appointment_date}' ,
                              `Resignation_date` = '{$resignation_date}' ,
                              `Backend_remark`   = '{$backend_remark}' 
                          WHERE `exam_id` = '{$exam_id}'" ;

    mysqli_query($link, $sql) ;


    //05.緩衝時間
    sleep(1);
    
                
    //06. 網頁跳轉
    header("Location:answer_index.php?page=1");

?>
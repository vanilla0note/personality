<?php
    if( !isset($_SESSION) ) session_start();

    //01.資料庫連線
    require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php");
    $link = create_connection() ;


    //02.登入權限判定(群組)
    include($_SERVER['DOCUMENT_ROOT'].'/module/auth/auth.php');
    $auth = myauth('system-setting');
    if ( $auth != "true"){
        header("Location:/./");
        exit();
    }


    //03.固定欄位記錄
    $dep_id   = $_POST["dep_id"];
    $dep_name = $_POST["dep_name"];
    $report_mail_1 = $_POST["report_mail_1"];
    $report_mail_2 = $_POST["report_mail_2"];

    ////////test//////
    //echo "dep_id=".$dep_id."<br/>";
    //echo "dep_name=".$dep_name."<br/>";
    //echo "report_mail_1=".$report_mail_1."<br/>";
    //echo "report_mail_2=".$report_mail_2."<br/>";
    //echo "<br/><br/>";
    //////////////////

    $group_id   = $_POST["group_id"];
    $group_name = $_POST["group_name"];
    $group_acc  = $_POST["group_acc"];
    for( $i=0 ;$i<count($group_id); $i++){

        ////////test//////
        //echo "group_id[".$i."]=".$group_id[$i]."<br/>";
        //echo "group_name[".$i."]=".$group_name[$i]."<br/>";
        //////////////////

        if (!isset($group_acc[$i])) $group_acc[$i] = "false" ;
        else                        $group_acc[$i] = "true" ;

        //////test//////
        //echo "group_acc[".$i."]=".$group_acc[$i]."<br/>";
        //echo "<br/><br/>";
        //////////////////
    }

    $job_id   = $_POST["job_id"];
    $job_name = $_POST["job_name"];
    $job_acc  = $_POST["job_acc"];
    for( $i=0 ;$i<count($job_id); $i++){

        ////////test//////
        //echo "job_id[".$i."]=".$job_id[$i]."<br/>";
        //echo "job_name[".$i."]=".$job_name[$i]."<br/>";
        //////////////////

        if (!isset($job_acc[$i])) $job_acc[$i] = "false" ;
        else                      $job_acc[$i] = "true" ;

        ////////test//////
        //echo "job_acc[".$i."]=".$job_acc[$i]."<br/>";
        //echo "<br/><br/>";
        //////////////////
    }



    //04.寫入資料庫要更新的部門資料
    $sql = "UPDATE `system-department` SET `dep_name` = '{$dep_name}',
                                           `report_mail_1` = '{$report_mail_1}',
                                           `report_mail_2` = '{$report_mail_2}'
                                       WHERE `dep_id` = '{$dep_id}'" ;
    mysqli_query($link, $sql) ;


    for( $i=0 ;$i<count($group_id); $i++){
        $sql = "UPDATE `system-department-group` SET `group_name` = '{$group_name[$i]}' ,
                                                     `acc` = '{$group_acc[$i]}'
                                                 WHERE `group_id` = '{$group_id[$i]}'" ;
        mysqli_query($link, $sql) ;
    }

    for( $i=0 ;$i<count($job_id); $i++){
        $sql = "UPDATE `system-department-job` SET `job_name` = '{$job_name[$i]}' ,
                                                   `acc` = '{$job_acc[$i]}'
                                                WHERE `job_id` = '{$job_id[$i]}'" ;
        mysqli_query($link, $sql) ;
    }


    //05.緩衝時間
    sleep(1);


    //06. 網頁跳轉
    header("Location:department_edit-confirm.php?id=".$dep_id);

?>
<?php
    if( !isset($_SESSION) ) session_start(); session_destroy();  //重新建立一份試題卷，刪除全部session
    session_start();


    //01.資料庫連線
    require_once($_SERVER['DOCUMENT_ROOT'] .'/conn/conn_user_php7.php');
    $link = create_connection() ;


    //02.接收數值
    header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8
    $name   = str_replace(' ','',$_POST["myckeck_name"]);//去除名字欄位中的空白字元
    $sex    = $_POST["myckeck_sex"];
    $area   = $_POST["myckeck_area"];
    $dep    = $_POST["myckeck_dep"];
    $group  = $_POST["myckeck_group"];
    $job    = $_POST["myckeck_job"];
    $remark = $_POST["myckeck_remark"];


    //03.建立一張新的試題卷
    date_default_timezone_set('Asia/Taipei');

    /****************************
      試題卷 應徵區域代號(第一碼)：
      A(台北地區)
      H(桃園地區)
      J(新竹地區)
      D(台南地區)
      E(高雄地區)

      用於開啟試題卷的權限判定
    ****************************/

    switch($area){
        case "Taipei" :    $digit = "A" ; $area_str = "台北地區" ; break ;
        case "Taoyuan" :   $digit = "H" ; $area_str = "桃園地區" ; break ;
        case "Hsinchu" :   $digit = "J" ; $area_str = "新竹地區" ; break ;
        case "Tainan" :    $digit = "D" ; $area_str = "台南地區" ; break ;
        case "Kaohsiung" : $digit = "E" ; $area_str = "高雄地區" ; break ;
        default: $digit = "@" ;  break ;

    }
    $exam_id = $digit.date("ymdHis"); //試題卷編號

    $sql = "INSERT INTO `exam` (`num`, `exam_id`, `same`,
                                `interview_name`, `interview_sex`, `interview_dep`, `interview_group`,`interview_job`, `loading_page`,`start_date`,
                                `exam_ans_1`, `exam_ans_2`, `exam_ans_3`, `exam_ans_4`, `exam_ans_5`,
                                `exam_ans_6`, `exam_ans_7`, `exam_ans_8`, `exam_ans_9`, `exam_ans_10`,
                                `exam_ans_11`,
                                `remark`, `now_date`, `state`, `Type_Score`,`Times_Score`,
                                `Employee_id`, `Dep`, `Appointment_date`, `Resignation_date`,`Backend_remark`) Values
                               ('', '{$exam_id}', 'false',
                                '{$name}','{$sex}','{$dep}' ,'{$group}','{$job}', 0, '',
                                '', '', '', '', '',
                                '', '', '', '', '',
                                '',
                                '{$remark}', '', 'A', '', '',
                                '', '', '', '','')" ;
    mysqli_query($link, $sql) ;


    //04.準備基本變數資料
    //應徵部門資料
    $sql_temp = "SELECT * FROM `system-department` WHERE `dep_id` = '{$dep}'" ;
    $result_temp = mysqli_query($link, $sql_temp) ;
    while( $row_temp = mysqli_fetch_assoc($result_temp) )
        $rows_temp[] = $row_temp ;
    $dep_str = $rows_temp[0]["dep_name"] ;

    unset($rows_temp) ;//清空陣列

    //應徵組別資料
    $sql_temp = "SELECT * FROM `system-department-group` WHERE `group_id` = '{$group}'" ;
    $result_temp = mysqli_query($link, $sql_temp) ;
    while( $row_temp = mysqli_fetch_assoc($result_temp) )
        $rows_temp[] = $row_temp ;
    $group_str = $rows_temp[0]["group_name"] ;

    unset($rows_temp) ;

    //應徵職位資料
    $sql_temp = "SELECT * FROM `system-department-job` WHERE `job_id` = '{$job}'" ;
    $result_temp = mysqli_query($link, $sql_temp) ;
    while( $row_temp = mysqli_fetch_assoc($result_temp) )
        $rows_temp[] = $row_temp ;
    $job_str = $rows_temp[0]["job_name"] ;

    unset($rows_temp);

    //05.建立session
    $_SESSION['careus_personality_exam_id']           = $exam_id ;   //試題卷id
    $_SESSION['careus_personality_exam_loading_page'] = 0 ;          //作答進度頁
    $_SESSION['careus_personality_exam_name']         = $name ;      //作答姓名
    $_SESSION['careus_personality_exam_area_str']     = $area_str ;  //應徵區域(中文)
    $_SESSION['careus_personality_exam_dep']          = $dep ;       //應徵部門
    $_SESSION['careus_personality_exam_dep_str']      = $dep_str ;   //應徵部門(中文)
    $_SESSION['careus_personality_exam_group']        = $group ;     //應徵組別
    $_SESSION['careus_personality_exam_group_str']    = $group_str ; //應徵組別(中文)
    $_SESSION['careus_personality_exam_job']          = $job ;       //應徵職位
    $_SESSION['careus_personality_exam_job_str']      = $job_str ;   //應徵職位(中文)


    //06.回傳
    echo json_encode(array(
        'myexam_id' => $exam_id ,
    ));


?>


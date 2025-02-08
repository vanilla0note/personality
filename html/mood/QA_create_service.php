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
    $id     = $_POST["myckeck_id"];
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
    $mood_id = $digit.date("ymdHis"); //試題卷編號

    $sql = "INSERT INTO `mood` (`num`, `mood_id`,
                                `staff_name`, `staff_sex`, `staff_id`, `staff_dep`, `staff_group`,`staff_job`, `loading_page`,`start_date`,
                                `mood_ans_1`, `mood_ans_2`, `mood_ans_3`,
                                `remark`, `now_date`, `state`, `Total_Score`,`Times_Score`,
                                `Backend_remark`) Values
                               ('', '{$mood_id}',
                                '{$name}','{$sex}','{$id}','{$dep}' ,'{$group}','{$job}', 0, '',
                                '', '', '',
                                '{$remark}', '', 'A', '', '',
                                '')" ;
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
    $_SESSION['careus_personality_mood_id']           = $mood_id ;   //試題卷id
    $_SESSION['careus_personality_mood_loading_page'] = 0 ;          //作答進度頁
    $_SESSION['careus_personality_mood_name']         = $name ;      //員工姓名
    $_SESSION['careus_personality_mood_workid']       = $id;         //員工帳號
    $_SESSION['careus_personality_mood_area_str']     = $area_str ;  //任職區域(中文)
    $_SESSION['careus_personality_mood_dep']          = $dep ;       //任職部門
    $_SESSION['careus_personality_mood_dep_str']      = $dep_str ;   //任職部門(中文)
    $_SESSION['careus_personality_mood_group']        = $group ;     //任職組別
    $_SESSION['careus_personality_mood_group_str']    = $group_str ; //任職組別(中文)
    $_SESSION['careus_personality_mood_job']          = $job ;       //任職職位
    $_SESSION['careus_personality_mood_job_str']      = $job_str ;   //任職職位(中文)


    //06.回傳
    echo json_encode(array(
        'mymood_id' => $mood_id ,
    ));


?>


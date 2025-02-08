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
    $dep_id = $_POST["dep_id"];
    $pw     = $_POST["pw"];
    $auth   = $_POST["auth"];
    $colony_read_title = $_POST["colony_read_title"];

    $colony_read_content = "" ;
    for( $i=0 ; $i<count($_POST["colony_read_content"]) ; $i++ ){
        if ( $i!=0 ) $colony_read_content = $colony_read_content.",".$_POST["colony_read_content"][$i] ;
        else         $colony_read_content = $colony_read_content.$_POST["colony_read_content"][$i] ;
    }

    //取得該帳號在資料庫的no值
    $sql_temp = "SELECT * FROM `system-account` WHERE `id` = '{$dep_id}' " ;
    $result_temp = mysqli_query($link, $sql_temp) ;
    while( $row_temp = mysqli_fetch_assoc($result_temp) )
        $rows_temp[] = $row_temp; 
    $no = $rows_temp[0]["no"] ;


    //////test//////
    //echo "no=".$no."<br/>";
    //echo "dep_id=".$dep_id."<br/>";
    //echo "pw=".$pw."<br/>";
    //echo "auth=".$auth."<br/>";
    //echo "colony_read_title=".$colony_read_title."<br/>";
    //echo "colony_read_content=".$colony_read_content."<br/>";
    //echo "<br>";
    ////////////////


    //04.寫入資料庫更新帳號資料
    if ( $pw == ""){
        $sql = "UPDATE `system-account` SET `auth` = '{$auth}',
                                            `colony_read_title` = '{$colony_read_title}',
                                            `colony_read_content` = '{$colony_read_content}'	
                                             WHERE `no` = '{$no}'" ;
    }
    else if ( $pw != ""){
        $pw = strtoupper(hash('SHA256', $pw )) ; //SHA256加密
        $sql = "UPDATE `system-account` SET `pw` = '{$pw}',
                                            `auth` = '{$auth}',
                                            `colony_read_title` = '{$colony_read_title}',
                                            `colony_read_content` = '{$colony_read_content}'	
                                             WHERE `no` = '{$no}'" ;
    }
    mysqli_query($link, $sql) ;

    //05.緩衝時間
    sleep(1);
                    

    //06. 網頁跳轉
    header("Location:account_edit-confirm.php?id=".$no);

?>
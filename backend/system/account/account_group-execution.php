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


    //03.接收表單欄位
    $no   = $_POST["no"] ;
    $name = $_POST["name"] ;    
    $auth = $_POST["auth"] ; //auth[1][0]、[1][1]... 至 [5][3]    [0][]為系統開發者，不作前台權限變更

    $field_keyname = Array('exam-answer','exam-excel','ai-analytics','system-setting');


    //04.將收到的值寫入資料庫
    for( $i=1 ; $i<=count($name) ; $i++ ){ 


        //更新name欄位
        $sql = "UPDATE `system-account-group` SET name = '{$name[$i]}' WHERE `no` = '{$no[$i]}'" ; 
        mysqli_query($link, $sql) ;

        /////test////////
        //echo "<br/>" ;
        //echo "no[".$i."]=".$no[$i]."<br/>" ;
        //echo "name[".$i."]=".$name[$i]."<br/>" ;
        //echo "<br/>" ;
        ////////////////


        //更新每個服務單元權限
        for( $j=0 ; $j<=3 ; $j++ ){  

            
            if ( !isset($auth[$i][$j]) ) $auth[$i][$j] = "false" ;  //沒有勾選，設定為 false
            else                         $auth[$i][$j] = "true" ;   //有勾選，設定為true

            ///////test////////
            //echo "field_keyname[".$j."] = ".$field_keyname[$j]."<br/>" ;
            //echo "auth[".$i."][".$j."] = ".$auth[$i][$j]."<br/>" ;
            //echo "<br/>" ;
            //////////////////

            $sql = "UPDATE `system-account-group` SET `{$field_keyname[$j]}` = '{$auth[$i][$j]}' WHERE `no` = '{$no[$i]}'" ;
            mysqli_query($link, $sql) ;

        }


    }


    //05.緩衝時間
    sleep(1);


    //06.寫入成功 網頁跳轉
    header("Location:account_group_index.php");


?>
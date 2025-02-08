<?php
    /* 登錄後台service */
    if( !isset($_SESSION) ) session_start();


    //如果是 POST 才會執行
    if ($_SERVER['REQUEST_METHOD'] == "POST") { 

        //01.固定欄位紀錄
        header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8
        $login_id = $_POST["myckeck_backend_id"];
        $login_pw = $_POST["myckeck_backend_pw"];


        //02.資料庫設定檔及網域套件載入
        require_once( $_SERVER['DOCUMENT_ROOT'].'/conn/conn_user_php7.php'); 
        $link = create_connection() ;
        require_once($_SERVER['DOCUMENT_ROOT']."/module/LDAP/LDAP.php");

        //03.比對資料表中的帳號
        $sql = "SELECT * FROM `system-account` WHERE `id` = '{$login_id}' AND `acc` = 'true' " ;
        $result = mysqli_query($link, $sql) ;
        $num = mysqli_num_rows($result);
        if ( $num == 0 ) $login_result = false ;        
        else if ( $num >= 1 ){
            while( $row = mysqli_fetch_assoc($result) )
                $rows[] = $row;    
             
            switch( $rows[0]["type"] ){

                //系統帳號(本地帳號)
                case "local":

                    $login_pw = strtoupper(hash('SHA256', $login_pw )) ; //SHA256加密

                    if ( $login_pw == $rows[0]["pw"] ){ 
                        $login_result = true ;

                        $login_auth = $rows[0]["auth"] ; //取得帳號所屬的群組權限
                        $_SESSION['careus_personality_id']   = $login_id   ;  //登錄帳號
                        $_SESSION['careus_personality_auth'] = $login_auth ;  //登錄帳號權限


                        $login_count = $rows[0]["login_count"] + 1 ; //登入次數累加
                        $login_time = date("Y-m-d H:i:s");   //登入時間

                        $sql = "UPDATE `system-account` SET `login_count` = '{$login_count}' ,
                                                            `login_time`  = '{$login_time}'
                                                        WHERE `id` = '{$login_id}'" ;
                        mysqli_query($link, $sql) ;
                    }
                    else if ( $login_pw != $rows[0]["pw"] )
                            $login_result = false ;  
                    break;

                //員工帳號(雲端帳號)
                case "cloud":
                    $LDAP_result = LDAP($login_id,$login_pw) ;

                    if ( $LDAP_result["conn_result"] == true && $LDAP_result["bind_result"] == true ){
                        $login_result = true ;

                        $login_auth = $rows[0]["auth"] ; //取得帳號所屬的群組權限
                        $_SESSION['careus_personality_id']   = $login_id   ;  //登錄帳號
                        $_SESSION['careus_personality_auth'] = $login_auth ;  //登錄帳號權限


                        $login_count = $rows[0]["login_count"] + 1 ; //登入次數累加
                        date_default_timezone_set('Asia/Taipei'); 
                        $login_time = date("Y-m-d H:i:s");   //登入時間

                        $sql = "UPDATE `system-account` SET `login_count` = '{$login_count}' ,
                                                            `login_time`  = '{$login_time}'
                                                        WHERE `id` = '{$login_id}'" ;
                        mysqli_query($link, $sql) ;
                    }
                    else if ( $LDAP_result["conn_result"] == false || $LDAP_result["bind_result"] == false )
                        $login_result = false ;


                    break;

                default: break;
            }
            
        }

        //04.緩衝時間
        sleep(1);                

        //05.回傳資料
        echo json_encode(array(
            'login_result' => $login_result,

        ));

    }
    else
        header("Location:http://www.google.com");

?>
<?php
    function myauth($unit){

        //01.��Ʈw�s�u
        require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php"); 
        $link = create_connection() ;


        //02.�T�{session���L��
        if ( !isset($_SESSION['careus_personality_id']) || !isset($_SESSION['careus_personality_auth'])){
            header("Location:/./");
            exit();
        }
        else{   
           $my_id = $_SESSION['careus_personality_id'] ; 
           $my_auth = $_SESSION['careus_personality_auth'] ; 
        }
        /////////// test ///////////
        //echo "<br/><br/>" ;
        //echo "my_id = " . $my_id ."<br/>";
        //echo "my_auth = " . $my_auth ."<br/>";
        //echo "<br/><br/>"; 
        ///////////////////////////


        //03.���o�b�����s���v��
        $sql_auth = "SELECT * FROM `system-account-group` WHERE `auth` = '{$my_auth}'";
        $result_auth = mysqli_query($link, $sql_auth) ; 
        while( $row_auth = mysqli_fetch_assoc($result_auth) ) 
            $rows_auth[] = $row_auth;  

        return $rows_auth[0]["exam-answer"];


        //if ( $rows_auth[0]["exam-answer"] != "true"){
        //    header("Location:/./");
        //    exit();
        //}

        ///////////// test /////////// 
        ////echo $rows_auth[0]["exam-answer"];
        /////////////////////////////

    }



?>    




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
    $auth = myauth('system-setting');
    if ( $auth != "true"){
        header("Location:/./");
        exit();
    }


    //03.取得群組資料
    $sql = "SELECT * FROM `system-account-group` order by `no` ASC LIMIT 0,10" ;
    $result = mysqli_query($link, $sql) ;

?>


<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>喜憨兒基金會 人才登錄培訓分析平台－帳號權限維護（後台管理）</title>

        <?php include($_SERVER['DOCUMENT_ROOT'].'/include/toolkit.php'); ?>
        <link rel="stylesheet" href="/./css/backend/bread.css?<?php echo date("is"); ?>" />
        <link rel="stylesheet" href="/./css/backend/system/account/account_group_index.css?<?php echo date("is"); ?>" />

    </head>

    <body>

        <div id="caption">
            <div id="caption_header">喜憨兒基金會 人才登錄培訓分析平台－帳號權限維護</div>
        </div>

        <div id="content">

            <div id="bread">
                <a href="/./backend/backend_panel.php" target="_parent">人才登錄分析平台</a> > 
                <a href="/./backend/system/account/account_index.php" target="_parent">帳號維護</a> >
                權限群組設定
            </div>

            <br/>

            <div style="float:right;padding:15px 10px 0 0"><div class='btn' onclick='form_run();'>儲存修改</div></div>

            <br/><br/><br/><br/><br/>

            <div id="result">
                <form method="post" action="" name="myForm1">

                    <div class='personality_title'>權限群組名稱</div>
                    <div class='personality_title2'>
                        <div class='personality_title2-1'>功能權限設定</div>
                        <div class='personality_title2-2-title'>管理作答卷</div>
                        <div class='personality_title2-2-title'>分析作答內容</div>
                        <div class='personality_title2-2-title'>系統設計</div>

                        <div class='personality_title2-2-sub'>查詢作答卷</div>
                        <div class='personality_title2-2-sub'>匯入作答卷</div>

                        <div class='personality_title2-2-sub' style="width:198px;">AI分析</div>

                        <div class='personality_title2-2-sub' style="width:198px;">各項設定</div>
                    </div>

                    <!-- 開始顯示結果 -->
                    <?php

                    //依照可以設定的權限，制定指標名稱
                    $field_keyname = Array('exam-answer',
                                           'exam-excel',
                                           'ai-analytics',
                                           'system-setting');
                    $i = 0 ;
                    while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC) ){

                        if( $row['no'] != 0 ){

                            echo "<div class='personality_txt'><input type='text' name='name[".$i."]' id='name[".$i."]' value='".$row['name']."'></div>" ;

                            for( $j=0 ; $j<count($field_keyname) ; $j++ ){ 
                
                                if( $j<=1 ){
                                    switch($row[ $field_keyname[$j] ]){
                                        case "true":  echo "<div class='personality_txt2'><input type='checkbox' name='auth[".$row['no']."][".$j."]' value='true' checked/></div>"; break;
                                        case "false": 
                                        default:      echo "<div class='personality_txt2'><input type='checkbox' name='auth[".$row['no']."][".$j."]' value=''            /></div>"; break;                                                   
                                    }
                                }        
                                else if( $j>=2 ){
                                    switch($row[ $field_keyname[$j] ]){
                                        case "true":  echo "<div class='personality_txt2' style='width:198px;'><input type='checkbox' name='auth[".$row['no']."][".$j."]' value='true' checked/></div>"; break;
                                        case "false": 
                                        default:      echo "<div class='personality_txt2' style='width:198px;'><input type='checkbox' name='auth[".$row['no']."][".$j."]' value=''            /></div>"; break;                                                   
                                    }
                                }

                            }
                        echo "<input type='hidden' name='no[".$i."]' value = '".$row['no']."' />"; //隱藏表單 id 
                        }

                        $i++ ;
                
                    }

                    ?>
                    <!-- 結束顯示結果 -->

                </form>
            </div>

        </div>

        <br/>

        <script src="/./javascript/backend/system/account/account_group_index.js?<?php echo date("is"); ?>"></script>

    </body>


</html>

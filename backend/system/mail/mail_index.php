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


    //03.從資料庫取得資料
    $sql  = "SELECT * FROM `system-mail`";
    $result = mysqli_query($link, $sql) ;
    while( $row = mysqli_fetch_assoc($result) )
        $rows[] = $row;

?>


<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>喜憨兒基金會 人才登錄培訓分析平台－郵件設定（後台管理）</title>

        <?php include($_SERVER['DOCUMENT_ROOT'].'/include/toolkit.php'); ?>
        <link rel="stylesheet" href="/./css/backend/bread.css?<?php echo date("is"); ?>" />
        <link rel="stylesheet" href="/./css/backend/system/mail/mail_index.css?<?php echo date("is"); ?>" />

    </head>

    <body>

        <div id="caption">
            <div id="caption_header">喜憨兒基金會 人才登錄培訓分析平台－郵件設定</div>
        </div>

        <div id="content">

            <div id="bread">
                <a href="/./backend/backend_panel.php" target="_parent">人才登錄分析平台</a> > 
                郵件設定 
            </div>

            <br/><br/>

            <div id="mail_result">

                <div id="button"><div style="float:right;"><div class='btn' onclick='mail_updata_edit();'>儲存更新</div></div></div>

                <div id="mail_title_all">郵件寄送設定</div>
                <div class="mail_title">信箱帳號</div>
                <div class="mail_txt">
                    <input id="mail_main_id" name="mail_main_id" type="text" value="<?php echo $rows[0]["mail_address"] ?>" />
                </div>

                <div class="mail_title">信箱密碼</div>
                <div class="mail_txt">
                    <input id="mail_main_pw" name="mail_main_pw" type="password" value="<?php echo $rows[0]["mail_password"] ?>" />
                    <label for="mail_main_pw"><img src="../../../img/eyes_close.png" id="eyes" onclick=password_eyes(id) /></label>
                </div>

                <div class="space">&nbsp;</div>

                <div id="mail_title_all">郵件副本設定</div>
                <div class="mail_directions">可設定多個郵件信箱，請以「,」分隔不同郵件信箱</div>
                <div class="mail_title">寄送副本信箱</div>
                <div class="mail_txt">
                    <input id="mail_cc" name="mail_cc" type="text" value="<?php echo $rows[1]["mail_address"] ?>" />
                </div>

            </div>

            <!--<div class="space">&nbsp;</div>-->




        </div>

        <br/>

        <script src="/./javascript/backend/system/mail/mail_index.js?<?php echo date("is"); ?>"></script>

    </body>


</html>

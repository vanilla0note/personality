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


    //03.取得要編輯部門
    $dep_id = $_GET["id"];


    //04.從資料庫取得要部門的資料
    $sql = "SELECT * FROM `system-department` WHERE `dep_id` = '{$dep_id}'";
    $result = mysqli_query($link, $sql) ;
    while( $row = mysqli_fetch_assoc($result) )
        $dep_rows[] = $row;

    $sql = "SELECT * FROM `system-department-group` WHERE `dep_id` = '{$dep_id}'" ;
    $result= mysqli_query($link, $sql) ;
    $group_amount = mysqli_num_rows($result) ; //組別數量

    $sql = "SELECT * FROM `system-department-job` WHERE `dep_id` = '{$dep_id}'" ;
    $result= mysqli_query($link, $sql) ;
    $job_amount = mysqli_num_rows($result) ; //職位數量

?>


<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>喜憨兒基金會 人才登錄培訓分析平台－部門維護（後台管理）</title>

        <?php include($_SERVER['DOCUMENT_ROOT'].'/include/toolkit.php'); ?>
        <link rel="stylesheet" href="/./css/backend/bread.css?<?php echo date("is"); ?>" />
        <link rel="stylesheet" href="/./css/backend/system/department/department_edit-confirm.css?<?php echo date("is"); ?>" />

        <script>
        $( function() {
          $( "#tabs" ).tabs();
        } );
        </script>

    </head>

    <body>

        <div id="caption">
            <div id="caption_header">喜憨兒基金會 人才登錄培訓分析平台－部門維護</div>
        </div>

        <div id="content">

            <div id="bread">
                <a href="/./backend/backend_panel.php" target="_parent">人才登錄分析平台</a> >
                <a href="/./backend/system/department/department_index.php" target="_parent">部門維護</a> >
                <?php echo $dep_rows[0]["dep_name"] ?>
            </div>

            <br />


            <div id="tabs">

                <form method="post" action="" name="myForm1">

                    <ul>
                        <li><a href="#tabs-1">部門</a></li>
                        <li><a href="#tabs-2">組別</a></li>
                        <li><a href="#tabs-3">職位</a></li>
                        <li><a href="#tabs-4">郵件設定</a></li>
                    </ul>

                    <div id="tabs-1" style="height:115px;">
                        <div class="personality_main_title">部門基本設定</div>
                        <div class="personality_main_content">
                            <div class="personality_sub_title1">部門名稱</div>
                            <div class="personality_sub_content2"><input type='text' name='dep_name' value='<?php echo $dep_rows[0]["dep_name"] ?>' /></div>
                        </div>
                        <input type='hidden' id="dep_id" name='dep_id' value='<?php echo $dep_rows[0]["dep_id"] ?>' />
                    </div>

                    <div id="tabs-2" style="height:<?php echo (55 * $group_amount + 115) . "px" ?>">

                        <div class="personality_main_title">組別設定<div class='btn_create' onclick='group_create();'>新建組別</div></div>
                        <div class="personality_main_content">

                            <div class="personality_sub_title2">組別名稱</div>
                            <div class="personality_sub_title1">啟用狀態</div>

                            <?php
                            //顯示部門所屬的組別
                            $i = 0;
                            $sql = "SELECT * FROM `system-department-group` WHERE `dep_id` = '{$dep_id}'";
                            $result = mysqli_query($link, $sql);
                            while ($group_rows = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

                                //組別名稱
                                echo "<div class='personality_sub_content2'><input type='text' name='group_name[" . $i . "]' value='" . $group_rows['group_name'] . "'></div>";

                                //啟用狀態
                                switch ($group_rows['acc']) {
                                    case "true":
                                        echo "<div class='personality_sub_content1'><input type='checkbox' name='group_acc[" . $i . "]' value='true' checked /></div>";
                                        break;

                                    default:
                                    case "false":
                                        echo "<div class='personality_sub_content1'><input type='checkbox' name='group_acc[" . $i . "]' value='false' /></div>";
                                        break;
                                }

                                echo "<input type='hidden' name='group_id[" . $i . "]' value='" . $group_rows['group_id'] . "'>";

                                $i++;
                            }

                            ?>

                        </div>
                        
                    </div>

                    <div id="tabs-3" style="height:<?php echo (55 * $job_amount + 115) . "px" ?>">

                        <div class="personality_main_title">職位設定<div class='btn_create' onclick='job_create();'>新建職位</div></div>
                        <div class="personality_main_content">

                            <div class="personality_sub_title2">職位名稱</div>
                            <div class="personality_sub_title1">啟用狀態</div>

                            <?php
                            //顯示部門所屬的職位
                            $i = 0;
                            $sql = "SELECT * FROM `system-department-job` WHERE `dep_id` = '{$dep_id}'";
                            $result = mysqli_query($link, $sql);
                            while ($job_rows = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

                                //職位名稱
                                echo "<div class='personality_sub_content2'><input type='text' name='job_name[" . $i . "]' value='" . $job_rows['job_name'] . "'></div>";

                                //啟用狀態
                                switch ($job_rows['acc']) {
                                    case "true":
                                        echo "<div class='personality_sub_content1'><input type='checkbox' name='job_acc[" . $i . "]' value='true' checked /></div>";
                                        break;

                                    default:
                                    case "false":
                                        echo "<div class='personality_sub_content1'><input type='checkbox' name='job_acc[" . $i . "]' value='false' /></div>";
                                        break;
                                }

                                echo "<input type='hidden' name='job_id[" . $i . "]' value='" . $job_rows['job_id'] . "'>";

                                $i++;
                            }
                            ?>

                        </div>

                    </div>

                    <div id="tabs-4" style="height:180px;">

                        <div class="personality_main_title">寄送測驗結果 郵件設定</div>
                        <div class="personality_main_content">
                            <div class="personality_sub_title1">第 1 組信箱</div>
                            <div class="personality_sub_content2"><input type='text' name='report_mail_1' value='<?php echo $dep_rows[0]["report_mail_1"] ?>' /></div>
                            <div class="personality_sub_title1">第 2 組信箱</div>
                            <div class="personality_sub_content2"><input type='text' name='report_mail_2' value='<?php echo $dep_rows[0]["report_mail_2"] ?>' /></div>
                        </div>
                        
                    </div>

                </form>

            </div>


            <div id="button">
                <div style="float:right;padding:15px 10px 0 0"><div class='btn' onclick='dep_edit_return();'>關閉離開</div></div>
                <div style="float:right;padding:15px 10px 0 0"><div class='btn' onclick='form_run();'>儲存更新</div></div>
            </div>

        </div>

        <br/>

        <script src="/./javascript/backend/system/department/department_edit-confirm.js?<?php echo date("is"); ?>"></script>

    </body>


</html>

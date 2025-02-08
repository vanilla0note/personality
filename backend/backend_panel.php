<?php
    if( !isset($_SESSION) ) session_start(); 

    ////// test //////
    //session_destroy();  //刪除全部session
    /////////////////

    //01.資料庫連線
    require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php"); 
    $link = create_connection() ; 


    //02.取得登入權限
    if ( !isset($_SESSION['careus_personality_id']) || !isset($_SESSION['careus_personality_auth'])){
        header("Location:/./");
        exit();
    }
    else{   
       $my_id   = $_SESSION['careus_personality_id'] ; 
       $my_auth = $_SESSION['careus_personality_auth'] ; 
    }
    /////////// test ///////////
    //echo "<br/><br/>" ;
    //echo "my_id   = " . $my_id ."<br/>";
    //echo "my_auth = " . $my_auth ."<br/>";
    //echo "<br/><br/>"; 
    ///////////////////////////

    //03.依照權限進行設定
    $sql_auth = "SELECT * FROM `system-account-group` WHERE `auth` = '{$my_auth}'";
    $result_auth = mysqli_query($link, $sql_auth) ; 
    while( $row_auth = mysqli_fetch_assoc($result_auth) ) 
        $rows_auth[] = $row_auth;   
    $account_auth = Array(
        "exam-answer"    => $rows_auth[0]["exam-answer"],
        "mood-answer"    => $rows_auth[0]["mood-answer"],
        "ai-analytics"   => $rows_auth[0]["ai-analytics"],
        "system-setting" => $rows_auth[0]["system-setting"]
    );
    /////////// test /////////// 
    //print_r(array_keys($account_auth));
    //echo $account_auth["exam-answer"];
    ///////////////////////////


    //04.依照權限計算content高度
    $height_num = 0 ;
    if ($account_auth["exam-answer"] == "true" || $account_auth["mood-answer"] == "true")
        $height_num++ ;
    if ($account_auth["ai-analytics"] == "true")
        $height_num++ ;
    if ($account_auth["system-setting"] == "true")
        $height_num++ ;


    //05.取得版本資訊
    $sql_ver = "SELECT * FROM `ver_record` ORDER BY `count` DESC";
    $result_ver = mysqli_query($link, $sql_ver) ; 
    while( $row_ver = mysqli_fetch_assoc($result_ver) ) 
        $rows_ver[] = $row_ver; 
    $year = substr($rows_ver[0]["publish_date"],0,4) ;
    $ver = $rows_ver[0]["ver"] ;


?>

<!DOCTYPE html>
<html>
    <head>

        <meta charset="UTF-8">
        <title>喜憨兒基金會 人才培訓分析平台（管理後台）</title>

        <?php include('../include/toolkit.php'); ?>
        <link rel="stylesheet" href="../css/backend/backend_panel.css?<?php echo date("is"); ?>">

    </head>

    <body>


        <div id="caption">

            <div id="caption_header">喜憨兒基金會 人才登錄分析平台（管理後台）</div>
            <div id="caption_ver"><?php echo $year ?> © 數位發展組(<?php echo $ver ?>)</div>
            <div id="caption_menu">
                <div class="caption_menu-sub"  onmouseover='switchMenu(this, "SubMenu1", "MouseOver")'  onmouseout='hideMenu()' >
                    <tt>版本歷程</tt>
                    <ul id='SubMenu1' class='sub-menu' style='display:none;'>
                        <li onclick='update();'><font color='#333'>更新紀錄</font></a></li>
                        <li onclick='ver();'><font color='#333'>關於系統</font></li>
                    </ul>
                </div>
                <div class="caption_menu-sub">
                    <tt onclick='logout();'>登出系統</tt>
                </div>
            </div>

        </div>


        <div id="content" style="height:<?php echo (215*$height_num) ."px" ; ?>">

            <!--作答管理-->
            <?php
            if ( $account_auth["exam-answer"] == "true" || $account_auth["mood-answer"] == "true" ){
            
                echo "<div class='menu'>";

                    //標題
                    echo "<div class='menu-title'>";
                        echo "<div class='menu-title-1'><img src='../img/backend/menu01.png'></div>";
                        echo "<div class='menu-title-2'> > 作答管理 </div>";
                    echo "</div>";

                    //內容
                    echo "<div class='menu-content'>";

                        if ( $account_auth["exam-answer"] == "true" )
                            echo "<a href='exam/answer/answer_index.php' target='_parent'><div class='btn'>人格特質</div></a>";
                        if ( $account_auth["mood-answer"] == "true" )
                            echo "<a href='mood/answer/answer_index.php' target='_parent'><div class='btn'>員工滿意度</div></a>";

                    echo "</div>";
                

                echo "</div>";                       

            }           
            ?>
            <!-------------->


            <!--結果分析-->
            <?php
            if ( $account_auth["ai-analytics"] == "true" ){

                /**** 兩個單元之間的分隔線 ****/
                if ( $account_auth["exam-answer"] == "true" || $account_auth["exam-excel"] == "true" )
                    echo "<hr/>";
                /*////////////////////////////*/

                
                echo "<div class='menu'>";

                    //標題
                    echo "<div class='menu-title'>";
                        echo "<div class='menu-title-1'><img src='../img/backend/menu02.png'></div>";
                        echo "<div class='menu-title-2'> > 結果分析 </div>";
                    echo "</div>";

                    //內容
                    echo "<div class='menu-content'>";
                        echo "<div class='btn_close'>錄取率分析</div>";
                        echo "<div class='btn_close'>任職期間分析</div>";
                    echo "</div>";

                echo "</div>";
                        
            }
            ?>
            <!-------------->


            <!--系統設定-->
            <?php
            if ( $account_auth["system-setting"] == "true" ){

                /**** 兩個單元之間的分隔線 ****/
                if ( $account_auth["exam-answer"] == "true" || $account_auth["exam-excel"] == "true" ||
                     $account_auth["ai-analytics"] == "true" )
                    echo "<hr/>";
                /*////////////////////////////*/


                echo "<div class='menu'>";

                    //標題
                    echo "<div class='menu-title'>";
                        echo "<div class='menu-title-1'><img src='../img/backend/menu03.png'></div>";
                        echo "<div class='menu-title-2'> > 系統設定 </div>";
                    echo "</div>";

                    //內容
                    echo "<div class='menu-content'>";
                        echo "<a href='system/account/account_index.php' target='_parent'><div class='btn'>帳號維護</div></a>";
                        echo "<a href='system/department/department_index.php' target='_parent'><div class='btn'>部門維護</div></a>";
                        echo "<a href='system/mail/mail_index.php' target='_parent'><div class='btn'>郵件設定</div></a>";
                        echo "<a href='system/paper/paper_index.php' target='_parent'><div class='btn'>題目維護</div></a>";
                        echo "<a href='system/schedule/schedule_index.php' target='_parent'><div class='btn'>排程管理</div></a>";
                    echo "</div>";

                echo "</div>";

            }
            ?>
            <!-------------->
            
        </div>

        <script src="../javascript/backend/backend_panel.js?<?php echo date("is"); ?>"></script>

    </body>


</html>
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


    //03.取得要編輯帳號
    $account_no = $_GET["id"];
    //如果是管理員帳號編輯，則強制離開
    if ( $account_no == 0 ){
        header("Location:/./");
        exit();
    }


    //04.從資料庫取得要帳號的資料
    $sql  = "SELECT * FROM `system-account` WHERE `no` = '{$account_no}'";
    $result = mysqli_query($link, $sql) ;
    while( $row = mysqli_fetch_assoc($result) )
        $rows[] = $row;

    //將目前擁有權限，依照 "," 切分為陣列    
    $colony_read_content  = explode( ",", $rows[0]["colony_read_content"] ); 


    //05.頁面基本設定資料
    //權限標題
    $colony_value = Array("ALL","AREA","DEP");
    $colony_text  = Array("最大權限","限定區域","限定部門");

    //帳號權限(區域)，要放入colony_read_content的內容
    $area_value = Array("area-A","area-H","area-J","area-D","area-E");
    $area_text  = Array("台北地區","桃園地區","新竹地區","台南地區","高雄地區");

    //帳號權限(部門)，要放入colony_read_content的內容
    $dep_value = Array();
    $dep_text  = Array();
    
    $sql_temp = "SELECT * FROM `system-department` WHERE `acc` = 'true' ORDER BY `no` ASC" ;
    $result_temp = mysqli_query($link, $sql_temp) ;
    while( $row_temp = mysqli_fetch_array($result_temp,MYSQLI_ASSOC) ){
        array_push ( $dep_value , $row_temp['dep_id']   ) ;
        array_push ( $dep_text  , $row_temp['dep_name'] );
    }  
?>


<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>喜憨兒基金會 人才登錄培訓分析平台－帳號權限維護（後台管理）</title>

        <?php include($_SERVER['DOCUMENT_ROOT'].'/include/toolkit.php'); ?>
        <link rel="stylesheet" href="/./css/backend/bread.css?<?php echo date("is"); ?>" />
        <link rel="stylesheet" href="/./css/backend/system/account/account_edit-confirm.css?<?php echo date("is"); ?>" />

    </head>

    <body>

        <div id="caption">
            <div id="caption_header">喜憨兒基金會 人才登錄培訓分析平台－帳號權限維護</div>
        </div>

        <div id="content">

            <div id="bread">
                <a href="/./backend/backend_panel.php" target="_parent">人才登錄分析平台</a> > 
                <a href="/./backend/system/account/account_index.php" target="_parent">帳號權限維護</a> >
                <?php echo $rows[0]["id"] ?>
            </div>

            <br/>

            <div id="result">

                <form method="post" action="" name="myForm1">                    
                    <div class="personality_main_title">帳號基本設定</div>
                    <div class="personality_sub_title1">帳號類型</div>
                    <div class="personality_sub_content1">
                    <?php 
                        switch ($rows[0]["type"] ){
                            case "local": echo "<tt style='color:#8BB381;font-weight:600;'>系統帳號</tt>"; 
                                          echo "<input type='hidden' id='change_password' value='allow' />"; break;
                            case "cloud": echo "<tt style='color:#9F000F;font-weight:600;'>員工帳號</tt>"; 
                                          echo "<input type='hidden' id='change_password' value='not_allowed' />"; break;
                            default:      echo "<tt style='color:#000000;font-weight:600;'>無法辨識</tt>"; 
                                          echo "<input type='hidden' id='change_password' value='not_allowed' />"; break;
                        }                        
                    ?>                    
                    </div>
                    <div class="personality_sub_title1">設定密碼</div>
                    <div class="personality_sub_content1"><input type='text' id='pw' name='pw' style='width:380px;' value='' placeholder=" 如不變更請留空" /></div>

                    <div class="personality_sub_title2" style="height:120px;">權限群組<br/><tt>（後台首頁顯示的功能表）</tt></div>
                    <div class="personality_sub_content2" style="width:421px; height:135px;line-height:125px;padding-left:30px;text-align:left;">
                        <select id='auth' name='auth' style='width:300px;'>
                        <?php
                            $sql_temp = "SELECT * FROM `system-account-group` order by `no` ASC " ;
                            $result_temp = mysqli_query($link, $sql_temp) ;

                            while( $row_temp = mysqli_fetch_array($result_temp,MYSQL_ASSOC) ){
                                if ( $row_temp['no'] != '0' ){
                                    if ( $rows[0]["auth"] == $row_temp["auth"])
                                        echo "<option value='".$row_temp["auth"]."' selected>".$row_temp["name"]."</option>";
                                    else
                                        echo "<option value='".$row_temp["auth"]."'         >".$row_temp["name"]."</option>";
                                }
                            }
                        ?>
                        </select>
                    </div>

                    <br/><br/><br/><br/><br/>

                    <div class="personality_main_title">權限設定</div>
                    <div class="personality_sub_title2">
                        設定「查詢作答卷」權限<br/><tt>（區域 > 部門）<br/>按住 Ctrl +滑鼠點選 可多選</tt>                                    
                    </div>
                    <div class="personality_sub_content2">
                        <div style="width:170px;height:250px;line-height:50px;float:left;">
                            <select id='colony_read_title' name='colony_read_title' style='width:160px;' onchange="colony_read(this.options[this.options.selectedIndex].value)">
                            <?php
                                for ($i=0 ; $i<=2 ; $i++){
                                    if( $rows[0]["colony_read_title"] == $colony_value[$i]) 
                                        echo "<option value='".$colony_value[$i]."' selected>".$colony_text[$i]."</option>" ;                                        
                                    else 
                                        echo "<option value='".$colony_value[$i]."' >".$colony_text[$i]."</option>" ;                                    
                                }

                            ?>
                            </select>
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <div style="width:190px;height:250px;line-height:40px;float:left;padding:5px 0 0 10px;"> 
                            <?php
                                //頁面載入時的顯示
                                switch( $rows[0]["colony_read_title"] ){
                                    case "ALL":     //當載入帳號頁面時，是選擇在ALL，顯示 colony_read_content 的欄位
                                        echo "<select id='colony_read_content' name='colony_read_content[]' style='width:200px;height:180px;' multiple>";
                                            echo "<option value='ALL' selected='selected'>全部地區</option>"; 
                                        echo "</select>";

                                        break;

                                    case "AREA":    //當載入帳號頁面時，是選擇在AREA，顯示 colony_read_content 的欄位
                                        echo "<select id='colony_read_content' name='colony_read_content[]' style='width:200px;height:180px;' multiple>";
                                            $count = 0 ; //比對計數
                                            for ( $i=0 ; $i<=4 ; $i++ ){
                                                for ( $j=0 ; $j<count($colony_read_content) ; $j++ )
                                                    if ( $area_value[$i] == $colony_read_content[$j] ) $count++ ;   //比對相同就計數
                                                if  ($count > 0 ) echo "<option value='".$area_value[$i]."' selected='selected'>".$area_text[$i]."</option>";
                                                else              echo "<option value='".$area_value[$i]."'                    >".$area_text[$i]."</option>";
                                                $count = 0 ; //清空計數                                                                                      
                                            }
                                        echo "</select>";
                                        break;
                            
                                    case "DEP":
                                        echo "<select id='colony_read_content' name='colony_read_content[]' style='width:200px;height:180px;' multiple>";
                                            $count = 0 ; //比對計數
                                            for ( $i=0 ; $i<count($dep_value) ; $i++ ){
                                                for ( $j=0 ; $j<count($colony_read_content) ; $j++ )
                                                    if ( $dep_value[$i] == $colony_read_content[$j] ) $count++ ;   //比對相同就計數
                                                if  ($count > 0 ) echo "<option value='".$dep_value[$i]."' selected='selected'>".$dep_text[$i]."</option>";
                                                else              echo "<option value='".$dep_value[$i]."'                    >".$dep_text[$i]."</option>";
                                                $count = 0 ; //清空計數                                                                                      
                                            }
                                        echo "</select>";
                                        break;
                            
                                    default:   //載入帳號頁面發生例外，不顯示 colony_read_content
                                        echo "<select id='colony_read_content' name='colony_read_content[]' style='width:200px;height:180px;display:none;' multiple></select>";
                                        break;
                                }
                            ?>
                        </div>
                    
                    </div>


                    <input type='hidden' id="dep_id" name='dep_id' value='<?php echo $rows[0]["id"] ?>' />
                </form>

            </div>

            <div id="button">
                <div style="float:right;padding:15px 10px 0 0"><div class='btn' onclick='account_edit_return();'>關閉離開</div></div>
                <div style="float:right;padding:15px 10px 0 0"><div class='btn' onclick='form_run();'>儲存更新</div></div>
            </div>

        </div>

        <br/>

        <script src="/./javascript/backend/system/account/account_edit-confirm.js?<?php echo date("is"); ?>"></script>

    </body>


</html>

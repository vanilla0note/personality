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


    //03.接收看看有沒有分頁數值和查詢語法，來決定輸出
    /* 第一次進來本頁$_SESSION['personality_system_account_sql_query']一定沒有；
       重新進入本頁，$_SESSION['personality_system_account_sql_query']可能有sql，但$_GET["page"]也不會有值，所以可以確定是重新進入，就不能再帶前次搜尋結果 */

    if ( isset($_GET["page"]) && $_SESSION['personality_system_account_sql_query'] != "" ){
        echo("<script>console.log('SESSION有sql值，重覆進入本頁');</script>");

        ////03-01.以下將session值取出，用於重複進入本頁的輸出
        $sql       = $_SESSION['personality_system_account_sql_query'] ; //本次查詢的語法（不帶頁數）
        $data_nums = $_SESSION['personality_system_account_data_nums'] ; //符合本次查詢資料的總筆數
        $all_pages = $_SESSION['personality_system_account_all_pages'] ; //符合本次查詢資料的總頁數
        $filter_content = explode( "@" , $_SESSION['personality_system_account_filter_content'] ) ; //本次查詢的篩選器條件
        $page = $_GET["page"] ; //目前所在的頁數
        $_SESSION['personality_system_account_get_page'] = $page ;//目前所在的頁數

        //03-02.分頁頁數組合
        //計算前後要顯示的頁數
        $pages_box = 0 ;
        for ( $i = 1; $i <= $all_pages ; $i++ ) 
            if ( $page - 4 < $i && $i < $page + 4 ) $pages_box++  ;
                
        //組合語法 + 頁數
        $sql = $sql." LIMIT ".($page*10-10).",10";

        $result = mysqli_query($link, $sql) ;
        $num = mysqli_num_rows($result) ; //本頁面要顯示的筆數

        //03-03.搜尋器帶入前一次搜尋值
        //前面session已經執行

        //////////// test////////
        //echo "<br/><br/><br/><br/><br/><br/>";
        //echo "sql=".json_encode($sql)."<br/>" ;
        //echo "all_pages=".$all_pages."<br/>" ;
        //echo "page=".$page."<br/>" ;
        //echo "filter_content[0]=".$filter_content[0]."<br/>" ;
        //echo "filter_content[1]=".$filter_content[1]."<br/>" ;
        /////////////////////////

    }
    else{
        echo("<script>console.log('page沒有值及SESSION沒有sql值，第一次或重新進入本頁');</script>");

        //03-01. 第一次進入，搜尋全部的資料
        $sql = "SELECT * FROM `system-account` order by `no` ASC" ;
        $result = mysqli_query($link, $sql) ;
        $data_nums = mysqli_num_rows($result) ; //第一次進入 資料總筆數

        $limit = 10; //每頁最多顯示資料數量
        $all_pages = ceil($data_nums/$limit); //第一次進入 資料總頁數 取得不小於值的下一個整數
            if ( $all_pages == 0 ) $all_pages = 1 ;  //等於 0 代表沒有資料，直接等於1，點選末頁才不會出錯

        //第一次進入本頁，搜尋器給予第一次的預設值
        $filter_content[0] = "" ;
        $filter_content[1] = "ALL" ;
        $filter_content[2] = "ALL" ;
        $filter_content[3] = "ALL" ;


        //03-02.第一次進入本頁，session給予初始值
        $_SESSION['personality_system_account_sql_query'] = $sql ;       //第一次查詢的語法
        $_SESSION['personality_system_account_data_nums'] = $data_nums ;       //第一次查詢資料的總筆數
        $_SESSION['personality_system_account_all_pages'] = $all_pages ;       //第一次查詢資料的總頁數
        $_SESSION['personality_system_account_filter_content'] = $filter_content[0]."@". 
                                                                 $filter_content[1]."@". 
                                                                 $filter_content[2]."@". 
                                                                 $filter_content[3] ;  //本次查詢的篩選器條件
        
        //////////// test////////
        //echo "<br/><br/><br/><br/><br/><br/>";
        //echo "sql=".json_encode($sql)."<br/>" ;
        //echo "data_nums=".$data_nums."<br/>" ;
        //echo "all_pages=".$all_pages."<br/>" ;
        //echo "filter_content[0]=".$filter_content[0]."<br/>" ;
        //echo "filter_content[1]=".$filter_content[1]."<br/>" ;
        //echo "filter_content[2]=".$filter_content[2]."<br/>" ;
        //echo "filter_content[3]=".$filter_content[3]."<br/>" ;
        /////////////////////////


        //03-03.網頁跳轉為page=1，往程式帶入有搜尋條件的模式
        header("Location: account_index.php?page=1");

    }

?>


<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>喜憨兒基金會 人才登錄培訓分析平台－帳號權限維護（後台管理）</title>

        <?php include($_SERVER['DOCUMENT_ROOT'].'/include/toolkit.php'); ?>
        <link rel="stylesheet" href="/./css/backend/bread.css?<?php echo date("is"); ?>" />
        <link rel="stylesheet" href="/./css/backend/system/account/account_index.css?<?php echo date("is"); ?>" />

    </head>

    <body>

        <div id="caption">
            <div id="caption_header">喜憨兒基金會 人才登錄培訓分析平台－帳號權限維護</div>
        </div>

        <div id="content" style="height:<?php echo (50*$num + 510). "px" ?>">

            <div id="bread">
                <a href="/./backend/backend_panel.php" target="_parent">人才登錄分析平台</a> >
                帳號維護
            </div>

            <br />

            <div id="search">

                <b>登入帳號名稱：</b>
                <input id="filter-account-id" type="text" value="<?php echo $filter_content[0] ?>" placeholder=' 請輸入搜尋帳號' />
                &nbsp;&nbsp; <br />

                <b>歸屬權限群組：</b>
                <select id='filter-account-auth'>
                    <?php
                        $state =array("ALL","不限定") ;

                        $sql_temp = "SELECT * FROM `system-account-group` order by `no` ASC";
                        $result_temp = mysqli_query($link, $sql_temp) ;
                        while( $row_temp = mysqli_fetch_array($result_temp,MYSQL_ASSOC) )
                            array_push($state, $row_temp['auth'], $row_temp['name']);

                        for ( $i=0 ; $i<count($state) ; $i=$i+2){
                            if( $i!=2 ){
                                if ( $state[$i] == $filter_content[1] ) echo "<option value='".$state[$i]."' selected>".$state[$i+1]."</option>";
                                else                                    echo "<option value='".$state[$i]."'         >".$state[$i+1]."</option>";
                            }
                        }
                        unset($state);
                    ?>
                </select>
                &nbsp;&nbsp;

                <b>帳號類型：</b>
                <select id='filter-account-type'>
                    <?php
                        $state =array("ALL","全部類型",
                                      "local","系統帳號",
                                      "cloud","員工帳號") ;
                        for ( $i=0 ; $i<count($state) ; $i=$i+2){
                            if ( $state[$i] == $filter_content[2] )
                                echo "<option value='".$state[$i]."' selected>".$state[$i+1]."</option>";
                            else
                                echo "<option value='".$state[$i]."'         >".$state[$i+1]."</option>";
                        }
                        unset($state);
                    ?>
                </select>
                &nbsp;&nbsp; <br />

                <b>啟用狀態：</b>
                <select id='filter-account-acc'>
                    <?php
                        $state =array("ALL","不限定",
                                      "true","啟用",
                                      "false","未啟用") ;
                        for ( $i=0 ; $i<count($state) ; $i=$i+2){
                            if ( $state[$i] == $filter_content[3] )
                                echo "<option value='".$state[$i]."' selected>".$state[$i+1]."</option>";
                            else
                                echo "<option value='".$state[$i]."'         >".$state[$i+1]."</option>";
                        }
                        unset($state);
                    ?>
                </select>
                &nbsp;&nbsp;

                <div style="float:right;padding:35px 10px 0 0"><div class='btn' onclick='filter_ready()'>查詢</div></div>

            </div>

            <br />

            <div style="float:right;padding:15px 10px 0 0"><div class='btn_create' onclick='account_create();'>新建帳號</div></div>
            <div style="float:right;padding:15px 10px 0 0"><div class='btn_create' onclick='group_edit()'>權限群組設定</div></div>

            <br /><br /><br /><br /><br />

            <!-- 開始顯示搜尋結果 -->
            <?php
            echo "<div id='result' style='height:".( 100 + 50 * $num )."px'>" ;

            //帳號維護 標題
            echo "<div class='personality_title'>登入帳號</div>";
            echo "<div class='personality_title2'>權限群組</div>";
            echo "<div class='personality_title'>帳號類型</div>";
            echo "<div class='personality_title'>登入次數</div>";
            echo "<div class='personality_title2'>最後登入時間</div>";
            echo "<div class='personality_title'>設定</div>";
            echo "<div class='personality_title'>啟用</div>";

            //帳號維護 內容
            $i = 0 ;
            while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC) ){

                //管理員帳號不顯示
                if ( $row['no'] !=0 ){

                    echo "<div class='personality_txt' style='font-size:14px;font-weight:bold;'>".$row['id']."</div>" ;

                    //權限群組
                    $sql_temp = "SELECT * FROM `system-account-group` WHERE `auth` = '{$row['auth']}'" ;
                    $result_temp = mysqli_query($link, $sql_temp) ;
                    while( $row_temp = mysqli_fetch_assoc($result_temp) ){
                        $rows_temp[] = $row_temp ;
                    }
                    echo "<div class='personality_txt2'>".substr( $rows_temp[0]["name"], 0 ,20 )."</div>" ;
                    unset($rows_temp);

                    //帳號類型
                    switch($row['type']){

                        case "local":
                            echo "<div class='personality_txt' style='color:#8BB381;font-size:16px;font-weight:600;'>系統帳號</div>" ;
                            break;
                        case "cloud":
                            echo "<div class='personality_txt' style='color:#9F000F;font-size:16px;font-weight:600;'>員工帳號</div>" ;
                            break;

                        default:
                            echo "<div class='personality_txt' style='color:#000000;font-size:16px;font-weight:bold;'>無法辨識</div>" ;
                            break;

                    }
                    //登入次數
                    echo "<div class='personality_txt'>".$row['login_count']."</div>" ;
                    //最後登入時間
                    echo "<div class='personality_txt2' style='font-size:13px;'>".$row['login_time']."</div>" ;
                    //設定
                    echo "<div class='personality_txt'><div id='' class='btn_edit'  onclick=account_edit('".$row['no']."')>編輯</div></div>" ;

                    switch($row['acc']){
                        case "true":
                            echo "<div class='personality_txt'><div class='btn_acc_enable' onclick=account_acc_edit('".$row['no']."')>已啟用</div></div>";
                            break;

                        default:
                        case "false":
                            echo "<div class='personality_txt'><div class='btn_acc_disable' onclick=account_acc_edit('".$row['no']."')>未啟用</div></div>";
                            break;
                    }
                }
            }

            echo "</div>" ;

            //分頁內容
            echo "<div id='pages' style='width:".( 140 + 60 * $pages_box )."px;'>" ;

                echo "<a href=?page=1><div class='pages_box' style='color:#00F'> << </div></a>"; //回到第一頁

                    for ( $i = 1; $i <= $all_pages ; $i++ ) {
                        if ( $page - 4 < $i && $i < $page + 4 ) {
                            if ( $i != $page )
                                echo "<a href=?page=".$i."><div class='pages_box' style='color:#00F'> ".$i." </div></a>" ;
                            else
                                echo "<div class='pages_box' style='color:#000;background-color:#f8c476;'> ".$i."</div>" ;
                        }
                    }

                echo "<a href=?page=".$all_pages."><div class='pages_box' style='color:#00F'> >> </div></a>"; //到最後一頁

            echo "</div>";

            ?>

            <!-- 結束顯示搜尋結果 -->


        </div>

        <br/>

        <script src="/./javascript/backend/system/account/account_index.js?<?php echo date("is"); ?>"></script>

    </body>


</html>

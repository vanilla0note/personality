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
    /* 第一次進來本頁$_SESSION['personality_system_dep_sql_query']一定沒有；
       重新進入本頁，$_SESSION['personality_system_dep_sql_query']可能有sql，但$_GET["page"]也不會有值，所以可以確定是重新進入，就不能再帶前次搜尋結果 */

    if ( isset($_GET["page"]) && $_SESSION['personality_system_dep_sql_query'] != "" ){
        echo("<script>console.log('SESSION有sql值，重覆進入本頁');</script>");

        ////03-01.以下將session值取出，用於重複進入本頁的輸出
        $sql       = $_SESSION['personality_system_dep_sql_query'] ; //本次查詢的語法（不帶頁數）
        $data_nums = $_SESSION['personality_system_dep_data_nums'] ; //符合本次查詢資料的總筆數
        $all_pages = $_SESSION['personality_system_dep_all_pages'] ; //符合本次查詢資料的總頁數
        $filter_content = explode( "@" , $_SESSION['personality_system_dep_filter_content'] ) ; //本次查詢的篩選器條件
        $page = $_GET["page"] ; //目前所在的頁數
        $_SESSION['personality_system_dep_get_page'] = $page ;//目前所在的頁數

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
        $sql = "SELECT * FROM `system-department` order by `no` ASC" ;
        $result = mysqli_query($link, $sql) ;
        $data_nums = mysqli_num_rows($result) ; //第一次進入 資料總筆數        

        $limit = 10; //每頁最多顯示資料數量
        $all_pages = ceil($data_nums/$limit); //第一次進入 資料總頁數 取得不小於值的下一個整數
            if ( $all_pages == 0 ) $all_pages = 1 ;  //等於 0 代表沒有資料，直接等於1，點選末頁才不會出錯

        //第一次進入本頁，搜尋器給予第一次的預設值
        $filter_content[0] = "" ;
        $filter_content[1] = "ALL" ;


        //03-02.第一次進入本頁，session給予初始值
        $_SESSION['personality_system_dep_sql_query'] = $sql ;       //第一次進入的查詢語法
        $_SESSION['personality_system_dep_data_nums'] = $data_nums ; //第一次進入的查詢總筆數
        $_SESSION['personality_system_dep_all_pages'] = $all_pages ; //第一次進入的查詢總頁數
        $_SESSION['personality_system_dep_filter_content'] = $filter_content[0]."@".$filter_content[1] ;  //第一次查詢的篩選器條件

        //////////// test////////
        //echo "<br/><br/><br/><br/><br/><br/>";
        //echo "sql=".json_encode($sql)."<br/>" ;
        //echo "data_nums=".$data_nums."<br/>" ;
        //echo "all_pages=".$all_pages."<br/>" ;
        //echo "filter_content[0]=".$filter_content[0]."<br/>" ;
        //echo "filter_content[1]=".$filter_content[1]."<br/>" ;
        /////////////////////////

       
        //03-03.網頁跳轉為page=1，往程式帶入有搜尋條件的模式
        header("Location: department_index.php?page=1"); 

    }

?>


<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>喜憨兒基金會 人才登錄培訓分析平台－部門維護（後台管理）</title>

        <?php include($_SERVER['DOCUMENT_ROOT'].'/include/toolkit.php'); ?>
        <link rel="stylesheet" href="/./css/backend/bread.css?<?php echo date("is"); ?>" />
        <link rel="stylesheet" href="/./css/backend/system/department/department_index.css?<?php echo date("is"); ?>" />

    </head>

    <body>

        <div id="caption">
            <div id="caption_header">喜憨兒基金會 人才登錄培訓分析平台－部門維護</div>
        </div>

        <div id="content" style="height:<?php echo (50*$num + 550)."px" ?>">

            <div id="bread">
                <a href="/./backend/backend_panel.php" target="_parent">人才登錄分析平台</a> > 
                部門維護 
            </div>

            <br/>

            <div id="search">

                <b>部門名稱：</b>
                <input id="filter-dep-name" type="text" value="<?php echo $filter_content[0] ?>" onblur="this.value=this.value.toUpperCase()" placeholder=' 請輸入部門名稱'>
                &nbsp;&nbsp; 
               
                <b>啟用狀態：</b>
                <select id='filter-dep-acc'>
                    <?php
                        $state =array("ALL","不限定",
                                      "true","啟用",
                                      "false","未啟用") ;
                        for ( $i=0 ; $i<=4 ; $i=$i+2){
                            if ( $state[$i] == $filter_content[1] )
                                echo "<option value='".$state[$i]."' selected>".$state[$i+1]."</option>";
                            else
                                echo "<option value='".$state[$i]."'         >".$state[$i+1]."</option>";
                        }             
                    ?>
                </select>
                &nbsp;&nbsp;

                &nbsp;&nbsp;
                <div style="float:right;padding:35px 10px 0 0"><div class='btn' onclick='filter_ready()'>查詢</div></div>

            </div>

            <br/>

            <div style="float:right;padding:15px 10px 0 0"><div class='btn_create' onclick='department_create();'>新建部門</div></div>

            <br/><br/><br/><br/><br/>


            <!-- 開始顯示搜尋結果 -->
            <?php
            echo "<div id='result' style='height:".( 150 + 50 * $num )."px'>" ;

            //部門維護 標題
            echo "<div class='personality_title'>部門名稱</div>";
            echo "<div class='personality_title'>數量統計</div>";
            echo "<div class='personality_title2'>設定</div>";
            echo "<div class='personality_title3'>狀態</div>";

            //部門維護 內容
            $i = 0 ;
            while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC) ){
                echo "<div class='personality_txt'>".$row['dep_name']."</div>" ;


                //資料統計
                $sql_temp = "SELECT * FROM `system-department-group` WHERE `dep_id` = '{$row['dep_id']}'";
                $result_temp = mysqli_query($link, $sql_temp);
                $group_num = mysqli_num_rows($result_temp);
                $sql_temp = "SELECT * FROM `system-department-job` WHERE `dep_id` = '{$row['dep_id']}'";
                $result_temp = mysqli_query($link, $sql_temp);
                $job_num = mysqli_num_rows($result_temp);
                echo "<div class='personality_txt' style='width:220px;text-align:left;padding-left:20px;'>組別：" . $group_num . "&nbsp;&nbsp;&nbsp;&nbsp;職位：". $job_num ."</div>";


                echo "<div class='personality_txt2'><div id='' class='btn_edit' onclick=department_edit('".$row['dep_id']."')>設定</div></div>" ;

                //啟用狀態
                switch($row['acc']){
                    case "true":
                        echo "<div class='personality_txt3'><div class='btn_acc_enable' onclick=department_acc_edit('".$row['dep_id']."')>已啟用</div></div>";
                        break;

                    default:
                    case "false":
                        echo "<div class='personality_txt3'><div class='btn_acc_disable' onclick=department_acc_edit('".$row['dep_id']."')>未啟用</div></div>";
                        break;
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

        <script src="/./javascript/backend/system/department/department_index.js?<?php echo date("is"); ?>"></script>

    </body>


</html>

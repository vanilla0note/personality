<?php
    /* 帳號權限維護－設定「查詢作答卷」權限service */


    //如果是 POST 才會執行
    if ($_SERVER['REQUEST_METHOD'] == "POST") { 

        //01.資料庫連線
        require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php"); 
        $link = create_connection() ; 


        //02.固定欄位紀錄
        header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8
        $content = $_POST["mycheck_option"];

        $get_content = Array();


        switch ($content){
            case "AREA":  
                //限定區域                  
                $get_content = Array( 0=>Array(0=>"area-A",1=>"台北地區"),
                                      1=>Array(0=>"area-H",1=>"桃園地區"),
                                      2=>Array(0=>"area-J",1=>"新竹地區"),
                                      3=>Array(0=>"area-D",1=>"台南地區"),
                                      4=>Array(0=>"area-E",1=>"高雄地區"),
                                    );              
                break;

            case "DEP":
                //限定部門
                $sql= "SELECT * FROM `system-department` WHERE acc = 'true' ORDER BY `no` ASC" ;
                $result = mysqli_query($link, $sql) ;

                $i = 0;
                while( $row = mysqli_fetch_array($result,MYSQL_ASSOC) ){
                    $get_content[$i][0] = $row['dep_id'] ;
                    $get_content[$i][1] = $row['dep_name'] ;

                    $i++ ;            
                }                                  
                break;

            default: break;
        }



        //03.回傳
        echo json_encode(array(
            'mycontent' => $get_content ,

        ));
    }

?>
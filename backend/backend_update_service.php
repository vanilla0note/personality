<?php      
    //如果是 POST 才會執行
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        //01.固定欄位紀錄
        header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8


        //02.資料庫設定檔及網域套件載入
        require_once( $_SERVER['DOCUMENT_ROOT'].'/conn/conn_user_php7.php'); 
        $link = create_connection() ;

        $sql = "SELECT * FROM `ver_record` order by `count` DESC" ;
        $result = mysqli_query($link, $sql) ;

        while( $row = mysqli_fetch_assoc($result) ){
            $ver[]          = $row["ver"] ;           //版本號碼
            $publish_date[] = $row["publish_date"] ;  //發佈日期
            $content[]      = str_replace( '\r\n' , '<br>' , $row["content"] );       //更新內容

        }
  

        //03.回傳
        echo json_encode(array(
            'myver'          => $ver,
            'mypublish_date' => $publish_date,
            'mycontent'      => $content,
        ));   

    }
    else
        header("Location:http://www.google.com");








       
?>
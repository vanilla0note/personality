<?php      
    //�p�G�O POST �~�|����
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        //01.�T�w������
        header('Content-Type: application/json; charset=UTF-8'); //�]�w��������� json�A�s�X utf-8


        //02.��Ʈw�]�w�ɤκ���M����J
        require_once( $_SERVER['DOCUMENT_ROOT'].'/conn/conn_user_php7.php'); 
        $link = create_connection() ;

        $sql = "SELECT * FROM `ver_record` order by `count` DESC" ;
        $result = mysqli_query($link, $sql) ;

        while( $row = mysqli_fetch_assoc($result) ){
            $ver[]          = $row["ver"] ;           //�������X
            $publish_date[] = $row["publish_date"] ;  //�o�G���
            $content[]      = str_replace( '\r\n' , '<br>' , $row["content"] );       //��s���e

        }
  

        //03.�^��
        echo json_encode(array(
            'myver'          => $ver,
            'mypublish_date' => $publish_date,
            'mycontent'      => $content,
        ));   

    }
    else
        header("Location:http://www.google.com");








       
?>
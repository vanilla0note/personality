<?php
    /*************************************************

        計算單一作答卷的分數

        $exam_id：當前要計算的作答卷

    *************************************************/


    function Score_calculate_one_exam($exam_id){

        //01.資料庫連線
        require_once( $_SERVER['DOCUMENT_ROOT'].'/conn/conn_user_php7.php');
        $link = create_connection() ;


        //02.宣告基本變數
        $exam_ans_temp = Array(); //從資料庫取出 答案卷答案(暫存)
        $exam_ans      = Array(); //整理好的     答案卷答案

        $type_explain_key = Array('A','B','C','D','E','F','G','H','I'); //人格九種類型代號

        //依照答案卷，存放計算類型分數
        $exam_ans_type = Array( 'A'=> 0 , 'B'=> 0 , 'C'=> 0 , 'D'=> 0 , 'E'=> 0 ,
                                'F'=> 0 , 'G'=> 0 , 'H'=> 0 , 'I'=> 0 );

        $temp = "" ; //暫存字串


        //03.取出試題卷作答的答案
        $sql = "SELECT * FROM `exam` WHERE `exam_id`  = '{$exam_id}'" ;
        $result = mysqli_query($link, $sql) ;
        while( $row = mysqli_fetch_assoc($result) )
            $rows[] = $row;
        //$state = $rows[0]["state"] ;

        for ( $i = 1 ; $i<=11 ; $i++ ){
            if ( $i < 11  ) $temp = $temp.$rows[0]["exam_ans_".$i]."," ;
            if ( $i == 11 ) $temp = $temp.$rows[0]["exam_ans_".$i] ;
        }
        $exam_ans_temp = explode(",",$temp) ; //作答的答案 存至 答案卷答案(暫存)


        //04.判斷作答答案的完整性

        //若沒有取得完整 108 個答案，將不進行分數計算
        if ( count($exam_ans_temp) == 108 ){

            //將 答案卷答案(暫存) 轉存至 $exam_ans[]，格式：$exam_ans['QA001'] = "Y" ....
            for ( $i = 0 ; $i< count($exam_ans_temp) ; $i++ )
                $exam_ans[ substr($exam_ans_temp[$i], 0 ,5) ] = substr($exam_ans_temp[$i], -1) ;


            //05.依照 答案卷答案($exam_ans)，比照人格類型題目(topic_question)，進行分數計算
            for ( $i = 0 ; $i< count($type_explain_key) ; $i++ ){

                $sql = "SELECT * FROM `system-question-exam` WHERE `type` = '{$type_explain_key[$i]}' ORDER BY `num` ASC" ;
                $result = mysqli_query($link, $sql) ;

                while( $row = mysqli_fetch_assoc($result) ){
                    $key = $row['id'] ;
                    if ( $exam_ans[$key] == 'Y')
                        $exam_ans_type[ $type_explain_key[$i] ] = $exam_ans_type[ $type_explain_key[$i] ] + 1 ;
                }

            }

        }
        else
            $exam_ans_type = "" ; //作答卷沒有完整答案，回傳空字串


        //06.回傳人格類型分數
        return $exam_ans_type;


    }

?>
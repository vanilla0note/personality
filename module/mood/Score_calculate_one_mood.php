<?php
    /*************************************************

        計算單一作答卷的分數

        $mood_id：當前要計算的作答卷

    *************************************************/


    function Score_calculate_one_mood($mood_id){

        //01.資料庫連線
        require_once( $_SERVER['DOCUMENT_ROOT'].'/conn/conn_user_php7.php');
        $link = create_connection() ;


        ////02.宣告基本變數
        $mood_ans_temp  = Array(); //從資料庫取出 答案卷答案(暫存)
        $mood_ans       = Array(); //整理好的     答案卷答案
        $mood_ans_score = 0 ;      //計算好的     答案卷分數

        $temp = "" ; //暫存字串


        //03.取出試題卷作答的答案
        $sql = "SELECT * FROM `mood` WHERE `mood_id`  = '{$mood_id}'" ;
        $result = mysqli_query($link, $sql) ;
        while( $row = mysqli_fetch_assoc($result) )
            $rows[] = $row;

        for ( $i=1 ; $i<=2 ; $i++ ){
            if ( $i == 1 ) $temp = $temp.$rows[0]["mood_ans_".$i]."," ;
            if ( $i == 2 ) $temp = $temp.$rows[0]["mood_ans_".$i] ;
        }
        $mood_ans_temp = explode(",",$temp) ; //作答的答案 存至 答案卷答案(暫存)


        //////test//////
        //echo "mood_ans_temp<br/>";
        //print_r($mood_ans_temp);
        //echo "<br/><br/>";
        ///////////////


        //04.判斷作答答案的完整性
        //若沒有取得完整 14 個答案，將不進行分數計算
        if ( count($mood_ans_temp) == 14 ){

            //將 答案卷答案(暫存) 轉存至 $mood_ans[]，格式：$mood_ans['CQA001'] = "1" ....
            for ( $i = 0 ; $i< count($mood_ans_temp) ; $i++ )
                $mood_ans[$i] = substr($mood_ans_temp[$i], -1) ;


            //05.依照 答案卷答案($mood_ans)，進行分數累加計算
            for ( $i = 0 ; $i< count($mood_ans) ; $i++ )
                $mood_ans_score = $mood_ans_score + $mood_ans[$i] ;

        }
        else
            $mood_ans_score = 0 ; //作答卷沒有完整答案，不計算



        //////test//////
        //echo "mood_ans<br/>";
        //print_r($mood_ans);
        //echo "<br/><br/>";
        //echo "mood_ans_score=".$mood_ans_score."<br/>";
        ///////////////


        //06.回傳人格類型分數
        return $mood_ans_score;


    }

?>
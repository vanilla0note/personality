<?php
    /*************************************************

        計算全部作答卷的分數

        $exam_id[]：要計算的作答卷
    
    *************************************************/


    function Score_calculate_all_exam(){

        //01.資料庫連線
        require_once( $_SERVER['DOCUMENT_ROOT'].'/conn/conn_user_php7.php'); 
        $link = create_connection() ; 


        //02.宣告基本變數
        $exam_ans_temp = Array(); //從資料庫取出 答案卷答案(暫存) 格式：$exam_ans_temp[0]='QA001-Y' $exam_ans_temp[1]='QA002-N' ....
        $exam_ans      = Array(); //整理好的     答案卷答案       格式：$exam_ans['QA001'] = "Y"    $exam_ans['QA002'] = "N" ....

        $type_explain_key = Array('A','B','C','D','E','F','G','H','I'); //人格九種類型代號               
        $exam_ans_type    = Array(); //存放計算類型分數


        //03.取出需要計算分數的試題卷(未計算的狀態)
        $sql = "SELECT * FROM `exam` WHERE `state` = 'A'" ;
        $result = mysqli_query($link, $sql) ; 

        $exam_id = Array();
        $point = 0 ;
        while( $row = mysqli_fetch_array($result,MYSQL_ASSOC) ){
            $exam_id[$point] = $row['exam_id'] ; //將需要計算的試題卷，紀錄在 $exam_id 陣列

            $point++ ;
        }

        //// test /////
        //for( $point=0 ; $point<count($exam_id); $point++)
        //    echo "exam_id[".$point."] = ".$exam_id[$point]."<br/>" ;
        //////////////


        //04.進行分數計算(當符合條件的試題卷才需要執行)        
        for( $point=0 ; $point<count($exam_id) ; $point++ ){

            //04-01.調整基本變數
            unset($exam_ans_temp); //清空 答案卷答案(暫存) 陣列
            unset($exam_ans);      //清空 答案卷答案       陣列
            unset($exam_ans_type);    //清空 計算類型分數     陣列

            //依照答案卷，計算類型分數
            $exam_ans_type = Array( 'A'=> 0 , 'B'=> 0 , 'C'=> 0 , 'D'=> 0 , 'E'=> 0 ,
                                    'F'=> 0 , 'G'=> 0 , 'H'=> 0 , 'I'=> 0 );         
            $temp = "";

            
            //04-02.取出試題卷作答的答案
            $sql = "SELECT * FROM `exam` WHERE `exam_id` = '{$exam_id[$point]}'" ;
            $result = mysqli_query($link, $sql) ; 

            unset($rows); //清空陣列
            while( $row = mysqli_fetch_assoc($result) )
                $rows[] = $row;    
            //$state = $rows[0]["state"] ;

            for ( $i = 1 ; $i<=11 ; $i++ ){
                if ( $i < 11  ) $temp = $temp.$rows[0]["exam_ans_".$i]."," ;
                if ( $i == 11 ) $temp = $temp.$rows[0]["exam_ans_".$i] ;
            }        
            $exam_ans_temp = explode(",",$temp) ; //作答的答案以陣列的方式紀錄 
                                                    //格式：$exam_ans_temp[0]='QA001-Y' $exam_ans_temp[1]='QA002-N' ....


            //04-03.判斷作答答案的完整性

            //若沒有取得完整 108 個答案，將不進行分數計算)
            if ( count($exam_ans_temp) == 108 ){

                //將答案轉存至$exam_ans[]，格式：$exam_ans['QA001'] = "Y" ....
                for ( $i = 0 ; $i< count($exam_ans_temp) ; $i++ )
                    $exam_ans[ substr($exam_ans_temp[$i], 0 ,5) ] = substr($exam_ans_temp[$i], -1) ;

        
                //05.依照答案卷內容，比照人格類型題目(topic_question)，進行分數計算 
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


            //04-04.更新至資料庫
            $score_result = $exam_ans_type ; //回傳人格類型分數至「相同變數」
            $type_score = "" ;

            if ( $score_result == "" ) $state = "Z" ;
            else{                     
                $state = "B" ;


                for( $i = 65 ; $i<= 73 ; $i++ ){
                    if( $i <  73 ) $type_score = $type_score.$score_result[ chr($i) ]."," ;
                    if( $i == 73 ) $type_score = $type_score.$score_result[ chr($i) ] ;
                }
            }


            $sql = "UPDATE `exam` SET `Type_Score` = '{$type_score}' ,
                                        `state` = '{$state}'
                                    WHERE  `exam_id` = '{$exam_id[$point]}'" ;
            $result = mysqli_query($link, $sql) ;



            //// test /////
            //echo "exam_id[".$point."] = ".$exam_id[$point]."<br/>";
            ////var_dump($exam_ans_temp) ;
            //var_dump($exam_ans_type) ;
            //echo "<br/>" ;
            //echo "exam_ans_type['A'] = ".$exam_ans_type['A']."<br/>";
            //echo "<br/><br/>" ;
            ///////////////


            //04-05.紀錄排程LOG
            date_default_timezone_set('Asia/Taipei'); 
            $now_date = date("Y-m-d H:i:s"); //執行試卷分數計算的時間戳記

            $sql = "INSERT INTO `exam_score_calculate_all_log` (`num`, `exam_id`, `state`, `now_date`) Values 
                                                            ('', '{$exam_id[$point]}', '{$state}', '{$now_date}')" ;     
            mysqli_query($link, $sql) ;


            echo "試題卷編號：".$exam_id[$point]."執行完成！<br/>";
        }

    }

?>
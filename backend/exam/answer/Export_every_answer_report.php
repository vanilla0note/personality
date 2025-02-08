<?php
    /* 建立檢視表，再由檢視表輸出 Excel 檔案後，再刪除檢視表 */


    ////測試報表
    //exporting_xls("SELECT `exam_id`,
    //                      `interview_name`,
    //                      `interview_sex`,
    //                      `interview_dep`,
    //                      `interview_group`,
    //                      `interview_job`,
    //                      `start_date`,
    //                      `now_date`,
    //                      `Times_Score`,
    //                      `Type_Score`,
    //                      `remark`,
    //                      `state`,
    //                      `Backend_remark`
    //                      FROM `exam` WHERE `exam_id` = 'A230217033325'") ;

    function exporting_xls($receive_sql){

        //01.資料庫連線
        require_once($_SERVER['DOCUMENT_ROOT']."/conn/conn_user_php7.php");
        $link = create_connection() ;
        $database = "alifeblo_personality" ;


        //02.PHPExcel
        require_once ($_SERVER['DOCUMENT_ROOT']."/module/PHPExcel/PHPExcel.php");  //載入 PHPExcel
        require_once ($_SERVER['DOCUMENT_ROOT']."/module/PHPExcel/PHPExcel/IOFactory.php");
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);


        //03.輸出 作答卷索引

        /* $receive_sql = 要執行輸出的 SQL 語法 */

        //03-01.依據語法建立檢視表
        $report_view_sql = "CREATE OR REPLACE VIEW `exam-every-answer-export_report_view` AS {$receive_sql} ";
        $result_view_result = mysqli_query($link, $report_view_sql) ;

        /////////// test ///////////
        //echo "檢視表 report_view_sql 語法= ".$report_view_sql."<br/>" ;
        ////////////////////////////


        //03-02.製作 Excel 檔案，設定Excel標題
        $field_name = Array(
                        "exam_id"          => "試題卷編號",
                        "interview_name"   => "作答人姓名",
                        "interview_sex"    => "作答人性別",
                        "interview_dep"    => "應徵部門",
                        "interview_group"  => "應徵組別",
                        "interview_job"    => "應徵職務",
                        "start_date"       => "開始作答時間",
                        "now_date"         => "最後作答時間",
                        "Times_Score"      => "作答花費時間(秒)",
                        "Type_Score"       => "人格類型(從1至9類)",
                        "remark"           => "測驗時備註",
                        "state"            => "試題卷狀態",
                        "Backend_remark"   => "管理員備註"
                     );

        //03-03.存入Excel標題
        //取得檢視表 每一個欄位名稱
        $field_sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
                      WHERE TABLE_schema ='".$database."' &&
                            TABLE_NAME ='exam-every-answer-export_report_view'
                      ORDER BY ORDINAL_POSITION " ;
        $field_result = mysqli_query($link, $field_sql) ;


        $i = 0 ; //從Excel的 第 A 欄開始儲存
        while ( $row = mysqli_fetch_array($field_result) ){

            $cell = chr(65 + $i)."1" ;   // chr(65 + $i) = 'A'(ASCII碼 為 65)
            $objPHPExcel->getActiveSheet()->setCellValue( $cell,$field_name[ $row[0] ] );

            $i++ ;
        }

        //03-04.存入Excel資料內容
        $report_view_sql_temp = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
                                 WHERE TABLE_schema ='".$database."' &&
                                       TABLE_NAME   ='exam-every-answer-export_report_view' " ; //取得檢視表 欄位數量
        $report_view_result_temp = mysqli_query($link, $report_view_sql_temp) ;
        //取得檢視表欄位數量，才知道for要印多少個
        while( $row = mysqli_fetch_array($report_view_result_temp) )
            $rows[] = $row;
        $report_view_count = $rows[0][0] ;
        unset($rows);

        $report_view_sql = "SELECT * FROM `exam-every-answer-export_report_view`" ;//取得檢視表 資料內容
        $report_view_result = mysqli_query($link, $report_view_sql) ;


        $i = 0 ; //從Excel的 第A欄、第二列開始儲存
        while ( $row = mysqli_fetch_array($report_view_result) ){

            $rows[] = $row;

            for ( $j = 0 ; $j < $report_view_count ; $j++ ){

                //Excel位置：A (ASCII碼 為 65) ； ($i + 2) = 2  從 Excel檔案的 A2 開始印出
                $cell = chr(65 + $j).( $i+2 ) ;   //

                //要印的資料：$rows[0][0]、$rows[0][1] ....

                //依照資料作個別處理

                //03-04-01.試題券編號及作答姓名作為輸出檔案名稱
                $filename = $rows[0][0]."(".$rows[0][1].")";

                //03-04-02.特別欄位 對應特別名稱或取出變數值
                switch($j){

                    //試題卷編號
                    case 0 :
                        $output_data = $rows[0][$j] ;
                        $exam_id = $rows[0][$j] ; //紀錄試題卷編號變數
                        break;

                    //作答人性別
                    case 2:
                        switch($rows[0][2]){
                            case "sex_m": $output_data = "男"   ; break;
                            case "sex_f": $output_data = "女" ; break;
                            default:      $output_data = "錯誤資料" ; break;
                        }
                        break;

                    //應徵部門
                    case 3 :
                        $sql_temp = "SELECT * FROM `system-department` WHERE `dep_id` = '{$rows[0][$j]}'";
                        $result_temp = mysqli_query($link, $sql_temp) ;
                        while( $row_temp = mysqli_fetch_assoc($result_temp) )
                            $rows_temp[] = $row_temp;
                        $output_data = $rows_temp[0]["dep_name"] ;
                        unset($rows_temp);
                        break;

                    //應徵組別
                    case 4 :
                        $sql_temp = "SELECT * FROM `system-department-group` WHERE `group_id` = '{$rows[0][$j]}'";
                        $result_temp = mysqli_query($link, $sql_temp) ;
                        while( $row_temp = mysqli_fetch_assoc($result_temp) )
                            $rows_temp[] = $row_temp;
                        $output_data = $rows_temp[0]["group_name"] ;
                        unset($rows_temp);
                        break;

                    //應徵職務
                    case 5 :
                        $sql_temp = "SELECT * FROM `system-department-job` WHERE `job_id` = '{$rows[0][$j]}'";
                        $result_temp = mysqli_query($link, $sql_temp) ;
                        while( $row_temp = mysqli_fetch_assoc($result_temp) )
                            $rows_temp[] = $row_temp;
                        $output_data = $rows_temp[0]["job_name"] ;
                        unset($rows_temp);
                        break;

                    //試題卷狀態
                    case 11 :
                        switch($rows[0][11]){
                            case "A": $output_data = "未計算"   ; break;
                            case "B": $output_data = "計算完成" ; break;
                            default:
                            case "Z": $output_data = "無效試卷" ; break;
                        }
                        break;

                    default:
                        $output_data = $rows[0][$j] ;
                        break;
                }

                //輸出在Excel
                $objPHPExcel->getActiveSheet()->setCellValue( $cell,$output_data );
            }

            $i++ ;
        }


        //04.輸出 作答卷 答案內容

        //04-01.製作 Excel 檔案，設定Excel標題
        $objPHPExcel->getActiveSheet()->mergeCells("A4:E4"); //單行合併
        $objPHPExcel->getActiveSheet()->setCellValue("A4","答案內容");

        //04-02.輸出試題卷 題目
        $exam_question_sql = "SELECT * FROM `system-question-exam` ORDER BY `num` ASC" ;
        $exam_question_result = mysqli_query($link, $exam_question_sql) ;

        $i = 5 ;
        while( $row_question = mysqli_fetch_assoc($exam_question_result) ){

            //題號
            $cell = "A".$i ;
            $objPHPExcel->getActiveSheet()->setCellValue( $cell,$row_question['num'] );

            //題目內容
            $cell = "B".$i ;
            $objPHPExcel->getActiveSheet()->setCellValue( $cell,$row_question['question'] );

            $i++ ;
        }

        //04-03.輸出試題卷 作答內容
        $exam_answer_sql = "SELECT * FROM `exam` WHERE `exam_id` = '{$exam_id}' " ;
        $exam_answer_result = mysqli_query($link, $exam_answer_sql) ;
        $exam_answer = "" ; //作答內容
        $exam_answer_temp = "" ; //作答內容暫存檔

        //整理作答內容存為陣列
        while( $row_answer = mysqli_fetch_assoc($exam_answer_result) )
            $rows_answer[] = $row_answer;
        for ( $i=1 ; $i<=11 ;$i++ ) {
            $key_temp = "exam_ans_".$i ;

            //確定有作答的答案，才需要進行答案整理
            if ( $rows_answer[0][$key_temp] != "" ){
                if ( $i!= 1 )
                    $exam_answer_temp = $exam_answer_temp.",".$rows_answer[0][$key_temp] ;
                else
                    $exam_answer_temp = $rows_answer[0][$key_temp] ;
            }
        }
        $exam_answer = explode(",",$exam_answer_temp);

        //作答內容
        for( $i=0 ; $i<count($exam_answer) ; $i++){
            $cell = "E".($i+5) ;
            $objPHPExcel->getActiveSheet()->setCellValue( $cell,substr($exam_answer[$i],6,1) );
        }


        //05.設定報表格式
        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(30); //預設每列高度 30
        for ( $i = 0 ; $i < 25 ; $i++ ){
            $cell = chr( 65 + $i ) ;
            $objPHPExcel->getActiveSheet()->getColumnDimension($cell)->setWidth(30); //  預設 A 欄以後寬度 30
        }

        $objPHPExcel->getActiveSheet()->getStyle("A1:Z120")->getFont()->setSize(12); //字型大小 12
        $objPHPExcel->getActiveSheet()->getStyle("A1:Z120")->getFont()->setName('微軟正黑體');

        $objPHPExcel->getActiveSheet()->getStyle("A1:M1")->getFont()->setBold(true); //字型粗體
        $objPHPExcel->getActiveSheet()->getStyle("A1:M2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);//水平靠左
        $objPHPExcel->getActiveSheet()->getStyle("A1:M2")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); //垂直置中
        //$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED); //設定字體顏色
        $objPHPExcel->getActiveSheet()->getStyle("A1:M1")->getFill()->applyFromArray(array("type" => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' =>'FFFFC2'))); //設定背景顏色

        $objPHPExcel->getActiveSheet()->getStyle("A4")->getFill()->applyFromArray(array("type" => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' =>'FFFFC2'))); //設定背景顏色
        $objPHPExcel->getActiveSheet()->getStyle("A4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//水平置中
        $objPHPExcel->getActiveSheet()->getStyle("A4:E112")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); //垂直置中
        $objPHPExcel->getActiveSheet()->getStyle("A4")->getFont()->setBold(true); //字型粗體

        //刪除不需要的欄位(需從後面往前刪除，避免欄位自動補位的問題)
        //$objPHPExcel->getActiveSheet()->removeColumn('M');
        //$objPHPExcel->getActiveSheet()->removeColumn('L');
        //$objPHPExcel->getActiveSheet()->removeColumn('C');


        //06.密碼保護
        $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
        $objPHPExcel->getActiveSheet()->getProtection()->setPassword('hrpassword');


        //07.輸出並儲存檔案
        $filename= $filename.date("Y-m-d H:i:s"); //檔案名稱使用時間戳記
        ob_end_clean();//清除緩衝區,避免亂碼
        header('Content-Type: applicationnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$filename.'人格特質逐題答案.xls');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

    }

?>
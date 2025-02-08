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
    //                      FROM `exam` WHERE `exam_id` = 'E240613101804'") ;


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


        //03.輸出 人格特質總表 索引

        /* $receive_sql = 要執行輸出的 SQL 語法 */

        //03-01.依據語法建立檢視表
        $report_view_sql = "CREATE OR REPLACE VIEW `exam-personality-type-export_report_view` AS {$receive_sql} ";
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
                            TABLE_NAME ='exam-personality-type-export_report_view'
                      ORDER BY ORDINAL_POSITION "  ;
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
                                       TABLE_NAME   ='exam-personality-type-export_report_view' " ; //取得檢視表 欄位數量
        $report_view_result_temp = mysqli_query($link, $report_view_sql_temp) ;
        //取得檢視表欄位數量，才知道for要印多少個
        while( $row = mysqli_fetch_array($report_view_result_temp) )
            $rows[] = $row;
        $report_view_count = $rows[0][0] ;
        unset($rows);

        $report_view_sql = "SELECT * FROM `exam-personality-type-export_report_view`" ;//取得檢視表 資料內容
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
        $objPHPExcel->getActiveSheet()->mergeCells("A4:H4"); //單行合併
        $objPHPExcel->getActiveSheet()->setCellValue("A4","人格分析類型");
        $objPHPExcel->getActiveSheet()->mergeCells("C5:E5"); //單行合併
        $objPHPExcel->getActiveSheet()->mergeCells("F5:H5"); //單行合併
        $objPHPExcel->getActiveSheet()->setCellValue("A5","人格特質類型");
        $objPHPExcel->getActiveSheet()->setCellValue("B5","作答分數");
        $objPHPExcel->getActiveSheet()->setCellValue("C5","特質描述");
        $objPHPExcel->getActiveSheet()->setCellValue("F5","適合工作");

        //04-02.輸出人格特質類型 標題
        $exam_type_sql = "SELECT * FROM `system-exam_type_explain` ORDER BY `auto_key` ASC" ;
        $exam_type_result = mysqli_query($link, $exam_type_sql) ;

        $i = 6 ;
        while( $row_type = mysqli_fetch_assoc($exam_type_result) ){

            //人格特質 標題
            $cell = "A".$i ;
            $objPHPExcel->getActiveSheet()->setCellValue( $cell,$row_type['name'] );

            //人格特質 說明
            $cell = "C".$i ;
            $objPHPExcel->getActiveSheet()->setCellValue( $cell,strip_tags($row_type['type_describe']) );
                                                               //strip_tags($變數)去除html標籤

            //適合工作 說明
            $cell = "F".$i ;
            $objPHPExcel->getActiveSheet()->setCellValue( $cell,$row_type['hope_work'] );

            $i++ ;
        }

        //04-03.輸出人格特質類型 作答分數
        $report_view_sql = "SELECT * FROM `exam-personality-type-export_report_view`" ;//取得檢視表 資料內容
        $report_view_result = mysqli_query($link, $report_view_sql) ;
        while( $row = mysqli_fetch_assoc($report_view_result) ) $rows[] = $row;
        $exam_type = explode(",", $rows[0]["Type_Score"]);

        //作答分數填入
        for( $i=0 ; $i<count($exam_type) ; $i++){
            $cell = "B".($i+6) ;
            $objPHPExcel->getActiveSheet()->setCellValue( $cell,$exam_type[$i] );
        }

        //04-04.作答分數 紀錄最大三個分數的位置
        $exam_type_temp = $exam_type ;
        rsort($exam_type_temp); // 對陣列進行降冪排序
        $thirdLargestValue = $exam_type_temp[2]; // 獲取第三大的數值
        for( $i=0 ; $i<count($exam_type) ; $i++){
            if ( $exam_type[$i]>= $thirdLargestValue ){
                $cell = "B" . ($i+6);
                $objPHPExcel->getActiveSheet()->getStyle($cell)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED); //設定字體顏色
                $objPHPExcel->getActiveSheet()->getStyle($cell)->getFont()->setBold(true); //字型粗體

                $objPHPExcel->getActiveSheet()->getStyle($cell)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle($cell)->getFill()->getStartColor()->setRGB('FDEEF4');
            }
        }
        //////// test ////////
        //echo "thirdLargestValue = ".$thirdLargestValue."<br/>";
        //////////////////////



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
        $objPHPExcel->getActiveSheet()->getStyle("A1:M1")->getFill()->applyFromArray(array("type" => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' =>'F3E5AB'))); //設定背景顏色

        $objPHPExcel->getActiveSheet()->getStyle("A4")->getFill()->applyFromArray(array("type" => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' =>'F3E5AB'))); //設定背景顏色
        $objPHPExcel->getActiveSheet()->getStyle("A4:H5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  //水平置中
        $objPHPExcel->getActiveSheet()->getStyle("A6:B14")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //水平置中
        $objPHPExcel->getActiveSheet()->getStyle("A4:H14")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); //垂直置中
        $objPHPExcel->getActiveSheet()->getStyle("A4:H5")->getFont()->setBold(true); //字型粗體

        ////刪除不需要的欄位(需從後面往前刪除，避免欄位自動補位的問題)
        ////$objPHPExcel->getActiveSheet()->removeColumn('M');
        ////$objPHPExcel->getActiveSheet()->removeColumn('L');
        ////$objPHPExcel->getActiveSheet()->removeColumn('C');


        //06.密碼保護
        $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
        $objPHPExcel->getActiveSheet()->getProtection()->setPassword('hrpassword');


        //07.輸出並儲存檔案
        $filename= $filename.date("Y-m-d H:i:s"); //檔案名稱使用時間戳記
        ob_end_clean();//清除緩衝區,避免亂碼
        header('Content-Type: applicationnd.ms-excel');
        header('Content-Disposition: attachment;filename='. $filename.'人格特質總表.xls');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

    }


?>
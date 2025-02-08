<?php
    if( !isset($_SESSION) ) session_start();

    //01.取得顯示訊息代號
    if( isset($_GET["msg"]) ) $msg = $_GET["msg"];
    else                      $msg = "999" ;

?>

<!DOCTYPE HTML>
<html>
    <head>

        <meta charset="UTF-8">
        <title>喜憨兒基金會 人才登錄分析平台－查詢作答卷（後台管理）</title>

        <?php include($_SERVER['DOCUMENT_ROOT'].'/include/toolkit.php'); ?>
        <link rel="stylesheet" href="/./css/backend/bread.css">
        <link rel="stylesheet" href="/./css/backend/dialog/dialog.css?<?php echo date("is"); ?>" />

    </head>

    <body>
        <div id="dialog" title="喜憨兒基金會 人才培訓分析平台">
           
            <?php
            switch ($msg) {
                //查詢作答卷 > 寄送郵件通知
                //上傳檔案
                case "answer_report_101" : echo "上傳檔案不符合規定的檔案！請重新選擇正確的檔案！";
                                           $url = "/./backend/exam/answer/answer_index.php?page=1";   $width=550; $height=250; break;
                case "answer_report_102" : echo "沒有完整上傳檔案！請重新選擇檔案！";
                                           $url = "/./backend/exam/answer/answer_index.php?page=1";   $width=450; $height=250; break;
                //選擇郵件
                case "answer_report_201" : echo "請選擇要寄送的郵件信箱！";
                                           $url = "/./backend/exam/answer/answer_index.php?page=1";   $width=450; $height=250; break;
                case "answer_report_202" : echo "請輸入要額外寄送的郵件信箱！";
                                           $url = "/./backend/exam/answer/answer_index.php?page=1";   $width=450; $height=250; break;

                case "answer_report_complete" : echo "郵件寄送完成！";
                                                $url = "/./backend/exam/answer/answer_index.php?page=1";   $width=450; $height=250; break;

                case "9999" : echo "系統發生錯誤！或非正常路徑進入！";
                             $url = "/./backend/backend_panel.php";   $width=400; $height=250; break;
            }
            ?>

        </div>

        <script>
        $(function () {
            $("#dialog").dialog({
                modal: true,
                width : <?php echo $width ?> ,
                height: <?php echo $height ?>,
                buttons: {
                    "確認": function () {
                        $(this).dialog("close");
                        window.location.href = '<?php echo $url ?>';
                    }
                }
            });
        });
        </script>

    </body>
</html>
<?php
    if( !isset($_SESSION) ) session_start(); 
    session_destroy();

    if( !isset($_GET["msg"]) ) $msg = "999";
    else                       $msg = $_GET["msg"];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>喜憨兒基金會 人才登錄分析平台</title>

    <?php include('../include/toolkit.php'); ?>

    <link rel="stylesheet" href="../css/html/topic_page_error.css?<?php echo date("is"); ?>" />


</head>
<body>
    <div id="dialog" title="喜憨兒基金會 人才培訓分析平台"><p> 
    <?php
        switch($msg){
         
            //系統
            case "901" : echo "非正常網址進入！請返回首頁！";                             $url = "/"; $width=400; break;
            case "902" : echo "發現開啟多個視窗或非正常流程作答，有作弊嫌疑<br/>請返回首頁！";     $url = "/"; $width=550; break;

            default:
            case "999" : echo "作答已經終止！請返回首頁！"; $url = "/"; $width=400; break;
        }

    ?>

    </p></div>

    <script>
        $(function () {
            $("#dialog").dialog({
                modal: true,
                width: '<?php echo $width ?>',
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
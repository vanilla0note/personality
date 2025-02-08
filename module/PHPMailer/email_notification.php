<?php
    /* 寄送郵件通知 - 寄送測驗結果 */
    include('PHPMailerAutoload.php'); //匯入PHPMailer類別

    //$Complete_message = [
    //                    "sender_Password"      => "&&CMIS5933",                 //寄件人mail password
    //                    "Recipient_mail"       => "t00642@careus.org.tw",       //主要收件人mail(字串)
    //                    "Copy_Recipient_mail"  => "eric@careus.org.tw",       //副本收件人mail(字串)
    //                    "message_title"        => "郵件測試",                   //郵件主旨
    //                    "message_content"      => "大家好, 這是一封測試信件!",  //郵件內文

    //                    "exam_id"          => "sample_exam_file",           //試題卷編號
    //                    "interview_name"   => "王大明"                      //作答人姓名
    //                    ] ;

    //email_notification($Complete_message);


    function email_notification($Complete_message){

        /*
         $Complete_message = [
                               "sender_Password"       //寄件人mail password
                               "Recipient_mail"        //主要收件人mail
                               "Copy_Recipient_mail"   //副本收件人mail
                               "message_title"         //郵件主旨
                               "message_content"       //郵件內文
                               "exam_id"               //試題卷編號
                               "interview_name"        //作答人姓名
                             ]
        */

        $message= new PHPMailer();                        //建立新物件
        $message->IsSMTP();                               //設定使用SMTP方式寄信
        $message->SMTPAuth = true;                        //設定SMTP需要驗證
        $message->Host = "smtp.office365.com";            //設定SMTP主機
        $message->Port = 587;                             //設定SMTP埠位
        $message->CharSet = "UTF-8";                      //設定郵件編碼

        $message->Username = "Careus-mis@careus.org.tw" ;             //寄件人帳號
        $message->Password = $Complete_message["sender_Password"] ;   //寄件人密碼

        $message->From = "Careus-mis@careus.org.tw";     //設定寄件者顯示信箱
        $message->FromName = "資訊組公共信箱";           //設定寄件者顯示姓名

        $message->Subject = $Complete_message["message_title"] ;     //設定郵件標題
        $message->Body    = $Complete_message["message_content"] ;   //設定郵件內容
        $message->IsHTML(true);                          //設定郵件內容為HTML

        //收件資訊(因要寄送給多個收件人，因此用,分割每一個mail)
        $Address_mail = explode(",",$Complete_message["Recipient_mail"]);
        for ( $i=0 ; $i< count($Address_mail) ; $i++ )
            $message->AddAddress($Address_mail[$i]);     //主要收件人

        $AddressCC_mail = explode(",",$Complete_message["Copy_Recipient_mail"]);
        for ( $i=0 ; $i< count($AddressCC_mail) ; $i++ )
            $message->AddCC($AddressCC_mail[$i]);     //副本收件人

        //$message->AddCC('Careus-mis@careus.org.tw','資訊組公用信箱');  //副本
        /*$message->AddBCC('it@careus.org.tw','資訊組');               // 密件副本*/

        //相對路徑
        //$index_file_path       = "../../filedir/".$Complete_message['exam_id']."/index.xls";           //人格特質總表.xls
        //$radar_chart_file_path = "../../filedir/" . $Complete_message['exam_id'] . "/radar_chart.png"; //人格分析雷達圖.xls

        //絕對路徑
        $index_file_path       = $_SERVER['DOCUMENT_ROOT']."/filedir/".$Complete_message['exam_id']."/index.xls";           //人格特質總表.xls
        $radar_chart_file_path = $_SERVER['DOCUMENT_ROOT']."/filedir/".$Complete_message['exam_id']."/radar_chart.png"; //人格分析雷達圖.xls

        $message->AddAttachment($index_file_path, $Complete_message['interview_name']."_人格特質總表.xls");        // 新增附件
        $message->AddAttachment($radar_chart_file_path, $Complete_message['interview_name']."人格特質雷達圖.png"); // 新增附件

        if(!$message->Send())
            echo "信件寄送失敗：" . $message->ErrorInfo;
        else
            echo "信件寄送成功！";
    }

?>
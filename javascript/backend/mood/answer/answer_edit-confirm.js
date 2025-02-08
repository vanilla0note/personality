window.onload = function () {

}

//作答備註
function text_click_show(str) {

    //alert(str);

    $("#dialog").remove();
    $("body").append(
        "<div id='dialog' title='喜憨兒基金會 人才登錄分析平台'>" +
        "<div style='margin:0 auto; padding:20px ;font-size:18px;'>" +
        str +
        "</div > " +
        "</div >");

    $("#dialog").dialog({
        modal: true,
        width: 450,
        height: 250,
        buttons: {
            "關閉視窗": function () {
                $(this).dialog("close");
            }
        }
    });
}

//作答花費時間
function Times_Score(id) {

    //alert(id);

    var start_date ; 
    var final_date ;
    var Times_Score;

    $.ajax({
        type: "POST",
        url: "personality_Times_Score_service.php",
        dataType: "json",
        async: false,  //非同步 (需等待全部的值都回傳回來，才執行下一步)
        data: {
            myckeck_id_val: id,
        },

        success: function (data) { 
            start_date = data.start_date;
            //console.log(start_date);
            final_date = data.final_date;
            //console.log(final_date);
            Times_Score = data.Times_Score;
            //console.log(Times_Score);
        },

        error: function (jqXHR, textStatus, errorThrown) {

            alert("錯誤！請洽設計開發人員！");
            console.log(jqXHR.readyState);
            console.log(jqXHR.status);
            console.log(jqXHR.statusText);
            console.log(jqXHR.responseText);
            console.log(textStatus);
            console.log(errorThrown);
        }
    })


    $("#dialog").remove();
    $("body").append(
        "<div id='dialog' title='喜憨兒基金會 人才登錄分析平台－作答時間'>" +
        "<div style='margin:0 auto; padding:20px ;font-size:18px;letter-spacing:2px;'>" +
            "開始作答時間：" + start_date + "<br/>" +
            "最後作答時間：" + final_date + "<br/><br/>" +
        "總花費時間：" + Math.floor(Times_Score/60) + " 分 " + Times_Score%60 + " 秒 (" + Times_Score +"秒)<br/>" +
        "</div > " +
        "</div >");

    $("#dialog").dialog({
        modal: true,
        width: 450,
        height: 280,
        buttons: {
            "關閉視窗": function () {
                $(this).dialog("close");
            }
        }
    });


}


/*匯出檔案 選單*/
function output_report(id) {

    //alert(id);

    //匯出檔案 內容產生
    var context = "<div style='width:368px;height:50px;line-height:30px;font-size:20px;font-family: Microsoft JhengHei, msjhbd;font-weight:bold;'>選擇匯出檔案類型</div>" +

                  "<div style='width:320px; height:100px;float:left;'>" +
                  "<div style='float:right;padding:15px 50px 0 0'><div class='btn' onclick=output_every_answer_excel('"+ id + "')>逐題答案</div></div>" +
                  "</div>";


    $("#dialog").remove();
    $("body").append(
        "<div id='dialog' title='喜憨兒基金會 人才登錄分析平台－匯出檔案'>" +
        "<div style='margin:0 auto; padding:20px ;font-size:18px;letter-spacing:2px;'>" +
        context +
        "</div > " +
        "</div >");

    $("#dialog").dialog({
        modal: true,
        width: 420,
        height: 360,
        buttons: {
            "關閉視窗": function () {
                $(this).dialog("close");
            }
        }
    });

}

/*匯出檔案 - 總表*/
//function output_personality_type_excel(id) {

//    //alert("output_personality_type_excel");
//}


/*匯出檔案 - 逐題答案*/
function output_every_answer_excel(id) {

    //alert("output_every_answer_excel");

    $.blockUI({
        message: '匯出逐題答案檔案，請勿關閉視窗！',
        css: {
            border: 'none',
            padding: '15px',
            'text-align': 'center',
            'font-size': '25px',
            'font-family': 'Microsoft JhengHei',

            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
        },
    })

    //等待時間 2 秒，解除BlockUI
    setTimeout(function () {
        $.unblockUI();

    }, 2000);

    window.location.assign("output_every_answer_excel.php?x=" + id);

}


//寄送郵件通知 按鈕
//function send_report_page(id) {

//    //alert(id);
//    window.location.assign("../report/.php?x=" + id);

//}


/*儲存更新資料*/
function update_mood() {

    //alert("update_exam");

    $.blockUI({
        message: '儲存更新中',
        css: {
            border: 'none',
            padding: '15px',
            'text-align': 'center',
            'font-size': '25px',
            'font-family': 'Microsoft JhengHei',

            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
        },
    });

    myForm1.action = "answer_edit-execution.php";
    myForm1.submit();

}


/*不儲存離開*/
//function return_exam() {
//    alert("return_exam");
//}
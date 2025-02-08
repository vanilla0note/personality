window.onload = function () {

    //alert("今天是" + Today.getDate() + "號");

    //設定datepicker為中文
    $.datepicker.regional['zh-TW'] = {
        dayNames: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
        dayNamesMin: ["日", "一", "二", "三", "四", "五", "六"],
        monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
        monthNamesShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
        prevText: "上月",
        nextText: "次月",
        weekHeader: "週"
    };
    //將預設語系設定為中文
    $.datepicker.setDefaults($.datepicker.regional["zh-TW"]);

    minNumber = 0;
    maxNumber = 180;

    $("#appointment_date").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        //minDate: minNumber, //限制最小日期
        //maxDate: maxNumber, //限制最大日期
        //beforeShowDay: $.datepicker.noWeekends, //限定周末不可選擇
    });
    $("#resignation_date").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        //minDate: minNumber, //限制最小日期
        //maxDate: maxNumber, //限制最大日期
        //beforeShowDay: $.datepicker.noWeekends //限定周末不可選擇
    });
    //修改限制日期
    $("#appointment_date").datepicker("option", {
        //minDate: minNumber,
        //maxDate: maxNumber,
        //beforeShowDay: $.datepicker.noWeekends
    });
    $("#resignation_date").datepicker("option", {
        //minDate: minNumber,
        //maxDate: maxNumber,
        //beforeShowDay: $.datepicker.noWeekends
    });


    //給予預設日期
    $("#appointment_date").val($("#hidden_Appointment_date").val());
    $("#resignation_date").val($("#hidden_Resignation_date").val());

}


//人格分析類型
function view_personality_type(interview_name,type_score) {

    var arr = JSON.parse("[" + type_score + "]");
    //alert(arr[0]);

    //人格分析類型內容產生
    var context = "<div style='width:918px;height:50px;line-height:30px;font-size:20px;font-family: Microsoft JhengHei, msjhbd;font-weight:bold;'>" + interview_name + "－人格分析類型</div>" +

                  "<div style='width:368px; height:440px;float:left;'>" +
                  "<div class='dialog_type_title'>人格特質類型</div><div class='dialog_type_title'>作答分數</div>" +
                  "<div class='dialog_type_content' style='cursor:pointer;' onclick=personality_type_click_explain('A');><u>[1號]完美型</u></div><div class='dialog_type_content'>" + arr[0] + "</div>" +
                  "<div class='dialog_type_content' style='cursor:pointer;' onclick=personality_type_click_explain('B');><u>[2號]助人型</u></div><div class='dialog_type_content'>" + arr[1] + "</div>" +
                  "<div class='dialog_type_content' style='cursor:pointer;' onclick=personality_type_click_explain('C');><u>[3號]成就型</u></div><div class='dialog_type_content'>" + arr[2] + "</div>" +
                  "<div class='dialog_type_content' style='cursor:pointer;' onclick=personality_type_click_explain('D');><u>[4號]自我型</u></div><div class='dialog_type_content'>" + arr[3] + "</div>" +
                  "<div class='dialog_type_content' style='cursor:pointer;' onclick=personality_type_click_explain('E');><u>[5號]理智型</u></div><div class='dialog_type_content'>" + arr[4] + "</div>" +
                  "<div class='dialog_type_content' style='cursor:pointer;' onclick=personality_type_click_explain('F');><u>[6號]忠誠型</u></div><div class='dialog_type_content'>" + arr[5] + "</div>" +
                  "<div class='dialog_type_content' style='cursor:pointer;' onclick=personality_type_click_explain('G');><u>[7號]活躍型</u></div><div class='dialog_type_content'>" + arr[6] + "</div>" +
                  "<div class='dialog_type_content' style='cursor:pointer;' onclick=personality_type_click_explain('H');><u>[8號]領袖型</u></div><div class='dialog_type_content'>" + arr[7] + "</div>" +
                  "<div class='dialog_type_content' style='cursor:pointer;' onclick=personality_type_click_explain('I');><u>[9號]和平型</u></div><div class='dialog_type_content'>" + arr[8] + "</div>" +
                  "</div>" +

                  "<div style='width:550px; height:440px;padding:0 0 0 20px;float:left;'>" +
                  "<div class='dialog_type_title2'>描述</div><div class='dialog_type_title2'>適合工作</div>" +

                  "<div id='type_describe' class='dialog_type_content2'>(請點選左方特質類型進一步分析)</div><div id='hope_work' class='dialog_type_content2'></div>" +

                  "</div>";

    //dialog產生
    $("#dialog").remove();
    $("body").append(
        "<div id='dialog' title='喜憨兒基金會 人才登錄分析平台－人格分析類型'>" +
        "<div id='type_table' style='width: 1000px; height:500px; margin:0 auto; padding:20px ;font-size:18px;'>" +
        context +
        "</div > " +
        "</div >");

    $("#dialog").dialog({
        modal: true,
        width: 1100,
        height: 700,
        buttons: {
            "關閉視窗": function () {
                $(this).dialog("close");
            }
        }
    });

}

//作答備註
function remark_click_show(str) {

    //alert(str);

    $("#dialog").remove();
    $("body").append(
        "<div id='dialog' title='喜憨兒基金會 人才登錄分析平台－作答備註'>" +
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


//人格分析類型(點擊解釋)
function personality_type_click_explain(type) {

    //alert(type);

    $.ajax({
        type: "POST",
        url: "personality_type_explain_service.php",
        dataType: "json",
        async: false,  //非同步 (需等待全部的值都回傳回來，才執行下一步)
        data: {
            type: type,
        },

        success: function (data) {
            //console.log(data.name);
            var name = data.name;
            //console.log(data.type_describe);
            var type_describe = data.type_describe;
            //console.log(data.hope_work);
            var hope_work = data.hope_work;

            $("#type_describe").html(type_describe);
            $("#hope_work").html(hope_work);
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


}


//作答重複標記提示訊息
function same_readme(event) {

    //alert(event.checked);

    if (event.checked) {

        $("#dialog").remove();
        $("body").append(
            "<div id='dialog' title='喜憨兒基金會 人才登錄分析平台－重覆作答標記' style='font-size:18px;letter-spacing:2px;'><br/>如遇到同一位面試者<b>重覆多次</b>答題<br/><br/>請勾選此選項，將這份作答卷標記為第二次以上的重覆答題<br/></div>");

        $("#dialog").dialog({
            modal: true,
            width: 600,
            height: 280,
            buttons: {
                "關閉訊息": function () {
                    $(this).dialog("close");
                }
            },
        })
    }
    //else {
    //    //boxTxt.innerText = "關";
    //}

}


/*匯出檔案 選單*/
function output_report(id) {

    //alert(id);

    //匯出檔案 內容產生
    var context = "<div style='width:368px;height:50px;line-height:30px;font-size:20px;font-family: Microsoft JhengHei, msjhbd;font-weight:bold;'>選擇匯出檔案類型</div>" +

                  "<div style='width:320px; height:100px;float:left;'>" +
                  "<div style='float:right;padding:15px 50px 0 0'><div class='btn' onclick=output_personality_type_excel('"+ id + "')>人格特質總表</div></div>" +
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

/*匯出檔案 - 人格特質總表*/
function output_personality_type_excel(id) {

    //alert("output_personality_type_excel");

    $.blockUI({
        message: '匯出人格特質總表檔案，請勿關閉視窗！',
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

    window.location.assign("output_personality_type_excel.php?x=" + id);
}


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


////寄送郵件通知 按鈕
//function send_report_page(id) {

//    //alert(id);
//    window.location.assign("../report/.php?x=" + id);

//}


/*儲存更新資料*/
function update_exam() {

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
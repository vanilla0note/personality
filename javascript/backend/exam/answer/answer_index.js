$(document).ready(function () {

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

    $("#filter-exam-date01").datepicker({
        dateFormat: 'yy-mm-dd',
        //minDate: -90, //限制最小日期
        maxDate: 0, //限制最大日期
    });
    $("#filter-exam-date02").datepicker({
        dateFormat: 'yy-mm-dd',
        //minDate: -90, //限制最小日期
        maxDate: 0, //限制最大日期
    });

    $("#filter-exam-date01").datepicker({
        dateFormat: 'yy-mm-dd',
        //minDate: -90, //限制最小日期
        maxDate: 0, //限制最大日期
    });
    $("#filter-exam-date02").datepicker({
        dateFormat: 'yy-mm-dd',
        //minDate: -90, //限制最小日期
        maxDate: 0, //限制最大日期
    });

});


/*查詢*/
function filter_ready() {
    //alert("訂單資料載入中！");

    $.blockUI({
        message: '資料載入中，請勿關閉視窗！',
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

    $.ajax({
        type: "POST",
        url: "answer_filter_service.php",
        dataType: "json",
        data: {            
            myckeck_filter_exam_date01_val: $("#filter-exam-date01").val(),
            myckeck_filter_exam_date02_val: $("#filter-exam-date02").val(),
            myckeck_filter_exam_id_val: $("#filter-exam-id").val(), 
            myckeck_filter_exam_name_val: $("#filter-exam-name").val(),
            myckeck_filter_exam_area_val: $("#filter-exam-area").val(),
            myckeck_filter_exam_dep_val: $("#filter-exam-dep").val(),
            myckeck_filter_exam_state_val: $("#filter-exam-state").val(),                      
        },

        success: function (data) {

            //$.unblockUI(); //解除前台搜尋狀態
            //console.log(data.mysql);
            window.location.assign("answer_index.php?page=1");
        },

        error: function (jqXHR, textStatus, errorThrown) {

            $.unblockUI(); //解除前台搜尋狀態

            alert("資料載入錯誤！請洽設計開發人員！");
            console.log(jqXHR.readyState);
            console.log(jqXHR.status);
            console.log(jqXHR.statusText);
            console.log(jqXHR.responseText);
            console.log(textStatus);
            console.log(errorThrown);

        }
    })
}


/*返回*/
function answer_return() {

    window.location.assign("../../backend_panel.php");

}

/*標題：狀態*/
function state_explain() {

    $("#dialog").remove();
    $("body").append(
        "<div id='dialog' title='喜憨兒基金會 人才登錄分析平台－狀態圖示說明' style='font-size:18px;letter-spacing:2px;'><br/>" +
        "<img src='/./img/backend/accept_icon.png'/>：<b>分數計算完成</b><br/><br/>" +
        "<img src='/./img/backend/oxygen_icon.png'/>：<b>無效試卷</b><br/>" +        
        "</div>");

    $("#dialog").dialog({
        modal: true,
        width: 420,
        height: 280,
        buttons: {
            "確定": function () {
                $(this).dialog("close");
            }
        },

    });

}



/*試題卷檢視*/
function answer_paper_edit(x) {

    var colony; //權限判定

    $.ajax({
        type: "POST",
        url: "answer_view_colony_service.php",
        dataType: "json",
        async: false,
        data: {
            myckeck_id_val: x,
        },
        success: function (data) {
            colony = data.colony;
            //console.log(data.colony);
        },
        error: function (jqXHR) {
            alert("錯誤！請洽管理員！");
            console.log(jqXHR.readyState);
            console.log(jqXHR.status);
            console.log(jqXHR.statusText);
            console.log(jqXHR.responseText);

        }
    })


    if (colony == 'true') window.location.assign("answer_edit-confirm.php?x=" + x);
    else if (colony == 'false') {

        $("#dialog").remove();
        $("body").append(
            "<div id='dialog' title='喜憨兒基金會 人才登錄分析平台' style='font-size:20px;letter-spacing:2px;'><br/>無法檢視這張試題卷！<br/></div>");
        $("#dialog").dialog({
            modal: true,
            width: 400,
            height: 250,
            buttons: {
                "確定": function () {
                    $(this).dialog("close");
                }
            },

        });
    }
}


/*當下試題卷計算分數*/
function score_calculate(id,msg) {

    //alert("計算:" + id); 
    //alert("訊息:" + msg); 

    $("#dialog").remove();

    /* T：試題卷有完成　F：試題卷沒有完成 */
    if (msg == 'T') {

        $("body").append(
            "<div id='dialog' title='喜憨兒基金會 人才登錄分析平台－執行分數計算' style='font-size:18px;letter-spacing:2px;'><br/>試題卷編號：" + id + "，<b>立即執行分數計算嗎？</b><br/></div>");

        $("#dialog").dialog({
            modal: true,
            width: 550,
            height: 220,
            buttons: {
                "立即執行": function () {
                    $(this).dialog("close");

                    $.ajax({
                        type: "POST",
                        url: "Now_Score_calculate_service.php",
                        dataType: "json",
                        data: {
                            myckeck_id_val: id,
                        },

                        success: function (data) {
                            //console.log(data.type_score);

                            $("#dialog").remove();
                            $("body").append(
                                "<div id='dialog' title='喜憨兒基金會 人才登錄分析平台－執行分數計算' style='font-size:18px;letter-spacing:2px;'><br/>試題卷狀態已經更新！<br/></div>");
                            $("#dialog").dialog({
                                modal: true,
                                width: 420,
                                height: 220,
                                buttons: {
                                    "關閉視窗": function () {
                                        $(this).dialog("close");
                                        window.location.reload();
                                    }
                                },
                            });
                        },
                        error: function (jqXHR) {
                            alert("錯誤！請洽管理員！");
                            //console.log(jqXHR.readyState);
                            //console.log(jqXHR.status);
                            //console.log(jqXHR.statusText);
                            //console.log(jqXHR.responseText);
                        }
                    })
                },
                "取消": function () {
                    $(this).dialog("close");
                }
            },
        });

    }
    else if (msg == 'F') {

        $("body").append(
            "<div id='dialog' title='喜憨兒基金會 人才登錄分析平台－執行分數計算' style='font-size:18px;letter-spacing:2px;'>" +
            "<br/> 試題卷編號：" + id + "，這張試題卷還沒有完成<br/><br/>" +
            "但作答者已經超過 2 分鐘沒有作答了<br/><br/><br/>" +
            "<b>確定現在就要執行分數計算嗎？</b>（可能會判定為無效試卷）<br/>" +
            "</div >");

        $("#dialog").dialog({
            modal: true,
            width: 580,
            height: 380,
            buttons: {
                "立即執行": function () {
                    $(this).dialog("close");

                    $.ajax({
                        type: "POST",
                        url: "Now_Score_calculate_service.php",
                        dataType: "json",
                        data: {
                            myckeck_id_val: id,
                        },

                        success: function (data) {
                            //console.log(data.type_score);

                            $("#dialog").remove();
                            $("body").append(
                                "<div id='dialog' title='喜憨兒基金會 人才登錄分析平台－執行分數計算' style='font-size:18px;letter-spacing:2px;'><br/>試題卷狀態已經更新！<br/></div>");
                            $("#dialog").dialog({
                                modal: true,
                                width: 420,
                                height: 220,
                                buttons: {
                                    "關閉視窗": function () {
                                        $(this).dialog("close");
                                        window.location.reload();
                                    }
                                },
                            });
                        },
                        error: function (jqXHR) {
                            alert("錯誤！請洽管理員！");
                            //console.log(jqXHR.readyState);
                            //console.log(jqXHR.status);
                            //console.log(jqXHR.statusText);
                            //console.log(jqXHR.responseText);
                        }
                    })
                },
                "取消": function () {
                    $(this).dialog("close");
                }
            },
        });

    }






}




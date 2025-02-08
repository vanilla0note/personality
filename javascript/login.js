//false 閉眼  true 睜眼
var eyes_flag = false; 


//人格特質 測驗
function exam_tested()  { exam_remind(); }      //人格特質 測驗：測驗前提醒訊息
function score_tested() { score_remind(); }     //員工滿意度 調查：測驗前提醒訊息
function backend_login() { login_all_panel(); } //登入管理後台


/******* 準備試題卷，需要的部門選項 *******/
function dep_arrange() {

    var dep_arrange_results;

    $.ajax({
        type: "POST",
        url: "/html/login_dep_arrange_service.php",
        dataType: "json",
        async: false,
        data: {},
        success: function (data) {
            dep_arrange_results = data.dep_arrange_results;
            //console.log(dep_arrange_results);
        },
        error: function (jqXHR) {
            alert("錯誤！請洽管理員！");
            //console.log(jqXHR.readyState);
            //console.log(jqXHR.status);
            //console.log(jqXHR.statusText);
            //console.log(jqXHR.responseText);
        }
    })

    //console.log(dep_arrange_results);
    return dep_arrange_results;

}
/*****************************************/

/******* 準備試題卷，需要的組別和職位選項 *******/
function group_job_arrange() {

    //alert($("#dep").val());

    var group_arrange_results;
    var job_arrange_results;
    $('#group').empty(); //清空group選項
    $('#job').empty(); //清空job選項

    $.ajax({
        type: "POST",
        url: "/html/login_group_job_arrange_service.php",
        dataType: "json",
        async: false,
        data: {
            myckeck_exam_dep_data: $("#dep").val(),
        },
        success: function (data) {
            group_arrange_results = data.group_arrange_results;
            job_arrange_results = data.job_arrange_results;
            //console.log(group_arrange_results);
            //console.log(job_arrange_results);
        },
        error: function (jqXHR) {
            alert("錯誤！請洽管理員！");
            console.log(jqXHR.readyState);
            console.log(jqXHR.status);
            console.log(jqXHR.statusText);
            console.log(jqXHR.responseText);
        }
    })

    //console.log(job_arrange_results);

    //把組別選項往 group select 內容放入
    for ( i=0 ; i<group_arrange_results.length ; i=i+2 )
        $('#group').append($("<option></option>").attr("value", group_arrange_results[i]).text(group_arrange_results[i+1]) )
    //把職位選項往 job select 內容放入
    for ( i=0 ; i<job_arrange_results.length ; i=i+2 )
        $('#job').append($("<option></option>").attr("value", job_arrange_results[i]).text(job_arrange_results[i+1]) )

}
/************************************************/

/********************************/
//人格特質 測驗：測驗前提醒訊息
function exam_remind() {

    $("#dialog").remove();
    $("body").append(
        "<div id='dialog' title='喜憨兒基金會 人格特質測驗－提醒訊息' style='font-size:22px;letter-spacing:3px;'><br/><br/>" +
        "面試者或員工填寫測驗時，基本資料<font color='#F00'>請務必填寫正確</font>！<br/><br/>" +
        "包含：<b>姓名(填寫中文姓名)</b>、<b>性別</b>、<b>應徵資料</b><br/><br/><br/>" +

        "</div>");

    $("#dialog").dialog({
        modal: true,
        width: 700,
        height: 380,
        buttons: {
            "我知道了": function () {
                $(this).dialog("close");

                exam_all_panel(); //人格特質 測驗：準備試題卷
            }
        },

    });

}

//人格特質 測驗：準備試題卷
function exam_all_panel() {

    $("#dialog").remove();
    $("body").append(
        "<form name='myForm'><div id='dialog' title='喜憨兒基金會 人格特質測驗' style='font-size:22px;letter-spacing:4px;'><br/>" +
        "<b>姓　　名</b>：<input type='text' name='name' id='name' value=''  placeholder='請填寫測驗者真實姓名'><br/><br/>" +
        "<b>性　　別</b>：<select name='sex' id='sex'><option value='sex_m'>男</option><option value='sex_f'>女</option></select><br/><br/>" +
        "<b>應徵區域</b>：<select name='area' id='area'><option value='Taipei'>台北地區</option><option value='Taoyuan'>桃園地區</option><option value='Hsinchu'>新竹地區</option><option value='Tainan'>台南地區</option><option value='Kaohsiung'>高雄地區</option></select><br/><br/>" +
        "<b>應徵部門</b>：<select name='dep' id='dep' onchange='group_job_arrange()'><option disabled selected>請選擇應徵部門</option>" + dep_arrange() + "</select><br/><br/>" +
        "<b>應徵組別</b>：<select name='group' id='group'></select><br/><br/>" +
        "<b>應徵職務</b>：<select name='job' id='job'></select><br/><br/>" +
        "<b>備　　註</b>：<input type='text' name='remark'  id='remark'  value=''  placeholder=''>&nbsp;" +
        "</div></form>");

    $("#dialog").dialog({
        modal: true,
        width: 500,
        height: 680,
        buttons: {
            "確定": function () {

                $("#name").css("border-color", "");
                $("#dep").css("border-color", "");
                $("#job").css("border-color", "");

                //console.log($("#job").val() );

                if ($("#name").val() == "") $("#name").css("border-color", "red");
                else if ($("#dep").val() == null) $("#dep").css("border-color", "red");
                else if ($("#group").val() == null) $("#group").css("border-color", "red");
                else if ($("#job").val() == null) $("#job").css("border-color", "red");
                else {                    
                    $.ajax({
                        type: "POST",
                        url: "/html/exam/QA_create_service.php",
                        dataType: "json",
                        data: {
                            myckeck_name: $("#name").val(),
                            myckeck_sex: $("#sex").val(),
                            myckeck_area: $("#area").val(),
                            myckeck_dep: $("#dep").val(),
                            myckeck_group: $("#group").val(),
                            myckeck_job: $("#job").val(),
                            myckeck_remark: $("#remark").val(),
                        },
                        success: function (data) {
                            $(location).attr('href', '/html/exam/topic_starting.php');

                            myexam_id = data.myexam_id;
                            console.log(myexam_id);
                        },
                        error: function (jqXHR) {
                            alert("錯誤！請洽管理員！");
                            console.log(jqXHR.readyState);
                            console.log(jqXHR.status);
                            console.log(jqXHR.statusText);
                            console.log(jqXHR.responseText);
                        }
                    })
                }

            },
            "取消": function () {
                $(this).dialog("close");
            }
        }
    });
};


/********************************/


//員工滿意度 調查：測驗前提醒訊息
function score_remind() {

    $("#dialog").remove();
    $("body").append(
        "<div id='dialog' title='喜憨兒基金會 員工滿意度調查－提醒訊息' style='font-size:22px;letter-spacing:3px;'><br/><br/>" +
        "員工填寫滿意度問卷，基本資料<font color='#F00'>請務必填寫正確</font>！<br/><br/>" +
        "包含：<b>姓名(填寫中文姓名)</b>、<b>性別</b>、<b>員工帳號</b>、<b>任職部門與職位</b><br/><br/><br/>" +

        "</div>");

    $("#dialog").dialog({
        modal: true,
        width: 760,
        height: 380,
        buttons: {
            "我知道了": function () {
                $(this).dialog("close");

                score_all_panel(); //員工滿意度 調查：準備試題卷
            }
        },

    });

}

//員工滿意度 調查：準備試題卷
function score_all_panel() {

    $("#dialog").remove();
    $("body").append(
        "<form name='myForm'><div id='dialog' title='喜憨兒基金會 員工滿意度調查' style='font-size:22px;letter-spacing:4px;line-height:30px;'><br/>" +
        "<b>姓　　名</b>：<input type='text' name='name' id='name' value=''  placeholder='請填寫測驗者真實姓名'><br/><br/>" +
        "<b>性　　別</b>：<select name='sex' id='sex'><option value='sex_m'>男</option><option value='sex_f'>女</option></select><br/><br/>" +
        "<b>員工帳號</b>：<input type='text' name='id' id='id' value=''  placeholder='請填寫員工帳號'><br/><br/>" +
        "<b>任職區域</b>：<select name='area' id='area'><option value='Taipei'>台北地區</option><option value='Taoyuan'>桃園地區</option><option value='Hsinchu'>新竹地區</option><option value='Tainan'>台南地區</option><option value='Kaohsiung'>高雄地區</option></select><br/><br/>" +
        "<b>任職部門</b>：<select name='dep' id='dep' onchange='group_job_arrange()'><option disabled selected>請選擇應徵部門</option>" + dep_arrange() + "</select><br/><br/>" +
        "<b>任職組別</b>：<select name='group' id='group'></select><br/><br/>" +
        "<b>任職職務</b>：<select name='job' id='job'></select><br/><br/>" +
        "<b>備　　註</b>：<input type='text' name='remark' id='remark' value=''  placeholder=''><br/><br/>" +
        "</div></form>");
        11
    $("#dialog").dialog({
        modal: true,
        width: 500,
        height: 770,
        buttons: {
            "確定": function () {

                $("#name").css("border-color", "");
                $("#id").css("border-color", "");
                $("#dep").css("border-color", "");
                $("#job").css("border-color", "");

                //console.log($("#job").val() );

                if ($("#name").val() == "") $("#name").css("border-color", "red");
                else if ($("#id").val() == "") $("#id").css("border-color", "red");
                else if ($("#dep").val() == null) $("#dep").css("border-color", "red");
                else if ($("#group").val() == null) $("#group").css("border-color", "red");
                else if ($("#job").val() == null) $("#job").css("border-color", "red");
                else {                    
                    $.ajax({
                        type: "POST",
                        url: "/html/mood/QA_create_service.php",
                        dataType: "json",
                        data: {
                            myckeck_name: $("#name").val(),
                            myckeck_sex: $("#sex").val(),
                            myckeck_id: $("#id").val(),
                            myckeck_area: $("#area").val(),
                            myckeck_dep: $("#dep").val(),
                            myckeck_group: $("#group").val(),
                            myckeck_job: $("#job").val(),
                            myckeck_remark: $("#remark").val(),
                        },
                        success: function (data) {
                            $(location).attr('href', '/html/mood/topic_starting.php');

                            //mymood_id = data.mymood_id;
                            //console.log(mymood_id);
                        },
                        error: function (jqXHR) {
                            alert("錯誤！請洽管理員！");
                            console.log(jqXHR.readyState);
                            console.log(jqXHR.status);
                            console.log(jqXHR.statusText);
                            console.log(jqXHR.responseText);
                        }
                    })
                }

            },
            "取消": function () {
                $(this).dialog("close");
            }
        }
    });
};

















/********************************/
//登錄管理後台
function login_all_panel() {

    $("#dialog").remove();
    $("body").append(
        "<form name='myForm'><div id='dialog' title='喜憨兒基金會 人才培訓分析平台' style='font-size:22px;letter-spacing:4px;'><br/>" +
        "帳號：<input type='text' name='backend_id' id='backend_id' value='admin'  placeholder='請輸入登入帳號'><br/><br/>" +
        "密碼：<input type='password' name='backend_pw'  id='backend_pw'  value='admin'  placeholder='請輸入登入密碼'>&nbsp;" +
        "</div></form>");

    $("#dialog").dialog({
        modal: true,
        width: 420,
        height: 300,
        buttons: {
            "確定": function () {

                $("#backend_id").css("border-color", "");
                $("#backend_pw").css("border-color", "");

                if ($("#backend_id").val() == "") $("#backend_id").css("border-color", "red");
                else if ($("#backend_pw").val() == "") $("#backend_pw").css("border-color", "red");
                else {
                    $.blockUI({
                        message: '系統登入中，請稍候！',
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
                        }
                    });


                    $.ajax({
                        type: "POST",
                        url: "/backend/backend_login_service.php",
                        dataType: "json",
                        data: {
                            myckeck_backend_id: $("#backend_id").val(),
                            myckeck_backend_pw: $("#backend_pw").val(),
                        },
                        success: function (data) {
                            var login_result = data.login_result;
                            console.log("login_result=" + login_result);

                            if (login_result) $(location).attr('href', '/backend/backend_panel.php');
                            else {
                                $.unblockUI();
                                $("#dialog").remove();
                                $("body").append(
                                    "<div id='dialog' title='喜憨兒基金會 人才培訓分析平台' style='font-size:20px;line-height:35px;letter-spacing:2px;'><br/>" +
                                    "帳號無法登入或密碼輸入錯誤！<br/>" +
                                    "</div>");
                                $("#dialog").dialog({
                                    modal: true,
                                    width: 400,
                                    height: 250,
                                    buttons: {
                                        "確定": function () { $(this).dialog("close"); }
                                    }
                                });
                            }

                        },
                        error: function (jqXHR) {
                            alert("錯誤！請洽管理員！");
                            console.log(jqXHR.readyState);
                            console.log(jqXHR.status);
                            console.log(jqXHR.statusText);
                            console.log(jqXHR.responseText);
                        }
                    })
                }

            },
            "取消": function () {
                $(this).dialog("close");
            }
        }
    });

}

/********************************/


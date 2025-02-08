function group_create() {

    $("#dialog").remove();
    $("body").append(
        "<div id='dialog' title='喜憨兒基金會 人才登錄分析平台－部門維護' style='font-size:22px;letter-spacing:2px;'><br />" +
        "組別名稱：<input id='new_group_name' type='text' value=''><br/><br/>" +
        "</div>");

    $("#dialog").dialog({
        modal: true,
        width: 450,
        height: 250,
        buttons: {
            "建立新組別": function () {
                if ($("#new_group_name").val() == "") {
                    //$("#dialog").remove();
                    $.blockUI({
                        message: '組別名稱不能空白！',
                        fadeIn: 700,
                        fadeOut: 700,
                        timeout: 2000,
                        showOverlay: false,
                        centerY: false,
                        css: {
                            width: '500px',
                            top: '20px',
                            left: '',
                            right: '20px',
                            border: 'none',
                            padding: '5px',
                            fontSize: '22px',
                            fontFamily: 'Microsoft JhengHei',
                            backgroundColor: '#000',
                            '-webkit-border-radius': '10px',
                            '-moz-border-radius': '10px',
                            opacity: .6,
                            color: '#fff'
                        }
                    });
                }
                else {
                    $.blockUI({
                        message: '組別建立中',
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
                        url: "department_edit-new_group_service.php",
                        dataType: "json",
                        data: {
                            myckeck_new_group_name_val: $("#new_group_name").val(),
                            myckeck_dep_id_val: $("#dep_id").val(),
                        },

                        success: function (data) {
                            $.unblockUI(); //解除前台搜尋狀態
                            dep_id = data.dep_id;
                            //console.log(data.job_id);
                            //console.log(data.dep_id);
                            //console.log(data.num);

                            window.location.assign("department_edit-confirm.php?id=" + dep_id);
                        },

                        error: function (jqXHR, textStatus, errorThrown) {

                            $.unblockUI(); //解除前台搜尋狀態

                            alert("資料載入錯誤！請洽設計開發人員！");
                            //console.log(jqXHR.readyState);
                            //console.log(jqXHR.status);
                            //console.log(jqXHR.statusText);
                            //console.log(jqXHR.responseText);
                            //console.log(textStatus);
                            //console.log(errorThrown);
                        }
                    })
                }
            },
            "取消": function () {
                $(this).dialog("close");
            }
        },

    });

}



function job_create() {

    $("#dialog").remove();
    $("body").append(
        "<div id='dialog' title='喜憨兒基金會 人才登錄分析平台－部門維護' style='font-size:22px;letter-spacing:2px;'><br />" +
        "職位名稱：<input id='new_job_name' type='text' value=''><br/><br/>" +
        "</div>");

    $("#dialog").dialog({
        modal: true,
        width: 450,
        height: 250,
        buttons: {
            "建立新職位": function () {
                if ($("#new_job_name").val() == "") {
                    //$("#dialog").remove();
                    $.blockUI({
                        message: '職位名稱不能空白！',
                        fadeIn: 700,
                        fadeOut: 700,
                        timeout: 2000,
                        showOverlay: false,
                        centerY: false,
                        css: {
                            width: '500px',
                            top: '20px',
                            left: '',
                            right: '20px',
                            border: 'none',
                            padding: '5px',
                            fontSize: '22px',
                            fontFamily: 'Microsoft JhengHei',
                            backgroundColor: '#000',
                            '-webkit-border-radius': '10px',
                            '-moz-border-radius': '10px',
                            opacity: .6,
                            color: '#fff'
                        }
                    });
                }
                else{
                    $.blockUI({
                        message: '職位建立中',
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
                        url: "department_edit-new_job_service.php",
                        dataType: "json",
                        data: {
                            myckeck_new_job_name_val: $("#new_job_name").val(),
                            myckeck_dep_id_val: $("#dep_id").val(),
                        },

                        success: function (data) {
                            $.unblockUI(); //解除前台搜尋狀態
                            dep_id = data.dep_id;
                            //console.log(data.job_id);
                            //console.log(data.dep_id);
                            //console.log(data.num);

                            window.location.assign("department_edit-confirm.php?id=" + dep_id);
                        },

                        error: function (jqXHR, textStatus, errorThrown) {

                            $.unblockUI(); //解除前台搜尋狀態

                            alert("資料載入錯誤！請洽設計開發人員！");
                            //console.log(jqXHR.readyState);
                            //console.log(jqXHR.status);
                            //console.log(jqXHR.statusText);
                            //console.log(jqXHR.responseText);
                            //console.log(textStatus);
                            //console.log(errorThrown);
                        }
                    })
                }
            },
            "取消": function () {
                $(this).dialog("close");
            }
        },

    });
}


function dep_edit_return() {

    window.location.assign("department_index.php");

}

function form_run() {

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

    myForm1.action = "department_edit-execution.php";
    myForm1.submit();
}

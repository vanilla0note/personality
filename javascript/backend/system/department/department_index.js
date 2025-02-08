function department_create() {

    //alert("department_create");

    $("#dialog").remove();
    $("body").append(
        "<div id='dialog' title='喜憨兒基金會 人才登錄分析平台－部門維護' style='font-size:22px;letter-spacing:2px;'><br />" +
        "部門名稱：<input id='new_dep_name' type='text' value=''><br/><br/>" +
        "</div>");

    $("#dialog").dialog({
        modal: true,
        width: 450,
        height: 250,
        buttons: {
            "建立新部門": function () {
                if ($("#new_dep_name").val() == "") {
                    //$("#dialog").remove();

                    $.blockUI({
                        message: '部門名稱不能空白！',
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
                        message: '部門建立中',
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
                        url: "department-new_dep_service.php",
                        dataType: "json",
                        data: {
                            myckeck_new_dep_name_val: $("#new_dep_name").val(),
                        },

                        success: function (data) {
                            $.unblockUI(); //解除前台狀態
                            location.reload(true);
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

//編輯部門
function department_edit(x) {

    window.location.assign("department_edit-confirm.php?id=" + x);

}

//部門啟用狀態
function department_acc_edit(x) {

    dep_id = x;

    $.blockUI({
        message: '資料更新中',
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
        url: "department_acc_service.php",
        dataType: "json",
        data: {
            myckeck_dep_id_val: dep_id,
        },

        success: function (data) {
            $.unblockUI(); //解除前台狀態
            location.reload(true);
        },

        error: function (jqXHR, textStatus, errorThrown) {

            $.unblockUI(); //解除前台狀態

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


/*查詢*/
function filter_ready() {

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
        url: "department_filter_service.php",
        dataType: "json",
        data: {
            myckeck_filter_dep_name_val: $("#filter-dep-name").val(),
            myckeck_filter_dep_acc_val: $("#filter-dep-acc").val(),
        },

        success: function (data) {

            //$.unblockUI(); //解除前台搜尋狀態
            //console.log(data.mysql);
            window.location.assign("department_index.php?page=1");
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
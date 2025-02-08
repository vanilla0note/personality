var group_select = ""; //權限群組 變數

//新建帳號
function account_create() {

    $("#dialog").remove();
    $("body").append(
        "<div id='dialog' title='喜憨兒基金會 人才登錄分析平台－帳號權限維護' style='font-size:22px;letter-spacing:2px;'><br />" +
        "帳號名稱：<input id='new_account_id'   type='text' value=''><br/><br/>" +
        "帳號類型：<select id='new_account_type' style='width:210px;'><option value='local'>系統帳號</option><option value='cloud'>員工帳號</option></select><br/><br/>" +
        "</div>");

    $("#dialog").dialog({
        modal: true,
        width: 550,
        height: 350,
        buttons: {
            "建立帳號": function () {
                if ($("#new_account_id").val() == "") {
                    //$("#dialog").remove();

                    $.blockUI({
                        message: '帳號名稱不能空白！',
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
                        message: '帳號建立中',
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
                        url: "account-new_id_service.php",
                        dataType: "json",
                        data: {
                            myckeck_new_account_id_val: $("#new_account_id").val(),
                            myckeck_new_account_type_val: $("#new_account_type").val(),
                        },

                        success: function (data) {
                            $.unblockUI(); //解除前台狀態
                            location.reload(true);
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
            },
            "取消": function () {
                $(this).dialog("close");
            }
        },

    });

}

//權限群組設定
function group_edit() {

    window.location.assign("account_group_index.php");

}


//編輯帳號
function account_edit(x) {

    window.location.assign("account_edit-confirm.php?id=" + x);

}


//啟用狀態
function account_acc_edit(x) {

    //alert("account_acc_edit:" + x);
    account_no = x;

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
        url: "account_acc_service.php",
        dataType: "json",
        data: {
            myckeck_account_no_val: account_no,
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


/*篩選器查詢*/
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
        url: "account_filter_service.php",
        dataType: "json",
        data: {
            myckeck_filter_account_id_val: $("#filter-account-id").val(),
            myckeck_filter_account_auth_val: $("#filter-account-auth").val(),
            myckeck_filter_account_type_val: $("#filter-account-type").val(),
            myckeck_filter_account_acc_val: $("#filter-account-acc").val(),
        },

        success: function (data) {

            //$.unblockUI(); //解除前台搜尋狀態
            //console.log(data.mysql);
            window.location.assign("account_index.php?page=1");
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
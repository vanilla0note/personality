function colony_read(content) {

    //alert("次項：" + content);
    $("#colony_read_content").empty(); //清空 內容

    switch (content) {
        case "ALL":
            $("#colony_read_content").append($('<option></option>').attr('value', 'ALL').text('全部地區'));
            $('#colony_read_content option').get(0).selected = true;
            //$('#colony_read_content option').attr('disabled', 'true');

            break;

        case "AREA":
        case "DEP":
            $.ajax({
                type: "POST",
                url: "account_edit_colony_prepare_service.php",
                dataType: "json",
                data: { mycheck_option: content, },
                success: function (data) {
                    var mycontent = data.mycontent;
                    //console.log(mycontent);
                    //console.log(mycontent['0'] );

                    //加入選項內容
                    for (i = 0; i < mycontent['length']; i++) {
                        //console.log(mycontent[i]);
                        $("#colony_read_content").append($("<option></option>").attr("value", mycontent[i][0]).text(mycontent[i][1]));
                    };

                    //$.unblockUI(); //解除前台搜尋狀態
                },

                error: function (jqXHR) {
                    //$.unblockUI(); //解除前台搜尋狀態
                    alert("錯誤！請洽管理員！");
                    //console.log(jqXHR.readyState);
                    //console.log(jqXHR.status);
                    //console.log(jqXHR.statusText);
                    //console.log(jqXHR.responseText);
                    //console.log(textStatus);
                    //console.log(errorThrown);
                }
            })

            break;

        default:break;
    }
    
}

function account_edit_return() {

    window.location.assign("account_index.php");

}


function form_run() {

    console.log($("#change_password").val());
    console.log($("#colony_read_content").val());

    $("#pw").css('border', '1px solid #999');
    $("#colony_read_content").css('border', '1px solid #999');

    if ($("#change_password").val() == "not_allowed" && $("#pw").val() != "") {

        $("#pw").css('border', '2px solid red');

        $.blockUI({
            message: '這個帳號為員工帳號，不能在此變更密碼！',
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
    else if ($("#colony_read_content").val() == null) {

        $("#colony_read_content").css('border', '2px solid red');

        $.blockUI({
            message: '權限設定不完整，無法儲存！',
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
            message: '正在儲存設定，請勿關閉視窗！',
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

        myForm1.action = "account_edit-execution.php";
        myForm1.submit();
    }

}


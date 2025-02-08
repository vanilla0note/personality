var eyes_flag = false;  //false 閉眼  true 睜眼

function password_eyes(x) {

    //alert(x);

    if      (eyes_flag == true)  eyes_flag = false;
    else if (eyes_flag == false) eyes_flag = true;

    //////// test ////////
    //alert("eyes_flag = " + eyes_flag);
    //////////////////////

    if (eyes_flag) {
        $("#eyes").attr("src", "../../../img/eyes_open.png");
        $("#mail_main_pw").attr("type", "text");
    }
    else {

        $("#eyes").attr("src", "../../../img/eyes_close.png");
        $("#mail_main_pw").attr("type", "password");
    }


}
//儲存更新
function mail_updata_edit() {

    //alert("mail_updata_edit");

    $.blockUI({
        message: '資料儲存中，請勿關閉視窗！',
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
        url: "mail_edit_service.php",
        dataType: "json",
        data: {
            myckeck_mail_main_id_val: $("#mail_main_id").val(),
            myckeck_mail_main_pw_val: $("#mail_main_pw").val(),
            myckeck_mail_cc_val: $("#mail_cc").val(),
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

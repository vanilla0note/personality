function schedule_acc_edit() {

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
        url: "schedule_acc_service.php",
        dataType: "json",
        data: {},

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
window.onload = function () {}


//寄送測驗結果
function send_report() {

    //alert("send_report");

    $.blockUI({
        message: '寄出郵件中，請勿關閉視窗！',
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

    myForm1.action = "answer_report-execution.php";
    myForm1.submit();
}

//返回作答卷
function return_to_exam(id) {

    //alert(id);
    window.location.assign("../answer/answer_edit-confirm.php?x=" + id);

}










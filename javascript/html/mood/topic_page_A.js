$(document).ready(function () {

    $("input[type='radio']").checkboxradio({ icon: false });
    $("fieldset").controlgroup();

    //$("#progress_bar").progressbar({
    //    value: 50
    //});
    //$("#progress_bar").progressbarValue({
    //    "background": '#' + "0000AA"
    //});

    $("#progress_bar").progressbar({ "value": 0 });
    $("#progress_bar").css({ 'background': '#EEE' });
    $("#progress_bar > div").css({ 'background': '#0AF' });

});


function progress_bar_update(x){

    //alert(x);

    $("#progress_bar").progressbar({ "value": x });

}


//填寫完成送出
function form_run() {

    //var formData = $('#myForm1').serializeArray();
    //console.log(formData);


    $.ajax({
        type: "POST",
        url: "/html/mood/topic_page_A_service.php",
        dataType: "json",
        data: {
            myckeck_formData: $('#myForm1').serialize(), //將表單內容存成字串
        },
        success: function (data) {
            page = data.mypage;
            //console.log(page);
            formData = data.myformData;
            //console.log(formData);

            complete_flag = data.mycomplete_flag;
            //console.log(complete_flag);

            if      (complete_flag == true)  from_complete();
            else if (complete_flag == false) from_not_complete();
        },
        error: function (jqXHR) {
            $(location).attr('href', '/html/topic_page_error.php?msg=999');
            //alert("錯誤！請洽管理員！");
            //console.log(jqXHR.readyState);
            //console.log(jqXHR.status);
            //console.log(jqXHR.statusText);
            //console.log(jqXHR.responseText);
        }
    })

}
    

//填寫未完成
function from_not_complete() {

    $("#dialog").remove();

    $("body").append(
        "<div id='dialog' title='喜憨兒基金會 人才培訓分析平台'>" +
        "<div style='width:350px; height:50px; margin:30px auto ;font-size:22px;'>還有題目未作答喔！</div>" +
        "</div >");
    $("#dialog").dialog({
        modal: true,
        width: 400,
        height: 250,
        resizable: false,
        buttons: {
            "繼續作答": function () {
                $(this).dialog("close");
            }
        }
    });

}

//填寫完成
function from_complete() {

    $.blockUI({
        message: '試題載入中，請稍候！',
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

    myForm1.action = "topic_page_A_execution.php";
    setTimeout(function () {
        myForm1.submit();
    }, 500);

}



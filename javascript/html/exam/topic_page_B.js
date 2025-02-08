$(document).ready(function () {

    $("input[type='radio']").checkboxradio({
        icon: false
    });

    //$("#progress_bar").progressbar({
    //    value: 50
    //});
    //$("#progress_bar").progressbarValue({
    //    "background": '#' + "0000AA"
    //});

    $("#progress_bar").progressbar({ "value": 100 });
    $("#progress_bar").css({ 'background': '#EEE' });
    $("#progress_bar > div").css({ 'background': '#0AF' });


});



function closing() {

    $(location).attr('href', 'https://www.c-are-us.org.tw/');
}
    


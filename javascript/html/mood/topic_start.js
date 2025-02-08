function starting(){

    $.ajax({
        type: "POST",
        url: "/html/mood/topic_starting_service.php",
        dataType: "json",
        data: { },
        success: function () {
            $(location).attr('href', '/html/mood/topic_page_A.php');
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





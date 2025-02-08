/*查詢*/
function form_run() {

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

    //var $form = $("#myForm1");
    //var data = getFormData($form);

    //console.log(data);
    //console.log(data["auth[4][2]"]);

    myForm1.action = "account_group-execution.php";
    myForm1.submit();

}

//function getFormData($form){
//    var unindexed_array = $form.serializeArray();
//    var indexed_array = {};

//    $.map(unindexed_array, function(n, i){
//        indexed_array[n['name']] = n['value'];
//    });

//    return indexed_array;
//}
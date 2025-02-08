var VisibleMenu = ''; // 記錄目前顯示的子選單的 ID
var myver = []; ; //版本更新號碼
var mypublish_date = []; ; //版本更新日期
var mycontent = []; ; //版本更新內容


//更新紀錄
function update() {
    
    $(function() {
        $("#dialog").remove();

        update_record();

        $("body").append(
            "<div id='dialog' title='喜憨兒基金會 人才登錄分析平台' style='font-size:17px;letter-spacing:3px;line-height:28px;'>" +

            "<br/>【版本號碼】&nbsp;" + myver[0] + "<br/>" +
            "<br/>【發佈日期】&nbsp;" + mypublish_date[0] + "<br/>" +
            "<br/>【系統更新項目】<br/>" +  
                     
            mycontent[0] +

            "<br/><br/>" +
            "</div >");

        $("#dialog").dialog({
            modal: true,
            width: 800,
            height:400,
            buttons: {
                "確認": function() {
                    $(this).dialog("close");
                }
            }
        });
    });
}

//關於系統
function ver() {

    $(function () {

        $("#dialog").remove();

        update_record();

        var record_content = ''; //用來記錄歷次更新紀錄
        for (i = 0; i < myver.length; i++) {
            record_content =
                record_content +
                "<div id='" + i + "' onclick='get_recard(this.id)' style='width:110px;height:40px;text-align:center;font-size:17px;padding: 0 0 0 10px;border: 1px solid #FFFFFF;line-height: 40px;background-color: #f3decd;float:left;cursor: pointer;'><u>" + myver[i] + "</u></div>" +
                "<div                       style='width:130px;height:40px;text-align:center;font-size:17px;padding: 0 0 0 10px;border: 1px solid #FFFFFF;line-height: 40px;background-color: #f3decd;float:left;'>" + mypublish_date[i] + "</div>";
        }


        $("body").append(
            "<div id='dialog' title='喜憨兒基金會 健康平台' style='font-size:17px;letter-spacing:2px;line-height:28px;'>" +

            "<br/>【目前版本】&nbsp;" + myver[0] + "&nbsp;&nbsp;©" + mypublish_date[0].substr(0, 4) + "&nbsp;數位發展組<br/>" +
            "<br/>【歷次更新版本】<br/>" +

            "<div style='width:300px;'> " +

            "<div style='width:110px;height:40px;text-align:center;font-size:17px;font-weight:bold;padding: 0 0 0 10px;border: 1px solid #FFFFFF;line-height: 40px;background-color: #f3decd;float:left;'>版本號碼</div>" +
            "<div style='width:130px;height:40px;text-align:center;font-size:17px;font-weight:bold;padding: 0 0 0 10px;border: 1px solid #FFFFFF;line-height: 40px;background-color: #f3decd;float:left;'>發佈日期</div>" +

            record_content +

            "</div>" +

            "<br/><br/>" +
            "</div>");


        $("#dialog").dialog({
            modal: false,
            width: 600,
            height: 500,
            buttons: {
                "關閉": function () {
                    $(this).dialog("close");
                }
            }
        });
    });

}


//登出系統
function logout() {

    //alert("logout");

    $("#dialog").remove();
    $("body").append(
        "<div id='dialog' title='喜憨兒基金會 人才培訓分析平台（管理後台）' style='font-size:18px;letter-spacing:2px;'><br/>確定登出系統嗎？<br/></div>");

    $("#dialog").dialog({
        modal: true,
        width: 400,
        height: 250,
        buttons: {
            "登出": function () {
                $(this).dialog("close");

                $.ajax({
                    type: "POST",
                    url: "backend_logout_service.php",
                    dataType: "json",
                    success: function () {
                        window.location.assign("/");
                    },
                    error: function (jqXHR) {
                        alert("錯誤！請洽管理員！");
                        console.log(jqXHR.readyState);
                        console.log(jqXHR.status);
                        console.log(jqXHR.statusText);
                        console.log(jqXHR.responseText);

                    }
                })
            },
            "取消": function () {
                $(this).dialog("close");
            }
        },

    });
}


//向service獲取 更新版本的紀錄全部內容
function update_record() {

    $.ajax({
        type: "POST",
        url: "backend_update_service.php",
        dataType: "json",
        async: false,
        data: {},
        success: function (data) {
            myver = data.myver;
            console.log(data.myver);
            mypublish_date = data.mypublish_date;
            mycontent = data.mycontent;
        },
        error: function (jqXHR) {
            alert("錯誤！請洽管理員！");
            console.log(jqXHR.readyState);
            console.log(jqXHR.status);
            console.log(jqXHR.statusText);
            console.log(jqXHR.responseText);
        }
    })

    //////// test /////////
    console.log("myver = " + myver[0]);
    console.log("mypublish_date = " + mypublish_date[0]);
    console.log("mycontent = " + mycontent[0]);
    ///////////////////////
}

//點擊向mycontent取得當次更新紀錄
function get_recard(x) {

    alert(mycontent[x].replace(/<br>/g, '\n'));
}



// menu顯示或隱藏子選單
function switchMenu(theMainMenu, theSubMenu, theEvent) {

    var SubMenu = document.getElementById(theSubMenu);
    if (SubMenu.style.display == 'none') { // 顯示子選單
        SubMenu.style.minWidth = '110px';
        //SubMenu.style.minWidth = theMainMenu.clientWidth; // 讓子選單的最小寬度與主選單相同 (僅為了美觀)
        SubMenu.style.display = 'block';
        hideMenu(); // 隱藏子選單
        VisibleMenu = theSubMenu;
    }
    else { // 隱藏子選單
        if (theEvent != 'MouseOver' || VisibleMenu != theSubMenu) {
            SubMenu.style.display = 'none';
            VisibleMenu = '';
        }
    }
}

// menu隱藏子選單
function hideMenu() {
    if (VisibleMenu != '') {
        document.getElementById(VisibleMenu).style.display = 'none';
    }
    VisibleMenu = '';
}
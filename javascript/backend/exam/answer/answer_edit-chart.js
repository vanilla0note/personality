/* 說明   vanilla0note
 * 
 *  title{value}                    //title物件
 *  series[ {value},{value},...]    //series陣列 每個位置放物件
 *  
 *
 *  最後 json.series = series; 畫圖
*/

var value = {}; //宣告一個空物件


//人格分析雷達圖
function view_personality_radar(exam_state,interview_name,type_score) {


    //作答卷狀態為B，才是分數計算完成
    if (exam_state == 'B') {

        //將分數轉換成陣列
        var exam_score = JSON.parse("[" + type_score + "]");
        //let exam_score = [4, 10, 3, 4, 5, 4, 2, 5, 4]; 


        //dialog產生
        $("#dialog").remove();
        $("body").append(
            "<div id='dialog' title='喜憨兒基金會 人才登錄分析平台－人格分析雷達圖'>" +
            "<div id='container' style='width: 850px; height:450px; margin:0 auto;font-size:18px;'></div>" +
            "</div >");

        $("#dialog").dialog({
            modal: true,
            width: 900,
            height: 600,
            buttons: {
                "下載雷達圖": function () {
                    var chart = $('#container').highcharts();
                    setTimeout(function () {
                        chart.exportChartLocal({
                            type: 'application/png',
                            filename: interview_name+"_人格特質圖"
                        });
                    }, 500);
                },
                "關閉視窗": function () {
                    $(this).dialog("close");
                }
            }
        });





        Highcharts.chart('container', {

            //主標題
            "title": {
                "text": interview_name + " - 人格特質",
                "style": {
                    color: '#000',
                    fontFamily: 'Microsoft JhengHei',
                    fontSize: '20px',
                    fontWeight: "bold",
                }
            },

            //副標題
            "subtitle": {
                //"text": "Bar Chart"
            },

            //圖表類型
            "chart": {
                "polar": true,
            },

            //X軸設定
            "xAxis": {
                "categories": ["[1號]完美型", "[2號]助人型", "[3號]成就型", "[4號]自我型", "[5號]理智型", "[6號]忠誠型", "[7號]活躍型", "[8號]領袖型", "[9號]和平型"],
                "title": {},
                "labels": {
                    style: {
                        color: '#000',
                        fontFamily: 'Microsoft JhengHei',
                        fontSize: '18px',
                        fontWeight: "bold",
                    },
                },
                //"lineWidth": 100,
                //"tickmarkPlacement": "on"
            },

            //Y軸設定
            "yAxis": {
                "title": {},
                "min": 0,
                "max": 14,
                //"max": Math.max(...exam_score), //取得 exam_score 陣列的最大值
                //"gridLineInterpolation": "polygon",
                //"lineWidth": 200,
            },

            //數值資料
            "series": [
                {
                    "type": 'area',
                    "name": '作答內容',
                    "title": {},
                    "color": '#3B9C9C',
                    "data": exam_score
                }

            ],

           "exporting": {
                enabled: false
            }
        });



    }
}


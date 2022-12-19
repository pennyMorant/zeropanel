{include file='admin/main.tpl'}

<main class="content">
    <div class="content-header ui-content-header">
        <div class="container">
            <h1 class="content-heading">汇总</h1>
        </div>
    </div>
    <div class="container">
        <section class="content-inner margin-top-no">
            <div class="row">
                <div class="col-xx-12">
                    <div class="card margin-bottom-no">
                        <div class="card-main">
                            <div class="card-inner">
                                <p>下面是系统运行情况简报。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui-card-wrap">
                <div class="row">
                    <div class="col-xx-12">
                        <div class="card">
                            <div class="card-main">
                            <!--
                                <div class="card-header" style="margin-top: 10px;">
                                    <button class="btn" style="margin-left: 10px;margin-right: 10px;" onclick="changechart('day')">天</button>
                                    <button class="btn btn-primary" onclick="changechart('month')">月</button>
                                </div>
                            -->
                                <div class="card-inner" id="card_day" style="display: ;">
                                    <button id="income_one_month">一月</button>
                                    <button id="income_six_month">半年</button>
                                    <button id="income_one_year">一年</button>
                                    <button id="income_all">全部</button>
                                    <p id="incomeday" style="margin-top: 10px;"></p>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-main">
                                <div class="card-inner">
                                    <button id="users_one_month">一月</button>
                                    <button id="users_six_month">半年</button>
                                    <button id="users_one_year">一年</button>
                                    <button id="users_all">全部</button>
                                    <p id="newusers" style="margin-top: 10px;"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xx-12 col-sm-6">
                        <div class="card">
                            <div class="card-main">
                                <div class="card-inner">

                                    <div id="alive_chart" style="height: 300px; width: 100%;"></div>

                                    <script src="//cdn.jsdelivr.net/gh/YihanH/canvasjs.js@v2.2/canvasjs.min.js"></script>
                                    <script type="text/javascript">
                                        var chart = new CanvasJS.Chart("alive_chart",
                                                {
                                                    title: {
                                                        text: "用户在线情况(总用户 {$sts->getTotalUser()}人)",
                                                        fontFamily: "Impact",
                                                        fontWeight: "normal"
                                                    },

                                                    legend: {
                                                        verticalAlign: "bottom",
                                                        horizontalAlign: "center"
                                                    },
                                                    data: [
                                                        {
                                                            //startAngle: 45,
                                                            indexLabelFontSize: 20,
                                                            indexLabelFontFamily: "Garamond",
                                                            indexLabelFontColor: "darkgrey",
                                                            indexLabelLineColor: "darkgrey",
                                                            indexLabelPlacement: "outside",
                                                            type: "doughnut",
                                                            showInLegend: true,
                                                            dataPoints: [
                                                                {
                                                                    y: {(($sts->getUnusedUser()/$sts->getTotalUser()))*100},
                                                                    legendText: "从未在线的用户 {number_format((($sts->getUnusedUser()/$sts->getTotalUser()))*100,2)}% {(($sts->getUnusedUser()))}人",
                                                                    indexLabel: "从未在线的用户 {number_format((($sts->getUnusedUser()/$sts->getTotalUser()))*100,2)}% {(($sts->getUnusedUser()))}人"
                                                                },
                                                                {
                                                                    y: {(($sts->getTotalUser()-$sts->getOnlineUser(86400)-$sts->getUnusedUser())/$sts->getTotalUser())*100},
                                                                    legendText: "一天以前在线的用户 {number_format((($sts->getTotalUser()-$sts->getOnlineUser(86400)-$sts->getUnusedUser())/$sts->getTotalUser())*100,2)}% {($sts->getTotalUser()-$sts->getOnlineUser(86400)-$sts->getUnusedUser())}人",
                                                                    indexLabel: "一天以前在线的用户 {number_format((($sts->getTotalUser()-$sts->getOnlineUser(86400)-$sts->getUnusedUser())/$sts->getTotalUser())*100,2)}% {($sts->getTotalUser()-$sts->getOnlineUser(86400)-$sts->getUnusedUser())}人"
                                                                },
                                                                {
                                                                    y: {($sts->getOnlineUser(86400)-$sts->getOnlineUser(3600))/$sts->getTotalUser()*100},
                                                                    legendText: "一天内在线的用户 {number_format(($sts->getOnlineUser(86400)-$sts->getOnlineUser(3600))/$sts->getTotalUser()*100,2)}% {($sts->getOnlineUser(86400)-$sts->getOnlineUser(3600))}人",
                                                                    indexLabel: "一天内在线的用户 {number_format(($sts->getOnlineUser(86400)-$sts->getOnlineUser(3600))/$sts->getTotalUser()*100,2)}% {($sts->getOnlineUser(86400)-$sts->getOnlineUser(3600))}人"
                                                                },
                                                                {
                                                                    y: {($sts->getOnlineUser(3600)-$sts->getOnlineUser(60))/$sts->getTotalUser()*100},
                                                                    legendText: "一小时内在线的用户 {number_format(($sts->getOnlineUser(3600)-$sts->getOnlineUser(60))/$sts->getTotalUser()*100,2)}% {($sts->getOnlineUser(3600)-$sts->getOnlineUser(60))}人",
                                                                    indexLabel: "一小时内在线的用户 {number_format(($sts->getOnlineUser(3600)-$sts->getOnlineUser(60))/$sts->getTotalUser()*100,2)}% {($sts->getOnlineUser(3600)-$sts->getOnlineUser(60))}人"
                                                                },
                                                                {
                                                                    y: {($sts->getOnlineUser(60))/$sts->getTotalUser()*100},
                                                                    legendText: "一分钟内在线的用户 {number_format(($sts->getOnlineUser(60))/$sts->getTotalUser()*100,2)}% {($sts->getOnlineUser(60))}人",
                                                                    indexLabel: "一分钟内在线的用户 {number_format(($sts->getOnlineUser(60))/$sts->getTotalUser()*100,2)}% {($sts->getOnlineUser(60))}人"
                                                                }
                                                            ]
                                                        }
                                                    ]
                                                });
                                        chart.render();
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xx-12 col-sm-6">
                        <div class="card">
                            <div class="card-main">
                                <div class="card-inner">

                                    <div id="node_chart" style="height: 300px; width: 100%;"></div>

                                    <script src="//cdn.jsdelivr.net/gh/YihanH/canvasjs.js@v2.2/canvasjs.min.js"></script>
                                    <script type="text/javascript">
                                        var chart = new CanvasJS.Chart("node_chart",
                                                {
                                                    title: {
                                                        text: "节点在线情况(节点数 {$sts->getTotalNodes()}个)",
                                                        fontFamily: "Impact",
                                                        fontWeight: "normal"
                                                    },

                                                    legend: {
                                                        verticalAlign: "bottom",
                                                        horizontalAlign: "center"
                                                    },
                                                    data: [
                                                        {
                                                            //startAngle: 45,
                                                            indexLabelFontSize: 20,
                                                            indexLabelFontFamily: "Garamond",
                                                            indexLabelFontColor: "darkgrey",
                                                            indexLabelLineColor: "darkgrey",
                                                            indexLabelPlacement: "outside",
                                                            type: "doughnut",
                                                            showInLegend: true,
                                                            dataPoints: [
                                                                {if $sts->getTotalNodes()!=0}
                                                                {
                                                                    y: {(1-($sts->getAliveNodes()/$sts->getTotalNodes()))*100},
                                                                    legendText: "离线节点 {number_format((1-($sts->getAliveNodes()/$sts->getTotalNodes()))*100,2)}% {$sts->getTotalNodes()-$sts->getAliveNodes()}个",
                                                                    indexLabel: "离线节点 {number_format((1-($sts->getAliveNodes()/$sts->getTotalNodes()))*100,2)}% {$sts->getTotalNodes()-$sts->getAliveNodes()}个"
                                                                },
                                                                {
                                                                    y: {(($sts->getAliveNodes()/$sts->getTotalNodes()))*100},
                                                                    legendText: "在线节点 {number_format((($sts->getAliveNodes()/$sts->getTotalNodes()))*100,2)}% {$sts->getAliveNodes()}个",
                                                                    indexLabel: "在线节点 {number_format((($sts->getAliveNodes()/$sts->getTotalNodes()))*100,2)}% {$sts->getAliveNodes()}个"
                                                                }
                                                                {/if}
                                                            ]
                                                        }
                                                    ]
                                                });

                                        chart.render();
                                    </script>

                                </div>

                            </div>
                        </div>


                        <div class="card">
                            <div class="card-main">
                                <div class="card-inner">

                                    <div id="traffic_chart" style="height: 300px; width: 100%;"></div>

                                    <script src="//cdn.jsdelivr.net/gh/YihanH/canvasjs.js@v2.2/canvasjs.min.js"></script>
                                    <script type="text/javascript">
                                        var chart = new CanvasJS.Chart("traffic_chart",
                                                {
                                                    title: {
                                                        text: "流量使用情况(总分配流量 {$sts->getTotalTraffic()})",
                                                        fontFamily: "Impact",
                                                        fontWeight: "normal"
                                                    },

                                                    legend: {
                                                        verticalAlign: "bottom",
                                                        horizontalAlign: "center"
                                                    },
                                                    data: [
                                                        {
                                                            //startAngle: 45,
                                                            indexLabelFontSize: 20,
                                                            indexLabelFontFamily: "Garamond",
                                                            indexLabelFontColor: "darkgrey",
                                                            indexLabelLineColor: "darkgrey",
                                                            indexLabelPlacement: "outside",
                                                            type: "doughnut",
                                                            showInLegend: true,
                                                            dataPoints: [
                                                                {if $sts->getRawTotalTraffic()!=0}
                                                                {
                                                                    y: {(($sts->getRawUnusedTrafficUsage()/$sts->getRawTotalTraffic()))*100},
                                                                    label: "总剩余可用",
                                                                    legendText: "总剩余可用 {number_format((($sts->getRawUnusedTrafficUsage()/$sts->getRawTotalTraffic()))*100,2)}% {(($sts->getUnusedTrafficUsage()))}",
                                                                    indexLabel: "总剩余可用 {number_format((($sts->getRawUnusedTrafficUsage()/$sts->getRawTotalTraffic()))*100,2)}% {(($sts->getUnusedTrafficUsage()))}"
                                                                },
                                                                {
                                                                    y: {(($sts->getRawLastTrafficUsage()/$sts->getRawTotalTraffic()))*100},
                                                                    label: "总过去已用",
                                                                    legendText: "总过去已用 {number_format((($sts->getRawLastTrafficUsage()/$sts->getRawTotalTraffic()))*100,2)}% {(($sts->getLastTrafficUsage()))}",
                                                                    indexLabel: "总过去已用 {number_format((($sts->getRawLastTrafficUsage()/$sts->getRawTotalTraffic()))*100,2)}% {(($sts->getLastTrafficUsage()))}"
                                                                },
                                                                {
                                                                    y: {(($sts->getRawTodayTrafficUsage()/$sts->getRawTotalTraffic()))*100},
                                                                    label: "总今日已用",
                                                                    legendText: "总今日已用 {number_format((($sts->getRawTodayTrafficUsage()/$sts->getRawTotalTraffic()))*100,2)}% {(($sts->getTodayTrafficUsage()))}",
                                                                    indexLabel: "总今日已用 {number_format((($sts->getRawTodayTrafficUsage()/$sts->getRawTotalTraffic()))*100,2)}% {(($sts->getTodayTrafficUsage()))}"
                                                                }
                                                                {/if}
                                                            ]
                                                        }
                                                    ]
                                                });

                                        chart.render();
                                    </script>

                                </div>

                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </section>
    </div>
</main>


{include file='admin/footer.tpl'}

<script>
getDay = function(day) {
	var now = new Date();
	var date = new Date(now.getTime() - day * 24 * 3600 * 1000);
	var year = date.getFullYear();
	var month = date.getMonth() + 1;
	var day = date.getDate();
	var result = year + '-' + month + '-' + day;
	return result;
}
    var url = '/admin/api/analytics/income';
    var date = new Date();
    $.ajax({
        dataType: "json",
        url: url,
        type: "POST",
        data: {},
        success: function(data) {
            chartincomeday.updateSeries([{
                name: '收入',
                data: data
            }])
        }
    });
    var options = {
        series: [],
        chart: {
            type: 'area',
            id: 'area-datetime',
            height: 350,
            zoom: {
                enabled: true,
                autoScaleYaxis: true
            }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        
        title: {
          text: '收入趋势',
          align: 'left'
        },
        subtitle: {
          text: '',
          align: 'left'
        },
        xaxis: {
            type: 'datetime',
            min: new Date("2020-01-01").getTime(),
            tickAmount: 6,
        },
        tooltip: {
            x: {
                format: 'dd MMM yyyy'
            }
        },
        yaxis: {
          opposite: true
        },
        
        legend: {
          horizontalAlign: 'left'
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.9,
            stops: [0, 100]
          }
        },
        };

        var chartincomeday = new ApexCharts(document.querySelector("#incomeday"), options);
        chartincomeday.render();
    var resetCssClasses = function(activeEl) {
        var els = document.querySelectorAll('button')
        Array.prototype.forEach.call(els, function(el) {
          el.classList.remove('active')
        })
      
        activeEl.target.classList.add('active')
      }
      
      document
        .querySelector('#income_one_month')
        .addEventListener('click', function(e) {
          resetCssClasses(e)
        var days = getDay(30);
          chartincomeday.zoomX(
            new Date(days).getTime(),
            new Date().getTime(),
          )
        })
      document
        .querySelector('#income_six_month')
        .addEventListener('click', function(e) {
          resetCssClasses(e)
        var days = getDay(180);
          chartincomeday.zoomX(
            new Date(days).getTime(),
            new Date().getTime(),
          )
        })
      document
        .querySelector('#income_one_year')
        .addEventListener('click', function(e) {
          resetCssClasses(e)
        var days = getDay(365);
          chartincomeday.zoomX(
            new Date(days).getTime(),
            new Date().getTime(),
          )
        })
      document
        .querySelector('#income_all')
        .addEventListener('click', function(e) {
          resetCssClasses(e)
        var days = getDay(30);
          chartincomeday.zoomX(
            new Date("2020-01-02").getTime(),
            new Date().getTime(),
          )
        })
</script>

<script> 
    var url = '/admin/api/analytics/new-users';
    $.ajax({
        dataType: "json",
        url: url,
        type: "POST",
        data: {},
        success: function(data) {
            chartusers.updateSeries([{
                name: '增加用户',
                data: data
            }])
        }
    });
    var options = {
        series: [],
        chart: {
        type: 'area',
        id: 'area-datetime',
        height: 350,
        zoom: {
            enabled: true,
            autoScaleYaxis: true
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        
        title: {
          text: '新用户趋势',
          align: 'left'
        },
        subtitle: {
          text: '',
          align: 'left'
        },
        xaxis: {
            type: 'datetime',
            min: new Date("2020-01-01").getTime(),
            tickAmount: 6,
        },
        tooltip: {
            x: {
                format: 'dd MMM yyyy'
            }
        },
        yaxis: {
          opposite: true
        },
        
        legend: {
          horizontalAlign: 'left'
        }
        };

        var chartusers = new ApexCharts(document.querySelector("#newusers"), options);
        chartusers.render();
    var resetCssClasses = function(activeEl) {
        var els = document.querySelectorAll('button')
        Array.prototype.forEach.call(els, function(el) {
          el.classList.remove('active')
        })
      
        activeEl.target.classList.add('active')
      }
      
      document
        .querySelector('#users_one_month')
        .addEventListener('click', function(e) {
          resetCssClasses(e)
        var days = getDay(30);
          chartusers.zoomX(
            new Date(days).getTime(),
            new Date().getTime(),
          )
        })
      document
        .querySelector('#users_six_month')
        .addEventListener('click', function(e) {
          resetCssClasses(e)
        var days = getDay(180);
          chartusers.zoomX(
            new Date(days).getTime(),
            new Date().getTime(),
          )
        })
      document
        .querySelector('#users_one_year')
        .addEventListener('click', function(e) {
          resetCssClasses(e)
        var days = getDay(365);
          chartusers.zoomX(
            new Date(days).getTime(),
            new Date().getTime(),
          )
        })
      document
        .querySelector('#users_all')
        .addEventListener('click', function(e) {
          resetCssClasses(e)
        var days = getDay(30);
          chartusers.zoomX(
            new Date("2020-01-02").getTime(),
            new Date().getTime(),
          )
        })
</script>
<script> 
    function changechart(date) {
        if (date == "day") {
            document.getElementById('card_day').style.display = "";
            document.getElementById('card_month').style.display = "none";
            //chartincomeday.render();
        } else {
            document.getElementById('card_day').style.display = "none";
            document.getElementById('card_month').style.display = "";
            //chartincomemonth.render();
        }
    }
</script>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{$config["appName"]} Dashboard</title>
        <link href="/theme/zero/assets/css/zero.css" rel="stylesheet" type="text/css"/>
        <meta charset="UTF-8" />
        <meta name="renderer" content="webkit" />
        <meta name="description" content="Updates and statistics" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="format-detection" content="telephone=no,email=no" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="theme-color" content="#3B5598" />
        <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <meta http-equiv="pragma" content="no-cache">
        <meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
        <meta http-equiv="expires" content="0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
        <link href="/theme/zero/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
        <link href="/theme/zero/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
        <link href="/theme/zero/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
        <link href="/favicon.png" rel="shortcut icon">
        <link href="/apple-touch-icon.png" rel="apple-touch-icon">
    </head>
	{include file ='admin/menu.tpl'}
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid mt-10">
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-xxl">
									<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
										<div class="col-xxl-6">
											<div class="card card-flush h-md-100">
												<div class="card-header border-0">
													<div class="card-title text-dark fs-3 fw-bolder">收入趋势</div>
                                                    <div class="card-toolbar">
                                                        <a class="btn btn-sm btn-color-muted btn-active btn-active-primary active px-4 me-1" id="income_all">所有</a>
                                                        <a class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4 me-1" id="income_year">年度</a>
                                                        <a class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4 me-1" id="income_half_year">半年</a>
                                                        <a class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4 me-1" id="income_month">月度</a>
													</div>
												</div>     
												<div class="card-body pt-0">
                                                    <div id="income_day"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6">
											<div class="card card-flush h-md-100">
												<div class="card-header border-0">
													<div class="card-title text-dark fs-3 fw-bolder">注册趋势</div>
                                                    <div class="card-toolbar">
                                                        <a class="btn btn-sm btn-color-muted btn-active btn-active-primary active px-4 me-1" id="signup_all">所有</a>
                                                        <a class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4 me-1" id="signup_year">年度</a>
                                                        <a class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4 me-1" id="signup_half_year">半年</a>
                                                        <a class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4 me-1" id="signup_month">月度</a>
													</div>
												</div>     
												<div class="card-body pt-0">
                                                    <div id="signup_day"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {include file='admin/footer.tpl'}
                    </div>
                </div>
            </div>
        </div>
        {include file='admin/script.tpl'}
    </body>
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
                text: '',
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

        var chartincomeday = new ApexCharts(document.querySelector("#income_day"), options);
        chartincomeday.render();
        var resetCssClasses = function(activeEl) {
            var els = document.querySelectorAll('a');
            Array.prototype.forEach.call(els, function(el) {
                el.classList.remove('active')
            });
            
            activeEl.target.classList.add('active')
            };
            
            document
            .querySelector('#income_month')
            .addEventListener('click', function(e) {
                resetCssClasses(e)
                var days = getDay(30);
                chartincomeday.zoomX(
                new Date(days).getTime(),
                new Date().getTime(),
                )
            });
            document
            .querySelector('#income_half_year')
            .addEventListener('click', function(e) {
                resetCssClasses(e)
                var days = getDay(180);
                chartincomeday.zoomX(
                new Date(days).getTime(),
                new Date().getTime(),
                )
            });
            document
            .querySelector('#income_year')
            .addEventListener('click', function(e) {
                resetCssClasses(e)
                var days = getDay(365);
                chartincomeday.zoomX(
                new Date(days).getTime(),
                new Date().getTime(),
                )
            });
            document
            .querySelector('#income_all')
            .addEventListener('click', function(e) {
                resetCssClasses(e)
                var days = getDay(30);
                chartincomeday.zoomX(
                new Date("2020-01-02").getTime(),
                new Date().getTime(),
                )
            });
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
                text: '',
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
    
            var chartusers = new ApexCharts(document.querySelector("#signup_day"), options);
            chartusers.render();
        var resetCssClasses = function(activeEl) {
            var els = document.querySelectorAll('a')
            Array.prototype.forEach.call(els, function(el) {
                el.classList.remove('active')
            });
            
            activeEl.target.classList.add('active')
            }
            
            document
            .querySelector('#signup_month')
            .addEventListener('click', function(e) {
                resetCssClasses(e)
                var days = getDay(30);
                chartusers.zoomX(
                new Date(days).getTime(),
                new Date().getTime(),
                )
            });
            document
            .querySelector('#signup_half_year')
            .addEventListener('click', function(e) {
                resetCssClasses(e)
                var days = getDay(180);
                chartusers.zoomX(
                new Date(days).getTime(),
                new Date().getTime(),
                )
            });
            document
            .querySelector('#signup_year')
            .addEventListener('click', function(e) {
                resetCssClasses(e)
                var days = getDay(365);
                chartusers.zoomX(
                new Date(days).getTime(),
                new Date().getTime(),
                )
            });
            document
            .querySelector('#signup_all')
            .addEventListener('click', function(e) {
                resetCssClasses(e)
                var days = getDay(30);
                chartusers.zoomX(
                new Date("2020-01-02").getTime(),
                new Date().getTime(),
                )
            });
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
</html>
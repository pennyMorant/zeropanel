<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{$config["appName"]} 仪表盘</title>
        <link href="/theme/zero/assets/css/zero.css" rel="stylesheet" type="text/css"/>
        <meta charset="UTF-8" />
        <meta name="renderer" content="webkit" />
        <meta name="description" content="Updates and statistics" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="format-detection" content="telephone=no,email=no" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />

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
											<div class="card card-flush mb-5">
												<div class="card-header border-0">
													<div class="card-title d-flex flex-column">
                                                        <div class="d-flex align-items-center">
                                                            <span class="text-dark fs-2hx fw-bold me-2" data-kt-countup="true" data-kt-countup-value={$income_this_month} data-kt-countup-decimal-places="2" data-kt-countup-prefix="$">0</span>
                                                        
                                                            <span class="badge badge-light-success fs-base" data-bs-toggle="tooltip" title="比上月增长百分比">                                                                                               
                                                                <span class="svg-icon svg-icon-5 svg-icon-success ms-n1">
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor"></rect>
                                                                        <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor"></path>
                                                                    </svg>
                                                                </span>
                                                                <span data-kt-countup="true" data-kt-countup-value={$increase_percentage_income} data-kt-countup-suffix="%">0</span>
                                                            </span>
                                                        
                                                        </div>
                                                        <span class="text-gray-400 pt-1 fw-semibold fs-6">本月收入</span>
                                                    </div>
												</div>     
												<div class="card-body pt-0">
                                                    <div id="income_day"></div>
                                                </div>
                                            </div>
                                            <div class="card card-flush">
												<div class="card-header border-0">
													<div class="card-title">七天内流量使用情况</div>
												</div>     
												<div class="card-body pt-0">
                                                    <div id="zero_admin_traffic_chart"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6">
											<div class="card card-flush mb-5">
												<div class="card-header border-0">
													<div class="card-title d-flex flex-column">
                                                        <div class="d-flex align-items-center">
                                                            <span class=" text-dark fs-2hx fw-bold me-2" data-kt-countup="true" data-kt-countup-value={$new_users_this_month}>0</span>
                                                            <span class="badge badge-light-success fs-base" data-bs-toggle="tooltip" title="比上月增长百分比">                                                                                               
                                                                <span class="svg-icon svg-icon-5 svg-icon-success ms-n1">
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor"></rect>
                                                                        <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor"></path>
                                                                    </svg>
                                                                </span>
                                                                <span data-kt-countup="true" data-kt-countup-value={$increase_percentage_new_users} data-kt-countup-suffix="%">0</span>
                                                            </span>
                                                        </div>
                                                        <span class="text-gray-400 pt-1 fw-semibold fs-6">本月新增用户</span>
                                                    </div>
												</div>     
												<div class="card-body pt-0">
                                                    <div id="signup_day"></div>
                                                </div>
                                            </div>
                                            <div class="card card-flush">
												<div class="card-header border-0">
													<div class="card-title">每日用户使用流量排名</div>
												</div>     
												<div class="card-body pt-0">
                                                    <div id="zero_admin_traffic_ranking_chart"></div>
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
            getMonthFirstDay = function() {
                const today = new Date(); // 获取当前日期
                const year = today.getFullYear(); // 获取年份
                const month = today.getMonth(); // 获取月份（0-11）
                const startOfMonth = new Date(year, month, 1); // 获取月份的第一天
                const start_date = year + '-' + month + '-1';
                return start_date;
            }
            var url = '/{$config['website_admin_path']}/ajax_data/chart/income';
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
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false,
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
                    show: false
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
        </script>
            
        <script> 
            var url = '/{$config['website_admin_path']}/ajax_data/chart/newusers';
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
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false,
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
        </script>

        <script> 
            $.ajax({
                dataType: "json",
                url: '/{$config['website_admin_path']}/ajax_data/chart/traffic',
                type: "POST",
                data: {},
                success: function(data) {
                    zeroAdminTrafficChart.updateSeries([{
                        name: data[0].name,
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
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false,
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
                },
                tooltip: {
                    style: {
                        fontSize: '12px'
                    },
                    y: {
                        formatter: function (val) {
                            return val + "GB"
                        }
                    },
                    
                },
                yaxis: {
                    opposite: true
                },
                
                legend: {
                    horizontalAlign: 'left'
                }
            };

            var zeroAdminTrafficChart = new ApexCharts(document.querySelector("#zero_admin_traffic_chart"), options);
            zeroAdminTrafficChart.render();
        </script>
        <script>
            $.ajax({
                dataType: "json",
                url: '/{$config['website_admin_path']}/ajax_data/chart/user_traffic_ranking',
                type: "POST",
                data: {},
                success: function(data) {
                    zeroAdminTrafficRankingChart.updateSeries([{
                        data: data,
                        name: '流量'
                    }]);
                }
            });
            var options = {
            series: [],
            chart: {
                height: 350,
                type: 'bar',
                
            },
            //colors: colors,
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    distributed: true,
                }
            },
            dataLabels: {
            enabled: false
            },
            legend: {
            show: false
            },
            xaxis: {
                categories: [],
                labels: {
                    style: {
                    //colors: colors,
                    fontSize: '12px'
                    }
                }
            },
            tooltip: {
                    style: {
                        fontSize: '12px'
                    },
                    y: {
                        formatter: function (val) {
                            return val + "GB"
                        }
                    },
                    
                },
            };

            var zeroAdminTrafficRankingChart = new ApexCharts(document.querySelector("#zero_admin_traffic_ranking_chart"), options);
            zeroAdminTrafficRankingChart.render();
        </script>
    </body>
</html>
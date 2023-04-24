"use strict";

// 流量首页记录
$(document).ready(function() {
    var element = document.getElementById("zero_user_traffic_chart");
    
    var height = parseInt(KTUtil.css(element, 'height'));
    if (!element) {
        return;
    }
    var color = element.getAttribute('data-kt-chart-color');
    var labelColor = KTUtil.getCssVariableValue('--bs-' + 'gray-800');
    var baseColor = KTUtil.getCssVariableValue('--bs-' + color);
    var lightColor = KTUtil.getCssVariableValue('--bs-' + color + '-light');
    var url = '/user/ajax_data/chart/traffic';
    $.ajax({
        dataType: "json",
        url: url,
        method: "POST",
        data: {},
        success: function(data) {
            chart.updateSeries([{
                    name: data[0].name,
                    data: data
            }]);
        }
    });

        var options = {
            series: [],
            chart: {
                type: 'area',
                height: height,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {},
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            fill: {
                type: 'solid',
                opacity: 1
            },
            stroke: {
                curve: 'smooth',
                show: true,
                width: 3,
                colors: [baseColor]
            },
            xaxis: {
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    show: false,
                    style: {
                        colors: labelColor,
                        fontSize: '12px'
                    }
                },
                crosshairs: {
                    show: false,
                    position: 'front',
                    stroke: {
                        color: '#E4E6EF',
                        width: 1,
                        dashArray: 3
                    }
                },
                tooltip: {
                    enabled: false,
                    formatter: undefined,
                    offsetY: 0,
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    show: false,
                    style: {
                        colors: labelColor,
                        fontSize: '12px',
                    }
                }
            },
            states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
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
                }
            },
            colors: [baseColor],
            markers: {
                colors: [baseColor],
                strokeColor: [lightColor],
                strokeWidth: 3
            }
        };

        var chart = new ApexCharts(element, options);
        chart.render();
})

// user wallet 
var KTZeroUserWalletChart = function () {
    // Private methods
    var initChart = function() {
        var element = document.getElementById('zero_user_wallet_chart');        

        if (!element) {
            return;
        }  
          
        var height = parseInt(KTUtil.css(element, 'height'));
        var balance = JSON.parse($("#user_balance").html());
        var commission = JSON.parse($("#user_commission").html());
        var options = {
            series: [balance, commission],                 
            chart: {           
                fontFamily: 'inherit', 
                type: 'donut',
                width: 250,
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '50%',
                        labels: {
                            value: {
                                fontSize: '10px'
                            }
                        }                        
                    }
                }
            },
            colors: [
                KTUtil.getCssVariableValue('--bs-success'), 
                KTUtil.getCssVariableValue('--bs-primary'), 
            ],           
            stroke: {
              width: 0
            },
            labels: ['可使用金额', '可提现佣金'],
            legend: {
                show: false,
            },
            fill: {
                type: 'false',          
            }     
        };                     

        var chart = new ApexCharts(element, options);        
        chart.render();  

    }

    // Public methods
    return {
        init: function () {           
            initChart();            
        }   
    }
}();


// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTZeroUserWalletChart.init();
});
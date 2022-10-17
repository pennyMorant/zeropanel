"use strict";

// 流量首页记录
$(document).ready(function() {
    var element = document.getElementById("traffic_widgets");
    
    var height = parseInt(KTUtil.css(element, 'height'));
    if (!element) {
        return;
    }
    var color = element.getAttribute('data-kt-chart-color');
    var labelColor = KTUtil.getCssVariableValue('--kt-' + 'gray-800');
    var baseColor = KTUtil.getCssVariableValue('--kt-' + color);
    var lightColor = KTUtil.getCssVariableValue('--kt-' + color + '-light');
    var url = '/user/ajax_data/chart/traffic_chart';
    $.ajax({
        dataType: "json",
        url: url,
        methond: "GET",
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

// Class definition
var KTZeroReferralTotalCommissionChart = function () {
    var chart = {
        self: null,
        rendered: false
    };

    // Private methods
    var initChart = function(chart) {
        var element = document.getElementById("zero_referral_total_commission_chart");

        if (!element) {
            return;
        }

        var height = parseInt(KTUtil.css(element, 'height'));       
        var borderColor = KTUtil.getCssVariableValue('--kt-border-dashed-color');
        var baseColor = KTUtil.getCssVariableValue('--kt-gray-800');
        var lightColor = KTUtil.getCssVariableValue('--kt-success');
        
        
        
        var options = {
            series: [],
            chart: {
                fontFamily: 'inherit',
                type: 'area',
                height: height,
                toolbar: {
                    show: false
                }
            },             
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            fill: {
                type: 'solid',
                opacity: 0
            },
            stroke: {
                curve: 'smooth',
                show: true,
                width: 2,
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
                    show: false
                },
                crosshairs: {
                    position: 'front',
                    stroke: {
                        color: baseColor,
                        width: 1,
                        dashArray: 3
                    }
                },
                tooltip: {
                    enabled: true,
                    formatter: undefined,
                    offsetY: 0,
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    show: false
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
                        return "$" + val
                    }
                }
            },
            colors: [lightColor],
            grid: { 
                borderColor: borderColor,                 
                strokeDashArray: 4,
                padding: {
                    top: 0,
                    right: -20,
                    bottom: -20,
                    left: -20
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            markers: {               
                strokeColor: baseColor,
                strokeWidth: 2
            }
        }; 

        chart.self = new ApexCharts(element, options);
        
        // Set timeout to properly get the parent elements width
        setTimeout(function() {
            chart.self.render();
            chart.rendered = true;
            var url = '/user/agent/ajax_data/chart/commission_records';
            $.ajax({
                dataType: "json",
                url: url,
                methond: "GET",
                data: {},
                success: function(response) {
                    chart.self.updateSeries([{
                            name: '佣金',
                            data: response,
                    }]);
                }
            });
        }, 300);
    }

    // Public methods
    return {
        init: function () {
            initChart(chart);

            // Update chart on theme mode change
            KTThemeMode.on("kt.thememode.change", function() {                
                if (chart.rendered) {
                    chart.self.destroy();
                }

                initChart(chart);
            });
        }     
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTZeroReferralTotalCommissionChart.init();
});


var KTZeroReferralTotalUsersChart = function () {
    var chart = {
        self: null,
        rendered: false
    };

    // Private methods
    var initChart = function(chart) {
        var element = document.getElementById("zero_referral_total_users_chart");

        if (!element) {
            return;
        }

        var height = parseInt(KTUtil.css(element, 'height'));       
        var borderColor = KTUtil.getCssVariableValue('--kt-border-dashed-color');
        var baseColor = KTUtil.getCssVariableValue('--kt-gray-800');
        var lightColor = KTUtil.getCssVariableValue('--kt-success');

        var options = {
            series: [],
            chart: {
                fontFamily: 'inherit',
                type: 'area',
                height: height,
                toolbar: {
                    show: false
                }
            },             
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            fill: {
                type: 'solid',
                opacity: 0
            },
            stroke: {
                curve: 'smooth',
                show: true,
                width: 2,
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
                    show: false
                },
                crosshairs: {
                    position: 'front',
                    stroke: {
                        color: baseColor,
                        width: 1,
                        dashArray: 3
                    }
                },
                tooltip: {
                    enabled: true,
                    formatter: undefined,
                    offsetY: 0,
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    show: false
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
                        return val + "个"
                    }
                }
            },
            colors: [lightColor],
            grid: {  
                borderColor: borderColor,                
                strokeDashArray: 4,
                padding: {
                    top: 0,
                    right: -20,
                    bottom: -20,
                    left: -20
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            markers: {
                strokeColor: baseColor,
                strokeWidth: 2
            }
        }; 

        chart.self = new ApexCharts(element, options);

        // Set timeout to properly get the parent elements width
        setTimeout(function() {
            chart.self.render();
            chart.rendered = true;
            var url = '/user/agent/ajax_data/chart/user_records';
            $.ajax({
                dataType: "json",
                url: url,
                methond: "GET",
                data: {},
                success: function(response) {
                    chart.self.updateSeries([{
                            name: '客户',
                            data: response,
                    }]);
                }
            });
        }, 300);
    }

    // Public methods
    return {
        init: function () {
            initChart(chart);

            // Update chart on theme mode change
            KTThemeMode.on("kt.thememode.change", function() {                
                if (chart.rendered) {
                    chart.self.destroy();
                }

                initChart(chart);
            });
        }     
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTZeroReferralTotalUsersChart.init();
});

// 佣金使用比列
var KTZeroReferralCommissionUsedChart = function () {
    // Private methods
    var initChart = function() {
        var el = document.getElementById('zero_referral_commission_used_chart'); 

        if (!el) {
            return;
        }

        var options = {
            size: el.getAttribute('data-kt-size') ? parseInt(el.getAttribute('data-kt-size')) : 70,
            lineWidth: el.getAttribute('data-kt-line') ? parseInt(el.getAttribute('data-kt-line')) : 11,
            rotate: el.getAttribute('data-kt-rotate') ? parseInt(el.getAttribute('data-kt-rotate')) : 145,            
            //percent:  el.getAttribute('data-kt-percent') ,
        }

        var canvas = document.createElement('canvas');
        var span = document.createElement('span'); 
            
        if (typeof(G_vmlCanvasManager) !== 'undefined') {
            G_vmlCanvasManager.initElement(canvas);
        }

        var ctx = canvas.getContext('2d');
        canvas.width = canvas.height = options.size;

        el.appendChild(span);
        el.appendChild(canvas);

        ctx.translate(options.size / 2, options.size / 2); // change center
        ctx.rotate((-1 / 2 + options.rotate / 180) * Math.PI); // rotate -90 deg

        //imd = ctx.getImageData(0, 0, 240, 240);
        var radius = (options.size - options.lineWidth) / 2;

        var drawCircle = function(color, lineWidth, percent) {
            percent = Math.min(Math.max(0, percent || 1), 1);
            ctx.beginPath();
            ctx.arc(0, 0, radius, 0, Math.PI * 2 * percent, false);
            ctx.strokeStyle = color;
            ctx.lineCap = 'round'; // butt, round or square
            ctx.lineWidth = lineWidth
            ctx.stroke();
        };

        var balance = JSON.parse($("#agent_balance").html());
        var commission = JSON.parse($("#agent_commission").html());
        // Init 
        drawCircle(KTUtil.getCssVariableValue('--kt-success'), options.lineWidth, balance / (commission + balance));
        drawCircle(KTUtil.getCssVariableValue('--kt-primary'), options.lineWidth, commission / (commission + balance));   
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
    KTZeroReferralCommissionUsedChart.init();
});

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
                KTUtil.getCssVariableValue('--kt-success'), 
                KTUtil.getCssVariableValue('--kt-primary'), 
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
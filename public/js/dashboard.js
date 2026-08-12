$(document).ready(function () {
    //declaring charts
    var bar_chart;
    var donut_chart;
    var line_chart;
    var pie_chart;

    loadbarChart(7);

    loadDonutChart();

    loadLineChart(7);

    loadPieChart();

//bar chart buttons
$("#btn_7_days").click(function () {
    loadbarChart(7);
});

$("#btn_14_days").click(function () {
    loadbarChart(14);
});

$("#btn_30_days").click(function () {
    loadbarChart(30);
});

$("#btn_line_7_days").click(function (e) {
    loadLineChart(7);
});
$("#btn_line_14_days").click(function (e) {
    loadLineChart(14);
});
$("#btn_line_30_days").click(function (e) {
    loadLineChart(30);
});

//load bar chart function
function loadbarChart(date_range)
{
    $.ajax({
        type: "get",
        url: "/getBarchartData",
        data: {
            date_range : date_range,
        },
        // dataType: "dataType",
        success: function (response) {
            console.log(response);

            var calDate = [];
            var subscriptionTotal = [];
            var voucherTotal = [];

        for (let i = 0; i < response.length; i++) {
            calDate[i] = response[i]['date'];
            subscriptionTotal[i] = response[i]['subscription_total'];
            voucherTotal[i] = response[i]['voucher_total'];
        }

        var barChart = {
          series: [{
          name: 'Subscriptions',
          data: subscriptionTotal
        }, {
          name: 'Vouchers',
          data: voucherTotal
        }],
          chart: {
          type: 'bar',
          height: 350,
          stacked: true,
          toolbar: {
            show: true
          },
          zoom: {
            enabled: true
          }
        },
        responsive: [{
          breakpoint: 480,
          options: {
            legend: {
              position: 'bottom',
              offsetX: -10,
              offsetY: 0
            }
          }
        }],
        plotOptions: {
          bar: {
            horizontal: false,
            borderRadius: 5,
            borderRadiusApplication: 'end', // 'around', 'end'
            borderRadiusWhenStacked: 'last', // 'all', 'last'
            dataLabels: {
              total: {
                enabled: true,
                style: {
                  fontSize: '13px',
                  fontWeight: 900
                }
              }
            }
          },
        },
        xaxis: {
          type: 'Date',
          categories: calDate,
        },
        legend: {
          position: 'right',
          offsetY: 40
        },
        fill: {
          opacity: 1
        }
        };//chart

        if(bar_chart)
        {
            bar_chart.destroy();
        }
        bar_chart = new ApexCharts(document.querySelector("#bar_chart"), barChart);
        bar_chart.render();
        }
    });
}

function loadDonutChart()
{
    $.ajax({
        type: "get",
        url: "/getDonutchartData",
        data: {
            date_range: 1,
        },
        // dataType: "dataType",
        success: function (response) {
            // console.log(response);

            var packages = [];
            var total_sales = [];

            for (let i = 0; i < response.length; i++) {
                packages[i] = response[i]['package_name'];
                total_sales[i] = parseFloat(response[i]['total_sales']);
            }

            // console.log(total_sales);

            var chart = {
                series: total_sales,
                chart: {
                    width: 380,
                    type: 'donut',
                  },
                plotOptions: {
                    pie: {
                        startAngle: -90,
                        endAngle: 270
                    }
                },
                labels: packages,
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: 'gradient',
                },
                title: {
                text: 'Package wise daily sale.'
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                      chart: {
                        width: 200
                      },
                      legend: {
                        position: 'bottom'
                      }
                    }
                  }],
            };//chart

            if(donut_chart)
            {
                donut_chart.destroy();
            }
            donut_chart = new ApexCharts(document.querySelector("#donut_chart"), chart);
            donut_chart.render();
        }
    });
}

function loadLineChart(date_range)
{
    $.ajax({
        type: "get",
        url: "/getLineChartData",
        data: {
            date_range: date_range,
        },
        // dataType: "dataType",
        success: function (response) {
            // console.log(response);

            var calDate = [];
            var total = [];

            for (let i = 0; i < response.length; i++) {
                calDate[i] = response[i]['date'];
                total[i] = response[i]['total'];
            }

            var lineChart = {
                series: [{
                name: "Daily Sale",
                data: total
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    zoom: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                // stroke: {
                //     curve: 'straight'
                // },
                title: {
                    text: 'Daily Sale',
                    align: 'left'
                },
                subtitle: {
                    text: 'Price Movements',
                    align: 'left'
                },
                labels: calDate,
                xaxis: {
                    type: 'Date',
                },
                yaxis: {
                    opposite: true
                },
                legend: {
                    horizontalAlign: 'left'
                }
            };//chart

            if(line_chart)
            {
                line_chart.destroy();
            }
            line_chart = new ApexCharts(document.querySelector("#line_chart"), lineChart);
            line_chart.render();
        }
    });
}

function loadPieChart()
{
    $.ajax({
        type: "get",
        url: "/getPieChartData",
        // data: "data",
        // dataType: "dataType",
        success: function (response) {
            // console.log(response);

            var series = response['data'];
            var labels = response['labels'];

            console.log(series);

            var pieChart = {
                series: series,
                chart: {
                    width: 380,
                    type: 'pie',
                },
                title: {
                    text: 'Subscriptions vs Vouchers sale'
                },
                labels: labels,
                responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                    width: 200
                    },
                    legend: {
                    position: 'bottom'
                    }
                }
                }]
            };
            if(pie_chart)
            {
                pie_chart.destroy();
            }
            pie_chart = new ApexCharts(document.querySelector("#pie_chart"), pieChart);
            pie_chart.render();
        }
    });
}//pie chart

});//dashboard jQuery

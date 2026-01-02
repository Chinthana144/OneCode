/*
|------------------------------------------------------------------------
|   CLIENT ANALYSIS PAGE
|------------------------------------------------------------------------
*/
//this page is used to display client analysis data
$(document).ready(function () {
    //declaring charts
    var bar_chart;

    loadbarChart(7);

    $("#btn_7_days").click(function () {
        loadbarChart(7);
    });

    $("#btn_14_days").click(function () {
        loadbarChart(14);
    });

    $("#btn_30_days").click(function () {
        loadbarChart(30);
    });

    function loadbarChart(date_range)
    {
        $.ajax({
            type: "get",
            url: "/getAnalysisBarchartData",
            data: {
                date_range : date_range,
            },
            // dataType: "dataType",
            success: function (response) {
                console.log(response);

                var calDate = [];
                var totals = [];

            for (let i = 0; i < response.length; i++) {
                calDate[i] = response[i]['date'];
                totals[i] = response[i]['total'];
            }

            console.log(totals);

            var chart = {
                chart: {
                    type: "bar",
                    height: 400,
                    offsetX: -15,
                    toolbar: { show: true },
                    foreColor: "#adb0bb",
                    fontFamily: 'inherit',
                    sparkline: { enabled: false },
                    },
                series: [
                    {name: "Daily Sale:", data:totals},
                ],

                xaxis:{
                    type: "Date",
                    categories: calDate,
                },

                plotOptions: {
                    bar: {
                    horizontal: false,
                    columnWidth: "35%",
                    borderRadius: [6],
                    borderRadiusApplication: 'end',
                    borderRadiusWhenStacked: 'all'
                    },
                },

                markers: { size: 0 },

                grid: {
                    borderColor: "rgba(0,0,0,0.1)",
                    strokeDashArray: 3,
                    xaxis: {
                    lines: {
                        show: false,
                    },
                    },
                },

                dataLabels: {
                    enabled: false,
                },


                legend: {
                    show: false,
                },

                stroke: {
                    show: true,
                    width: 3,
                    lineCap: "butt",
                    colors: ["transparent"],
                },

                responsive: [
                    {
                    breakpoint: 600,
                    options: {
                        plotOptions: {
                        bar: {
                            borderRadius: 3,
                        }
                        },
                    }
                    }
                ]
            }//chart

            if(bar_chart)
            {
                bar_chart.destroy();
            }
            bar_chart = new ApexCharts(document.querySelector("#bar_chart"), chart);
            bar_chart.render();
            }
        });
    }

});

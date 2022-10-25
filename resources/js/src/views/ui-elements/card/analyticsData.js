/*=========================================================================================
	File Name: analyticsData.vue
	Description: Data shown by charts
	----------------------------------------------------------------------------------------
	Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
	Author: Pixinvent
	Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


export default {
    // LINE CHART
    siteTraffic: {
        series: [{
            name: 'Traffic Rate',
            data: [150, 200, 125, 225, 200, 250]
        }],
        chartOptions: {
            grid: {
                show: false,
                padding: {
                    left: 0,
                    right: 0
                }
            },
            chart: {
                type: 'line',
                dropShadow: {
                    enabled: true,
                    top: 5,
                    left: 0,
                    blur: 4,
                    opacity: 0.10,
                },
                toolbar: {
                    show: false,
                },
                sparkline: {
                    enabled: true
                }
            },
            stroke: {
                width: 5,
                curve: 'smooth'
            },
            xaxis: {
                type: 'numeric',
            },
            colors: ['#7367F0'],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    gradientToColors: ['#A9A2F6'],
                    shadeIntensity: 1,
                    type: 'horizontal',
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100, 100, 100]
                },
            },
            markers: {
                size: 0,
                hover: {
                    size: 5
                }
            },
            tooltip: {
                x: { show: false }
            },
        }
    },
    activeUsers: {
        series: [{
            name: 'Active Users',
            data: [750, 1000, 900, 1250, 1000, 1200, 1100]
        }],
        chartOptions: {
            grid: {
                show: false,
                padding: {
                    left: 0,
                    right: 0
                }
            },
            chart: {
                type: 'line',
                dropShadow: {
                    enabled: true,
                    top: 5,
                    left: 0,
                    blur: 4,
                    opacity: 0.10,
                },
                toolbar: {
                    show: false,
                },
                sparkline: {
                    enabled: true
                }
            },
            stroke: {
                width: 5,
                curve: 'smooth'
            },
            xaxis: {
                type: 'numeric',
            },
            colors: ['#28C76F'],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    gradientToColors: ['#55DD92'],
                    shadeIntensity: 1,
                    type: 'horizontal',
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 75, 100, 100]
                },
            },
            markers: {
                size: 0,
                hover: {
                    size: 5
                }
            },
            tooltip: {
                x: { show: false }
            },
        }
    },
    newsletter: {
        series: [{
            name: 'Newsletter',
            data: [365, 390, 365, 400, 375, 400]
        }],
        chartOptions: {
            grid: {
                show: false,
                padding: {
                    left: 0,
                    right: 0
                }
            },
            chart: {
                type: 'line',
                dropShadow: {
                    enabled: true,
                    top: 5,
                    left: 0,
                    blur: 4,
                    opacity: 0.10,
                },
                toolbar: {
                    show: false,
                },
                sparkline: {
                    enabled: true
                }
            },
            stroke: {
                width: 5,
                curve: 'smooth'
            },
            xaxis: {
                type: 'numeric',
            },
            colors: ['#FF9F43'],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    gradientToColors: ['#ffc085'],
                    shadeIntensity: 1,
                    type: 'horizontal',
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 75, 100, 100]
                },
            },
            markers: {
                size: 0,
                hover: {
                    size: 5
                }
            },
            tooltip: {
                x: { show: false }
            },
        }
    },
    revenueComparisonLine: {
        // series: [{
        //         name: "This Month",
        //         data: [45000, 47000, 44800, 47500, 45500, 48000, 46500, 48600]
        //     },
        //     {
        //         name: "Last Month",
        //         data: [46000, 48000, 45500, 46600, 44500, 46500, 45000, 47000]
        //     }
        // ],
        chartOptions: {
            chart: {
                toolbar: { show: false },
                dropShadow: {
                    enabled: true,
                    top: 5,
                    left: 0,
                    blur: 4,
                    opacity: 0.10,
                },
            },
            stroke: {
                curve: 'smooth',
                dashArray: [0, 8],
                width: [4, 2],
            },
            grid: {
                borderColor: '#e7e7e7',
            },
            legend: {
                show: false,
            },
            colors: ['#F97794', '#b8c2cc'],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    inverseColors: false,
                    gradientToColors: ['#7367F0', '#b8c2cc'],
                    shadeIntensity: 1,
                    type: 'horizontal',
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100, 100, 100]
                },
            },
            markers: {
                size: 0,
                hover: {
                    size: 5
                }
            },
            xaxis: {
                labels: {
                    style: {
                        cssClass: 'text-grey fill-current',
                    }
                },
                axisTicks: {
                    show: false,
                },
                categories: ['01', '05', '09', '13', '17', '21', '26', '31'],
                axisBorder: {
                    show: false,
                },
            },
            yaxis: {
                tickAmount: 5,
                labels: {
                    style: {
                        cssClass: 'text-grey fill-current',
                    },
                    formatter: function(val) {
                        return val > 999 ? (val / 1000).toFixed(1) + 'k' : val;
                    }
                }
            },
            tooltip: {
                x: { show: false }
            }
        }
    },

    // LINE AREA CHART
    subscribersGained: {
        series: [{
            name: 'Subscribers',
            data: [28, 40, 36, 52, 38, 60, 55]
        }],
        chartOptions: {
            grid: {
                show: false,
                padding: {
                    left: 0,
                    right: 0
                }
            },
            chart: {
                toolbar: {
                    show: false,
                },
                sparkline: {
                    enabled: true
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2.5
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 0.9,
                    opacityFrom: 0.7,
                    opacityTo: 0.5,
                    stops: [0, 80, 100]
                }
            },
            xaxis: {
                type: 'numeric',
                lines: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                },
                labels: { show: false }
            },
            yaxis: [{
                y: 0,
                offsetX: 0,
                offsetY: 0,
                padding: { left: 0, right: 0 },
            }],
            tooltip: {
                x: { show: false }
            },
        },
    },
    quarterlySales: {
        series: [{
            name: 'Sales',
            data: [10, 15, 7, 12, 3, 16]
        }],
        chartOptions: {
            grid: {
                show: false,
                padding: {
                    left: 0,
                    right: 0
                }
            },
            chart: {
                toolbar: {
                    show: false,
                },
                sparkline: {
                    enabled: true
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2.5
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 0.9,
                    opacityFrom: 0.7,
                    opacityTo: 0.5,
                    stops: [0, 80, 100]
                }
            },
            xaxis: {
                type: 'numeric',
                lines: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                },
                labels: { show: false }
            },
            yaxis: [{
                y: 0,
                offsetX: 0,
                offsetY: 0,
                padding: { left: 0, right: 0 },
            }],
            tooltip: {
                x: { show: false }
            },
        },
    },
    revenueGenerated: {
        series: [{
            name: 'Revenue',
            data: [350, 275, 400, 300, 350, 300, 450]
        }],
        chartOptions: {
            grid: {
                show: false,
                padding: {
                    left: 0,
                    right: 0
                }
            },
            chart: {
                toolbar: {
                    show: false,
                },
                sparkline: {
                    enabled: true
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2.5
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 0.9,
                    opacityFrom: 0.7,
                    opacityTo: 0.5,
                    stops: [0, 80, 100]
                }
            },
            xaxis: {
                type: 'numeric',
                lines: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                },
                labels: { show: false }
            },
            yaxis: [{
                y: 0,
                offsetX: 0,
                offsetY: 0,
                padding: { left: 0, right: 0 },
            }],
            tooltip: {
                x: { show: false }
            },
        },
    },
    ordersRecevied: {
        series: [{
            name: 'Orders',
            data: [10, 15, 8, 15, 7, 12, 8]
        }],
        chartOptions: {
            grid: {
                show: false,
                padding: {
                    left: 0,
                    right: 0
                }
            },
            chart: {
                toolbar: {
                    show: false,
                },
                sparkline: {
                    enabled: true
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2.5
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 0.9,
                    opacityFrom: 0.7,
                    opacityTo: 0.5,
                    stops: [0, 80, 100]
                }
            },
            xaxis: {
                type: 'numeric',
                lines: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                },
                labels: { show: false }
            },
            yaxis: [{
                y: 0,
                offsetX: 0,
                offsetY: 0,
                padding: { left: 0, right: 0 },
            }],
            tooltip: {
                x: { show: false }
            },
        },
    },
    // BAR CHART
    salesBar: {
        // series: [{
        //     name: 'Sessions',
        //     data: [75, 125, 225, 175, 125, 75, 25]
        // }],
        chartOptions: {
            grid: {
                show: false,
                padding: {
                    left: 0,
                    right: 0
                }
            },
            chart: {
                type: 'bar',
                sparkline: { enabled: true },
                toolbar: { show: false },
            },
            states: {
                hover: {
                    filter: 'none'
                }
            },
            colors: ['#e6edf7', '#e6edf7', '#7367f0', '#e6edf7', '#e6edf7', '#e6edf7'],
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    distributed: true,
                    endingShape: 'rounded', // Deprecated
                    // borderRadius: '20px', // Coming Soon
                }
            },
            tooltip: {
                x: { show: false }
            },
        }
    },
    // RADIAL BAR
    goalOverviewRadialBar: {
        // series: [83],
        chartOptions: {
            plotOptions: {
                radialBar: {
                    size: 110,
                    startAngle: -150,
                    endAngle: 150,
                    hollow: {
                        size: '77%',
                    },
                    track: {
                        background: "#bfc5cc",
                        strokeWidth: '50%',
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            offsetY: 18,
                            color: '#99a2ac',
                            fontSize: '4rem'
                        }
                    }
                }
            },
            colors: ['#00db89'],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'horizontal',
                    shadeIntensity: 0.5,
                    gradientToColors: ['#00b5b5'],
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                },
            },
            stroke: {
                lineCap: 'round'
            },
            chart: {
                sparkline: {
                    enabled: true,
                },
                dropShadow: {
                    enabled: true,
                    blur: 3,
                    left: 1,
                    top: 1,
                    opacity: 0.1
                },
            },
        }
    },
    supportTrackerRadialBar: {
        // chartData: {
        //     totalTickets: 163,
        //     openTickets: 103,
        //     lastResponse: '1d',
        // },
        // series: [83],
        chartOptions: {
            plotOptions: {
                radialBar: {
                    size: 158,
                    offsetY: 0,
                    startAngle: -135,
                    endAngle: 135,
                    hollow: {
                        size: '65%',
                    },
                    track: {
                        background: "rgba(0,0,0,0)",
                        strokeWidth: '100%',

                    },
                    dataLabels: {
                        value: {
                            offsetY: 30,
                            color: '#99a2ac',
                            fontSize: '2rem'
                        }
                    }
                },
            },
            colors: ['#EA5455'],
            fill: {
                type: 'gradient',
                gradient: {
                    // enabled: true,
                    shade: 'dark',
                    type: 'horizontal',
                    shadeIntensity: 0.5,
                    gradientToColors: ['#7367F0'],
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                },
            },
            stroke: {
                dashArray: 8
            },
            chart: {
                sparkline: {}
            },
            labels: ['Sales'],
        }
    },
    // RADAR
    statisticsRadar: {
        // series: [{
        //     name: 'Visits',
        //     data: [90, 50, 86, 40, 100, 20],
        // }, {
        //     name: 'Sales',
        //     data: [70, 75, 70, 76, 20, 85],
        // }],
        chartOptions: {
            labels: ['Ene', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            dataLabels: {
                style: {
                    colors: ['#b9c3cd', '#b9c3cd', '#b9c3cd', '#b9c3cd', '#b9c3cd', '#b9c3cd']
                }
            },
            yaxis: {
                show: false,
            },
            grid: {
                show: false,
            },
            legend: { show: false },
            chart: {
                dropShadow: {
                    enabled: true,
                    blur: 8,
                    left: 1,
                    top: 1,
                    opacity: 0.2
                },
                toolbar: {
                    show: false
                }
            },
            stroke: {
                width: 0
            },
            colors: ['#9f8ed7', '#1edec5'],
            plotOptions: {
                radar: {
                    polygons: {
                        strokeColors: ['#e8e8e8', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent'],
                        connectorColors: 'transparent'
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    gradientToColors: ['#8e9ad6', '#1fcadb'],
                    shadeIntensity: 1,
                    type: 'horizontal',
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100, 100, 100]
                },
            },
            markers: {
                size: 0,
            },
        }
    },
    // SessionsByDevice
    sessionsByDeviceDonut: {
        // analyticsData: [
        //     { device: 'Dekstop', icon: 'MonitorIcon', color: 'primary', sessionsPercentgae: 58.6, comparedResultPercentage: 2 },
        //     { device: 'Mobile', icon: 'SmartphoneIcon', color: 'warning', sessionsPercentgae: 34.9, comparedResultPercentage: 8 },
        //     { device: 'Tablet', icon: 'TabletIcon', color: 'danger', sessionsPercentgae: 6.5, comparedResultPercentage: -5 },
        // ],
        // comparedResult: [2, -3, 8],
        // series: [58.6, 34.9, 6.5],
        chartOptions: {
            labels: ['Desktop', 'Mobile', 'Tablet'],
            dataLabels: {
                enabled: false
            },
            legend: { show: false },
            chart: {
                offsetY: 30,
                type: 'donut',
                toolbar: {
                    show: false
                }
            },
            stroke: { width: 0 },
            colors: ['#7961F9', '#FF9F43', '#EA5455'],
            fill: {
                type: 'gradient',
                gradient: {
                    gradientToColors: ['#9c8cfc', '#FFC085', '#f29292']
                }
            }
        }
    },
    // Product Orders
    productOrdersRadialBar: {
        // analyticsData: [
        //     { 'orderType': 'Finished', 'counts': 23043, color: 'primary' },
        //     { 'orderType': 'Pending', 'counts': 14658, color: 'warning' },
        //     { 'orderType': 'Rejected ', 'counts': 4758, color: 'danger' },
        // ],
        // series: [70, 52, 26],
        chartOptions: {
            labels: ['Finished', 'Pending', 'Rejected'],
            plotOptions: {
                radialBar: {
                    size: 165,
                    offsetY: -25,
                    hollow: {
                        size: '20%'
                    },
                    track: {
                        background: "#ebebeb",
                        strokeWidth: '100%',
                        margin: 15,
                    },
                    dataLabels: {
                        show: true,
                        name: {
                            fontSize: '18px',
                        },
                        value: {
                            fontSize: '16px',
                            color: "#636a71",
                            offsetY: 11
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function() {
                                return 42459
                            }
                        }
                    }
                },
            },
            responsive: [{
                breakpoint: 576,
                options: {
                    plotOptions: {
                        radialBar: {
                            size: 150,
                            hollow: {
                                size: '20%'
                            },
                            track: {
                                background: "#ebebeb",
                                strokeWidth: '100%',
                                margin: 15,
                            },
                        }
                    }
                }
            }],
            colors: ['#7961F9', '#FF9F43', '#EA5455'],
            fill: {
                type: 'gradient',
                gradient: {
                    // enabled: true,
                    shade: 'dark',
                    type: 'vertical',
                    shadeIntensity: 0.5,
                    gradientToColors: ['#9c8cfc', '#FFC085', '#f29292'],
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                },
            },
            stroke: {
                lineCap: 'round'
            },
            chart: {
                dropShadow: {
                    enabled: true,
                    blur: 3,
                    left: 1,
                    top: 1,
                    opacity: 0.1
                },
            }
        }
    },
    // Customers
    customersPie: {
        // analyticsData: [
        //     { 'customerType': 'New', 'counts': 890, color: 'primary' },
        //     { 'customerType': 'Returning', 'counts': 258, color: 'warning' },
        //     { 'customerType': 'Referrals ', 'counts': 149, color: 'danger' },
        // ],
        // series: [690, 258, 149],
        chartOptions: {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril'],
            dataLabels: {
                enabled: false
            },
            legend: { show: false },
            chart: {
                type: 'pie',
                offsetY: 0,
                dropShadow: {
                    enabled: false,
                    blur: 5,
                    left: 1,
                    top: 1,
                    opacity: 0.2
                },
                toolbar: {
                    show: false
                }
            },
            stroke: {
                width: 5
            },
            colors: ['#7961F9', '#FF9F43', '#EA5455', '#28C76F'],
            fill: {
                type: 'gradient',
                gradient: {
                    gradientToColors: ['#9c8cfc', '#FFC085', '#f29292', '#DFF7EA']
                }
            }
        }
    },
    // Sales monthly
    salesLine: {
        // series: [{
        //     name: "Sales",
        //     data: [140, 180, 150, 205, 160, 295, 125, 255, 205, 305, 240, 295]
        // }],
        chartOptions: {
            chart: {
                toolbar: { show: false },
                dropShadow: {
                    enabled: true,
                    top: 20,
                    left: 2,
                    blur: 6,
                    opacity: 0.20
                }
            },
            stroke: {
                curve: 'smooth',
                width: 4,
            },
            grid: {
                borderColor: '#ebebeb',
            },
            legend: {
                show: false,
            },
            colors: ['#df87f2'],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    inverseColors: false,
                    gradientToColors: ['#7367F0'],
                    shadeIntensity: 1,
                    type: 'horizontal',
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100, 100, 100]
                },
            },
            markers: {
                size: 0,
                hover: {
                    size: 5
                }
            },
            xaxis: {
                labels: {
                    style: {
                        cssClass: 'text-grey fill-current',
                    }
                },
                axisTicks: {
                    show: false,
                },
                categories: ['Ene', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                axisBorder: {
                    show: false,
                },
            },
            yaxis: {
                tickAmount: 5,
                labels: {
                    style: {
                        cssClass: 'text-grey fill-current',
                    },
                    formatter: function(val) {
                        return val > 999 ? (val / 1000).toFixed(1) + 'k' : val;
                    }
                }
            },
            tooltip: {
                x: { show: false }
            }
        }
    },
    // ClientRetention Bar Chart
    clientRetentionBar: {
        // series: [{
        //     name: 'New Clients',
        //     data: [175, 125, 225, 175, 160, 189, 206, 134, 159, 216, 148, 123]
        // }, {
        //     name: 'Retained Clients',
        //     data: [-144, -155, -141, -167, -122, -143, -158, -107, -126, -131, -140, -137]
        // }],
        chartOptions: {
            grid: {
                borderColor: '#ebebeb',
                padding: {
                    left: 0,
                    right: 0
                }
            },
            legend: {
                show: false,
            },
            dataLabels: {
                enabled: false
            },
            chart: {
                stacked: true,
                type: 'bar',
                toolbar: { show: false },
            },
            colors: ['#7367F0', '#EA5455'],
            plotOptions: {
                bar: {
                    columnWidth: '10%'
                }
            },
            xaxis: {
                labels: {
                    style: {
                        cssClass: 'text-grey fill-current',
                    }
                },
                axisTicks: {
                    show: false,
                },
                categories: ['Ene', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                axisBorder: {
                    show: false,
                },
            },
            yaxis: {
                tickAmount: 5,
                labels: {
                    style: {
                        cssClass: 'text-grey fill-current',
                    }
                }
            },
            tooltip: {
                x: { show: false }
            },
        }
    },
    // OTHER
    browserAnalytics: [{
            id: 1,
            name: 'Google Chrome',
            ratio: 73,
            time: 'Mon Dec 10 2018 07:46:05 GMT+0000 (GMT)',
            comparedResult: '800'
        },
        {
            id: 3,
            name: 'Opera',
            ratio: 8,
            time: 'Mon Dec 10 2018 07:46:05 GMT+0000 (GMT)',
            comparedResult: '-200'
        },
        {
            id: 2,
            name: 'Firefox',
            ratio: 19,
            time: 'Mon Dec 10 2018 07:46:05 GMT+0000 (GMT)',
            comparedResult: '100'
        },
        {
            id: 4,
            name: 'Internet Explorer',
            ratio: 27,
            time: 'Mon Dec 10 2018 07:46:05 GMT+0000 (GMT)',
            comparedResult: '-450'
        },
    ],
    //--------------------------------------------------------------------------------------SOKAI ELEMENTS--------------------------------------------------------------//
    //GRAFICO PIE DE CLIENTES
    ClientPie: {
        chartOptions: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dataLabels: {
                enabled: true,
            },
            legend: {
                show: true,
                position: 'left',
                fontSize: '13px',
                offsetY: 0,
                formatter: function(seriesName, opts) {
                    return [seriesName, " - ", opts.w.globals.series[opts.seriesIndex]]
                },
                itemMargin: {
                    horizontal: 3,
                    vertical: 3
                },
            },
            chart: {
                type: 'donut',
                offsetY: 0,
                offsetX: 0,
                dropShadow: {
                    enabled: false,
                    blur: 5,
                    left: 1,
                    top: 1,
                    opacity: 0.2
                },
                toolbar: {
                    show: false
                }
            },
            stroke: {
                width: 5
            },
            //colors: ['#f0b200', '#f6da00', '#f9f400', '#aed424', '#7ec540', '#3ea2a3', '#4b5bb0', '#513ea4', '#76379f', '#c61b83', '#e02523', '#e88208'],
            fill: {
                type: 'gradient',
                /* gradient: {
                     gradientToColors: ['#f4c745', '#f8e445', '#fbf745', '#b0e05f', '#a1d574', '#72bbbc', '#7c87c5', '#8072bd', '#9b6db9', '#d559a4', '#e8605e', '#eea44b']
                 }*/
            }
        }
    },
    //GRAFICO DE BARRAS VENTAS TOTALES
    VentasTotalesBar: {
        chartOptions: {
            grid: {
                borderColor: '#ebebeb',
                padding: {
                    left: 0,
                    right: 0
                }
            },
            chart: {
                stacked: true,
                type: 'bar',
                toolbar: { show: false },
                events: {
                    click: function(chart, w, e) {
                        // console.log(chart, w, e)
                    }
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
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
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                },
                axisTicks: {
                    show: true,
                },
                categories: [
                    ['Ene'],
                    ['Feb'],
                    ['Mar'],
                    ['Abr'],
                    ['May'],
                    ['Jun'],
                    ['Jul'],
                    ['Ago'],
                    ['Sep'],
                    ['Oct'],
                    ['Nov'],
                    ['Dic']
                ],
            },
            onDatasetHover: {
                highlightDataSeries: true,
            },
            yaxis: {
                tickAmount: 5,
                labels: {
                    style: {
                        cssClass: 'text-grey fill-current',
                    }
                },
            },
            tooltip: {
                x: { show: true, },
                y: {
                    formatter: function(val) {
                        return "$ " + val.toFixed(2)
                    }
                }
            },
        },
    },
    //GRAFICO DE BARRAS VENTAS TOTALES
    VentasVendedorBar: {
        chartOptions: {
            grid: {
                borderColor: '#ebebeb',
                padding: {
                    left: 0,
                    right: 0
                }
            },
            legend: {
                show: true,
                horizontalAlign: 'center',
                position: 'bottom',
                offsetX: 0,
                offsetY: 0,
                itemMargin: {
                    horizontal: 40,
                    vertical: 5
                },
            },
            dataLabels: {
                enabled: false
            },
            chart: {
                stacked: true,
                type: 'bar',
                toolbar: { show: false },
                stackType: '100%'
            },
            //colors: ['#f0b200', '#f6da00', '#f9f400', '#aed424', '#7ec540', '#3ea2a3', '#4b5bb0', '#513ea4', '#76379f', '#c61b83', '#e02523', '#e88208'],
            plotOptions: {
                bar: {
                    columnWidth: '40%'
                }
            },
            xaxis: {
                labels: {
                    style: {
                        cssClass: 'text-grey fill-current',
                    }
                },
                axisTicks: {
                    show: false,
                },
                categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                axisBorder: {
                    show: false,
                },
            },
            yaxis: {
                tickAmount: 5,
                labels: {
                    style: {
                        cssClass: 'text-grey fill-current',
                    }
                }
            },
            tooltip: {
                x: { show: false },
                y: {
                    formatter: function(val) {
                        return val + "%"
                    }
                }
            },
        }
    },
    //GRAFICO DE RADIO CUENTAS PAGAR
    CuentasPagarRadialBar: {
        chartOptions: {
            plotOptions: {
                radialBar: {
                    //size: 158,
                    offsetY: 0,
                    startAngle: -135,
                    endAngle: 135,
                    hollow: {
                        size: '45%',
                    },
                    dataLabels: {
                        value: {
                            offsetY: 20,
                            //offsetX: 20,
                            color: '#99a2ac',
                            fontSize: '2rem'
                        }
                    }
                },
            },
            colors: ['#f28b2c'],
            fill: {
                type: 'gradient',
                gradient: {
                    // enabled: true,
                    shade: 'dark',
                    type: 'horizontal',
                    shadeIntensity: 0.5,
                    gradientToColors: ['#28C76F'],
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                },
            },
            stroke: {
                dashArray: 6
            },
            chart: {
                sparkline: {}
            },
            labels: [''],
        }
    },
    //GRAFICO DE UTILIDAD BURTA Y NETA
    UtilidadesLine: {
        series: [{
                name: "Neta",
                data: [45000, 47000, 44800, 47500, 45500, 48000, 46500, 48600, 45500, 48000, 46500, 48000]
            },
            {
                name: "Bruta",
                data: [46000, 48000, 45500, 46600, 44500, 46500, 45000, 47000, 48000, 45500, 46600, 44500]
            }
        ],
        chartOptions: {
            chart: {
                toolbar: { show: false },
                dropShadow: {
                    enabled: true,
                    top: 5,
                    left: 0,
                    blur: 4,
                    opacity: 0.10,
                },
            },
            stroke: {
                curve: 'smooth',
                dashArray: [0, 8],
                width: [4, 2],
            },
            grid: {
                borderColor: '#e7e7e7',
            },
            legend: {
                show: false,
            },
            //colors: ['#F97794', '#b8c2cc'],

            markers: {
                size: 0,
                hover: {
                    size: 5
                }
            },
            xaxis: {
                labels: {
                    style: {
                        cssClass: 'text-grey fill-current',
                    }
                },
                axisTicks: {
                    show: false,
                },
                categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                axisBorder: {
                    show: false,
                },
            },
            yaxis: {
                tickAmount: 5,
                labels: {
                    style: {
                        cssClass: 'text-grey fill-current',
                    },
                    formatter: function(val) {
                        return val > 999 ? (val / 1000).toFixed(1) + 'k' : val;
                    }
                }
            },
            tooltip: {
                x: { show: false }
            }
        }
    },
}
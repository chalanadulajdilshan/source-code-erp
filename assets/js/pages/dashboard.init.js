var options1 = { series: [{ data: [25, 66, 41, 89, 63, 25, 44, 20, 36, 40, 54] }], fill: { colors: ["#5b73e8"] }, chart: { type: "bar", width: 70, height: 40, sparkline: { enabled: !0 } }, plotOptions: { bar: { columnWidth: "50%" } }, labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], xaxis: { crosshairs: { width: 1 } }, tooltip: { fixed: { enabled: !1 }, x: { show: !1 }, y: { title: { formatter: function (e) { return "" } } }, marker: { show: !1 } } }, chart1 = new ApexCharts(document.querySelector("#total-revenue-chart"), options1); chart1.render(); var options = { fill: { colors: ["#34c38f"] }, series: [70], chart: { type: "radialBar", width: 45, height: 45, sparkline: { enabled: !0 } }, dataLabels: { enabled: !1 }, plotOptions: { radialBar: { hollow: { margin: 0, size: "60%" }, track: { margin: 0 }, dataLabels: { show: !1 } } } }, chart = new ApexCharts(document.querySelector("#orders-chart"), options); chart.render(); options = { fill: { colors: ["#5b73e8"] }, series: [55], chart: { type: "radialBar", width: 45, height: 45, sparkline: { enabled: !0 } }, dataLabels: { enabled: !1 }, plotOptions: { radialBar: { hollow: { margin: 0, size: "60%" }, track: { margin: 0 }, dataLabels: { show: !1 } } } }; (chart = new ApexCharts(document.querySelector("#customers-chart"), options)).render(); var options2 = { series: [{ data: [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54] }], fill: { colors: ["#f1b44c"] }, chart: { type: "bar", width: 70, height: 40, sparkline: { enabled: !0 } }, plotOptions: { bar: { columnWidth: "50%" } }, labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], xaxis: { crosshairs: { width: 1 } }, tooltip: { fixed: { enabled: !1 }, x: { show: !1 }, y: { title: { formatter: function (e) { return "" } } }, marker: { show: !1 } } }, chart2 = new ApexCharts(document.querySelector("#growth-chart"), options2); chart2.render();

// Sales Analytics Chart
var options = {
    chart: {
        height: 339,
        type: 'bar',
        toolbar: {
            show: false
        }
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '45%',
            endingShape: 'rounded'
        },
    },
    dataLabels: {
        enabled: false
    },
    colors: ["#5b73e8"],
    series: [{
        name: 'Sales Amount',
        data: [23000, 11000, 22000, 27000, 13000, 22000, 37000, 21000, 44000, 22000, 30000, 45000]
    }],
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        title: {
            text: 'Months',
            style: {
                fontSize: '14px',
                fontWeight: 'bold'
            }
        }
    },
    yaxis: {
        title: {
            text: 'Sales Amount (LKR)',
            style: {
                fontSize: '14px',
                fontWeight: 'bold'
            }
        },
        labels: {
            formatter: function (value) {
                return 'Rs. ' + value.toLocaleString();
            }
        }
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return 'Rs. ' + val.toLocaleString()
            }
        }
    },
    grid: {
        borderColor: '#f1f1f1'
    }
};

var chart = new ApexCharts(document.querySelector("#sales-analytics-chart"), options);
chart.render();
/**
 * DataTables Basic
 */

'use strict';
var from = 'admin';
if (window.location.href.indexOf("seller/") > -1) {
    from = 'seller';
}


// --- variable definitions --
var purpleColor = '#836AF9',
    yellowColor = '#ffe800',
    cyanColor = '#28dac6',
    orangeColor = '#FF8132',
    orangeLightColor = '#ffcf5c',
    oceanBlueColor = '#299AFF',
    greyColor = '#4F5D70',
    greyLightColor = '#EDF1F4',
    blueColor = '#2B9AFF',
    blueLightColor = '#84D0FF';
var cardColor, headingColor, labelColor, borderColor, legendColor;

if (isDarkStyle) {
    cardColor = config.colors_dark.cardColor;
    headingColor = config.colors_dark.headingColor;
    labelColor = config.colors_dark.textMuted;
    legendColor = config.colors_dark.bodyColor;
    borderColor = config.colors_dark.borderColor;
} else {
    cardColor = config.colors.cardColor;
    headingColor = config.colors.headingColor;
    labelColor = config.colors.textMuted;
    legendColor = config.colors.bodyColor;
    borderColor = config.colors.borderColor;
}

function drawChart(domId, labels, dataList, type) {

    console.log("drawChart", arguments);
  /*  var barChart = document.getElementById(domId);
    new Chart(barChart, {
        type: type,
        data: {
            labels: labels,
            datasets: [
                {
                    data: dataList,
                    backgroundColor: orangeLightColor,
                    borderColor: 'transparent',
                    maxBarThickness: 15,
                    borderRadius: {
                        topRight: 15,
                        topLeft: 15
                    }
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 500
            },
            plugins: {
                tooltip: {
                    rtl: isRtl,
                    backgroundColor: cardColor,
                    titleColor: headingColor,
                    bodyColor: legendColor,
                    borderWidth: 1,
                    borderColor: borderColor
                },
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        color: borderColor,
                        drawBorder: false,
                        borderColor: borderColor
                    },
                    ticks: {
                        color: labelColor
                    }
                },
                y: {
                    min: 0,
                    max: 400,
                    grid: {
                        color: borderColor,
                        drawBorder: false,
                        borderColor: borderColor
                    },
                    ticks: {
                        stepSize: 100,
                        color: labelColor
                    }
                }
            }
        }
    })
*/
}

function drawDonutChart(domId, data) {

    if (data.length <= 1)
        return;

    data.splice(0, 1);
    var defColors = Object.values(config.colors);
    var labels = data.map((item, index) => item[0]);
    var values = data.map((item, index) => item[1]);
    var colors = data.map((item, index) => defColors[index]);

}
// ----------- end -----------

var fv, offCanvasEl;
// datatable (jquery)
function drawTopDataTable(id) {
    var dt_basic_table = $('#' + id), dt_basic;
    var columns = [];
    var columnNames = dt_basic_table.attr('data-column-names');
    if (columnNames) {
        columnNames = columnNames.split(',');
        columns = columnNames.map((item) => { return { data: item}});
    }
    // DataTable with buttons
    // --------------------------------------------------------------------
    if(dt_basic_table.length) {
        dt_basic = dt_basic_table.DataTable({
            ajax: $(dt_basic_table).attr("data-url"),
            columns: columns,
            order: [[2, 'desc']],
            dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 7,
            lengthMenu: [7, 10, 25, 50, 75, 100],
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-label-primary dropdown-toggle me-2',
                    text: '<i class="mdi mdi-export-variant me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [
                        {
                            extend: 'print',
                            text: '<i class="mdi mdi-printer-outline me-1" ></i>Print',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [3, 4, 5, 6, 7],
                                // prevent avatar to be display
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function (index, item) {
                                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                result = result + item.lastChild.firstChild.textContent;
                                            } else if (item.innerText === undefined) {
                                                result = result + item.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            },
                            customize: function (win) {
                                //customize print view for dark
                                $(win.document.body)
                                    .css('color', config.colors.headingColor)
                                    .css('border-color', config.colors.borderColor)
                                    .css('background-color', config.colors.bodyBg);
                                $(win.document.body)
                                    .find('table')
                                    .addClass('compact')
                                    .css('color', 'inherit')
                                    .css('border-color', 'inherit')
                                    .css('background-color', 'inherit');
                            }
                        },
                        {
                            extend: 'csv',
                            text: '<i class="mdi mdi-file-document-outline me-1" ></i>Csv',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [3, 4, 5, 6, 7],
                                // prevent avatar to be display
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function (index, item) {
                                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                result = result + item.lastChild.firstChild.textContent;
                                            } else if (item.innerText === undefined) {
                                                result = result + item.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'excel',
                            text: '<i class="mdi mdi-file-excel-outline me-1"></i>Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [3, 4, 5, 6, 7],
                                // prevent avatar to be display
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function (index, item) {
                                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                result = result + item.lastChild.firstChild.textContent;
                                            } else if (item.innerText === undefined) {
                                                result = result + item.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="mdi mdi-file-pdf-box me-1"></i>Pdf',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [3, 4, 5, 6, 7],
                                // prevent avatar to be display
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function (index, item) {
                                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                result = result + item.lastChild.firstChild.textContent;
                                            } else if (item.innerText === undefined) {
                                                result = result + item.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'copy',
                            text: '<i class="mdi mdi-content-copy me-1" ></i>Copy',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [3, 4, 5, 6, 7],
                                // prevent avatar to be display
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function (index, item) {
                                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                result = result + item.lastChild.firstChild.textContent;
                                            } else if (item.innerText === undefined) {
                                                result = result + item.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            }
                        }
                    ]
                }
            ],
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                            var data = row.data();
                            return 'Details of ' + data['full_name'];
                        }
                    }),
                    type: 'column',
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                                ? '<tr data-dt-row="' +
                                col.rowIndex +
                                '" data-dt-column="' +
                                col.columnIndex +
                                '">' +
                                '<td>' +
                                col.title +
                                ':' +
                                '</td> ' +
                                '<td>' +
                                col.data +
                                '</td>' +
                                '</tr>'
                                : '';
                        }).join('');

                        return data ? $('<table class="table"/><tbody />').append(data) : false;
                    }
                }
            },
            bFilter: false,
            bLengthChange: false
        });

        if (dt_basic_table.attr('data-title'))
            dt_basic_table.parent().find('div.head-label').html('<h5 class="card-title mb-0">' + dt_basic_table.attr('data-title') + '</h5>');
    }
};


/*
drawTopDataTable('top_sellers_table')
drawTopDataTable('top_categories_table')
drawTopDataTable('bottom_table')
*/






/**
 * Charts ChartsJS
 */
'use strict';

(function () {
    // Color Variables
    const purpleColor = '#836AF9',
        yellowColor = '#ffe800',
        cyanColor = '#28dac6',
        orangeColor = '#FF8132',
        orangeLightColor = '#ffcf5c',
        oceanBlueColor = '#299AFF',
        greyColor = '#4F5D70',
        greyLightColor = '#EDF1F4',
        blueColor = '#2B9AFF',
        blueLightColor = '#84D0FF';

    let cardColor, headingColor, labelColor, borderColor, legendColor;

    if (isDarkStyle) {
        cardColor = config.colors_dark.cardColor;
        headingColor = config.colors_dark.headingColor;
        labelColor = config.colors_dark.textMuted;
        legendColor = config.colors_dark.bodyColor;
        borderColor = config.colors_dark.borderColor;
    } else {
        cardColor = config.colors.cardColor;
        headingColor = config.colors.headingColor;
        labelColor = config.colors.textMuted;
        legendColor = config.colors.bodyColor;
        borderColor = config.colors.borderColor;
    }

    // Set height according to their data-height
    // --------------------------------------------------------------------
    const chartList = document.querySelectorAll('.chartjs');
    chartList.forEach(function (chartListItem) {
        chartListItem.height = chartListItem.dataset.height;
    });

})();







(function() {
    $.ajax({
        url: base_url + from + '/home/category_wise_product_count',
        type: 'GET',
        dataType: 'json',
        success: function (data) {

            data.splice(0, 1);
            var defColors = Object.values(config.colors);
            var labels = data.map((item, index) => item[0]);
            var values = data.map((item, index) => item[1]);
            var colors = data.map((item, index) => defColors[index]);
            // drawDonutChart('doughnutChart', result);
            const doughnutChart = document.getElementById('doughnutChart');
            if (doughnutChart) {
                const doughnutChartVar = new Chart(doughnutChart, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                data: values,
                                backgroundColor: colors,
                                borderWidth: 0,
                                pointStyle: 'rectRounded'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        animation: {
                            duration: 500
                        },
                        cutout: '68%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        // console.log(context)
                                        // const label = context.labels || '',
                                        //     value = context.parsed;
                                        // const output = ' ' + label + ' : ' + value + ' %';
                                        return context.label + ' : ' + context.parsed;
                                    }
                                },
                                // Updated default tooltip UI
                                rtl: isRtl,
                                backgroundColor: cardColor,
                                titleColor: headingColor,
                                bodyColor: legendColor,
                                borderWidth: 1,
                                borderColor: borderColor
                            }
                        }
                    }
                });
            }
            // let html = '';
            // for (let i in labels) {
            //     html += '<li class="ct-series-2">\n' +
            //         '                            <h5 class="mb-0 fw-bold">' + labels[i] + '</h5>\n' +
            //         '                            <span\n' +
            //         '                                    class="badge badge-dot my-2 cursor-pointer rounded-pill"\n' +
            //         '                                    style="background-color: ' + colors[i] + '; width: 35px; height: 6px"></span>\n' +
            //         '                            <div class="text-muted">' + values[i] + '</div>\n' +
            //         '                        </li>';
            // }
            //
            // $('#doughnutChart_content').html(html);

        }
    });

    $.ajax({
        url: base_url + from + '/home/fetch_sales',
        type: 'GET',
        dataType: 'json',
        success: function (result) {
            const lineChartDay = document.getElementById('scoreLineToDay1');
            const lineChartWeek = document.getElementById('scoreLineToWeek1');
            const lineChartMonth = document.getElementById('scoreLineToMonth1');
            let s = result[2].day;
            let i = {
                    name: "series-1",
                    data: result[2].total_sale
                };
            let o = result[1].week;
            let r = {
                    name: "series-1",
                    data: result[1].total_sale
                };
            let l = result[0].month_name;
            let h = {
                    name: "series-1",
                    data: result[0].total_sale
                };
            if (lineChartDay) {
                const lineChartDayVar = new Chart(lineChartDay, {
                    type: 'line',
                    data: {
                        labels: s.data,
                        datasets: [
                            {
                                data: i.data,
                                label: 'Day',
                                borderColor: config.colors.primary,
                                tension: 0.5,
                                pointStyle: 'circle',
                                backgroundColor: config.colors.primary,
                                fill: false,
                                pointRadius: 1,
                                pointHoverRadius: 5,
                                pointHoverBorderWidth: 5,
                                pointBorderColor: 'transparent',
                                pointHoverBorderColor: cardColor,
                                pointHoverBackgroundColor: config.colors.primary
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                grid: {
                                    color: borderColor,
                                    drawBorder: false,
                                    borderColor: borderColor
                                },
                                ticks: {
                                    color: labelColor
                                }
                            },
                            y: {
                                scaleLabel: {
                                    display: true
                                },
                                min: 0,
                                max: 400,
                                ticks: {
                                    color: labelColor,
                                    stepSize: 100
                                },
                                grid: {
                                    color: borderColor,
                                    drawBorder: false,
                                    borderColor: borderColor
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                // Updated default tooltip UI
                                rtl: isRtl,
                                backgroundColor: cardColor,
                                titleColor: headingColor,
                                bodyColor: legendColor,
                                borderWidth: 1,
                                borderColor: borderColor
                            },
                            legend: {
                                position: 'top',
                                align: 'start',
                                rtl: isRtl,
                                labels: {
                                    font: {
                                        family: 'Inter'
                                    },
                                    usePointStyle: true,
                                    padding: 35,
                                    boxWidth: 6,
                                    boxHeight: 6,
                                    color: legendColor
                                }
                            }
                        }
                    }
                });
            }
            if (lineChartWeek) {
                const lineChartWeekVar = new Chart(lineChartWeek, {
                    type: 'line',
                    data: {
                        labels: o,
                        datasets: [
                            {
                                data: r.data,
                                label: 'Week',
                                borderColor: config.colors.primary,
                                tension: 0.5,
                                pointStyle: 'circle',
                                backgroundColor: config.colors.primary,
                                fill: false,
                                pointRadius: 1,
                                pointHoverRadius: 5,
                                pointHoverBorderWidth: 5,
                                pointBorderColor: 'transparent',
                                pointHoverBorderColor: cardColor,
                                pointHoverBackgroundColor: config.colors.primary
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                grid: {
                                    color: borderColor,
                                    drawBorder: false,
                                    borderColor: borderColor
                                },
                                ticks: {
                                    color: labelColor
                                }
                            },
                            y: {
                                scaleLabel: {
                                    display: true
                                },
                                min: 0,
                                max: 400,
                                ticks: {
                                    color: labelColor,
                                    stepSize: 100
                                },
                                grid: {
                                    color: borderColor,
                                    drawBorder: false,
                                    borderColor: borderColor
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                // Updated default tooltip UI
                                rtl: isRtl,
                                backgroundColor: cardColor,
                                titleColor: headingColor,
                                bodyColor: legendColor,
                                borderWidth: 1,
                                borderColor: borderColor
                            },
                            legend: {
                                position: 'top',
                                align: 'start',
                                rtl: isRtl,
                                labels: {
                                    font: {
                                        family: 'Inter'
                                    },
                                    usePointStyle: true,
                                    padding: 35,
                                    boxWidth: 6,
                                    boxHeight: 6,
                                    color: legendColor
                                }
                            }
                        }
                    }
                });
            }
            if (lineChartMonth) {
                const lineChartMonthVar = new Chart(lineChartMonth, {
                    type: 'line',
                    data: {
                        labels: l,
                        datasets: [
                            {
                                data: h.data,
                                label: 'Month',
                                borderColor: config.colors.primary,
                                tension: 0.5,
                                pointStyle: 'circle',
                                backgroundColor: config.colors.primary,
                                fill: false,
                                pointRadius: 1,
                                pointHoverRadius: 5,
                                pointHoverBorderWidth: 5,
                                pointBorderColor: 'transparent',
                                pointHoverBorderColor: cardColor,
                                pointHoverBackgroundColor: config.colors.primary
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                grid: {
                                    color: borderColor,
                                    drawBorder: false,
                                    borderColor: borderColor
                                },
                                ticks: {
                                    color: labelColor
                                }
                            },
                            y: {
                                scaleLabel: {
                                    display: true
                                },
                                min: 0,
                                max: 400,
                                ticks: {
                                    color: labelColor,
                                    stepSize: 100
                                },
                                grid: {
                                    color: borderColor,
                                    drawBorder: false,
                                    borderColor: borderColor
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                // Updated default tooltip UI
                                rtl: isRtl,
                                backgroundColor: cardColor,
                                titleColor: headingColor,
                                bodyColor: legendColor,
                                borderWidth: 1,
                                borderColor: borderColor
                            },
                            legend: {
                                position: 'top',
                                align: 'start',
                                rtl: isRtl,
                                labels: {
                                    font: {
                                        family: 'Inter'
                                    },
                                    usePointStyle: true,
                                    padding: 35,
                                    boxWidth: 6,
                                    boxHeight: 6,
                                    color: legendColor
                                }
                            }
                        }
                    }
                });
            }
        }
    });
})();

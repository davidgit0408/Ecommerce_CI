"use strict";
/* ---------------------------------------------------------------------------------------------------------------------------------------------------

Common-Functions or events
 1.login-Module
 2.Product-Module
 3.Category-Module
 4.Order-Module
 5.Featured_Section-Module
 6.Notifation-Module
 7.Faq-Module
 8.Slider-Module
 9.Offer-Module
 10.Promo_code-Module
 11.Delivery_boys-Module
 12.Settings-Module
 13.City-Module
 14.Transaction_Module
 15.Customer-Wallet-Module
 16.Fund-Transfer-Module
 17.Return-Request-Module
 18.Tax-Module
 19.Image Upload
 20.Client Api Key Module
 21.System Users
 22.custom-notification-Module
--------------------------------------------------------------------------------------------------------------------------------------------------- */


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

    var barChart = document.getElementById(domId);
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

    return barChart;
}

function drawDonutChart(domId, data) {
return;
    if (data.length <= 1)
        return;

    data.splice(0, 1);
    var defColors = Object.values(config.colors);
    var labels = data.map((item, index) => item[0]);
    var values = data.map((item, index) => item[1]);
    var colors = data.map((item, index) => defColors[index]);

    new Chart(document.getElementById(domId), {
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
                            const label = context.labels || '',
                                value = context.parsed;
                            const output = ' ' + label + ' : ' + value + ' %';
                            return output;
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
    })
}
// ----------- end -----------

$(document).ready(function () {
    $('#loading').hide();
});

var from = 'admin';
if (window.location.href.indexOf("seller/") > -1) {
    from = 'seller';
}


$.event.special.touchstart = {
    setup: function (_, ns, handle) {
        this.addEventListener("touchstart", handle, {
            passive: !ns.includes("noPreventDefault")
        });
    }
};
$(document).ready(function () {
    $('.kv-fa').rating({
        theme: 'krajee-fa',
        filledStar: '<i class="fas fa-star"></i>',
        emptyStar: '<i class="far fa-star"></i>',
        showClear: false,
        size: 'md'
    });
});


$(document).on('load-success.bs.table', '#product-rating-table', function (event) {

    $('.kv-fa').rating({
        theme: 'krajee-fa',
        filledStar: '<i class="fas fa-star"></i>',
        emptyStar: '<i class="far fa-star"></i>',
        showClear: false,
        size: 'md'
    });

});

$(document).on('load-success.bs.table', '#products_table', function (event) {

    $('.kv-fa').rating({
        theme: 'krajee-fa',
        filledStar: '<i class="fas fa-star"></i>',
        emptyStar: '<i class="far fa-star"></i>',
        showClear: false,
        size: 'md'
    });

});

$(document).on('column-switch.bs.table', '#products_table', function (event) {

    $('.kv-fa').rating({
        theme: 'krajee-fa',
        filledStar: '<i class="fas fa-star"></i>',
        emptyStar: '<i class="far fa-star"></i>',
        showClear: false,
        size: 'md'
    });

});


$(document).on('click', '.delete-product-rating', function () {
    var cat_id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'admin/product/delete_rating',
                    data: {
                        id: cat_id
                    },
                    dataType: 'json'
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('table').bootstrapTable('refresh');
                            csrfName = response['csrfName'];
                            csrfHash = response['csrfHash'];
                        } else {
                            Swal.fire('Oops...', response.message, 'warning');
                            $('table').bootstrapTable('refresh');
                            csrfName = response['csrfName'];
                            csrfHash = response['csrfHash'];
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                        csrfName = response['csrfName'];
                        csrfHash = response['csrfHash'];
                    });
            });
        },
        allowOutsideClick: false
    });
});




/*
iziToast.settings({
    position: 'topRight',
});
*/

$("[data-toggle=tooltip]").tooltip();

var attributes_values_selected = [];
var variant_values_selected = [];
var value_check_array = [];
var attributes_selected_variations = [];
var attributes_values = [];
var pre_selected_attr_values = [];
var current_attributes_selected = [];
var current_variants_selected = [];
var attribute_flag = 0;
var pre_selected_attributes_name = []
var current_selected_image;
var attributes_values = [];
var all_attributes_values = [];
var counter = 0;
var variant_counter = 0;

//-------------
//- CATEGORY EISE PRODUCT SALE CHART -
//-------------
// Get context with jQuery - using jQuery's .get() method.


if (document.getElementById('doughnutChart')) {
    $.ajax({
        url: base_url + from + '/home/category_wise_product_count',
        type: 'GET',
        dataType: 'json',
        success: function (result) {

            result = [[], ['asdf', 1], ['afsdf', 2]];
            drawDonutChart('doughnutChart', result);
/*
            google.charts.load("current", {
                packages: ["corechart"]
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable(result);

                var options = {
                    title: '',
                    is3D: true,
                };

                var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
                chart.draw(data, options);
            }*/
        }
    });



    $.ajax({
        url: base_url + from + '/home/fetch_sales',
        type: 'GET',
        dataType: 'json',
        success: function (result) {
            $(window).on,
                function (e, t, a) {
                    "use strict";
                    var n, s, i, o, r, l, h, c;
                    n = function (e, t, a) {
                        // drawChart(e, t, a, 'bar');
                        var myChart = echarts.init(document.getElementById("#" + e));
                        // specify chart options here
                        myChart.setOption({
                            xAxis: {
                                type: 'category',
                                data: t
                            },
                            yAxis: {
                                type: 'value'
                            },
                            series: [
                                {
                                    data: a,
                                    type: 'line',
                                    smooth: true
                                }
                            ]
                        });
                        var n = new Chartist.Line("#" + e, {
                            labels: t,
                            series: [a]
                        }, {
                            lineSmooth: Chartist.Interpolation.simple({
                                divisor: 2
                            }),
                            fullWidth: !0,
                            chartPadding: {
                                right: 25
                            },
                            series: {
                                "series-1": {
                                    showArea: !1
                                }
                            },
                            axisX: {
                                showGrid: !1
                            },
                            axisY: {
                                labelInterpolationFnc: function (e) {
                                    return e / 1e3 + "K"
                                },
                                scaleMinSpace: 40
                            },
                            low: 0,
                            showPoint: !1,
                            height: 300
                        });
                        n.on("created", (function (t) {
                            var a = t.svg.querySelector("defs") || t.svg.elem("defs"),
                                n = (t.svg.width(), t.svg.height(), a.elem("filter", {
                                    x: 0,
                                    y: "-10%",
                                    id: "shadow" + e
                                }, "", !0));
                            return n.elem("feGaussianBlur", {
                                in: "SourceAlpha",
                                stdDeviation: "24",
                                result: "offsetBlur"
                            }), n.elem("feOffset", {
                                dx: "0",
                                dy: "32"
                            }), n.elem("feBlend", {
                                in: "SourceGraphic",
                                mode: "multiply"
                            }), a.elem("linearGradient", {
                                id: e + "-gradient",
                                x1: 0,
                                y1: 0,
                                x2: 1,
                                y2: 0
                            }).elem("stop", {
                                offset: 0,
                                "stop-color": "rgba(22, 141, 238, 1)"
                            }).parent().elem("stop", {
                                offset: 1,
                                "stop-color": "rgba(98, 188, 246, 1)"
                            }), a
                        })).on("draw", (function (t) {
                            "line" === t.type ? t.element.attr({
                                filter: "url(#shadow" + e + ")"
                            }) : "point" === t.type && new Chartist.Svg(t.element._node.parentNode).elem("line", {
                                x1: t.x,
                                y1: t.y,
                                x2: t.x + .01,
                                y2: t.y,
                                class: "ct-point-content"
                            }), "line" !== t.type && "area" != t.type || t.element.animate({
                                d: {
                                    begin: 1e3 * t.index,
                                    dur: 1e3,
                                    from: t.path.clone().scale(1, 0).translate(0, t.chartRect.height()).stringify(),
                                    to: t.path.clone().stringify(),
                                    easing: Chartist.Svg.Easing.easeOutQuint
                                }
                            })
                        }))
                    },
                        s = result[2].day, i = {
                            name: "series-1",
                            data: result[2].total_sale
                        },
                        o = result[1].week,
                        r = {
                            name: "series-1",
                            data: result[1].total_sale
                        },
                        l = result[0].month_name,
                        h = {
                            name: "series-1",
                            data: result[0].total_sale
                        }, (c = function (e) {
                            switch ((e || a("#ecommerceChartView .chart-action").find(".active")).attr("href")) {
                                case "#scoreLineToDay":
                                    n("scoreLineToDay", s, i);
                                    break;
                                case "#scoreLineToWeek":
                                    n("scoreLineToWeek", o, r);
                                    break;
                                case "#scoreLineToMonth":
                                    n("scoreLineToMonth", l, h)
                            }
                        })(), a(".chart-action li a").on("click", (function () {
                            c(a(this))
                        }))
                }(window, document, jQuery);
        }
    });


}

$(document).on("click", '[data-toggle="lightbox"]', function (event) {
    event.preventDefault();
    $(this).ekkoLightbox();
});

var url = window.location.origin + window.location.pathname;
var $selector = $('.sidebar a[href="' + url + '"]');
console.log("selector", $selector);
$($selector).parent().addClass('active');
$($selector).closest('ul').closest('li').addClass('open');
$($selector).closest('ul').removeAttr('style');

var tmp = [];
var permute_counter = 0;

//User defined functions


function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
}

function containsAll(needles, haystack) {
    for (var i = 0; i < needles.length; i++) {
        if ($.inArray(needles[i], haystack) == -1) return false;
    }
    return true;
}

function getPermutation(args) {
    var r = [],
        max = args.length - 1;

    function helper(arr, i) {
        for (var j = 0, l = args[i].length; j < l; j++) {
            var a = arr.slice(0); // clone arr
            a.push(args[i][j]);
            if (i == max)
                r.push(a);
            else
                helper(a, i + 1);
        }
    }
    helper([], 0);
    return r;
}


function clear_form_elements(class_name) {
    jQuery("." + class_name).find(':input').each(function () {
        switch (this.type) {
            case 'password':
            case 'text':
            case 'textarea':
            case 'file':
            case 'select-one':
            case 'select-multiple':
            case 'date':
            case 'number':
            case 'tel':
            case 'email':
                jQuery(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;
                break;
        }
    });
}

function add_product_variant_html(type) {

    if (type == 'packet') {
        var html = "<div class='row offset-md-1 border-bottom ml-5 mr-5 mb-3'><div class='col-md-12 mt-2 remove_pro_btn'><div class='card-tools float-right'> <label>Remove</label> <button type='button' class='btn btn-tool' id='remove_product_btn'> <i class='text-danger far fa-times-circle fa-2x '></i> </button></div></div><div class='form-group col-md-4'> <label for='inputPassword' class='col-sm-4 col-form-label'>Measurement</label><div class='col-sm-10'> <span><input type='number' name='packet_measurement[]' ></span></div></div><div class='form-group col-md-4'> <label for='inputPassword' class='col-sm-6 col-form-label'>Unit</label><div class='col-sm-6'> <select class='form-control valid' name='packet_measurement_unit_id[]' aria-invalid='false'><option value='1'>kg</option><option value='2'>gm</option><option value='3'>ltr</option><option value='4'>ml</option><option value='5'>pack</option> </select></div></div><div class='form-group col-md-4'> <label for='inputPassword' class='col-sm-4 col-form-label'>Price</label><div class='col-sm-10'> <span><input type='number' class='price' name='packet_price[]'></span></div></div><div class='form-group col-md-4'> <label for='inputPassword' class='col-sm-4 col-form-label'>Discounted Price</label><div class='col-sm-10'> <span><input type='number' class='discount' name='packet_discnt[]'></span></div></div><div class='form-group col-md-4'> <label for='inputPassword' class='col-sm-4 col-form-label'>Stock</label><div class='col-sm-10'> <input type='number' name='packet_stock[]'></div></div><div class='form-group col-md-4'> <label for='inputPassword' class='col-sm-4 col-form-label'>Unit</label><div class='col-sm-6'> <select class='form-control valid' name='packet_stock_unit_id[]' aria-invalid='false'><option value='1'>kg</option><option value='2'>gm</option><option value='3'>ltr</option><option value='4'>ml</option><option value='5'>pack</option> </select></div></div><div class='form-group col-md-4'> <label for='inputPassword' class='col-sm-4 col-form-label'>Status</label><div class='col-sm-6'> <select name='packet_serve_for[]' class='form-control' required='' aria-invalid='false'><option value='Available'>Available</option><option value='Sold Out'>Sold Out</option> </select></div></div></div>";
        return html;
    } else {
        var html = '<div class="row offset-md-1 border-bottom ml-5 mr-5 mb-3"><div class="col-md-12 mt-2 remove_pro_btn"><div class="card-tools float-right"> <label>Remove</label> <button type="button" class="btn btn-tool" id="remove_product_btn"> <i class="text-danger far fa-times-circle fa-2x "></i> </button></div></div><div class="form-group col-md-3 col-12"> <label for="inputPassword" class="col-sm-12 col-form-label">Measurement</label><div class="col-12"> <span><input type="number" name="loose_measurement[]" class="col-12" ></span></div></div><div class="form-group col-md-3"> <label for="inputPassword" class="col-sm-6 col-form-label">Unit</label><div class="col-sm-12"> <select class="form-control valid" name="loose_measurement_unit_id[] col-12" aria-invalid="false"><option value="1">kg</option><option value="2">gm</option><option value="3">ltr</option><option value="4">ml</option><option value="5">pack</option> </select></div></div><div class="form-group col-md-3"> <label for="inputPassword" class="col-sm-4 col-form-label">Price</label><div class="col-sm-10"> <span><input type="number" name="loose_price[]" class="col-12 price"></span></div></div><div class="form-group col-md-3"> <label for="inputPassword" class="col-sm-12 col-form-label">Discounted Price</label><div class="col-sm-10"> <span><input type="number" name="loose_discnt[]" class="col-12 discount"></span></div></div></div>';
        return html;
    }

}


function save_attributes() {



    attributes_values = [];
    all_attributes_values = [];
    var tmp = $('.product-attr-selectbox');
    $.each(tmp, function (index) {
        var data = $(tmp[index]).closest('.row').find('.multiple_values').select2('data');
        var tmp_values = [];
        for (var i = 0; i < data.length; i++) {
            if (!$.isEmptyObject(data[i])) {
                tmp_values[i] = data[i].id;
            }
        }
        if (!$.isEmptyObject(data)) {
            all_attributes_values.push(tmp_values);
        }
        if ($(tmp[index]).find('.is_attribute_checked').is(':checked')) {
            if (!$.isEmptyObject(data)) {
                attributes_values.push(tmp_values);
            }
        }
    });


}

function create_variants(preproccessed_permutation_result = false, from) {

    var html = "";
    var is_appendable = false;
    var permutated_attribute_value = [];
    if (preproccessed_permutation_result != false) {
        var response = preproccessed_permutation_result;
        is_appendable = true;
    } else {
        var response = getPermutation(attributes_values);
    }
    var selected_variant_ids = JSON.stringify(response);
    var selected_attributes_values = JSON.stringify(attributes_values);

    $('.no-variants-added').hide();
    $.ajax({
        type: 'GET',
        url: base_url + from + '/product/get_variants_by_id',
        data: {
            variant_ids: selected_variant_ids,
            attributes_values: selected_attributes_values,
        },
        dataType: 'json',
        success: function (data) {
            var result = data['result'];
            $.each(result, function (a, b) {

                variant_counter++;
                var attr_name = 'pro_attr_' + variant_counter;
                html += '<div class="form-group move row my-auto p-2 border rounded bg-gray-light product-variant-selectbox"><div class="col-1 text-center my-auto"><i class="fas fa-sort"></i></div>';
                var tmp_variant_value_id = " ";
                $.each(b, function (key, value) {
                    tmp_variant_value_id = tmp_variant_value_id + " " + value.id;
                    html += '<div class="col-2"> <input type="text" class="col form-control" value="' + value.value + '" readonly></div>';
                });
                html += '<input type="hidden" name="variants_ids[]" value="' + tmp_variant_value_id + '"><div class="col my-auto row justify-content-center"> <a data-toggle="collapse" class="btn btn-tool text-primary" data-target="#' + attr_name + '" aria-expanded="true"><i class="fas fa-angle-down fa-2x"></i> </a> <button type="button" class="btn btn-tool remove_variants"> <i class="text-danger far fa-times-circle fa-2x "></i> </button></div><div class="col-12" id="variant_stock_management_html"><div id=' + attr_name + ' style="" class="collapse">';
                if ($('.variant_stock_status').is(':checked') && $('.variant-stock-level-type').val() == 'variable_level') {
                    html += '<div class="form-group row"><div class="col col-xs-12"><label class="control-label">Price :</label><input type="number" name="variant_price[]" class="col form-control price varaint-must-fill-field" min="1" step="0.01"></div><div class="col col-xs-12"><label class="control-label">Special Price :</label><input type="number" name="variant_special_price[]" class="col form-control discounted_price" min="0" step="0.01"></div><div class="col col-xs-12"> <label class="control-label">SKU :</label> <input type="text" name="variant_sku[]" class="col form-control varaint-must-fill-field"></div><div class="col col-xs-12"> <label class="control-label">Total Stock :</label> <input type="number" min = "1" name="variant_total_stock[]" class="col form-control varaint-must-fill-field"></div><div class="col col-xs-12"> <label class="control-label">Stock Status :</label> <select type="text" name="variant_level_stock_status[]" class="col form-control varaint-must-fill-field"><option value="1">In Stock</option><option value="0">Out Of Stock</option> </select></div></div>';
                } else {
                    html += '<div class="form-group row"><div class="col col-xs-12"><label class="control-label">Price :</label><input type="number" name="variant_price[]" class="col form-control price varaint-must-fill-field" min="1" step="0.01"></div><div class="col col-xs-12"><label class="control-label">Special Price :</label><input type="number" name="variant_special_price[]" class="col form-control discounted_price" min="0" step="0.01"></div>';
                }
                html += '<div class="col-12 pt-3"><label class="control-label">Images :</label><div class="col-md-3"><a class="uploadFile img btn btn-primary text-white btn-sm"  data-input="variant_images[' + a + '][]" data-isremovable="1" data-is-multiple-uploads-allowed="1" data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class="fa fa-upload"></i> Upload</a> </div><div class="container-fluid row image-upload-section"></div></div>';
                html += '</div></div></div></div></div>';
            });

            if (is_appendable == false) {
                $('#variants_process').html(html);
            } else {
                $('#variants_process').append(html);
            }
            $('#variants_process').unblock();
        }
    });
}

function create_attributes(value, selected_attr) {
    counter++;
    var $attribute = $('#attributes_values_json_data').find('.select_single');
    var $options = $($attribute).clone().html();
    var $selected_attrs = [];
    if (selected_attr) {
        $.each(selected_attr.split(","), function () {
            $selected_attrs.push($.trim(this));
        });
    }

    var attr_name = 'pro_attr_' + counter;

    // product-attr-selectbox
    if ($('#product-type').val() == 'simple_product') {
        var html = '<div class="form-group move row my-auto p-2 border rounded bg-gray-light product-attr-selectbox" id=' + attr_name + '><div class="col-md-1 col-sm-12 text-center my-auto"><i class="fas fa-sort"></i></div><div class="col-md-4 col-sm-12"> <select name="attribute_id[]" class="attributes select_single" data-placeholder=" Type to search and select attributes"><option value=""></option>' + $options + '</select></div><div class="col-md-4 col-sm-12"> <select name="attribute_value_ids[]" class="multiple_values" multiple="" data-placeholder=" Type to search and select attributes values"><option value=""></option> </select></div><div class="col-md-2 col-sm-6 text-center py-1 align-self-center"> <button type="button" class="btn btn-tool remove_attributes"> <i class="text-danger far fa-times-circle fa-2x "></i> </button></div></div>';
    } else {
        $('#note').removeClass('d-none');
        var html = '<div class="form-group row move my-auto p-2 border rounded bg-gray-light product-attr-selectbox" id=' + attr_name + '><div class="col-md-1 col-sm-12 text-center my-auto"><i class="fas fa-sort"></i></div><div class="col-md-4 col-sm-12"> <select name="attribute_id[]" class="attributes select_single" data-placeholder=" Type to search and select attributes"><option value=""></option>' + $options + '</select></div><div class="col-md-4 col-sm-12"> <select name="attribute_value_ids[]" class="multiple_values" multiple="" data-placeholder=" Type to search and select attributes values"><option value=""></option> </select></div><div class="col-md-2 col-sm-6 text-center py-1 align-self-center"><input type="checkbox" name="variations[]" class="is_attribute_checked custom-checkbox mt-2"></div><div class="col-md-1 col-sm-6 text-center py-1 align-self-center"> <button type="button" class="btn btn-tool remove_attributes"> <i class="text-danger far fa-times-circle fa-2x "></i> </button></div></div>';
    }
    $('#attributes_process').append(html);
    if (selected_attr) {
        if ($.inArray(value.name, $selected_attrs) > -1) {
            $("#attributes_process").find('.product-attr-selectbox').last().find('.is_attribute_checked').prop('checked', true).addClass('custom-checkbox mt-2');
            $("#attributes_process").find('.product-attr-selectbox').last().find('.remove_attributes').addClass('remove_edit_attribute').removeClass('remove_attributes');

        }
    }
    $("#attributes_process").find('.product-attr-selectbox').last().find(".attributes").select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
    }).val(value.name);

    $("#attributes_process").find('.product-attr-selectbox').last().find(".attributes").trigger('change');
    $("#attributes_process").find('.product-attr-selectbox').last().find(".select_single").trigger('select2:select');

    var multiple_values = [];
    $.each(value.ids.split(","), function () {
        multiple_values.push($.trim(this));
    });

    $("#attributes_process").find('.product-attr-selectbox').last().find(".multiple_values").select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
    }).val(multiple_values);
    $("#attributes_process").find('.product-attr-selectbox').last().find(".multiple_values").trigger('change');
}

function create_fetched_attributes_html(from) {
    var edit_id = $('input[name="edit_product_id"]').val();
    $.ajax({
        type: 'GET',
        url: base_url + from + '/product/fetch_attributes_by_id',
        data: {
            edit_id: edit_id,
            [csrfName]: csrfHash,
        },
        dataType: 'json',
        success: function (data) {
            csrfName = data['csrfName'];
            csrfHash = data['csrfHash'];
            var result = data['result'];

            if (!$.isEmptyObject(result.attr_values)) {

                $.each(result.attr_values, function (key, value) {
                    create_attributes(value, result.pre_selected_variants_names);
                });

                $.each(result['pre_selected_variants_ids'], function (key, val) {
                    var tempArray = [];
                    if (val.variant_ids) {
                        $.each(val.variant_ids.split(','), function (k, v) {
                            tempArray.push($.trim(v));
                        });
                        pre_selected_attr_values[key] = tempArray;
                    }
                });

                if (result.pre_selected_variants_names) {
                    $.each(result.pre_selected_variants_names.split(','), function (key, value) {
                        pre_selected_attributes_name.push($.trim(value));
                    });
                }
            } else {
                $('.no-attributes-added').show();
                $('#save_attributes').addClass('d-none');
            }
        }
    });
    return $.Deferred().resolve();
}

function search_category_wise_products() {
    var category_id = $('#category_parent').val();
    if (category_id != '') {
        $.ajax({
            data: {
                'cat_id': category_id,
            },
            type: 'GET',
            url: base_url + 'admin/product/search_category_wise_products',
            dataType: 'json',
            success: function (result) {
                var html = "";
                var i = 0;
                if (!$.isEmptyObject(result)) {
                    $.each(result, function (index, value) {
                        html += '<li class="list-group-item d-flex bg-gray-light align-items-center h-25 ui-sortable-handle" id="product_id-' + value['id'] + '">';
                        html += '<div class="col-md-1"><span> ' + i + ' </span></div>';
                        html += '<div class="col-md-3"><span> ' + value['row_order'] + ' </span></div>';
                        html += '<div class="col-md-4"><span>' + value['name'] + '</span></div>';
                        html += '<div class="col-md-4"><img src="' + base_url + value['image'] + '"  class="w-25"></div>';
                        i++;
                    });
                    $('#sortable').html(html);
                } else {

                    iziToast.error({
                        message: 'No Products Are Available',
                    });

                    html += '<li class="list-group-item d-flex justify-content-center bg-gray-light align-items-center h-25 ui-sortable-handle" id="product_id-3"><div class="col-md-12 d-flex justify-content-center"><span>No Products  Are  Available</span></div></li>';
                    $('#sortable').html(html);
                }
            }
        });
    } else {
        iziToast.error({
            message: 'Category Field Should Be Selected',
        });
    }
}



function save_product(form) {

    $('input[name="product_type"]').val($('#product-type').val());
    if ($('.simple_stock_management_status').is(':checked')) {
        $('input[name="simple_product_stock_status"]').val($('#simple_product_stock_status').val());
    } else {
        $('input[name="simple_product_stock_status"]').val('');
    }
    $('#product-type').prop('disabled', true);
    $('.product-attributes').removeClass('disabled');
    $('.product-variants').removeClass('disabled');
    $('.simple_stock_management_status').prop('disabled', true);

    var catid = $('#product_category_tree_view_html').jstree("get_selected");
    var formData = new FormData(form);
    var submit_btn = $('#submit_btn');
    var btn_html = $('#submit_btn').html();
    var btn_val = $('#submit_btn').val();
    var button_text = (btn_html != '' || btn_html != 'undefined') ? btn_html : btn_val;
    save_attributes();
    formData.append(csrfName, csrfHash);

    formData.append('category_id', catid);
    formData.append('attribute_values', all_attributes_values);

    $.ajax({
        type: 'POST',
        url: $(form).attr('action'),
        data: formData,
        beforeSend: function () {
            submit_btn.html('Please Wait..');
            submit_btn.attr('disabled', true);
        },
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (result) {
            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];

            if (result['error'] == true) {
                submit_btn.html(button_text);
                submit_btn.attr('disabled', false);
                iziToast.error({
                    message: result['message'],
                });

            } else {
                submit_btn.html(button_text);
                submit_btn.attr('disabled', false);
                iziToast.success({
                    message: result['message'],
                });
                setTimeout(function () {
                    location.reload();
                }, 600);
            }
        }
    });
}


function get_variants(edit_id, from) {
    return $.ajax({
        type: 'GET',
        url: base_url + from + '/product/fetch_variants_values_by_pid',
        data: {
            edit_id: edit_id
        },
        dataType: 'json'
    })
        .done(function (data) {
            return data.responseCode != 200 ?
                $.Deferred().reject(data) : data;
        });

}

function create_fetched_variants_html(add_newly_created_variants = false, from) {

    var newArr1 = [];
    for (var i = 0; i < pre_selected_attr_values.length; i++) {
        var temp = newArr1.concat(pre_selected_attr_values[i]);
        newArr1 = [...new Set(temp)];

    }
    var newArr2 = [];
    for (var i = 0; i < attributes_values.length; i++) {
        newArr2 = newArr2.concat(attributes_values[i]);
    }


    current_attributes_selected = $.grep(newArr2, function (x) {
        return $.inArray(x, newArr1) < 0
    });

    if (containsAll(newArr1, newArr2)) {
        var temp = [];
        if (!$.isEmptyObject(current_attributes_selected)) {
            $.ajax({
                type: 'GET',
                url: base_url + from + '/product/fetch_attribute_values_by_id',
                data: {
                    id: current_attributes_selected,
                },
                dataType: 'json',
                success: function (result) {
                    temp = result;
                    $.each(result, function (key, value) {
                        if (pre_selected_attributes_name.indexOf($.trim(value.name)) > -1) {
                            delete temp[key];
                        }
                    });
                    var resetArr = temp.filter(function () {
                        return true;
                    });
                    setTimeout(function () {
                        var edit_id = $('input[name="edit_product_id"]').val();
                        get_variants(edit_id, from).done(function (data) {
                            create_editable_variants(data.result, resetArr, add_newly_created_variants);
                        });
                    }, 1000);
                }
            });
        } else {
            if (attribute_flag == 0) {
                var edit_id = $('input[name="edit_product_id"]').val();
                get_variants(edit_id, from).done(function (data) {
                    create_editable_variants(data.result, false, add_newly_created_variants);
                });
            }
        }
    } else {
        var edit_id = $('input[name="edit_product_id"]').val();
        get_variants(edit_id, from).done(function (data) {
            create_editable_variants(data.result, false, add_newly_created_variants);
        });
    }
}

function create_editable_variants(data, newly_selected_attr = false, add_newly_created_variants = false) {
    if (data[0].variant_ids) {
        $('#reset_variants').show();
        var html = '';

        if (!$.isEmptyObject(attributes_values) && add_newly_created_variants == true) {
            var permuted_value_result = getPermutation(attributes_values);
        }

        $.each(data, function (a, b) {

            if (!$.isEmptyObject(permuted_value_result) && add_newly_created_variants == true) {
                var permuted_value_result_temp = permuted_value_result;
                var varinat_ids = b.variant_ids.split(',');
                $.each(permuted_value_result_temp, function (index, value) {
                    if (containsAll(varinat_ids, value)) {
                        permuted_value_result.splice(index, 1);
                    }
                });
            }

            variant_counter++;
            var attr_name = 'pro_attr_' + variant_counter;
            html += '<div class="form-group move row my-auto p-2 border rounded bg-gray-light product-variant-selectbox"><div class="col-1 text-center my-auto"><i class="fas fa-sort"></i></div>';
            html += '<input type="hidden" name="edit_variant_id[]" value=' + b.id + '>';
            var tmp_variant_value_id = "";
            var varaint_array = [];
            var varaint_ids_temp_array = [];
            var flag = 0;
            var variant_images = "";
            var image_html = "";
            if (b.images) {
                variant_images = JSON.parse(b.images);
            }

            $.each(b.variant_ids.split(","), function (key) {
                varaint_ids_temp_array[key] = $.trim(this);
            });

            $.each(b.variant_values.split(","), function (key) {
                varaint_array[key] = $.trim(this);
            });

            if (variant_images) {
                $.each(variant_images, function (img_key, img_value) {
                    image_html += '<div class="col-md-3 col-sm-12 shadow bg-white rounded m-3 p-3 text-center grow"><div class="image-upload-div"><img src=' + base_url + img_value + ' alt="Image Not Found"></div> <a href="javascript:void(0)" class="delete-img m-3" data-id="' + b.id + '" data-field="images" data-img=' + img_value + ' data-table="product_variants" data-path="uploads/media/" data-isjson="true"> <span class="btn btn-block bg-gradient-danger btn-xs"><i class="far fa-trash-alt "></i> Delete</span></a> <input type="hidden" name="variant_images[' + a + '][]"  value=' + img_value + '></div>';
                });
            }

            for (var i = 0; i < varaint_array.length; i++) {
                html += '<div class="col-2 variant_col"> <input type="hidden"  value="' + varaint_ids_temp_array[i] + '"><input type="text" class="col form-control" value="' + varaint_array[i] + '" readonly></div>';
            }
            if (newly_selected_attr != false && newly_selected_attr.length > 0) {
                for (var i = 0; i < newly_selected_attr.length; i++) {
                    var tempVariantsIds = [];
                    var tempVariantsValues = [];
                    $.each(newly_selected_attr[i].attribute_values_id.split(','), function () {
                        tempVariantsIds.push($.trim(this));
                    });
                    html += '<div class="col-2"><select class="col new-added-variant form-control" ><option value="">Select Attribute</option>';
                    $.each(newly_selected_attr[i].attribute_values.split(','), function (key) {
                        tempVariantsValues.push($.trim(this));
                        html += '<option value="' + tempVariantsIds[key] + '">' + tempVariantsValues[key] + '</option>';
                    });
                    html += '</select></div>';
                }
            }
            html += '<input type="hidden" name="variants_ids[]" value="' + b.attribute_value_ids + '"><div class="col my-auto row justify-content-center"> <a data-toggle="collapse" class="btn btn-tool text-primary" data-target="#' + attr_name + '" aria-expanded="true"><i class="fas fa-angle-down fa-2x"></i> </a> <button type="button" class="btn btn-tool remove_variants"> <i class="text-danger far fa-times-circle fa-2x "></i> </button></div><div class="col-12" id="variant_stock_management_html"><div id=' + attr_name + ' style="" class="collapse">';
            if ($('.variant_stock_status').is(':checked') && $('.variant-stock-level-type').val() == 'variable_level') {
                var selected = (b.availability == '0') ? 'selected' : ' ';
                html += '<div class="form-group row"><div class="col col-xs-12"><label class="control-label">Price :</label><input type="number" name="variant_price[]" class="col form-control price varaint-must-fill-field" value="' + b.price + '" min="1" step="0.01"></div><div class="col col-xs-12"><label class="control-label">Special Price :</label><input type="number" name="variant_special_price[]" class="col form-control discounted_price" min="0" value="' + b.special_price + '" step="0.01"></div><div class="col col-xs-12"> <label class="control-label">Sku :</label> <input type="text" name="variant_sku[]" class="col form-control varaint-must-fill-field"  value="' + b.sku + '" ></div><div class="col col-xs-12"> <label class="control-label">Total Stock :</label> <input type="number" min="1" name="variant_total_stock[]" class="col form-control varaint-must-fill-field" value="' + b.stock + '"></div><div class="col col-xs-12"> <label class="control-label">Stock Status :</label> <select type="text" name="variant_level_stock_status[]" class="col form-control varaint-must-fill-field"><option value="1">In Stock</option><option value="0"  ' + selected + '  >Out Of Stock</option> </select></div></div>';
            } else {
                html += '<div class="form-group row"><div class="col col-xs-12"><label class="control-label">Price :</label><input type="number" name="variant_price[]" class="col form-control price varaint-must-fill-field" value="' + b.price + '" min="1" step="0.01"></div><div class="col col-xs-12"><label class="control-label">Special Price :</label><input type="number" name="variant_special_price[]" class="col form-control discounted_price"  min="0" value="' + b.special_price + '" step="0.01"></div></div>';
            }
            html += '<div class="col-12 pt-3"><label class="control-label">Images :</label><div class="col-md-3"><a class="uploadFile img btn btn-primary text-white btn-sm"  data-input="variant_images[' + a + '][]" data-isremovable="1" data-is-multiple-uploads-allowed="1" data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class="fa fa-upload"></i> Upload</a> </div><div class="container-fluid row image-upload-section"> ' + image_html + ' </div></div>';
            html += '</div></div></div>';

            $('#variants_process').html(html);
        });

        if (!$.isEmptyObject(attributes_values) && add_newly_created_variants == true) {
            create_variants(permuted_value_result, from);
        }

    }
}

function status_date_wise_search() {
    $('.table-striped').bootstrapTable('refresh');
}


function resetfilters() {
    $('#datepicker').val('');
    $('#media-type').val('');
    $('#start_date').val('');
    $('#end_date').val('');
}

function formatRepo(repo) {

    if (repo.loading) return repo.text;
    var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'>" + repo.name + "</div>";

    if (repo.description) {
        markup += "<div class='select2-result-repository__description'> In " + repo.category_name + "</div>";
    }

    return markup;
}

function formatRepo1(repo) {

    if (repo.loading) return repo.text;
    var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'>" + repo.zipcode + "</div>";

    return markup;
}

function formatRepoSelection(repo) {
    return repo.name || repo.text;
}

function formatRepoSelection1(repo) {
    return repo.zipcode || repo.text;
}

function mediaParams(p) {
    return {
        'type': $('#media_type').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function mediaUploadParams(p) {
    return {
        'type': $('#media-type').val(),
        "start_date": $('#start_date').val(),
        "end_date": $('#end_date').val(),
        "seller_id": $('#seller_id').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function noti_query_params(p) {
    return {
        "message_type": $('#message_type').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}


function home_query_params(p) {
    return {
        "start_date": $('#start_date').val(),
        "end_date": $('#end_date').val(),
        "order_status": $('#order_status').val(),
        "payment_method": $('#payment_method').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function queryParams(p) {
    return {
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function ticket_queryParams(p) {
    return {
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function customer_wallet_query_params(p) {
    return {
        transaction_type: 'wallet',
        user_type: 'members',
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function seller_wallet_query_params(p) {
    return {
        transaction_type: 'wallet',
        user_type: 'seller',
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function order_tracking_query_params(p) {
    return {
        "order_id": $('input[name="order_id"]').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
function digital_order_mails_query_params(p) {
    return {
        "order_item_id": $('input[name="order_item_id"]').val(),
        "order_id": $('input[name="order_id"]').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function category_query_params(p) {
    return {
        "category_id": $('#category_id').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function blog_query_params(p) {
    return {
        "category_id": $('#category_parent').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function brand_query_params(p) {
    return {
        "brand_id": $('#brand_id').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function product_query_params(p) {
    return {
        "category_id": $('#category_parent').val(),
        "seller_id": $('#seller_filter').val(),
        "status": $('#status_filter').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function stock_query_params(p) {
    return {
        "status": $('#status_filter').val(),
        limit: p.limit,
        offset: p.offset,
        sort: p.sort,
        order: p.order,
        search: p.search,
        seller_id: $('#seller_filter').val(),
        category_id: $('#category_parent').val(),
    };
}

function payment_request_queryParams(p) {
    return {
        "user_filter": $('#user_filter').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function ratingParams(p) {
    return {
        "category_id": $('#category_parent').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}


function address_query_params(p) {
    return {
        user_id: $('#address_user_id').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function orders_query_params(p) {
    return {
        "start_date": $('#start_date').val(),
        "end_date": $('#end_date').val(),
        "order_status": $('#order_status').val(),
        "user_id": $('#order_user_id').val(),
        "seller_id": $('#order_seller_id').val(),
        "payment_method": $('#payment_method').val(),
        "order_type": $('#order_type').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function transaction_query_params(p) {
    return {
        transaction_type: 'transaction',
        user_id: $('#transaction_user_id').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function sales_report_query_params(p) {
    return {
        "start_date": $('#start_date').val(),
        "end_date": $('#end_date').val(),
        "seller_id": $('#seller_id').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function sales_inventory_report_query_params(p) {
    return {
        "start_date": $('#start_date').val(),
        "end_date": $('#end_date').val(),
        "seller_id": $('#seller_ids').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
$(document).on("change", "#send_to", function (e) {
    e.preventDefault();
    var type_val = $(this).val();
    if (type_val == 'specific_user') {
        // to specific user
        $('.notification-users').removeClass('d-none');
    } else {
        $('.notification-users').addClass('d-none');
    }
});

$(document).on('change', '.type_event_trigger', function (e, data) {
    e.preventDefault();
    var type_val = $(this).val();
    if (type_val != 'default' && type_val != ' ') {
        if (type_val == 'categories') {
            $('.slider-categories').removeClass('d-none');
            $('.notification-categories').removeClass('d-none');
            $('.slider-products').addClass('d-none');
            $('.notification-products').addClass('d-none');
        } else if (type_val == 'products') {
            $('.slider-products').removeClass('d-none');
            $('.notification-products').removeClass('d-none');
            $('.slider-categories').addClass('d-none');
            $('.notification-categories').addClass('d-none');
        }
    } else {
        $('.slider-categories').addClass('d-none');
        $('.slider-products').addClass('d-none');
        $('.notification-categories').addClass('d-none');
        $('.notification-products').addClass('d-none');
    }
});
if ($("input[data-bootstrap-switch]").length) {

    $("input[data-bootstrap-switch]").each(function () {
        $('input[data-bootstrap-switch]').bootstrapSwitch();
    });
}

$(document).on('click', '.edit_btn', function () {
    var id = $(this).data('id');
    var url = $(this).data('url');;
    $('.edit-modal-lg').modal('show').find('.modal-body').load(base_url + url + '?edit_id=' + id + ' .form-submit-event', function () {
        if ($("input[data-bootstrap-switch]").length) {

            $("input[data-bootstrap-switch]").each(function () {
                $('input[data-bootstrap-switch]').bootstrapSwitch();
            });
        }
        $('#category_parent').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            templateResult: function (data) {
                if (!data.element) {
                    return data.text;
                }

                var $element = $(data.element);

                var $wrapper = $('<span></span>');
                $wrapper.addClass($element[0].className);

                $wrapper.text(data.text);

                return $wrapper;
            }
        });
        $('.select_multiple').each(function () {
            $(this).select2({
                theme: 'bootstrap4',
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                allowClear: Boolean($(this).data('allow-clear')),
            });
        });

        $(".search_admin_product").select2({
            ajax: {
                url: base_url + 'admin/product/get_product_data',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (response, params) {
                    params.page = params.page || 1;

                    return {
                        results: response.rows,
                        pagination: {
                            more: (params.page * 30) < response.total
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            minimumInputLength: 1,
            templateResult: formatRepo,
            templateSelection: formatRepoSelection,
            theme: 'bootstrap4',
            placeholder: 'Search for products'
        });
        $(".search_admin_digital_product").select2({
            ajax: {
                url: base_url + 'admin/product/get_digital_product_data',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (response, params) {
                    params.page = params.page || 1;

                    return {
                        results: response.rows,
                        pagination: {
                            more: (params.page * 30) < response.total
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            minimumInputLength: 1,
            templateResult: formatRepo,
            templateSelection: formatRepoSelection,
            theme: 'bootstrap4',
            placeholder: 'Search for products'
        });


        $(".hashtag").click(function () {
            var data = $("textarea#text-box").text();
            var tab = $.trim($(this).text());
            var message = data + tab;
            $('textarea#text-box').val(message);
        });
        $(".hashtag_input").click(function () {
            var data = $("#udt_title").val();
            var tab = $.trim($(this).text());
            var message = data + tab;
            $('input#update_title').val(message);
        });
        searchable_zipcodes();
        setTimeout(function () {
            $('.edit-modal-lg').unblock();
        }, 2000);

    });
});



function searchable_zipcodes() {
    var search_zipcodes = $(".search_zipcode").select2({
        ajax: {
            url: base_url + from + '/area/get_zipcodes',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (response, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;

                return {
                    results: response.data,
                    pagination: {
                        more: (params.page * 30) < response.total
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        minimumInputLength: 1,
        templateResult: formatRepo1,
        templateSelection: formatRepoSelection1,
        theme: 'bootstrap4',
        placeholder: 'Search for zipcodes',
        allowClear: Boolean($(this).data('allow-clear')),
    });
    return search_zipcodes;
}



$(document).on('click', '.view_address', function () {
    var id = $(this).data('id');
    var url = $(this).data('url');;

});

$(document).on('click', '.view_btn', function () {
    var id = $(this).data('id');
    var url = $(this).data('url');
    $('.modal-body').load(base_url + url + '?edit_id=' + id + ' .form-submit-event');
    $('.modal-title').html('Manage Promo code');
    $('.modal-body').addClass('view');
    $(".edit-modal-lg").modal();
});

$(document).on('hidden.bs.modal', '.edit-modal-lg', function () {
    $('.edit-modal-lg .modal-body').removeClass('view');
    $('.edit-modal-lg .modal-body').html('');
})

//form-submit-event
$(document).on('submit', '.container-fluid .form-submit-event', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    var update_id = $('#update_id').val();
    if (update_id == '1') {
        var error_box = $('.edit-modal-lg #error_box');
        var submit_btn = $('.edit-modal-lg #submit_btn');
        var btn_html = $('.edit-modal-lg #submit_btn').html();
        var btn_val = $('.edit-modal-lg #submit_btn').val();
        var button_text = (btn_html != '' || btn_html != 'undefined') ? btn_html : btn_val;

    } else {
        var error_box = $('#error_box', this);
        var submit_btn = $('#submit_btn');
        var btn_html = $('#submit_btn').html();
        var btn_val = $('#submit_btn').val();
        var button_text = (btn_html != '' || btn_html != 'undefined') ? btn_html : btn_val;
    }

    formData.append(csrfName, csrfHash);

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        beforeSend: function () {
            submit_btn.html('Please Wait..');
            submit_btn.attr('disabled', true);
        },
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (result) {
            console.log(result)
            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];

            if (result['error'] == true) {

                error_box.addClass("msg_error rounded p-3").removeClass('d-none msg_success');
                error_box.show().delay(1000).fadeOut();
                error_box.html(result['message']);
                submit_btn.html(button_text);
                submit_btn.attr('disabled', false);
                iziToast.error({
                    message: result['message'],
                });

            } else {

                error_box.addClass("msg_success rounded p-3").removeClass('d-none msg_error');
                error_box.show().delay(1000).fadeOut();
                error_box.html(result['message']);
                submit_btn.html(button_text);
                submit_btn.attr('disabled', false);
                setTimeout(function () {
                    $('.modal').modal('hide');
                }, 1000);
                $('.table-striped').bootstrapTable('refresh');
                iziToast.success({
                    message: result['message'],
                });
                $('.form-submit-event')[0].reset();
                if (window.location.href.indexOf("login") > -1) {
                    setTimeout(function () {
                        location.reload();
                    }, 600);
                }

                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        }
    });
});

// 1.login

//forgot_page
$(document).ready(function () {
    $('#forgot_password_page').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            beforeSend: function () {
                $('#submit_btn').html('Please Wait..');
                $('#submit_btn').attr('disabled', true);
            },
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                $('#result').html(result['message']);
                $('#result').show().delay(6000).fadeOut();
                $('#submit_btn').html('Send Email');
                $('#submit_btn').attr('disabled', false);
            }
        });
    });
});

//2.Product-Module
var edit_product_id = $('input[name=edit_product_id]').val();

if (edit_product_id) {

    create_fetched_attributes_html('seller').done(function () {
        $('.no-attributes-added').hide();
        $('#save_attributes').removeClass('d-none');
        $('.no-variants-added').hide();
        save_attributes();
        create_fetched_variants_html(false, from);
    });

}

$(document).on('switchChange.bootstrapSwitch', '#is_cancelable', function (event) {
    event.preventDefault();
    var state = $(this).bootstrapSwitch('state');
    if (state) {
        $('#cancelable_till').show();
    } else {
        $('#cancelable_till').hide();
    }

});
$(document).on('switchChange.bootstrapSwitch', '#download_allowed', function (event) {
    event.preventDefault();
    var state = $(this).bootstrapSwitch('state');
    if (state) {
        $('#download_type').show();

    } else {
        $('#download_type').hide();
        $('#digital_link_container').addClass('d-none');
        $('#digital_media_container').addClass('d-none');
    }

});


$(document).on('change', '#category_parent', function () {
    $('#products_table').bootstrapTable('refresh');
});

$(document).on('change', '#category_parent', function () {
    $('#category_table').bootstrapTable('refresh');
});

$(document).on('change', '#seller_filter', function () {
    $('#products_table').bootstrapTable('refresh');
});
$(document).on('change', '#user_filter', function () {
    $('#payment_request_table').bootstrapTable('refresh');
});
$(document).on('change', '#status_filter', function () {
    $('#products_table').bootstrapTable('refresh');
});

//Summer-note
$(document).ready(function () {



    var sub_id = $('#subcategory_id_js').val();
    if (typeof sub_id !== 'undefined') {
        $('#category_id').trigger('change', [{
            subcategory_id: sub_id
        }]);
    }

});

$(document).on('click', '#variation_product_btn', function (e) {
    e.preventDefault();
    var radio = $("input[name='pro_input_type']:checked").val();
    var edit_product_id = $('input[name=edit_product_id]').val();
    var html = '';
    html = add_product_variant_html(radio);
    $('#product_variance_html').append(html);
    if (typeof edit_product_id != 'undefined') {
        $("#product_variance_html").children('div').last().append("<input type='hidden' name='edit_product_variant[]'>");
    }
});
$(document).on('click', '#remove_product_btn', function (e) {
    e.preventDefault();
    $(this).closest('.row').remove();
});
$(document).on('click', '.delete-img', function () {
    var isJson = false;
    var id = $(this).data('id');
    var path = $(this).data('path');
    var field = $(this).data('field');
    var img_name = $(this).data('img');
    var table_name = $(this).data('table');
    var t = this;
    var isjson = $(this).data('isjson');
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'POST',
                    url: base_url + 'admin/home/delete_image',
                    data: {
                        id: id,
                        path: path,
                        field: field,
                        img_name: img_name,
                        table_name: table_name,
                        isjson: isjson,
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function (result) {
                        csrfName = result['csrfName'];
                        csrfHash = result['csrfHash'];
                        if (result['is_deleted'] == true) {
                            $(t).closest('div').remove();
                            Swal.fire('Success', 'Media Deleted !', 'success');
                        } else {
                            Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                        }
                    }
                });
            });
        },
        allowOutsideClick: false
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Cancelled!', 'Your data is  safe.', 'error');
        }
    });
});

$(document).on('click', '.delete-media', function () {

    var id = $(this).data('id');
    var t = this;
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + from + '/media/delete/' + id,
                    dataType: 'json',
                    success: function (result) {
                        csrfName = result['csrfName'];
                        csrfHash = result['csrfHash'];
                        if (result['error'] == false) {
                            $('table').bootstrapTable('refresh');
                            Swal.fire('Success', 'File Deleted !', 'success');
                        } else {
                            Swal.fire('Oops...', result['message'], 'error');
                        }
                    }
                });
            });
        },
        allowOutsideClick: false
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Cancelled!', 'Your data is  safe.', 'error');
        }
    });
});



$(document).on('focusout', '.discounted_price', function () {
    var discount_amt = parseInt($(this).val());
    var price = parseInt($(this).closest('.form-group').siblings().find('.price').val());
    if (typeof price != 'undefined' && price != "") {
        if (discount_amt > price) {
            iziToast.error({
                message: "Special price can" + "'" + "t exceed price",
            });
            $(this).val('');
        }
    }
});
$(document).on('focusout', '.price', function () {
    var price = parseInt($(this).val());
    var discount_amt = parseInt($(this).closest('.form-group').siblings().find('.discounted_price').val());
    if (typeof discount_amt != 'undefined' && discount_amt != "") {
        if (discount_amt > price) {
            iziToast.error({
                message: "Special price can" + "'" + "t exceed price",
            });
            $(this).val('');
        }
    }
});
$(document).on('click', '.clear-product-variance', function () {
    var edit_product_id = $('input[name=edit_product_id]').val();
    var radio_val = $("input[name='pro_input_type']:checked").val();
    var t = this;
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: base_url + 'admin/product/delete_product',
                    type: 'GET',
                    data: {
                        'id': edit_product_id
                    },
                    dataType: 'json'
                }).done(function (response, textStatus) {
                    Swal.fire('Deleted!', response.message);
                    if (radio_val == 'packet') {
                        html = add_product_variant_html(radio_val);
                        $('#product_variance_html').html(html);
                        $('#product_loose_html').hide();
                        $('.pro_loose').hide();
                        $('.remove_pro_btn').hide();
                        $(t).hide();
                    } else {
                        $('#product_variance_html').show();
                        html = add_product_variant_html(radio_val);
                        $('#product_loose_html').show();
                        $('#product_variance_html').html(html);
                        $('.remove_pro_btn').hide();
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                });
            });
        },
        allowOutsideClick: false
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Cancelled!', 'Your data is  safe.', 'error');
        }
    });
});
$('#sortable').sortable({
    axis: 'y',
    opacity: 0.6,
    cursor: 'grab'
});

$('#sortable').sortableJS({
    axis: 'y',
    opacity: 0.6,
    cursor: 'grab'
});

$(document).on('click', '#save_product_order', function () {
    var data = $('#sortable').sortable('serialize');
    console.log('here');
    $.ajax({
        data: data,
        type: 'GET',
        url: base_url + 'admin/product/update_product_order',
        dataType: 'json',
        success: function (response) {
            if (response.error == false) {
                iziToast.success({
                    message: response.message,
                });
            } else {
                iziToast.error({
                    message: response.message,
                });
            }
        }
    });
});

$(document).on('click', '#save_product_order', function () {
    var data = $('#sortable').sortableJS('serialize');
    console.log('here');
    $.ajax({
        data: data,
        type: 'GET',
        url: base_url + 'admin/product/update_product_order',
        dataType: 'json',
        success: function (response) {
            if (response.error == false) {
                iziToast.success({
                    message: response.message,
                });
            } else {
                iziToast.error({
                    message: response.message,
                });
            }
        }
    });
});

$(document).on('click', '#save_section_order', function () {
    var data = $('#sortable').sortable('serialize');
    $.ajax({
        data: data,
        type: 'GET',
        url: base_url + 'admin/featured_sections/update_section_order',
        dataType: 'json',
        success: function (response) {
            if (response.error == false) {
                iziToast.success({
                    message: response.message,
                });
            } else {
                iziToast.error({
                    message: response.message,
                });
            }
        }
    });
});

$(document).on('click', '#save_section_order', function () {
    var data = $('#sortable').sortableJS('serialize');
    $.ajax({
        data: data,
        type: 'GET',
        url: base_url + 'admin/featured_sections/update_section_order',
        dataType: 'json',
        success: function (response) {
            if (response.error == false) {
                iziToast.success({
                    message: response.message,
                });
            } else {
                iziToast.error({
                    message: response.message,
                });
            }
        }
    });
});

//form-submit-event
$(document).on('submit', '#save-product', function (e) {
    e.preventDefault();
    var product_type = $('#product-type').val();
    var counter = 0;
    if (product_type != 'undefined' && product_type != ' ') {

        if ($.trim(product_type) == 'simple_product') {
            if ($('.simple_stock_management_status').is(':checked')) {
                var len = 0
            } else {
                var len = 1
            }

            if ($('.stock-simple-mustfill-field').filter(function () {
                return this.value === '';
            }).length === len) {

                $('input[name="product_type"]').val($('#product-type').val());
                if ($('.simple_stock_management_status').is(':checked')) {
                    $('input[name="simple_product_stock_status"]').val($('#simple_product_stock_status').val());
                } else {
                    $('input[name="simple_product_stock_status"]').val('');
                }
                $('#product-type').prop('disabled', true);
                $('.product-attributes').removeClass('disabled');
                $('.product-variants').removeClass('disabled');
                $('.simple_stock_management_status').prop('disabled', true);

                save_product(this);
            } else {
                iziToast.error({
                    message: 'Please Fill All The Fields',
                });
            }
        }

        if ($.trim(product_type) == 'variable_product') {
            if ($('.variant_stock_status').is(":checked")) {
                var variant_stock_level_type = $('.variant-stock-level-type').val();
                if (variant_stock_level_type == 'product_level') {
                    if ($('.variant-stock-level-type').filter(function () {
                        return this.value === '';
                    }).length === 0 && $.trim($('.variant-stock-level-type').val()) != "") {

                        if ($('.variant-stock-level-type').val() == 'product_level' && $('.variant-stock-mustfill-field').filter(function () {
                            return this.value === '';
                        }).length !== 0) {
                            iziToast.error({
                                message: 'Please Fill All The Fields',
                            });
                        } else {
                            var varinat_price = $('input[name="variant_price[]"]').val();

                            if ($('input[name="variant_price[]"]').length >= 1) {

                                if ($('.varaint-must-fill-field').filter(function () {
                                    return this.value === '';
                                }).length == 0) {

                                    $('input[name="product_type"]').val($('#product-type').val());
                                    $('input[name="variant_stock_level_type"]').val($('#stock_level_type').val());
                                    $('input[name="varaint_stock_status"]').val("0");
                                    $('#product-type').prop('disabled', true);
                                    $('#stock_level_type').prop('disabled', true);
                                    $(this).removeClass('save-variant-general-settings');
                                    $('.product-attributes').removeClass('disabled');
                                    $('.product-variants').removeClass('disabled');
                                    $('.variant-stock-level-type').prop('readonly', true);
                                    $('#stock_status_variant_type').attr('readonly', true);
                                    $('.variant-product-level-stock-management').find('input,select').prop('readonly', true);
                                    $('#tab-for-variations').removeClass('d-none');
                                    $('.variant_stock_status').prop('disabled', true);
                                    $('#product-tab a[href="#product-attributes"]').tab('show');
                                    save_product(this);

                                } else {
                                    $('.varaint-must-fill-field').each(function () {
                                        $(this).css('border', '');
                                        if ($(this).val() == '') {
                                            $(this).css('border', '2px solid red');
                                            $(this).closest('#variant_stock_management_html').find('div:first').addClass('show');
                                            $('#product-tab a[href="#product-variants"]').tab('show');
                                            counter++;
                                        }
                                    });
                                }


                            } else {

                                Swal.fire('Variation Needed !', 'Atleast Add One Variation To Add The Product.', 'warning');
                            }
                        }
                    } else {
                        iziToast.error({
                            message: 'Please Fill All The Fields',
                        });
                    }
                } else {
                    if ($('input[name="variant_price[]"]').length >= 1) {

                        if ($('.varaint-must-fill-field').filter(function () {
                            return this.value === '';
                        }).length == 0) {
                            $('input[name="product_type"]').val($('#product-type').val());
                            $('.variant_stock_status').prop('disabled', true);
                            $('#product-type').prop('disabled', true);
                            $('.product-attributes').removeClass('disabled');
                            $('.product-variants').removeClass('disabled');
                            $('#tab-for-variations').removeClass('d-none');
                            save_product(this);
                        } else {
                            $('.varaint-must-fill-field').each(function () {
                                $(this).css('border', '');
                                if ($(this).val() == '') {
                                    $(this).css('border', '2px solid red');
                                    $(this).closest('#variant_stock_management_html').find('div:first').addClass('show');
                                    $('#product-tab a[href="#product-variants"]').tab('show');
                                    counter++;
                                }
                            });
                        }

                    } else {

                        Swal.fire('Variation Needed !', 'Atleast Add One Variation To Add The Product.', 'warning');

                    }
                }
            } else {

                if ($('input[name="variant_price[]"]').length == 0) {
                    Swal.fire('Variation Needed !', 'Atleast Add One Variation To Add The Product.', 'warning');
                } else {
                    if ($('.varaint-must-fill-field').filter(function () {
                        return this.value === '';
                    }).length == 0) {
                        save_product(this);
                    } else {
                        $('.varaint-must-fill-field').each(function () {
                            $(this).css('border', '');
                            if ($(this).val() == '') {
                                $(this).css('border', '2px solid red');
                                $(this).closest('#variant_stock_management_html').find('div:first').addClass('show');
                                $('#product-tab a[href="#product-variants"]').tab('show');
                                counter++;
                            }
                        });
                    }
                }
            }
        }
        if ($.trim(product_type) == 'digital_product') {
            console.log('this is digital products');
            save_product(this);
        }

    } else {
        iziToast.error({
            message: 'Please Select Product Type !',
        });
    }

    if (counter > 0) {
        iziToast.error({
            message: 'Please fill all the required fields in the variation tab !',
        });
    }

});



$(document).on('click', '#delete-product', function () {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + from + '/product/delete_product',
                    data: {
                        id: id
                    },
                    dataType: 'json'
                }).done(function (response, textStatus) {
                    if (response.error == false) {

                        Swal.fire('Deleted!', response.message, 'success');
                    } else {
                        Swal.fire('Oops...', response.message, 'error');
                    }
                    $('table').bootstrapTable('refresh');
                    csrfName = response['csrfName'];
                    csrfHash = response['csrfHash'];
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    csrfName = response['csrfName'];
                    csrfHash = response['csrfHash'];
                });
            });
        },
        allowOutsideClick: false
    });
});

$(document).on('click', '#delete-mission', function () {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + from + '/delegate/delete_mission',
                    data: {
                        id: id
                    },
                    dataType: 'json'
                }).done(function (response, textStatus) {
                    if (response.error == false) {

                        Swal.fire('Deleted!', response.message, 'success');
                    } else {
                        Swal.fire('Oops...', response.message, 'error');
                    }
                    $('table').bootstrapTable('refresh');
                    csrfName = response['csrfName'];
                    csrfHash = response['csrfHash'];
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    csrfName = response['csrfName'];
                    csrfHash = response['csrfHash'];
                });
            });
        },
        allowOutsideClick: false
    });
});

// multiple_values
$('.select_single , .multiple_values , #product-type, #delegate_name, #customer_name').each(function () {
    $(this).select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
    });
});

$(document).on('select2:selecting', '.select_single', function (e) {

    if ($.inArray($(this).val(), attributes_values_selected) > -1) {
        //Remove value if further selected
        attributes_values_selected.splice(attributes_values_selected.indexOf($(this).select2().find(":selected").val()), 1);
    }

});

$(document).on('select2:selecting', '.select_single .variant_attributes', function (e) {

    if ($.inArray($(this).val(), variant_values_selected) > -1) {
        //Remove value if further selected
        variant_values_selected.splice(variant_values_selected.indexOf($(this).select2().find(":selected").val()), 1);

    }

});

$(document).on('select2:select', '.select_single', function (e) {
    var text = this.className;
    var type;
    $(this).closest('.row').find(".multiple_values").text(null).trigger('change');
    var data = $(this).select2().find(":selected").data("values");
    if (text.search('attributes') != -1) {
        value_check_array = attributes_values_selected.slice();
        type = 'attributes';
    }

    if (text.search('variant_attributes') != -1) {
        value_check_array = variant_values_selected.slice();
        type = 'variant_attributes';
    }

    if ($.inArray($(this).select2().find(":selected").val(), value_check_array) > -1) {
        iziToast.error({
            message: 'Attribute Already Selected',
        });
        $(this).val('').trigger('change');
    } else {
        value_check_array.push($(this).select2().find(":selected").val());
    }
    if (text.search('attributes') != -1) {
        attributes_values_selected = value_check_array.slice();
    }

    if (text.search('variant_attributes') != -1) {
        variant_values_selected = value_check_array.slice();
    }
    $(this).closest('.row').find("." + type).select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
    });
    $(this).closest('.row').find(".multiple_values").select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
        data: data,
    });


});

$(document).on('click', ' #add_attributes , #tab-for-variations', function (e) {

    if (e.target.id == 'add_attributes') {

        $('.no-attributes-added').hide();
        $('#save_attributes').removeClass('d-none');
        counter++;
        var $attribute = $('#attributes_values_json_data').find('.select_single');
        var $options = $($attribute).clone().html();
        var attr_name = 'pro_attr_' + counter;
        // product-attr-selectbox
        if ($('#product-type').val() == 'simple_product') {
            var html = '<div class="form-group move row my-auto p-2 border rounded bg-gray-light product-attr-selectbox" id=' + attr_name + '><div class="col-md-1 col-sm-12 text-center my-auto"><i class="fas fa-sort"></i></div><div class="col-md-4 col-sm-12"> <select name="attribute_id[]" class="attributes select_single" data-placeholder=" Type to search and select attributes"><option value=""></option>' + $options + '</select></div><div class="col-md-4 col-sm-12 "> <select name="attribute_value_ids[]" class="multiple_values" multiple="" data-placeholder=" Type to search and select attributes values"><option value=""></option> </select></div><div class="col-md-2 col-sm-6 text-center py-1 align-self-center"> <button type="button" class="btn btn-tool remove_attributes"> <i class="text-danger far fa-times-circle fa-2x "></i> </button></div></div>';
        } else {
            $('#note').removeClass('d-none');
            var html = '<div class="form-group row move my-auto p-2 border rounded bg-gray-light product-attr-selectbox" id=' + attr_name + '><div class="col-md-1 col-sm-12 text-center my-auto"><i class="fas fa-sort"></i></div><div class="col-md-4 col-sm-12"> <select name="attribute_id[]" class="attributes select_single" data-placeholder=" Type to search and select attributes"><option value=""></option>' + $options + '</select></div><div class="col-md-4 col-sm-12 "> <select name="attribute_value_ids[]" class="multiple_values" multiple="" data-placeholder=" Type to search and select attributes values"><option value=""></option> </select></div><div class="col-md-2 col-sm-6 text-center py-1 align-self-center"><input type="checkbox" name="variations[]" class="is_attribute_checked custom-checkbox "></div><div class="col-md-1 col-sm-6 text-center py-1 align-self-center "> <button type="button" class="btn btn-tool remove_attributes"> <i class="text-danger far fa-times-circle fa-2x "></i> </button></div></div>';
        }
        $('#attributes_process').append(html);

        $("#attributes_process").last().find(".attributes").select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });

        $("#attributes_process").last().find(".multiple_values").select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
    }

    if (e.target.id == 'tab-for-variations') {
        $('.additional-info').block({
            message: '<h6>Loading Variations</h6>',
            css: {
                border: '3px solid #E7F3FE'
            }
        });
        if (attributes_values.length > 0) {

            $('.no-variants-added').hide();
            create_variants(false, from);

        }
        setTimeout(function () {
            $('.additional-info').unblock();
        }, 3000);
    }

});

$(document).on('click', '#reset_variants', function () {

    Swal.fire({
        title: 'Are You Sure To Reset!',
        text: "You won't be able to revert this after update!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Reset it!',
        showLoaderOnConfirm: true,
        allowOutsideClick: false
    }).then((result) => {
        if (result.value) {
            $('.additional-info').block({
                message: '<h6>Reseting Variations</h6>',
                css: {
                    border: '3px solid #E7F3FE'
                }
            });
            if (attributes_values.length > 0) {
                $('.no-variants-added').hide();
                create_variants(false, 'seller');
            }
            setTimeout(function () {
                $('.additional-info').unblock();
            }, 2000);
        }
    });
});

$(document).on('click', '.remove_edit_attribute', function (e) {

    $(this).closest('.row').remove();
});

$(document).on('click', '.remove_attributes , .remove_variants', function (e) {
    Swal.fire({
        title: 'Are you sure want to delete!',
        text: "You won't be able to revert this after update!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete it!',
        showLoaderOnConfirm: true,
        allowOutsideClick: false
    }).then((result) => {
        if (result.value) {
            var text = this.className;
            if (text.search('remove_attributes') != -1) {
                var edit_id = $('#edit_product_id').val();

                attributes_values_selected.splice(attributes_values_selected.indexOf($(this).select2().find(":selected").val()), 1);
                $(this).closest('.row').remove();
                counter -= 1;
                var numItems = $('.product-attr-selectbox').length;
                if (numItems == 0) {
                    $('.no-attributes-added').show();
                    $('#save_attributes').addClass('d-none');
                    $('#note').addClass('d-none');

                }

            }
            if (text.search('remove_variants') != -1) {
                variant_values_selected.splice(variant_values_selected.indexOf($(this).select2().find(":selected").val()), 1);
                $(this).closest('.form-group').remove();
                variant_counter -= 1;
                var numItems = $('.product-variant-selectbox').length;
                if (numItems == 0) {
                    $('.no-variants-added').show();
                }
            }
        }
    });
});

$(document).on('select2:select', '#product-type', function () {
    var value = $(this).val();
    if ($.trim(value) != "") {
        if (value == 'simple_product') {
            $('#variant_stock_level').hide(200);
            $('#general_price_section').show(200);
            $('.simple-product-save').show(700);
            $('.product-attributes').addClass('disabled');
            $('.product-variants').addClass('disabled');
            $('#digital_product_setting').hide(200);
            $('.cod_allowed').removeClass('d-none');
            $('.is_returnable').removeClass('d-none');
            $('.is_cancelable').removeClass('d-none');
        }
        if (value == 'variable_product') {
            $('#general_price_section').hide(200);
            $('.simple-product-level-stock-management').hide(200);
            $('.simple-product-save').hide(200);
            $('.product-attributes').addClass('disabled');
            $('.product-variants').addClass('disabled');
            $('#variant_stock_level').show();
            $('#digital_product_setting').hide(200);
            $('.cod_allowed').removeClass('d-none');
            $('.is_returnable').removeClass('d-none');
            $('.is_cancelable').removeClass('d-none');
        }

    } else {
        $('.product-attributes').addClass('disabled');
        $('.product-variants').addClass('disabled');
        $('#general_price_section').hide(200);
        $('.simple-product-level-stock-management').hide(200);
        $('.simple-product-save').hide(200)
        $('#variant_stock_level').hide(200);

    }
});
$(document).on('change', '#product_type_menu', function () {
    var value = $(this).val();
    if (value == 'digital_product') {
        var html = '<option value="digital_product">Digital Product</option>';

        $('#product-type').html(html);
        $('#variant_stock_level').hide(200);
        $('#general_price_section').show(200);
        $('.simple-product-save').hide(200);
        $('.simple-product-level-stock-management').addClass('d-none');
        $('.simple_stock_management').addClass('d-none');
        $('.product-attributes').addClass('disabled');
        $('.product-variants').addClass('disabled');
        $('#digital_product_setting').show();
        $('.cod_allowed').addClass('d-none');
        $('.is_returnable').addClass('d-none');
        $('.is_cancelable').addClass('d-none');
        $('.indicator').addClass('d-none');
        $('.total_allowed_quantity').addClass('d-none');
        $('.minimum_order_quantity').addClass('d-none');
        $('.guarantee_period').addClass('d-none');
        $('.warranty_period').addClass('d-none');
        $('.quantity_step_size').addClass('d-none');
        $('.deliverable_type').addClass('d-none');
        $('.hsn_code').addClass('d-none');
    } else {
        var html = ' <option value=" ">Select Type</option>' +
            '<option value="simple_product">Simple Product</option>' +
            '<option value="variable_product">Variable Product</option>';
        $('#product-type').html(html);
        $('.cod_allowed').removeClass('d-none');
        $('.is_returnable').removeClass('d-none');
        $('.is_cancelable').removeClass('d-none');
        $('.indicator').removeClass('d-none');
        $('.total_allowed_quantity').removeClass('d-none');
        $('.minimum_order_quantity').removeClass('d-none');
        $('.guarantee_period').removeClass('d-none');
        $('.warranty_period').removeClass('d-none');
        $('.quantity_step_size').removeClass('d-none');
        $('.deliverable_type').removeClass('d-none');
        $('.hsn_code').removeClass('d-none');
    }
});

$(document).on('change', '.variant_stock_status', function () {
    if ($(this).prop("checked") == true) {
        $(this).attr("checked", true);
        $('#stock_level').show(200);
    } else {
        $(this).attr("checked", false);
        $('#stock_level').hide(200);
    }
});

$(document).on('change', '.variant-stock-level-type', function () {
    if ($('.variant-stock-level-type').val() == 'product_level') {
        $('.variant-product-level-stock-management').show();
    }
    if ($.trim($('.variant-stock-level-type').val()) != 'product_level') {
        $('.variant-product-level-stock-management').hide();
    }
});

$(document).on('change', '.simple_stock_management_status', function () {
    if ($(this).prop("checked") == true) {
        $(this).attr("checked", true);
        $('.simple-product-level-stock-management').show(200);
    } else {
        $(this).attr("checked", false);
        $('.simple-product-level-stock-management').hide(200);
        $('.simple-product-level-stock-management').find('input').val('');
    }
});

$(document).on('click', '#save_attributes', function () {
    Swal.fire({
        title: 'Are you sure want to save changes!',
        text: "Do not save attributes if you made no changes! It will reset the variants if there are no changes in attributes or its values !",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, save it!',
        showLoaderOnConfirm: true,
        allowOutsideClick: false
    }).then((result) => {
        if (result.value) {
            attribute_flag = 1;
            save_attributes();
            create_fetched_variants_html(true, from);
            iziToast.success({
                message: 'Attributes Saved Succesfully',
            });
        }
    });
});

$('#attributes_process').sortable({
    axis: 'y',
    opacity: 0.6,
    cursor: 'grab'
});

$('#variants_process').sortableJS({
    axis: 'y',
    opacity: 0.6,
    cursor: 'grab'
});

$(document).on('click', '.reset-settings', function (e) {

    Swal.fire({
        title: 'Are You Sure To Reset!',
        text: "This will reset all attributes && variants too if added.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Reset it!',
        showLoaderOnConfirm: true,
        allowOutsideClick: false
    }).then((result) => {
        if (result.value) {
            attributes_values_selected = [];
            value_check_array = [];
            pre_selected_attr_values = [];
            var html = ' <input type="hidden" name="reset_settings" value="1"><div class="row mt-4 col-md-12 "> <nav class="w-100"><div class="nav nav-tabs" id="product-tab" role="tablist"> <a class="nav-item nav-link active" id="tab-for-general-price" data-toggle="tab" data-target="#general-settings" role="tab" aria-controls="general-price" aria-selected="true">General</a> <a class="nav-item nav-link disabled product-attributes" id="tab-for-attributes" data-toggle="tab" href="#product-attributes" role="tab" aria-controls="product-attributes" aria-selected="false">Attributes</a> <a class="nav-item nav-link disabled product-variants d-none" id="tab-for-variations" data-toggle="tab" href="#product-variants" role="tab" aria-controls="product-variants" aria-selected="false">Variations</a></div> </nav><div class="tab-content p-3 col-md-12" id="nav-tabContent"><div class="tab-pane fade active show" id="general-settings" role="tabpanel" aria-labelledby="general-settings-tab"><div class="form-group"> <label for="type" class="col-md-2">Type Of Product :</label><div class="col-md-12"> <input type="hidden" name="product_type"> <input type="hidden" name="simple_product_stock_status"> <input type="hidden" name="variant_stock_level_type"> <input type="hidden" name="variant_stock_status"> <select name="type" id="product-type" class="form-control product-type" data-placeholder=" Type to search and select type"><option value=" ">Select Type</option><option value="simple_product">Simple Product</option><option value="digital_product">Digital Product</option><option value="variable_product">Variable Product</option> </select></div></div><div id="product-general-settings"><div id="general_price_section" class="collapse"><div class="form-group"> <label for="type" class="col-md-2">Price:</label><div class="col-md-12"> <input type="number" name="simple_price" class="form-control stock-simple-mustfill-field price" min="0"></div></div><div class="form-group"> <label for="type" class="col-md-2">Special Price:</label><div class="col-md-12"> <input type="number" name="simple_special_price" class="form-control discounted_price" min="0"></div></div><div class="form-group simple_stock_management"><div class="col"> <input type="checkbox" name="simple_stock_management_status" class="align-middle simple_stock_management_status"> <span class="align-middle">Enable Stock Management</span></div></div></div><div class="form-group simple-product-level-stock-management collapse"><div class="col col-xs-12"> <label class="control-label">SKU :</label> <input type="text" name="product_sku" class="col form-control simple-pro-sku"></div><div class="col col-xs-12"> <label class="control-label">Total Stock :</label> <input type="text" name="product_total_stock" class="col form-control stock-simple-mustfill-field"></div><div class="col col-xs-12"> <label class="control-label">Stock Status :</label> <select type="text" class="col form-control stock-simple-mustfill-field" id="simple_product_stock_status"><option value="1">In Stock</option><option value="0">Out Of Stock</option> </select></div></div><div class="form-group collapse simple-product-save"><div class="col"> <a href="javascript:void(0);" class="btn btn-primary save-settings">Save Settings</a></div></div></div><div id="variant_stock_level" class="collapse"><div class="form-group"><div class="col"> <input type="checkbox" name="variant_stock_management_status" class="align-middle variant_stock_status"> <span class="align-middle"> Enable Stock Management</span></div></div><div class="form-group collapse" id="stock_level"> <label for="type" class="col-md-2">Choose Stock Management Type:</label><div class="col-md-12"> <select id="stock_level_type" class="form-control variant-stock-level-type" data-placeholder=" Type to search and select type"><option value=" ">Select Stock Type</option><option value="product_level">Product Level ( Stock Will Be Managed Generally )</option><option value="variable_level">Variable Level ( Stock Will Be Managed Variant Wise )</option> </select><div class="form-group row variant-product-level-stock-management collapse"><div class="col col-xs-12"> <label class="control-label">SKU :</label> <input type="text" name="sku_variant_type" class="col form-control"></div><div class="col col-xs-12"> <label class="control-label">Total Stock :</label> <input type="text" name="total_stock_variant_type" class="col form-control variant-stock-mustfill-field"></div><div class="col col-xs-12"> <label class="control-label">Stock Status :</label> <select type="text" id="stock_status_variant_type" name="variant_status" class="col form-control variant-stock-mustfill-field"><option value="1">In Stock</option><option value="0">Out Of Stock</option> </select></div></div></div></div><div class="form-group"><div class="col"> <a href="javascript:void(0);" class="btn btn-primary save-variant-general-settings">Save Settings</a></div></div></div></div><div class="tab-pane fade" id="product-attributes" role="tabpanel" aria-labelledby="product-attributes-tab"><div class="info col-12 p-3 d-none" id="note"><div class=" col-12 d-flex align-center"> <strong>Note : </strong> <input type="checkbox" checked="checked" class="ml-3 my-auto custom-checkbox" disabled> <span class="ml-3">check if the attribute is to be used for variation </span></div></div><div class="col-md-12"> <a href="javascript:void(0);" id="add_attributes" class="btn btn-block btn-outline-primary col-md-2 float-right m-2 btn-sm">Add Attributes</a> <a href="javascript:void(0);" id="save_attributes" class="btn btn-block btn-outline-primary col-md-2 float-right m-2 btn-sm d-none">Save Attributes</a></div><div class="clearfix"></div><div id="attributes_process"><div class="form-group text-center row my-auto p-2 border rounded bg-gray-light col-md-12 no-attributes-added"><div class="col-md-12 text-center">No Product Attribures Are Added !</div></div></div></div><div class="tab-pane fade" id="product-variants" role="tabpanel" aria-labelledby="product-variants-tab"><div class="clearfix"></div><div class="form-group text-center row my-auto p-2 border rounded bg-gray-light col-md-12 no-variants-added"><div class="col-md-12 text-center">No Product Variations Are Added !</div></div><div id="variants_process" class="ui-sortable"></div></div></div></div>';
            $('.additional-info').html(html);
            $('.no-attributes-added').show();
            $('#product-type').each(function () {
                $(this).select2({
                    theme: 'bootstrap4',
                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                    placeholder: $(this).data('placeholder'),
                    allowClear: Boolean($(this).data('allow-clear')),
                });
            });
        }
    });


});
$(document).on('click', '.save-settings', function (e) {
    e.preventDefault();

    if ($('.simple_stock_management_status').is(':checked')) {
        var len = 0
    } else {
        var len = 1
    }

    if ($('.stock-simple-mustfill-field').filter(function () {
        return this.value === '';
    }).length === len) {
        $('.additional-info').block({
            message: '<h6>Saving Settings</h6>',
            css: {
                border: '3px solid #E7F3FE'
            }
        });

        $('input[name="product_type"]').val($('#product-type').val());
        if ($('.simple_stock_management_status').is(':checked')) {
            $('input[name="simple_product_stock_status"]').val($('#simple_product_stock_status').val());
        } else {
            $('input[name="simple_product_stock_status"]').val('');
        }
        $('#product-type').prop('disabled', true);
        $('.product-attributes').removeClass('disabled');
        $('.product-variants').removeClass('disabled');
        $('.simple_stock_management_status').prop('disabled', true);
        setTimeout(function () {
            $('.additional-info').unblock();
        }, 2000);

    } else {
        iziToast.error({
            message: 'Please Fill All The Fields',
        });
    }
});
$(document).on('click', '.save-digital-product-settings', function (e) {
    e.preventDefault();
    console.log("save setting of digital product");
    $('.product-attributes').removeClass('disabled');
});
$(document).on('click', '.delete_system_noti', function () {
    var value = $(this).data('id');
    var url = base_url + 'admin/Notification_settings/delete_system_notification';
    Swal.fire({
        title: 'Are You Sure !',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        'id': value
                    },
                    dataType: 'json',
                })
                    .done(function (result, textStatus) {
                        if (result['error'] == false) {
                            Swal.fire('Deleted!', result['message'], 'success');
                            $('table').bootstrapTable('refresh');
                        } else {
                            Swal.fire('Oops...', result['message'], 'warning');
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
            });
        },
        allowOutsideClick: false
    });
});
$(document).on('click', '.save-variant-general-settings', function (e) {
    e.preventDefault();
    if ($('.variant_stock_status').is(":checked")) {
        if ($('.variant-stock-level-type').filter(function () {
            return this.value === '';
        }).length === 0 && $.trim($('.variant-stock-level-type').val()) != "") {

            if ($('.variant-stock-level-type').val() == 'product_level' && $('.variant-stock-mustfill-field').filter(function () {
                return this.value === '';
            }).length !== 0) {
                iziToast.error({
                    message: 'Please Fill All The Fields',
                });
            } else {
                $('input[name="product_type"]').val($('#product-type').val());
                $('input[name="variant_stock_level_type"]').val($('#stock_level_type').val());
                $('input[name="variant_stock_status"]').val("0");
                $('#product-type').prop('disabled', true);
                $('#stock_level_type').prop('disabled', true);
                $(this).removeClass('save-variant-general-settings');
                $('.product-attributes').removeClass('disabled');
                $('.product-variants').removeClass('disabled');
                $('.variant-stock-level-type').prop('readonly', true);
                $('#stock_status_variant_type').attr('readonly', true);
                $('.variant-product-level-stock-management').find('input,select').prop('readonly', true);
                $('#tab-for-variations').removeClass('d-none');
                $('.variant_stock_status').prop('disabled', true);
                $('#product-tab a[href="#product-attributes"]').tab('show');
                Swal.fire('Settings Saved !', 'Attributes & Variations Can Be Added Now', 'success');
            }
        } else {
            iziToast.error({
                message: 'Please Fill All The Fields',
            });
        }

    } else {

        $('input[name="product_type"]').val($('#product-type').val());
        $('input[name="variant_stock_status"]').val("");
        $('input[name="variant_stock_level_type"]').val("");
        $('#product-tab a[href="#product-attributes"]').tab('show');
        $('.variant_stock_status').prop('disabled', true);
        $('#product-type').prop('disabled', true);
        $('.product-attributes').removeClass('disabled');
        $('.product-variants').removeClass('disabled');
        $('#tab-for-variations').removeClass('d-none');
        Swal.fire('Settings Saved !', 'Attributes & Variations Can Be Added Now', 'success');
    }

});


$(document).on('change', '.new-added-variant', function () {

    var myOpts = $(this).children().map(function () {
        return $(this).val();
    }).get();
    var variant_id = $(this).val();
    var curr_vals = [];
    var $variant_ids = $(this).closest('.product-variant-selectbox').find('input[name="variants_ids[]"]').val();
    $.each($variant_ids.split(','), function (key, val) {
        if (val != '') {
            curr_vals[key] = $.trim(val);
        }
    });
    var newvalues = curr_vals.filter((el) => !myOpts.includes(el));
    var len = newvalues.length;
    if (variant_id != '') {
        newvalues[len] = $.trim(variant_id);
    }
    $(this).closest('.product-variant-selectbox').find('input[name="variants_ids[]"]').val(newvalues.toString());
});



function get_seller_categories(seller_id, ignore_status, edit_id, from) {
    $.ajax({
        type: 'GET',
        url: base_url + from + '/category/get_seller_categories',
        data: {
            "ignore_status": ignore_status,
            "seller_id": seller_id
        },
        dataType: 'json',
        success: function (result) {
            $('#product_category_tree_view_html').jstree("destroy").empty();
            $('#product_category_tree_view_html').jstree({
                plugins: ["checkbox", 'themes'],
                'core': {
                    multiple: false,
                    'data': result['data'],
                },
                checkbox: {
                    three_state: false,
                    cascade: "none"
                }
            });

            $('#product_category_tree_view_html')
                .bind('ready.jstree', function (e, data) {
                    $(this).jstree(true).select_node(edit_id);
                });
        }
    });
}

if (window.location.href.indexOf("admin/product") > -1) {
    var edit_id = $('input[name="category_id"]').val();
    var seller_id = $('input[name="seller_id"]').val();
    var ignore_status = $.isNumeric(edit_id) && edit_id > 0 ? 1 : 0;
    if ($.isNumeric(seller_id) && seller_id > 0) {
        get_seller_categories(seller_id, ignore_status, edit_id, 'admin');
    } else {
        $.ajax({
            type: 'GET',
            url: base_url + 'admin/category/get_categories',
            data: {
                "ignore_status": ignore_status
            },
            dataType: 'json',
            success: function (result) {
                var edit_id = $('input[name="category_id"]').val();
                $('#product_category_tree_view_html').jstree({
                    plugins: ["checkbox", 'themes'],
                    'core': {
                        'data': result['data'],
                        multiple: false
                    },
                    checkbox: {
                        three_state: false,
                        cascade: "none"
                    }
                });
                $('#product_category_tree_view_html')
                    .bind('ready.jstree', function (e, data) {
                        $(this).jstree(true).select_node(edit_id);
                    });
            }
        });
    }
}
$(document).on('click', '.update_active_status', function () {

    var update_id = $(this).data('id');
    var status = $(this).data('status');
    var table = $(this).data('table');
    update_status(update_id, status, table, from);

});
if (window.location.href.indexOf("seller/product") > -1) {
    var edit_id = $('input[name="category_id"]').val();
    var seller_id = $('input[name="seller_id"]').val();
    var ignore_status = $.isNumeric(edit_id) && edit_id > 0 ? 1 : 0;
    if ($.isNumeric(seller_id) && seller_id > 0) {
        get_seller_categories(seller_id, ignore_status, edit_id, from);
    } else {
        $.ajax({
            type: 'GET',
            url: base_url + 'seller/category/get_categories',
            data: {
                "ignore_status": ignore_status
            },
            dataType: 'json',
            success: function (result) {
                var edit_id = $('input[name="category_id"]').val();
                $('#product_category_tree_view_html').jstree({
                    plugins: ["checkbox", 'themes'],
                    'core': {
                        'data': result['data'],
                        multiple: false
                    },
                    checkbox: {
                        three_state: false,
                        cascade: "none"
                    }
                });

                $('#product_category_tree_view_html')
                    .bind('ready.jstree', function (e, data) {
                        $(this).jstree(true).select_node(edit_id);
                    });
            }
        });
    }

}

$(document).on('change', '#seller_id', function (e) {
    e.preventDefault();
    var edit_id = $('input[name="category_id"]').val();
    var seller_id = $(this).val();
    var ignore_status = $.isNumeric(edit_id) && edit_id > 0 ? 1 : 0;
    get_seller_categories(seller_id, ignore_status, edit_id, 'admin');
});


// 3.Category-Module
$(document).on('click', '#list_view', function () {
    $('#list_view_html').show();
    $('#tree_view_html').hide();
});

$(document).on('click', '#tree_view', function () {
    $('#tree_view_html').show();
    $('#list_view_html').hide();

    var category_url = base_url + 'admin/category/get_categories';
    if (from == 'seller') {
        category_url = base_url + 'seller/category/get_seller_categories';
    }
    $.ajax({
        type: 'GET',
        url: category_url,
        dataType: 'json',
        success: function (result) {
            $('#tree_view_html').jstree({
                'core': {
                    'data': result['data']
                }
            });
        }
    });
});

function update_status(update_id, status, table, user) {
    $.ajax({
        type: 'GET',
        url: base_url + user + '/home/update_status',
        data: {
            id: update_id,
            status: status,
            table: table
        },
        dataType: 'json',
        success: function (result) {
            if (result['error'] == true) {
                iziToast.success({
                    message: '<span style="text-transform:capitalize">' + result.message + '</span> Status Updated',
                });
                $('.table').bootstrapTable('refresh');
            } else {
                iziToast.error({
                    message: '<span style="text-transform:capitalize">' + result.message + '</span> Status Not Updated',
                });
            }
        }
    });
}

$(document).on('click', '.update_default_theme', function () {
    var theme_id = $(this).data('id');
    $.ajax({
        type: 'POST',
        url: base_url + 'admin/setting/set-default-theme',
        data: {
            [csrfName]: csrfHash,
            theme_id: theme_id
        },
        dataType: 'json',
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result['error'] == false) {
                iziToast.success({
                    message: result.message,
                });
                $('.table').bootstrapTable('refresh');
            } else {
                iziToast.error({
                    message: result.message,
                });
            }
        }
    });
});

$(document).on('submit', '#bulk_area_update_form', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        dataType: 'json',
        data: formData,
        processData: false,
        contentType: false,
        success: function (result) {
            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];
            if (result.error == false) {
                iziToast.success({
                    message: result.message,
                });
                $('#bulk_area_update_form')[0].reset();
            } else {
                iziToast.error({
                    message: result.message,
                });
            }
        }
    });
});





$(document).on('click', '.delete-categoty', function () {
    var cat_id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'admin/category/delete_category',
                    data: {
                        id: cat_id
                    },
                    dataType: 'json'
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('table').bootstrapTable('refresh');
                            csrfName = response['csrfName'];
                            csrfHash = response['csrfHash'];
                        } else {
                            Swal.fire('Oops...', response.message, 'warning');
                            $('table').bootstrapTable('refresh');
                            csrfName = response['csrfName'];
                            csrfHash = response['csrfHash'];
                        }

                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                        csrfName = response['csrfName'];
                        csrfHash = response['csrfHash'];
                    });
            });
        },
        allowOutsideClick: false
    });
});



$(document).on('click', '.delete-blog-category', function () {
    var cat_id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'admin/blogs/delete_category',
                    data: {
                        id: cat_id
                    },
                    dataType: 'json'
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('table').bootstrapTable('refresh');
                            csrfName = response['csrfName'];
                            csrfHash = response['csrfHash'];
                        } else {
                            Swal.fire('Oops...', response.message, 'warning');
                            $('table').bootstrapTable('refresh');
                            csrfName = response['csrfName'];
                            csrfHash = response['csrfHash'];
                        }

                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                        csrfName = response['csrfName'];
                        csrfHash = response['csrfHash'];
                    });
            });
        },
        allowOutsideClick: false
    });
});

$(document).on('click', '.delete-blog', function () {
    var cat_id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'admin/blogs/delete_blog',
                    data: {
                        id: cat_id
                    },
                    dataType: 'json'
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('table').bootstrapTable('refresh');
                            csrfName = response['csrfName'];
                            csrfHash = response['csrfHash'];
                        } else {
                            Swal.fire('Oops...', response.message, 'warning');
                            $('table').bootstrapTable('refresh');
                            csrfName = response['csrfName'];
                            csrfHash = response['csrfHash'];
                        }

                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                        csrfName = response['csrfName'];
                        csrfHash = response['csrfHash'];
                    });
            });
        },
        allowOutsideClick: false
    });
});

$('#category_parent').each(function () {
    $(this).select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
        dropdownCssClass: "test",
        templateResult: function (data) {
            // We only really care if there is an element to pull classes from
            if (!data.element) {
                return data.text;
            }

            var $element = $(data.element);

            var $wrapper = $('<span></span>');
            $wrapper.addClass($element[0].className);

            $wrapper.text(data.text);

            return $wrapper;
        }
    });
});

$(document).on('click', '#save_category_order', function () {
    var data = $('#sortable').sortable('serialize');
    $.ajax({
        data: data,
        type: 'GET',
        url: base_url + 'admin/category/update_category_order',
        dataType: 'json',
        success: function (response) {
            if (response.error == false) {
                iziToast.success({
                    message: response.message,
                });
            } else {
                iziToast.error({
                    message: response.message,
                });
            }
        }
    });
});

$(document).on('click', '#save_category_order', function () {
    var data = $('#sortable').sortableJS('serialize');
    $.ajax({
        data: data,
        type: 'GET',
        url: base_url + 'admin/category/update_category_order',
        dataType: 'json',
        success: function (response) {
            if (response.error == false) {
                iziToast.success({
                    message: response.message,
                });
            } else {
                iziToast.error({
                    message: response.message,
                });
            }
        }
    });
});



//4.Order-Module
$('#datepicker').attr({
    'placeholder': ' Select Date Of Ranges To Filter ',
    'autocomplete': 'off'
});
$('#datepicker').on('cancel.daterangepicker', function (ev, picker) {
    $(this).val('');
    $('#start_date').val('');
    $('#end_date').val('');
});
$('#datepicker').on('apply.daterangepicker', function (ev, picker) {
    var drp = $('#datepicker').data('daterangepicker');
    $('#start_date').val(drp.startDate.format('YYYY-MM-DD'));
    $('#end_date').val(drp.endDate.format('YYYY-MM-DD'));
    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
});

$('#datepicker').daterangepicker({
    showDropdowns: true,
    alwaysShowCalendars: true,
    autoUpdateInput: false,
    ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment().subtract(29, 'days'),
    endDate: moment(),
    locale: {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "cancelLabel": 'Clear',
        'label': 'Select range of dates to filter'
    }
});

$(document).on('click', '.update_status_admin', function (e) {
    var order_id = $(this).data('id');
    var status = $(this).closest('.row').find('select').val();

    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'POST',
                    url: base_url + from + '/orders/update_order_status',
                    data: {
                        'order_item_id[]': order_id,
                        status: status,
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function (result) {
                        csrfName = result['csrfName'];
                        csrfHash = result['csrfHash'];
                        if (result['error'] == false) {
                            iziToast.success({
                                message: result['message'],
                            });
                        } else {
                            iziToast.error({
                                message: result['message'],
                            });
                        }
                        swal.close();
                    }
                });
            });
        },
        allowOutsideClick: false
    });
});
$(document).on('click', '.update_delivery_boy_admin', function (e) {
    var order_id = $(this).data('id');
    var d_boy = $(this).closest('.delivery_boy').find('select').val();
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'POST',
                    url: base_url + from + '/orders/update_order_status',
                    data: {
                        'order_item_id[]': order_id,
                        deliver_by: d_boy,
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function (result) {
                        csrfName = result['csrfName'];
                        csrfHash = result['csrfHash'];
                        if (result['error'] == false) {
                            iziToast.success({
                                message: result['message'],
                            });
                        } else {
                            iziToast.error({
                                message: result['message'],
                            });
                        }
                        swal.close();
                    }
                });
            });
        },
        allowOutsideClick: false
    });
});
$(document).on('click', '.update_status_admin_bulk', function (e) {
    var data = $('#update_form').serialize();
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'POST',
                    url: base_url + from + '/orders/update_order_status',
                    data: data + "&" + csrfName + "=" + csrfHash,
                    dataType: 'json',
                    success: function (result) {
                        csrfName = result['csrfName'];
                        csrfHash = result['csrfHash'];
                        if (result['error'] == false) {
                            iziToast.success({
                                message: result['message'],
                            });
                            window.location.reload();
                        } else {
                            iziToast.error({
                                message: result['message'],
                            });
                        }
                        swal.close();
                    }
                });
            });
        },
        allowOutsideClick: false
    });
});

$(document).on('click', '.update_status_delivery_boy', function (e) {
    var order_item_id = $(this).data('id');
    var otp_system = $(this).data('otp-system');
    order_item_id = order_item_id.replace(' ', '');
    var status = $(this).closest('.row').find('select').val();
    var post_otp = 0;
    if (otp_system == 1 && status == 'delivered') {
        post_otp = prompt('Enter Order OTP');
    }
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'delivery_boy/orders/update_order_status',
                    data: {
                        id: order_item_id,
                        status: status,
                        otp: post_otp
                    },
                    dataType: 'json',
                    success: function (result) {
                        csrfName = result['csrfName'];
                        csrfHash = result['csrfHash'];
                        if (result['error'] == false) {
                            iziToast.success({
                                message: result['message'],
                            });
                        } else {
                            iziToast.error({
                                message: result['message'],
                            });
                        }
                        swal.close();
                    }
                });
            });
        },
        allowOutsideClick: false
    });

});

$(document).on('click', '.delete-orders', function () {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'admin/orders/delete_orders',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function (result) {
                        if (result['error'] == false) {
                            Swal.fire('Deleted!', 'success');
                            $('table').bootstrapTable('refresh');
                        } else {
                            Swal.fire('Oops...', result['message'], 'error');
                        }
                    }
                });
            });
        },
        allowOutsideClick: false
    })
        .then((result) => {
            if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire(
                    'Cancelled!',
                    'Your data is  safe.',
                    'error'
                );
            }
        });

});
$(document).on('click', '.delete-order-items', function () {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'admin/orders/delete_order_items',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function (result) {
                        if (result['error'] == false) {
                            Swal.fire('Deleted!', 'success');
                            $('table').bootstrapTable('refresh');
                        } else {
                            Swal.fire('Oops...', result['message'], 'error');
                        }
                    }
                });
            });
        },
        allowOutsideClick: false
    })
        .then((result) => {
            if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire(
                    'Cancelled!',
                    'Your data is  safe.',
                    'error'
                );
            }
        });

});

$(document).on('click', '.remove-sellers', function () {
    var id = $(this).data('id');
    var status = $(this).data('seller_status');
    Swal.fire({
        title: 'Are You Sure! All data & media will be remove related to this seller',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Remove it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'admin/sellers/remove_sellers',
                    data: {
                        id: id,
                        status: status
                    },
                    dataType: 'json',
                    success: function (result) {
                        if (result['error'] == false) {
                            Swal.fire('Removed!', 'success');
                            $('table').bootstrapTable('refresh');
                        } else {
                            Swal.fire('Oops...', result['message'], 'error');
                        }
                    }
                });
            });
        },
        allowOutsideClick: false
    })
        .then((result) => {
            if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire(
                    'Cancelled!',
                    'Your data is  safe.',
                    'error'
                );
            }
        });
});
$(document).on('click', '.delete-sellers', function () {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure! All data & media will be remove related to this seller',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Remove it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'admin/sellers/delete_sellers',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function (result) {
                        if (result['error'] == false) {
                            Swal.fire('Deleted!', result['message']);
                            $('table').bootstrapTable('refresh');
                        } else {
                            Swal.fire('Oops...', result['message'], 'error');
                        }
                    }
                });
            });
        },
        allowOutsideClick: false
    })
        .then((result) => {
            if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire(
                    'Cancelled!',
                    'Your data is  safe.',
                    'error'
                );
            }
        });
});

//5.Featured_Section-Module
$('.select_multiple').each(function () {
    $(this).select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
    });
});


$('.search_admin_product').each(function () {
    $(this).select2({
        ajax: {
            url: base_url + 'admin/product/get_product_data',
            dataType: 'json',
            delay: 250,
            data: function (data) {
                return {
                    search: data.term, // search term
                    limit: 10
                };
            },
            processResults: function (response) {
                return {
                    results: response.rows
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        minimumInputLength: 1,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection,
        placeholder: 'Search for products',
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
    });
});
$('.search_admin_digital_product').each(function () {
    $(this).select2({
        ajax: {
            url: base_url + 'admin/product/get_digital_product_data',
            dataType: 'json',
            delay: 250,
            data: function (data) {
                return {
                    search: data.term, // search term
                    limit: 10
                };
            },
            processResults: function (response) {
                return {
                    results: response.rows
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        minimumInputLength: 1,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection,
        placeholder: 'Search for products',
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
    });
});

$(document).on('click', '#delete-featured-section', function () {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'admin/featured_sections/delete_featured_section',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function (result) {
                        if (result['error'] == false) {
                            Swal.fire('Deleted!', result['message'], 'success');
                            $('table').bootstrapTable('refresh');
                        } else {
                            Swal.fire('Oops...', result['message'], 'warning');
                        }
                    }
                });
            });
        },
        allowOutsideClick: false
    })
        .then((result) => {
            if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire(
                    'Cancelled!',
                    'Your data is  safe.',
                    'error'
                );
            }
        });
});

//6.Notifation-Module
$("#image_checkbox").on('click', function () {
    if (this.checked) {
        $(this).prop("checked", true);
        $('.include_image').removeClass('d-none');
    } else {
        $(this).prop("checked", false);
        $('.include_image').addClass('d-none');
    }
});

$(document).on('click', '.delete_notifications', function () {
    var value = $(this).data('id');
    var url = base_url + 'admin/Notification_settings/delete_notification';
    Swal.fire({
        title: 'Are You Sure !',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        'id': value
                    },
                    dataType: 'json',
                })
                    .done(function (result, textStatus) {
                        if (result['error'] == false) {
                            Swal.fire('Deleted!', result['message'], 'success');
                            $('table').bootstrapTable('refresh');
                        } else {
                            Swal.fire('Oops...', result['message'], 'warning');
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
            });
        },
        allowOutsideClick: false
    });
});

//7.Faq-Module
$(document).on('click', '.edit_faq', function () {
    var faq = $(this).parent().closest('.card');
    if ($(this).hasClass('cancel')) {
        $(this).removeClass('cancel');
        $(this).html('<i class="fa fa-pen"></i>');
        $(this).closest('button').addClass('btn-primary').removeClass('btn-danger');
        $(faq).find('input').addClass('d-none');
        $(faq).find('textarea').addClass('d-none');
        $(faq).find('.faq_question').show();
        $(faq).find('.faq_answer').show();
        $(faq).find('.save').addClass('d-none');
        $(faq).find('.collapse').collapse("hide");
    } else {
        $(this).addClass('cancel');
        $(this).html('<i class="fa fa-times"></i>');
        $(this).closest('button').addClass('btn-danger').removeClass('btn-primary');
        var question = $(faq).find('.faq_question').html();
        var answer = $(faq).find('.faq_answer').html();
        $(faq).find('.faq_question').hide();
        $(faq).find('.faq_answer').hide();
        $(faq).find('.collapse').collapse("show");
        $(faq).find('input').removeClass('d-none').val($.trim(question));
        $(faq).find('.save').removeClass('d-none');
        $(faq).find('textarea').removeClass('d-none').val($.trim(answer));
    }
});

$(document).on('click', '.delete_faq', function () {
    var id = $(this).data('id');
    var t = this;
    Swal.fire({
        title: 'Are You Sure !',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: base_url + 'admin/faq/delete_faq',
                    type: 'GET',
                    data: {
                        'id': id
                    },
                    dataType: 'json',
                })
                    .done(function (result, textStatus) {
                        if (result['error'] == false) {
                            $(t).closest('.card').remove();
                            Swal.fire('Deleted!', result['message'], 'success');
                        } else {
                            Swal.fire('Oops...', result['message'], 'warning');
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
            });
        },
        allowOutsideClick: false
    });
});

//8.Slider-Module
$(document).on('click', '#delete-slider', function () {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure !',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: base_url + 'admin/Slider/delete_slider',
                    type: 'GET',
                    data: {
                        'id': id
                    },
                    dataType: 'json',
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('table').bootstrapTable('refresh');
                        } else {
                            Swal.fire('Oops...', response.message, 'warning');
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
            });
        },
        allowOutsideClick: false
    });
});

$(document).on('change', '.product_type', function (e, data) {
    e.preventDefault();
    var sort_type_val = $(this).val();
    if (sort_type_val == 'custom_products' && sort_type_val != ' ') {
        $('.custom_products').removeClass('d-none');
    } else {
        $('.custom_products').addClass('d-none');
    }
    if (sort_type_val == 'digital_product' && sort_type_val != ' ') {
        $('.digital_products').removeClass('d-none');
    } else {
        $('.digital_products').addClass('d-none');
    }
});

//9.Offer-Module
$(document).on('click', '#delete-offer', function () {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure !',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: base_url + 'admin/Offer/delete_offer',
                    type: 'GET',
                    data: {
                        'id': id
                    },
                    dataType: 'json',
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('table').bootstrapTable('refresh');
                        } else {
                            Swal.fire('Oops...', response.message, 'warning');
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
            });
        },
        allowOutsideClick: false
    });
});

//10.Promo_code-Module
$(document).on('change', '#repeat_usage', function () {
    var repeat_usage = $(this).val();
    if (typeof repeat_usage != 'undefined' && repeat_usage == '1') {
        $('#repeat_usage_html').removeClass('d-none');
    } else {
        $('#repeat_usage_html').addClass('d-none');
    }

});

$(document).on('click', '#delete-promo-code', function () {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure !',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: base_url + 'admin/Promo_code/delete_promo_code',
                    type: 'GET',
                    data: {
                        'id': id
                    },
                    dataType: 'json',
                })
                    .done(function (response, textStatus) {
                        Swal.fire('Deleted!', response.message, 'success');
                        $('.table-striped').bootstrapTable('refresh');
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
            });
        },
        allowOutsideClick: false
    });
});

//11.Delivery_boys-Module 
$(document).on('click', '#delete-delivery-boys', function () {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure !',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: base_url + 'admin/Delivery_boys/delete_delivery_boys',
                    type: 'GET',
                    data: {
                        'id': id
                    },
                    dataType: 'json',
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('table').bootstrapTable('refresh');
                        } else {
                            Swal.fire('Oops...', response.message, 'warning');
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
            });
        },
        allowOutsideClick: false
    });
});

//12.Settings-Module
$(document).on('click', '#delete-time-slot', function () {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure !',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: base_url + 'admin/Time_slots/delete_time_slots',
                    type: 'GET',
                    data: {
                        'id': id
                    },
                    dataType: 'json',
                })
                    .done(function (response, textStatus) {
                        Swal.fire('Deleted!', response.message);
                        $('.table-striped').bootstrapTable('refresh');
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
            });
        },
        allowOutsideClick: false
    });
});

//13.City-Module
$(document).on('click', '#delete-location', function () {

    var id = $(this).data('id');
    var table = $(this).data('table');
    Swal.fire({
        title: 'Are You Sure !',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: base_url + 'admin/Area/delete_city',
                    type: 'GET',
                    data: {
                        'id': id,
                        'table': table
                    },
                    dataType: 'json',
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('table').bootstrapTable('refresh');
                        } else {
                            Swal.fire('Oops...', response.message, 'warning');
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
            });
        },
        allowOutsideClick: false
    });
});

//14.Transaction_Module
$(document).on('change', '#transaction_type', function () {
    $('.table-striped').bootstrapTable('refresh');
});

//15.Customer-Wallet-Module  
$('#customers').on('check.bs.table', function (e, row) {
    $('#customer_dtls').val(row.name + " | " + row.email);
    $('#user_id').val(row.id);
});

//16.Fund-Transder-Module
$("#fund_transfer").on("click-cell.bs.table", function (field, value, row, $el) {
    $('#name').val($el.name);
    $('#mobile').val($el.mobile);
    $('#balance').val($el.balance);
    $('#delivery_boy_id').val($el.id);
});

//17.Return-Request-Module
$("#return_request_table").on("click-cell.bs.table", function (field, value, row, $el) {
    $('input[name="return_request_id"]').val($el.id);
    $('#user_id').val($el.user_id);
    $('#order_item_id').val($el.order_item_id);
    $('#update_remarks').html($el.remarks);

    if ($el.status_digit == 0) {
        $('.pending').prop('checked', true);
    } else if ($el.status_digit == 1) {
        $('.approved').prop('checked', true);
    } else if ($el.status_digit == 2) {
        $('.rejected').prop('rejected', true);
    }
});

//18.Tax-Module
$(document).on('click', '#delete-tax', function () {
    var tax_id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'admin/taxes/delete_tax',
                    data: {
                        id: tax_id
                    },
                    dataType: 'json'
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Deleted!', response.message, 'success');
                        } else {
                            Swal.fire('Opps', response.message, 'warning');
                        }
                        $('table').bootstrapTable('refresh');
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
            });
        },
        allowOutsideClick: false
    });
});

//19.Payment-Request-Module
$("#payment_request_table").on("click-cell.bs.table", function (field, value, row, $el) {
    $('input[name="payment_request_id"]').val($el.id);
    $('#update_remarks').html($el.remarks);

    if ($el.status_digit == 0) {
        $('.pending').prop('checked', true);
    } else if ($el.status_digit == 1) {
        $('.approved').prop('checked', true);
    } else if ($el.status_digit == 2) {
        $('.rejected').prop('rejected', true);
    }
});


$('#upload-media').on('click', function () {
    var $result = $('#media-upload-table').bootstrapTable('getSelections');

    var path = base_url + $result[0].sub_directory + $result[0].name;
    var sub_directory = $result[0].sub_directory + $result[0].name;
    var media_type = $('#media-upload-modal').find('input[name="media_type"]').val();
    var input = $('#media-upload-modal').find('input[name="current_input"]').val();
    var is_removable = $('#media-upload-modal').find('input[name="remove_state"]').val();
    var ismultipleAllowed = $('#media-upload-modal').find('input[name="multiple_images_allowed_state"]').val();
    var removable_btn = (is_removable == '1') ? '<button class="remove-image btn btn-danger btn-xs mt-3">Remove</button>' : '';

    $(current_selected_image).closest('.form-group').find('.image').removeClass('d-none');
    if (ismultipleAllowed == '1') {
        for (let index = 0; index < $result.length; index++) {
            $(current_selected_image).closest('.form-group').find('.image-upload-section').append('<div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image"><div class="image-upload-div"><img class="img-fluid" alt="' + $result[index].name + '" title="' + $result[index].name + '" src=' + base_url + $result[index].sub_directory + $result[index].name + ' ><input type="hidden" name=' + input + ' value=' + $result[index].sub_directory + $result[index].name + '></div>' + removable_btn + '</div>');
        }
    } else {
        path = (media_type != 'image') ? base_url + 'assets/admin/images/' + media_type + '-file.png' : path;
        $(current_selected_image).closest('.form-group').find('.image-upload-section').html('<div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image"><div class="image-upload-div"><img class="img-fluid" alt="' + $result[0].name + '" title="' + $result[0].name + '" src=' + path + ' ><input type="hidden" name=' + input + ' value=' + sub_directory + '></div>' + removable_btn + '</div>');
    }

    current_selected_image = '';
    $('#media-upload-modal').modal('hide');
});

$(document).on('show.bs.modal', '#media-upload-modal', function (event) {
    var triggerElement = $(event.relatedTarget);
    current_selected_image = triggerElement;

    var input = $(current_selected_image).data('input');
    var isremovable = $(current_selected_image).data('isremovable');
    var ismultipleAllowed = $(current_selected_image).data('is-multiple-uploads-allowed');
    var media_type = ($(current_selected_image).is('[data-media_type]')) ? $(current_selected_image).data('media_type') : 'image';
    $('#media_type').val(media_type);
    if (ismultipleAllowed == 1) {
        $('#media-upload-table').bootstrapTable('refreshOptions', {
            singleSelect: false,
        });
    } else {
        $('#media-upload-table').bootstrapTable('refreshOptions', {
            singleSelect: true,
        });
    }

    $(this).find('input[name="current_input"]').val(input);
    $(this).find('input[name="remove_state"]').val(isremovable);
    $(this).find('input[name="multiple_images_allowed_state"]').val(ismultipleAllowed);
});

$(document).on('change', '#video_type', function () {
    var video_type = $(this).val();
    if (video_type == 'youtube' || video_type == 'vimeo') {
        $("#video_link_container").removeClass('d-none');
        $("#video_media_container").addClass('d-none');
    } else if (video_type == 'self_hosted') {
        $("#video_link_container").addClass('d-none');
        $("#video_media_container").removeClass('d-none');
    } else {
        $("#video_link_container").addClass('d-none');
        $("#video_media_container").addClass('d-none');
    }
});
$(document).on('change', '#download_link_type', function () {
    var download_link_type = $(this).val();
    if (download_link_type == 'add_link') {
        $("#digital_link_container").removeClass('d-none');
        $("#digital_media_container").addClass('d-none');
    } else if (download_link_type == 'self_hosted') {
        $("#digital_link_container").addClass('d-none');
        $("#digital_media_container").removeClass('d-none');
    } else {
        $("#digital_media_container").addClass('d-none');
        $("#digital_link_container").addClass('d-none');
    }
});
if ($('#tags').length) {
    var tags_element = document.querySelector('input[name=tags]');
    new Tagify(tags_element);
}

$(document).on('show.bs.modal', '#customer-address-modal', function (event) {
    var triggerElement = $(event.relatedTarget);
    current_selected_image = triggerElement;
    var id = $(current_selected_image).data('id');
    var existing_url = $(this).find('#customer-address-table').data('url');

    if (existing_url.indexOf('?') > -1) {
        var temp = $(existing_url).text().split('?');
        var new_url = temp[0] + '?user_id=' + id;
    } else {
        var new_url = existing_url + '?user_id=' + id;
    }
    $('#customer-address-table').bootstrapTable('refreshOptions', {
        url: new_url,
    });
});
$(document).on('show.bs.modal', '#product-rating-modal', function (event) {
    var triggerElement = $(event.relatedTarget);
    current_selected_image = triggerElement;
    var id = $(current_selected_image).data('id');

    var existing_url = $(this).find('#product-rating-table').data('url');
    if (existing_url.indexOf('?') > -1) {
        var temp = $(existing_url).text().split('?');
        var new_url = temp[0] + '?product_id=' + id;
    } else {
        var new_url = existing_url + '?product_id=' + id;
    }
    $('#product-rating-table').bootstrapTable('refreshOptions', {
        url: new_url,
    });
});

$(document).on('click', '.remove-image', function (e) {
    e.preventDefault();
    $(this).closest('.image').remove();
});

$(document).on('change', '#media-type', function () {
    $('table').bootstrapTable('refresh');
});

Dropzone.autoDiscover = false;

if (document.getElementById('dropzone')) {

    var myDropzone = new Dropzone("#dropzone", {
        url: base_url + from + '/media/upload',
        paramName: "documents",
        autoProcessQueue: false,
        parallelUploads: 12,
        maxFiles: 12,
        autoDiscover: false,
        addRemoveLinks: true,
        timeout: 180000,
        dictRemoveFile: 'x',
        dictMaxFilesExceeded: 'Only 12 files can be uploaded at a time ',
        dictResponseError: 'Error',
        uploadMultiple: true,
        dictDefaultMessage: '<p><input type="submit" value="Select Files" class="btn btn-success" /><br> or <br> Drag & Drop Media Files Here</p>',
    });

    myDropzone.on("addedfile", function (file) {
        var i = 0;
        if (this.files.length) {
            var _i, _len;
            for (_i = 0, _len = this.files.length; _i < _len - 1; _i++) {
                if (this.files[_i].name === file.name && this.files[_i].size === file.size && this.files[_i].lastModifiedDate.toString() === file.lastModifiedDate.toString()) {
                    this.removeFile(file);
                    i++;
                }
            }
        }
    });

    myDropzone.on("error", function (file, response) { });


    myDropzone.on('sending', function (file, xhr, formData) {
        formData.append(csrfName, csrfHash);
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var response = JSON.parse(this.response);
                csrfName = response.csrfName;
                csrfHash = response.csrfHash;
                if (response['error'] == false) {
                    Dropzone.forElement('#dropzone').removeAllFiles(true);
                    $("#media-upload-table").bootstrapTable('refresh');
                    iziToast.success({
                        message: response['message'],
                    });
                    $('#media-table').bootstrapTable('refresh');
                } else {
                    iziToast.error({
                        title: 'Error',
                        message: response['message'],
                    });
                }
                $(file.previewElement).find('.dz-error-message').text(response.message);
            }
        };
    });
}
if (document.getElementById('system-update-dropzone')) {

    var systemDropzone = new Dropzone("#system-update-dropzone", {
        url: base_url + 'admin/updater/upload_update_file',
        paramName: "update_file",
        autoProcessQueue: false,
        parallelUploads: 1,
        maxFiles: 1,
        timeout: 360000,
        autoDiscover: false,
        addRemoveLinks: true,
        dictRemoveFile: 'x',
        dictMaxFilesExceeded: 'Only 1 file can be uploaded at a time ',
        dictResponseError: 'Error',
        uploadMultiple: true,
        dictDefaultMessage: '<p><input type="submit" value="Select Files" class="btn btn-success" /><br> or <br> Drag & Drop System Update / Installable / Plugin\'s .zip file Here</p>',
    });

    systemDropzone.on("addedfile", function (file) {
        var i = 0;
        if (this.files.length) {
            var _i, _len;
            for (_i = 0, _len = this.files.length; _i < _len - 1; _i++) {
                if (this.files[_i].name === file.name && this.files[_i].size === file.size && this.files[_i].lastModifiedDate.toString() === file.lastModifiedDate.toString()) {
                    this.removeFile(file);
                    i++;
                }
            }
        }
    });

    systemDropzone.on("error", function (file, response) { });


    systemDropzone.on('sending', function (file, xhr, formData) {
        formData.append(csrfName, csrfHash);
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var response = JSON.parse(this.response);
                csrfName = response.csrfName;
                csrfHash = response.csrfHash;
                if (response['error'] == false) {
                    iziToast.success({
                        message: response['message'],
                    });
                } else {
                    iziToast.error({
                        title: 'Error',
                        message: response['message'],
                    });
                }
                $(file.previewElement).find('.dz-error-message').text(response.message);
            }
        };
    });
    $('#system_update_btn').on('click', function (e) {
        e.preventDefault();
        systemDropzone.processQueue();
    });
}

$('#upload-files-btn').on('click', function (e) {
    e.preventDefault();
    myDropzone.processQueue();
});




$(document).on('click', '.copy-to-clipboard', function () {

    var $element = $(this).closest('tr').find('.path');
    copyToClipboard($element);
    iziToast.success({
        message: 'Image path copied to clipboard',
    });
});
$(document).on('click', '.copy-relative-path', function () {
    var $element = $(this).closest('tr').find('.relative-path');
    copyToClipboard($element);
    iziToast.success({
        message: 'Image path copied to clipboard',
    });
});


$(document).on('click', 'button[type="reset"]', function () {
    $('.image-upload-div').remove();
    $('.image-upload-section').find('.image').addClass('d-none');
});

//20.Client Api Key Module
$(document).on('click', '#delete-client', function () {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'admin/client_api_keys/delete_client',
                    data: {
                        id: id
                    },
                    dataType: 'json'
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Deleted!', response.message, 'success');
                        } else {
                            Swal.fire('Opps', response.message, 'warning');
                        }
                        $('table').bootstrapTable('refresh');
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
            });
        },
        allowOutsideClick: false
    });
});

//21.System Users
$(document).on('click', '#delete-system-users', function () {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'admin/system_users/delete_system_user',
                    data: {
                        id: id
                    },
                    dataType: 'json'
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Deleted!', response.message, 'success');
                        } else {
                            Swal.fire('Opps', response.message, 'warning');
                        }
                        $('table').bootstrapTable('refresh');
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
            });
        },
        allowOutsideClick: false
    });
});

$(document).on('change', '.system-user-role', function () {
    var role = $(this).val();
    if (role > 0) {
        $('.permission-table').removeClass('d-none');
    } else {
        $('.permission-table').addClass('d-none');
    }
});

$(document).on('click', '.remove_individual_variants', function () {
    var variant_id = $(this).closest('.variant_col').find('input[type="hidden"]').val();
    var all_variant_ids = $(this).closest('.row').find('input[name="variants_ids[]"]').val().split(',');
    all_variant_ids.splice(all_variant_ids.indexOf(variant_id), 1);
    if ($.isEmptyObject(all_variant_ids)) {
        $(this).closest('.row').remove();
    } else {
        $(this).closest('.row').find('input[name="variants_ids[]"]').val(all_variant_ids.toString());
        $(this).closest('.variant_col').remove();
    }
});

$(document).on('change', '#system_timezone', function () {
    var gmt = $(this).find(':selected').data('gmt');
    $('#system_timezone_gmt').val(gmt);
});

$('#city').on('change', function (e) {
    e.preventDefault();
    $.ajax({
        type: 'POST',
        data: {
            'city_id': $(this).val(),
            [csrfName]: csrfHash,
        },
        url: base_url + 'my-account/get-areas',
        dataType: 'json',
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.error == false) {
                var html = '';
                $.each(result.data, function (i, e) {
                    html += '<option value=' + e.id + '>' + e.name + '</option>';
                });
                $('#area').html(html);
            } else {
                Toast.fire({
                    icon: 'error',
                    title: result.message
                });
                $('#area').html('');
            }
        }
    })
});

$('#add-address-form').on('submit', function (e) {
    e.preventDefault();
    var formdata = new FormData(this);
    formdata.append(csrfName, csrfHash);
    $.ajax({
        type: 'POST',
        data: formdata,
        url: $(this).attr('action'),
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $('#save-address-submit-btn').val('Please Wait...').attr('disabled', true);
        },
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.error == false) {
                $('#save-address-result').html("<div class='alert alert-success'>" + result.message + "</div>").delay(1500).fadeOut();
                $('#add-address-form')[0].reset();
                $('#address_list_table').bootstrapTable('refresh');
            } else {
                $('#save-address-result').html("<div class='alert alert-danger'>" + result.message + "</div>").delay(1500).fadeOut();
            }
            $('#save-address-submit-btn').val('Save').attr('disabled', false);
        }
    })
})

$(document).on('click', '.delete-address', function (e) {
    e.preventDefault();
    if (confirm('Are you sure ? You want to delete this address?')) {
        $.ajax({
            type: 'POST',
            data: {
                'id': $(this).data('id'),
                [csrfName]: csrfHash,
            },
            url: base_url + 'my-account/delete-address',
            dataType: 'json',
            success: function (result) {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
                if (result.error == false) {
                    $('#address_list_table').bootstrapTable('refresh');
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: result.message
                    });
                }
            }
        })
    }
});
$('#edit_city').on('change', function (e, data) {
    e.preventDefault();
    $.ajax({
        type: 'POST',
        data: {
            'city_id': $(this).val(),
            [csrfName]: csrfHash,
        },
        url: base_url + 'my-account/get-areas',
        dataType: 'json',
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.error == false) {
                var html = '';
                $.each(result.data, function (i, e) {
                    html += '<option value=' + e.id + '>' + e.name + '</option>';
                });
                $('#edit_area').html(html);
            } else {
                Toast.fire({
                    icon: 'error',
                    title: result.message
                });
                $('#edit_area').html('');
            }
        }
    })
});

$('#edit-address-form').on('submit', function (e) {
    e.preventDefault();
    var formdata = new FormData(this);
    formdata.append(csrfName, csrfHash);
    $.ajax({
        type: 'POST',
        data: formdata,
        url: $(this).attr('action'),
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $('#edit-address-submit-btn').val('Please Wait...').attr('disabled', true);
        },
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.error == false) {
                $('#edit-address-result').html("<div class='alert alert-success'>" + result.message + "</div>").delay(1500).fadeOut();
                $('#edit-address-form')[0].reset();
                $('#address_list_table').bootstrapTable('refresh');
                setTimeout(function () {
                    $('#address-modal').modal('hide');
                }, 2000)
            } else {
                $('#edit-address-result').html("<div class='alert alert-danger'>" + result.message + "</div>").delay(1500).fadeOut();
            }
            $('#edit-address-submit-btn').val('Save').attr('disabled', false);
        }
    })
})

$('#add-new-language-form').on('submit', function (e) {
    e.preventDefault();
    var formdata = new FormData(this);
    formdata.append(csrfName, csrfHash);
    $.ajax({
        type: 'POST',
        data: formdata,
        url: $(this).attr('action'),
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $('#submit_btn').val('Please Wait...').attr('disabled', true);
        },
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.error == false) {
                $('#result').show().removeClass('msg_error').addClass('msg_success').html(result.message).delay(1500).fadeOut();
                $('#add-new-language-form')[0].reset();
                setTimeout(function () {
                    $('#language-modal').modal('hide');
                }, 2000)
            } else {
                $('#result').show().removeClass('msg_success').addClass('msg_error').html(result.message).delay(1500).fadeOut();
            }
            $('#submit_btn').val('Save').attr('disabled', false);
        }
    })
})

$('#selected_language').on('change', function () {
    var id = $(this).val();
    window.location.href = base_url + 'admin/language?id=' + id;
});
$('#update-language-form').on('submit', function (e) {
    e.preventDefault();
    var formdata = new FormData(this);
    formdata.append(csrfName, csrfHash);
    $.ajax({
        type: 'POST',
        data: formdata,
        url: $(this).attr('action'),
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $('#update_btn').val('Please Wait...').attr('disabled', true);
        },
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.error == false) {
                $('#update-result').show().removeClass('msg_error').addClass('msg_success').html(result.message).delay(1500).fadeOut();
            } else {
                $('#update-result').show().removeClass('msg_success').addClass('msg_error').html(result.message).delay(1500).fadeOut();
            }
            $('#update_btn').val('Save').attr('disabled', false);
        }
    })
})

function product_rating_query_params(p) {
    return {
        'product_id': $('#product_id').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

$('#area_wise_delivery_charge').on('switchChange.bootstrapSwitch', function (event, state) {
    if (state == false) {
        if ($(".delivery_charge").hasClass("d-none")) {
            $(".delivery_charge").removeClass("d-none")
        }
        if ($(".min_amount").hasClass("d-none")) {
            $(".min_amount").removeClass("d-none")
        }
        if ($(".area_wise_delivery_charge").hasClass("col-md-6")) {
            $(".area_wise_delivery_charge").removeClass("col-md-6")
            $(".area_wise_delivery_charge").addClass("col-md-4")
        }
    } else {
        if (!$(".delivery_charge").hasClass("d-none")) {
            $(".delivery_charge").addClass("d-none")
        }
        if (!$(".min_amount").hasClass("d-none")) {
            $(".min_amount").addClass("d-none")
        }
        if ($(".area_wise_delivery_charge").hasClass("col-md-4")) {
            $(".area_wise_delivery_charge").removeClass("col-md-4")
            $(".area_wise_delivery_charge").addClass("col-md-6")
        }


    }
});

$('#bulk_upload_form').on('submit', function (e) {
    e.preventDefault();
    var type = $('#type').val();
    if (type != '') {
        var formdata = new FormData(this);
        formdata.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            data: formdata,
            url: $(this).attr('action'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#submit_btn').html('Please Wait...').attr('disabled', true);
            },
            success: function (result) {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
                if (result.error == false) {
                    $('#upload_result').show().removeClass('msg_error').addClass('msg_success').html(result.message).delay(3000).fadeOut();
                } else {
                    $('#upload_result').show().removeClass('msg_success').addClass('msg_error').html(result.message).delay(3000).fadeOut();
                }
                $('#submit_btn').html('Submit').attr('disabled', false);
            }
        })
    } else {
        iziToast.error({
            message: 'Please select type',
        });
    }

});
$('#location_bulk_upload_form').on('submit', function (e) {
    e.preventDefault();
    var type = $('#type').val();
    var location_type = $('#location_type').val();
    if (type != '' && location_type != "" && type != "undefined" && location_type != "undefined") {
        var formdata = new FormData(this);
        formdata.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            data: formdata,
            url: $(this).attr('action'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#submit_btn').html('Please Wait...').attr('disabled', true);
            },
            success: function (result) {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
                if (result.error == false) {
                    $('#upload_result').show().removeClass('msg_error').addClass('msg_success').html(result.message).delay(3000).fadeOut();
                } else {
                    $('#upload_result').show().removeClass('msg_success').addClass('msg_error').html(result.message).delay(3000).fadeOut();
                }
                $('#submit_btn').html('Submit').attr('disabled', false);
            }
        });
    } else {
        iziToast.error({
            message: 'Please select Type and Location Type',
        });
    }

});


// swatche js

$('#swatche_color').hide();
$('#swatche_image').hide();
$(document.body).on('change', '.swatche_type', function (e) {
    e.preventDefault();
    var swatche_type = $(this).val();
    if (swatche_type == "1") {
        $('#swatche_image').hide();
        $('#swatche_color').show();
        $('#swatche_image').val('');
    } else if (swatche_type == "2") {
        $('#swatche_color').hide();
        $('#swatche_image').show();
        $('#swatche_color').val('');
    } else {
        $('#swatche_color').hide();
        $('#swatche_image').hide();
        $('#swatche_color').val('');
        $('#swatche_image').val('');
    }
});
if ($('#google_pay_currency_code').length) {
    $('#google_pay_currency_code').on('change', function (e) {
        e.preventDefault();
        var country_code = $(this).find(':selected').data('countrycode');
        $('#google_pay_country_code').val(country_code);
    });
}

var ticket_id = "";
var scrolled = 0;
$(document).on('click', '.view_ticket', function (e, row) {
    e.preventDefault();
    scrolled = 0;
    $(".ticket_msg").data('max-loaded', false);
    ticket_id = $(this).data("id");
    var username = $(this).data("username");
    var date_created = $(this).data("date_created");
    var subject = $(this).data("subject");
    var status = $(this).data("status");
    var ticket_type = $(this).data("ticket_type");
    $('input[name="ticket_id"]').val(ticket_id);
    $('#user_name').html(username);
    $('#date_created').html(date_created);
    $('#subject').html(subject);
    $('.change_ticket_status').data('ticket_id', ticket_id);
    if (status == 1) {
        $('#status').html('<label class="badge badge-secondary ml-2">PENDING</label>');
    } else if (status == 2) {
        $('#status').html('<label class="badge badge-info ml-2">OPENED</label>');
    } else if (status == 3) {
        $('#status').html('<label class="badge badge-success ml-2">RESOLVED</label>');
    } else if (status == 4) {
        $('#status').html('<label class="badge badge-danger ml-2">CLOSED</label>');
    } else if (status == 5) {
        $('#status').html('<label class="badge badge-warning ml-2">REOPENED</label>');
    }
    $('#ticket_type').html(ticket_type);
    $('.ticket_msg').html("");
    $('.ticket_msg').data('limit', 5);
    $('.ticket_msg').data('offset', 0);
    load_messages($('.ticket_msg'), ticket_id);
});

$(document).ready(function () {
    if ($("#element").length) {
        $("#element").scrollTop($("#element")[0].scrollHeight);
        $('#element').scroll(function () {
            if ($('#element').scrollTop() == 0) {
                load_messages($('.ticket_msg'), ticket_id);
            }
        });

        $('#element').bind('mousewheel', function (e) {
            if (e.originalEvent.wheelDelta / 120 > 0) {
                if ($(".ticket_msg")[0].scrollHeight < 370 && scrolled == 0) {
                    load_messages($('.ticket_msg'), ticket_id);
                    scrolled = 1;
                }
            }
        });
    }
});

$('#ticket_send_msg_form').on('submit', function (e) {
    e.preventDefault();
    var formdata = new FormData(this);
    formdata.append(csrfName, csrfHash);

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formdata,
        beforeSend: function () {
            $('#submit_btn').html('Sending..').attr('disabled', true);
        },
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (result) {

            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $('#submit_btn').html('Send').attr('disabled', false);
            if (result.error == false) {
                if (result.data.id > 0) {
                    var message = result.data;
                    var is_left = (message.user_type == 'user') ? 'left' : 'right';
                    var message_html = "";
                    var atch_html = "";
                    var i = 1;
                    if (message.attachments.length > 0) {
                        message.attachments.forEach(atch => {
                            atch_html += "<div class='container-fluid image-upload-section'>" +
                                "<a class='btn btn-danger btn-xs mr-1 mb-1' href='" + atch.media + "'  target='_blank' alt='Attachment Not Found'>Attachment " + i + "</a>" +
                                "<div class='col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image d-none'></div>" +
                                "</div>";
                            i++;
                        });
                    }
                    message_html += "<div class='direct-chat-msg " + is_left + "'>" +
                        "<div class='direct-chat-infos clearfix'>" +
                        "<span class='direct-chat-name float-" + is_left + "' id='name'>" + message.name + "</span>" +
                        "<span class='direct-chat-timestamp float-" + is_left + "' id='last_updated'>" + message.last_updated + "</span>" +
                        "</div>" +
                        "<div class='direct-chat-text' id='message'>" + message.message + "</br>" + atch_html + "</div>" +
                        "</div>";

                    $('.ticket_msg').append(message_html);
                    $("#message_input").val('');

                    $("#element").scrollTop($("#element")[0].scrollHeight);
                    $('input[name="attachments[]"]').val('');
                }
            } else {
                $("#element").data('max-loaded', true);
                iziToast.error({
                    message: '<span style="text-transform:capitalize">' + result.message + '</span> ',
                });
                return false;
            }
            iziToast.success({
                message: '<span style="text-transform:capitalize">' + result.message + '</span> ',
            });

        }
    });
});


$(document).on('click', '#delete-ticket', function () {

    var id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure !',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: base_url + 'admin/tickets/delete_ticket',
                    type: 'GET',
                    data: {
                        'id': id
                    },
                    dataType: 'json',
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('table').bootstrapTable('refresh');
                        } else {
                            Swal.fire('Oops...', response.message, 'warning');
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
            });
        },
        allowOutsideClick: false
    });
});


$(document).on('change', '.change_ticket_status', function () {
    var status = $(this).val();
    if (status != '') {
        if (confirm("Are you sure you want to mark the ticket as " + $(".change_ticket_status option:selected").text() + "? ")) {
            var id = $(this).data('ticket_id');
            var dataString = {
                ticket_id: id,
                status: status,
                [csrfName]: csrfHash
            };
            $.ajax({
                type: 'post',
                url: base_url + 'admin/tickets/edit-ticket-status',
                data: dataString,
                dataType: 'json',
                success: function (result) {
                    csrfHash = result.csrfHash;
                    if (result.error == false) {
                        $('#ticket_table').bootstrapTable('refresh');
                        iziToast.success({
                            message: '<span style="text-transform:capitalize">' + result.message + '</span> ',
                        });

                    } else {
                        iziToast.error({
                            message: '<span>' + result.message + '</span> ',
                        });
                    }
                }
            });
        }
    }
});

$(document).on('click', '.delete-ticket-type', function () {
    var cat_id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'admin/tickets/delete_ticket_type',
                    data: {
                        id: cat_id
                    },
                    dataType: 'json'
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('table').bootstrapTable('refresh');
                            csrfName = response['csrfName'];
                            csrfHash = response['csrfHash'];
                        } else {
                            Swal.fire('Oops...', response.message, 'warning');
                            $('table').bootstrapTable('refresh');
                            csrfName = response['csrfName'];
                            csrfHash = response['csrfHash'];
                        }

                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                        csrfName = response['csrfName'];
                        csrfHash = response['csrfHash'];
                    });
            });
        },
        allowOutsideClick: false
    });
});

function load_messages(element, ticket_id) {
    var limit = element.data('limit');
    var offset = element.data('offset');

    element.data('offset', limit + offset);
    var max_loaded = element.data('max-loaded');
    if (max_loaded == false) {
        var loader = '<div class="loader text-center"><img src="' + base_url + 'assets/pre-loader.gif" alt="Loading. please wait.. ." title="Loading. please wait.. ."></div>';
        $.ajax({
            type: 'get',
            data: 'ticket_id=' + ticket_id + '&limit=' + limit + '&offset=' + offset,
            url: base_url + 'admin/tickets/get_ticket_messages',
            beforeSend: function () {
                $('.ticket_msg').prepend(loader);
            },
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.error == false) {
                    if (result.error == false && result.data.length > 0) {
                        var messages_html = "";
                        var is_left = "";
                        var is_right = "";
                        var atch_html = "";
                        var i = 1;
                        result.data.reverse().forEach(messages => {
                            is_left = (messages.user_type == 'user') ? 'left' : 'right';
                            is_right = (messages.user_type == 'user') ? 'right' : 'left';
                            if (messages.attachments.length > 0) {
                                messages.attachments.forEach(atch => {
                                    atch_html += "<div class='container-fluid image-upload-section'>" +
                                        "<a class='btn btn-danger btn-xs mr-1 mb-1' href='" + atch.media + "'  target='_blank' alt='Attachment Not Found'>Attachment " + i + "</a>" +
                                        "<div class='col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image d-none'></div>" +
                                        "</div>";
                                    i++;
                                });
                            }
                            messages_html += "<div class='direct-chat-msg " + is_left + "'>" +
                                "<div class='direct-chat-infos clearfix'>" +
                                "<span class='direct-chat-name float-" + is_left + "' id='name'>" + messages.name + "</span>" +
                                "<span class='direct-chat-timestamp float-" + is_left + "' id='last_updated'>" + messages.last_updated + "</span>" +
                                "</div>" +
                                "<div class='direct-chat-text' id='message'>" + messages.message + "</br>" + atch_html + "</div>" +
                                "</div>";
                        });
                        $('.ticket_msg').prepend(messages_html);
                        $('.ticket_msg').find('.loader').remove();
                        $(element).animate({
                            scrollTop: $(element).offset().top
                        });
                    }
                } else {
                    element.data('offset', offset);
                    element.data('max-loaded', true);
                    $('.ticket_msg').find('.loader').remove();
                    $('.ticket_msg').prepend('<div class="text-center"> <p>You have reached the top most message!</p></div>');
                }
                $('#element').scrollTop(20); // Scroll alittle way down, to allow user to scroll more
                $(element).animate({
                    scrollTop: $(element).offset().top
                });
                return false;
            }
        });

    }
}

$(document).on('click', '.edit_transaction', function (e, row) {
    e.preventDefault();
    var id = $(this).data("id");
    var txn_id = $(this).data("txn_id");
    var status = $(this).data("status");
    var message = $(this).data("message");

    $('#id').val(id);
    $('#txn_id').val(txn_id);
    $('#t_status').val(status);
    $('#message').val(message);

});
$(document).on('click', '.edit_order_tracking', function (e, rows) {
    e.preventDefault();
    var order_item_id = $(this).data("order_item_id");
    var order_id = $(this).data("order_id");
    var order_item_id = $(this).data("order_item_id");
    var courier_agency = $(this).data("courier_agency");
    var tracking_id = $(this).data("tracking_id");
    var url = $(this).data("url");
    $('#order_item_id').val(order_item_id);
    $('input[name="order_id"]').val(order_id);
    $('input[name="order_item_id"]').val(order_item_id);
    $('#order_id').val(order_id);
    $('#order_item_id').val(order_item_id);
    $('#courier_agency').val(courier_agency);
    $('#tracking_id').val(tracking_id);
    $('#url').val(url);
    $('#order_tracking_table').bootstrapTable('refresh');
});

$(document).on('click', '.edit_digital_order_mails', function (e, rows) {
    e.preventDefault();
    var order_item_id = $(this).data("order_item_id");
    var order_id = $(this).data("order_id");
    $('input[name="order_item_id"]').val(order_item_id);
    $('input[name="order_id"]').val(order_id);
    $('#digital_order_mail_table').bootstrapTable('refresh');

});

$('#edit_transaction_form').on('submit', function (e) {
    e.preventDefault();
    var formdata = new FormData(this);
    formdata.append(csrfName, csrfHash);

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formdata,
        beforeSend: function () {
            $('#submit_btn').html('Please Wait..').attr('disabled', true);
        },
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (result) {
            csrfHash = result.csrfHash;
            $('#submit_btn').html('Update Transaction').attr('disabled', false);
            if (result.error == false) {
                $('table').bootstrapTable('refresh');
                iziToast.success({
                    message: '<span style="text-transform:capitalize">' + result.message + '</span> ',
                });
            } else {
                iziToast.error({
                    message: '<span>' + result.message + '</span> ',
                });
            }
        }
    });
});

$('#order_tracking_form').on('submit', function (e) {
    e.preventDefault();
    var formdata = new FormData(this);
    formdata.append(csrfName, csrfHash);

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formdata,
        beforeSend: function () {
            $('#submit_btn').html('Please Wait..').attr('disabled', true);
        },
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (result) {
            csrfHash = result.csrfHash;
            $('#submit_btn').html('Update Transaction').attr('disabled', false);
            if (result.error == false) {
                $('table').bootstrapTable('refresh');
                iziToast.success({
                    message: '<span style="text-transform:capitalize">' + result.message + '</span> ',
                });
            } else {
                iziToast.error({
                    message: '<span>' + result.message + '</span> ',
                });
            }
        }
    });
});

$(document).on('click', '.delete-receipt', function () {
    var cat_id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'admin/orders/delete_receipt',
                    data: {
                        id: cat_id
                    },
                    dataType: 'json'
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('table').bootstrapTable('refresh');
                            csrfName = response['csrfName'];
                            csrfHash = response['csrfHash'];
                            window.location.reload();
                        } else {
                            Swal.fire('Oops...', response.message, 'warning');
                            $('table').bootstrapTable('refresh');
                            csrfName = response['csrfName'];
                            csrfHash = response['csrfHash'];
                        }

                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                        csrfName = response['csrfName'];
                        csrfHash = response['csrfHash'];
                    });
            });
        },
        allowOutsideClick: false
    });
});

$('#update_receipt_status').on('change', function (e) {
    e.preventDefault();
    var order_id = $(this).data('id');
    var user_id = $(this).data('user_id');
    var status = $(this).val();
    $.ajax({
        type: 'POST',
        data: {
            'order_id': order_id,
            'status': status,
            'user_id': user_id,
            [csrfName]: csrfHash,
        },
        url: base_url + 'admin/orders/update_receipt_status',
        dataType: 'json',
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result['error'] == false) {
                iziToast.success({
                    message: result['message'],
                });
            } else {
                iziToast.error({
                    message: result['message'],
                });
            }
        }
    });
});

//13.City-Module
$(document).on('click', '#delete-zipcode', function () {

    var id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure !',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: base_url + 'admin/Area/delete_zipcode',
                    type: 'GET',
                    data: {
                        'id': id
                    },
                    dataType: 'json',
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('table').bootstrapTable('refresh');
                        } else {
                            Swal.fire('Oops...', response.message, 'warning');
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
            });
        },
        allowOutsideClick: false
    });
});

var search_zipcodes = searchable_zipcodes();

search_zipcodes.on('select2:select', function (e) {
    var data = e.params.data;
    if (data.link != undefined && data.link != null) {
        window.location.href = data.link;
    }
});


$(document).on('change', '#deliverable_type', function () {
    var type = $(this).val();
    if (type == "1" || type == "0") {
        $('#deliverable_zipcodes').prop('disabled', 'disabled');
    } else {
        $('#deliverable_zipcodes').prop('disabled', false);
    }
});

var cat_html = "";
var count_view = 0;
$(document).on('click', '#seller_model', function (e) {
    e.preventDefault();
    cat_html = $('#cat_html').html();
    var cat_ids = $(this).data('cat_ids') + ',';
    var cat_array = cat_ids.split(",");
    cat_array = cat_array.filter(function (v) {
        return v !== ''
    });
    cat_array.sort(function (a, b) {
        return a - b;
    });
    var seller_id = $(this).data('seller_id');
    if (cat_ids != "" && cat_ids != "," && cat_ids != 'undefined' && seller_id != "" && seller_id != 'undefined' && count_view == 0) {
        $.ajax({
            type: 'POST',
            data: {
                'id': seller_id,
                [csrfName]: csrfHash
            },
            url: base_url + 'admin/sellers/get_seller_commission_data',
            dataType: 'json',
            success: function (result) {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
                if (result.error == false) {
                    var option_html = $('#cat_html').html();
                    $.each(result.data, function (i, e) {
                        var is_selected = (e.category_id == cat_array[i] && e.seller_id == seller_id) ? "selected" : "";
                        if (is_selected === '') {
                            load_category_section(cat_html);
                        } else {
                            option_html += '<option value=' + e.category_id + ' ' + is_selected + '>' + e.name + '</option>';
                            load_category_section("", true, option_html, e.commission);
                        }
                    });
                } else {
                    iziToast.error({
                        message: '<span>' + result.message + '</span> ',
                    });
                }
            }
        });
        count_view = 1;
    } else {
        if (count_view == 0) {
            load_category_section(cat_html);
        }
        count_view = 1;

    }

});
$(document).on('click', '#add_category', function (e) {
    e.preventDefault();
    load_category_section(cat_html, false);
});

function load_category_section(cat_html, is_edit = false, option_html = "", commission = 0) {

    if (is_edit == true) {

        var html = ' <div class="form-group  row">' +
            '<div class="col-sm-5">' +
            '<select name="category_id" class="form-control select_multiple w-100" data-placeholder=" Select Category">' +
            '<option value="">Select Category </option>' + option_html +
            '</select>' +
            '</div>' +
            '<div class="col-sm-5">' +
            '<input type="number" step="any"  min="0" class="form-control"  placeholder="Enter Commission" name="commission" required value="' + commission + '">' +
            '</div>' +
            '<div class="col-sm-2"> ' +
            '<button type="button" class="btn btn-tool remove_category_section" > <i class="text-danger far fa-times-circle fa-2x "></i> </button>' +
            '</div>' +
            '</div>' +
            '</div>';
    } else {
        var html = ' <div class="form-group row">' +
            '<div class="col-sm-5">' +
            '<select name="category_id" class="form-control select_multiple w-100" data-placeholder="Select Category">' +
            '<option value="">Select Category </option>' + cat_html +
            '</select>' +
            '</div>' +
            '<div class="col-sm-5">' +
            '<input type="number" step="any"  min="0" class="form-control"  placeholder="Enter Commission" name="commission"  value="0">' +
            '</div>' +
            '<div class="col-sm-2"> ' +
            '<button type="button" class="btn btn-tool remove_category_section" > <i class="text-danger far fa-times-circle fa-2x "></i> </button>' +
            '</div>' +
            '</div>' +
            '</div>';
    }
    $('#category_section').append(html);
    $('.select_multiple').each(function () {
        $('.select_multiple').select2({
            theme: 'bootstrap4',
            width: $('.select_multiple').data('width') ? $('.select_multiple').data('width') : $('.select_multiple').hasClass('w-100') ? '100%' : 'style',
            placeholder: $('.select_multiple').data('placeholder'),
            allowClear: Boolean($('.select_multiple').data('allow-clear')),
        });
    });


}

$(document).on('click', '.remove_category_section', function () {
    $(this).closest('.row').remove();
});

$("#seller_table").on("click-cell.bs.table", function (field, value, row, $el) {
    $('input[name="seller_status"]').val($el.id);
});


$('#add-seller-commission-form').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);

    var object = {};
    formData.forEach((value, key) => {
        // Reflect.has in favor of: object.hasOwnProperty(key)
        if (!Reflect.has(object, key)) {
            object[key] = value;
            return;
        }
        if (!Array.isArray(object[key])) {
            object[key] = [object[key]];
        }
        object[key].push(value);
    });
    var json = JSON.stringify(object);
    $('#cat_data').val(json);
    setTimeout(function () {
        $('#set_commission_model').modal('hide');
    }, 2000);

});

$(document).on('submit', '#verify-acount-form', function (e) {
    e.preventDefault();
    var formdata = new FormData(this);
    formdata.append(csrfName, csrfHash);

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formdata,
        beforeSend: function () {
            $('#submit_btn1').html('Please Wait..').attr('disabled', true);
        },
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (result) {
            csrfHash = result.csrfHash;
            $('#submit_btn1').html('Verify Account').attr('disabled', false);
            if (result.error == false) {
                if (result.redirect == "3") {
                    window.location.replace(base_url + 'seller/auth/sign_up');
                } else if (result.redirect == "1") {
                    iziToast.success({
                        message: '<span style="text-transform:capitalize">' + result.message + '</span> ',
                    });
                }

            } else {
                if (result.redirect == '2') {
                    window.location.replace(base_url + 'seller/auth/sign_up');
                }
                iziToast.error({
                    message: '<span>' + result.message + '</span> ',
                });
            }
            $('#verify-acount-form').trigger("reset");
            setTimeout(function () {
                $('#has_account_model').modal('hide');
            }, 3000);
        }
    });
});

$(document).on('click', '#create-slug', function (e) {
    e.preventDefault();
    $.ajax({
        type: 'get',
        url: base_url + 'admin/sellers/create_slug',
        beforeSend: function () {
            $(this).html('Please Wait..').attr('disabled', true);
        },
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (result) {
            csrfHash = result.csrfHash;
            $(this).html('Create Seller Slug').attr('disabled', false);
            if (result.error == false) {
                iziToast.success({
                    message: result['message'],
                });
            } else {
                iziToast.error({
                    message: '<span>' + result.message + '</span> ',
                });
            }
        }
    });
});

$(document).on('click', '.update-seller-commission', function () {
    Swal.fire({
        title: 'Are You Sure !',
        text: "You won't be able to revert this!",
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, settle commission!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: base_url + 'admin/cron-job/settle-seller-commission',
                    type: 'GET',
                    data: {
                        'is_date': true
                    },
                    dataType: 'json',
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Done!', response.message, 'success');
                            $('table').bootstrapTable('refresh');
                        } else {
                            Swal.fire('Oops...', response.message, 'warning');
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
            });
        },
        allowOutsideClick: false
    });
});

function cash_collection_query_params(p) {
    return {
        filter_date: $('#filter_date').val(),
        filter_status: $('#filter_status').val(),
        filter_d_boy: $('#filter_d_boy').val(),
        "start_date": $('#start_date').val(),
        "end_date": $('#end_date').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

var d_boy_cash = 0;
$('#delivery_boys_details').on('check.bs.table', function (e, row) {
    d_boy_cash = row.cash_received;
    $('#details').val("Id: " + row.id + " | Name:" + row.name + " | Mobile: " + row.mobile + " | Cash: " + row.cash_received);
    $('#delivery_boy_id').val(row.id);
});

function validate_amount() {
    var cash = d_boy_cash;
    var amount = $('#amount').val();
    var details_val = $('#details').val();
    if (details_val == "") {
        iziToast.error({
            message: '<span>you have to select delivery boy to collect cash.</span> ',
        });
        $('#amount').val('');
    } else {
        if (parseInt(cash) > 0) {
            if (parseInt(amount) > parseInt(cash)) {
                iziToast.error({
                    message: '<span>You Can not enter amount greater than cash</span> ',
                });
                $('#amount').val('');
            }
            if (parseInt(amount) <= 0) {
                iziToast.error({
                    message: '<span>Amount must be greater than zero</span> ',
                });
                $('#amount').val('');
            }
        } else {
            iziToast.error({
                message: '<span>Cash must be greater than zero</span> ',
            });
            $('#amount').val('');
        }
    }
}

function idFormatter() {
    return 'Total'
}

function priceFormatter(data) {
    var field = this.field
    var store_currency = $('input[name="store_currency"]').val();

    return '<span style="color:green;font-weight:bold;font-size:large;">' + store_currency + data.map(function (row) {
        return +row[field]
    })
        .reduce(function (sum, i) {
            return sum + i
        }, 0);
}
// Feature Section Hide or Show Category Field

$(document).on('change', '.product_type', function () {
    var product_type = $('.product_type').val();
    var exclude_product_type = ["custom_products"];
    if (exclude_product_type.includes(product_type)) {
        $(".select-categories").hide();
    } else {
        $(".select-categories").show();
    }
});
$(document).on('click', '.add_promo_code_discount', function () {
    Swal.fire({
        title: 'Are You Sure !',
        text: "You won't be able to revert this!",
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, settle Discounted!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: base_url + 'admin/cron_job/settle_cashback_discount',
                    type: 'GET',
                    data: {
                        'is_date': true
                    },
                    dataType: 'json',
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Done!', response.message, 'success');
                            $('table').bootstrapTable('refresh');
                        } else {
                            Swal.fire('Oops...', response.message, 'warning');
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
            });
        },
        allowOutsideClick: false
    });
});
$('.discount_type').on('change', function () {
    if ($(this).val() == 'percentage') {
        if ($('.discount').val() > 100) {
            $('.discount').attr('max', 100);
            $('#submit_btn').attr('disabled', true);
            $('.error').html('<small class="text-danger">You cannot set percantage more then 100</small>')
        } else {
            $('.discount').removeAttr('max');
            $('#submit_btn').attr('disabled', false);
            $('.error').html('')
        }
    } else {
        $('.discount').removeAttr('max');
        $('#submit_btn').attr('disabled', false);
        $('.error').html('')
    }
});
$('.discount').keyup(function () {
    if ($('.discount_type').val() == 'percentage') {
        if ($('.discount').val() > 100) {
            $('.discount').attr('max', 100);
            $('#submit_btn').attr('disabled', true);
            $('.error').html('<small class="text-danger">You cannot set percantage more then 100</small>')
        } else {
            $('.discount').removeAttr('max');
            $('#submit_btn').attr('disabled', false);
            $('.error').html('')
        }
    } else {
        $('.discount').removeAttr('max');
        $('#submit_btn').attr('disabled', false);
        $('.error').html('')
    }
});

// select 2 js select countries
$(".country_list").select2({
    ajax: {
        url: base_url + 'admin/product/get_countries_data',
        type: "GET",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                search: params.term, // search term
            };
        },
        processResults: function (response) {
            return {
                results: response
            };
        },
        cache: true
    },
    minimumInputLength: 1,
    theme: 'bootstrap4',
    placeholder: 'Search for countries',
});

$("#city_list").select2({
    ajax: {
        url: base_url + 'admin/area/get_cities',
        type: "GET",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                search: params.term, // search term
            };
        },
        processResults: function (response) {
            return {
                results: response
            };
        },
        cache: true
    },

    minimumInputLength: 1,
    theme: 'bootstrap4',
    placeholder: 'Search for cities',
})
$("#zipcode_list").select2({
    ajax: {
        url: base_url + 'admin/area/get_zipcode_list',
        type: "GET",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                search: params.term, // search term
            };
        },
        processResults: function (response) {
            return {
                results: response
            };
        },
        cache: true
    },

    minimumInputLength: 1,
    theme: 'bootstrap4',
    placeholder: 'Search for cities',
})

$(".country_list").select2({
    ajax: {
        url: base_url + 'seller/product/get_countries_data',
        type: "GET",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                search: params.term, // search term
            };
        },
        processResults: function (response) {
            return {
                results: response
            };
        },
        cache: true
    },
    minimumInputLength: 1,
    theme: 'bootstrap4',
    placeholder: 'Search for countries',
});

//bonus_type
$(document).on('change', '.bonus_type', function (e, data) {
    e.preventDefault();
    var sort_type_val = $(this).val();
    if (sort_type_val == 'fixed_amount_per_order' && sort_type_val != ' ') {
        $('.fixed_amount_per_order').removeClass('d-none');
    } else {
        $('.fixed_amount_per_order').addClass('d-none');
    }
    if (sort_type_val == 'percentage_per_order' && sort_type_val != ' ') {
        $('.percentage_per_order').removeClass('d-none');
    } else {
        $('.percentage_per_order').addClass('d-none');
    }
});
//type
$(document).on('change', '.type', function (e, data) {
    e.preventDefault();
    var sort_type_val = $(this).val();
    if (sort_type_val == 'place_order' && sort_type_val != ' ') {
        $('.place_order').removeClass('d-none');
    } else {
        $('.place_order').addClass('d-none');
    }
    if (sort_type_val == 'settle_cashback_discount' && sort_type_val != ' ') {
        $('.settle_cashback_discount').removeClass('d-none');
    } else {
        $('.settle_cashback_discount').addClass('d-none');
    }
    if (sort_type_val == 'settle_seller_commission' && sort_type_val != ' ') {
        $('.settle_seller_commission').removeClass('d-none');
    } else {
        $('.settle_seller_commission').addClass('d-none');
    }
    if (sort_type_val == 'customer_order_received' && sort_type_val != ' ') {
        $('.customer_order_received').removeClass('d-none');
    } else {
        $('.customer_order_received').addClass('d-none');
    }
    if (sort_type_val == 'customer_order_processed' && sort_type_val != ' ') {
        $('.customer_order_processed').removeClass('d-none');
    } else {
        $('.customer_order_processed').addClass('d-none');
    }
    if (sort_type_val == 'customer_order_shipped' && sort_type_val != ' ') {
        $('.customer_order_shipped').removeClass('d-none');
    } else {
        $('.customer_order_shipped').addClass('d-none');
    }
    if (sort_type_val == 'customer_order_delivered' && sort_type_val != ' ') {
        $('.customer_order_delivered').removeClass('d-none');
    } else {
        $('.customer_order_delivered').addClass('d-none');
    }
    if (sort_type_val == 'customer_order_cancelled' && sort_type_val != ' ') {
        $('.customer_order_cancelled').removeClass('d-none');
    } else {
        $('.customer_order_cancelled').addClass('d-none');
    }
    if (sort_type_val == 'customer_order_returned' && sort_type_val != ' ') {
        $('.customer_order_returned').removeClass('d-none');
    } else {
        $('.customer_order_returned').addClass('d-none');
    }
    if (sort_type_val == 'delivery_boy_order_deliver' && sort_type_val != ' ') {
        $('.delivery_boy_order_deliver').removeClass('d-none');
    } else {
        $('.delivery_boy_order_deliver').addClass('d-none');
    }
    if (sort_type_val == 'wallet_transaction' && sort_type_val != ' ') {
        $('.wallet_transaction').removeClass('d-none');
    } else {
        $('.wallet_transaction').addClass('d-none');
    }
    if (sort_type_val == 'ticket_status' && sort_type_val != ' ') {
        $('.ticket_status').removeClass('d-none');
    } else {
        $('.ticket_status').addClass('d-none');
    }
    if (sort_type_val == 'ticket_message' && sort_type_val != ' ') {
        $('.ticket_message').removeClass('d-none');
    } else {
        $('.ticket_message').addClass('d-none');
    }
    if (sort_type_val == 'bank_transfer_receipt_status' && sort_type_val != ' ') {
        $('.bank_transfer_receipt_status').removeClass('d-none');
    } else {
        $('.bank_transfer_receipt_status').addClass('d-none');
    }
    if (sort_type_val == 'bank_transfer_proof' && sort_type_val != ' ') {
        $('.bank_transfer_proof').removeClass('d-none');
    } else {
        $('.bank_transfer_proof').addClass('d-none');
    }

});

//custom-notification-Module
$(document).on('click', '.delete_custom_notification', function () {
    var id = $(this).data('id');
    var t = this;
    Swal.fire({
        title: 'Are You Sure !',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: base_url + 'admin/custom_notification/delete_custom_notification',
                    type: 'GET',
                    data: {
                        'id': id
                    },
                    dataType: 'json',
                })
                    .done(function (result, textStatus) {
                        if (result['error'] == false) {
                            Swal.fire('Deleted!', result['message'], 'success');
                            $('table').bootstrapTable('refresh');
                        } else {
                            Swal.fire('Oops...', result['message'], 'warning');
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
            });
        },
        allowOutsideClick: false
    });
});

var noti_user_id = 0;
$('#select_user_id').on('change', function () {
    noti_user_id = ($('#select_user_id').val());
});

$('.search_user').each(function () {
    $(this).select2({
        ajax: {
            url: base_url + 'admin/customer/search_user',
            type: "GET",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term, // search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        },
        minimumInputLength: 1,
        theme: 'bootstrap4',
        placeholder: 'Search for countries',
    });
});

$(document).on('click', '.delete-product-faq', function () {
    var faq_id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'admin/product_faqs/delete_product_faq',
                    data: {
                        id: faq_id
                    },
                    dataType: 'json'
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('table').bootstrapTable('refresh');
                            csrfName = response['csrfName'];
                            csrfHash = response['csrfHash'];
                        } else {
                            Swal.fire('Oops...', response.message, 'warning');
                            $('table').bootstrapTable('refresh');
                            csrfName = response['csrfName'];
                            csrfHash = response['csrfHash'];
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                        csrfName = response['csrfName'];
                        csrfHash = response['csrfHash'];
                    });
            });
        },
        allowOutsideClick: false
    });
});
$(document).on('click', '.delete-seller-product-faq', function () {
    var faq_id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'seller/product_faqs/delete_product_faq',
                    data: {
                        id: faq_id
                    },
                    dataType: 'json'
                })
                    .done(function (response, textStatus) {
                        if (response.error == false) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('table').bootstrapTable('refresh');
                            csrfName = response['csrfName'];
                            csrfHash = response['csrfHash'];
                        } else {
                            Swal.fire('Oops...', response.message, 'warning');
                            $('table').bootstrapTable('refresh');
                            csrfName = response['csrfName'];
                            csrfHash = response['csrfHash'];
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                        csrfName = response['csrfName'];
                        csrfHash = response['csrfHash'];
                    });
            });
        },
        allowOutsideClick: false
    });
});
$(".search_product").select2({
    ajax: {
        url: base_url + 'seller/product/get_product_data',
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                search: params.term, // search term
                page: params.page
            };
        },
        processResults: function (response, params) {
            params.page = params.page || 1;

            return {
                results: response.rows,
                pagination: {
                    more: (params.page * 30) < response.total
                }
            };
        },
        cache: true
    },
    escapeMarkup: function (markup) {
        return markup;
    },
    minimumInputLength: 1,
    templateResult: formatRepo,
    templateSelection: formatRepoSelection,
    theme: 'bootstrap4',
    placeholder: 'Search for products'
});
searchable_zipcodes();
setTimeout(function () {
    $('.edit-modal-lg').unblock();
}, 2000);

function faqParams(p) {
    return {
        "user_id": $('#user_id').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
// custom_notification_message

$(document).ready(function () {
    $(".hashtag").click(function () {
        var txt = $.trim($(this).text());
        var box = $("#text-box");
        box.val(box.val() + txt);
    });
    $(".hashtag_input").click(function () {
        var txt = $.trim($(this).text());
        var box = $("#update_title");
        box.val(box.val() + txt);
    });
});

$(document).on('show.bs.modal', '#product-faqs-modal', function (event) {
    var triggerElement = $(event.relatedTarget);
    current_selected_image = triggerElement;
    var id = $(current_selected_image).data('id');
    var existing_url = $(this).find('#product-faqs-table').data('url');

    if (existing_url.indexOf('?') > -1) {
        var temp = $(existing_url).text().split('?');
        var new_url = temp[0] + '?product_id=' + id;
    } else {
        var new_url = existing_url + '?product_id=' + id;
    }
    $('#product-faqs-table').bootstrapTable('refreshOptions', {
        url: new_url,
    });
});

$(document).on('click', '.edit_order_refund', function () {
    var order_item_id = $(this).data('order_item_id');
    var txn_id = $(this).data('txn_id');
    var txn_amount = $(this).data('txn_amount');
    $('#transaction_id').val(txn_id);
    $('#txn_amount').val(txn_amount);
    $('#item_id').val(order_item_id);
});

$('#refund_form').on('click', function (e) {
    e.preventDefault();
    var txn_id = $('#transaction_id').val();
    var txn_amount = $('#txn_amount').val();
    var item_id = $('#item_id').val();
    $.ajax({
        type: 'POST',
        data: {
            'txn_id': txn_id,
            'txn_amount': txn_amount,
            'item_id': item_id,
            [csrfName]: csrfHash,
        },
        url: base_url + 'admin/orders/refund_payment',
        dataType: 'json',
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result['error'] == false) {
                iziToast.success({
                    message: result['message'],
                });
            } else {
                iziToast.error({
                    message: result['message'],
                });
            }
        }
    });
});

function salesReport(index, row) {
    var html = []
    var indexs = 0

    $.each(row, function (key, value) {
        var columns = $("th:eq(" + (indexs + 1) + ")").data("field")
        if (columns != undefined) {
            html.push('<p><b>' + columns + ' :</b> ' + row[columns] + '</p>')
            indexs++;
        }
    })
    return html;
}

// google translate

function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'en'
    }, 'google_translate_element');
}

// send admin notification
$(document).ready(function () {
    setInterval(function () {
        $.ajax({
            type: 'GET',
            url: base_url + 'admin/home/get_notification',
            dataType: 'json',
            success: function (result) {
                var html = '';
                html += '<a href="" id="notification_count" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg"><i class="fas fa-bell"></i><span class="badge badge-danger navbar-badge order_notification">' + result.count_notifications + '</span></a>';
                $('#refresh_notification').html(html);
            }
        });
    }, 30000);
});

$(document).on('click', '#notification_count', function (e, rows) {
    e.preventDefault();
    $.ajax({
        type: 'GET',
        url: base_url + 'admin/home/new_notification_list',
        dataType: 'json',
        success: function (result) {
            var html = '';
            var beep;
            var seconds_ago;
            var time;
            $.each(result.notifications, function (i, a) {
                beep = (a.read_by && a.read_by == 0) ? '<span><i class="fa fa-certificate ml-3 text-orange text-sm"></i></span>' : "";
                seconds_ago = a.date_sent;

                html += '  <a href=" ' + base_url + 'admin/orders/edit_orders' + '?edit_id=' + a.type_id + '&noti_id=' + a.id + '"\ class="dropdown-item">\
                            <div class="media">\
                                <div class="media-body">\
                                    <h3 class="dropdown-item-title">' + a.title + beep + '</h3>\
                                    <p class="text-sm">' + a.message + '</p>\
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i>' + seconds_ago + '</p>\
                                </div>\
                            </div>\
                        </a>\
                <div class="dropdown-divider"></div>';
            });

            html += '<a href="' + base_url + 'admin/Notification_settings/manage_system_notifications' + '" class="dropdown-item dropdown-footer">See All Messages</a>';

            $('#list').html(html).addClass("show");
            $('#notification_count').on('click', function (e) {
                $("#list").removeClass("show");
            });
        }
    });
});

$(document).ready(function () {
    $('.edit-modal-lg').on('shown.bs.modal', function (e) {
        if ($(".textarea").length > 0) {
            tinymce.init({
                selector: '.textarea',
                plugins: [
                    'a11ychecker', 'advlist', 'advcode', 'advtable', 'autolink', 'checklist', 'export', 'forecolor backcolor',
                    'lists', 'link', 'image', 'charmap', 'preview', 'code', 'anchor', 'searchreplace', 'visualblocks',
                    'powerpaste', 'fullscreen', 'formatpainter', 'insertdatetime', 'media', 'image', 'spellchecker', 'directionality', 'fullscreen', 'table', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | image media | code fullscreen| formatpainter casechange blocks fontsize | bold italic forecolor backcolor | ' +
                    'alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist checklist outdent indent | removeformat | ltr rtl |a11ycheck table help',

                font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
                image_uploadtab: false,
                images_upload_url: base_url + "admin/media/upload",
                relative_urls: false,
                remove_script_host: false,
                file_picker_types: 'image media',
                media_poster: false,
                media_alt_source: false,

                file_picker_callback: function (callback, value, meta) {
                    if (meta.filetype == "media" || meta.filetype == "image") {
                        const input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.setAttribute('accept', 'image/* audio/* video/*');

                        input.addEventListener('change', (e) => {
                            const file = e.target.files[0];

                            var reader = new FileReader();
                            var fd = new FormData();
                            var files = file;
                            fd.append("documents[]", files);
                            fd.append('filetype', meta.filetype);
                            // fd.append(csrfName, csrfHash);

                            var filename = "";
                            // AJAX
                            jQuery.ajax({
                                url: base_url + "admin/media/upload",
                                type: "post",
                                data: fd,
                                contentType: false,
                                processData: false,
                                async: false,
                                success: function (response) {
                                    var response = jQuery.parseJSON(response)
                                    filename = response.file_name;
                                }
                            });

                            reader.onload = function (e) {
                                callback(base_url + "uploads/media/2022/" + filename);
                            };
                            // console.log(reader);
                            reader.readAsDataURL(file);
                        });
                        input.click();
                    }
                },
                setup: function (editor) {
                    editor.on("change keyup", function (e) {
                        //tinyMCE.triggerSave(); // updates all instances
                        editor.save(); // updates this instance's textarea
                        $(editor.getElement()).trigger('change'); // for garlic to detect change
                    });
                }
            });
        }
    });
    tinymce.init({
        selector: '.addr_editor',
        menubar: true,
        plugins: [
            'a11ychecker', 'advlist', 'advcode', 'advtable', 'autolink', 'checklist', 'export', 'forecolor backcolor',
            'lists', 'link', 'image', 'charmap', 'preview', 'code', 'anchor', 'searchreplace', 'visualblocks',
            'powerpaste', 'fullscreen', 'formatpainter', 'insertdatetime', 'media', 'image', 'spellchecker', 'directionality', 'fullscreen', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | image media | code fullscreen| formatpainter casechange blocks fontsize | bold italic forecolor backcolor | ' +
            'alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist checklist outdent indent | removeformat | ltr rtl |a11ycheck table help',

        font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
        image_uploadtab: false,
        images_upload_url: base_url + "admin/media/upload",
        relative_urls: false,
        remove_script_host: false,
        file_picker_types: 'image media',
        media_poster: false,
        media_alt_source: false,

        file_picker_callback: function (callback, value, meta) {
            if (meta.filetype == "media" || meta.filetype == "image") {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/* audio/* video/*');

                input.addEventListener('change', (e) => {
                    const file = e.target.files[0];

                    var reader = new FileReader();
                    var fd = new FormData();
                    var files = file;
                    fd.append("documents[]", files);
                    fd.append('filetype', meta.filetype);
                    fd.append(csrfName, csrfHash);

                    var filename = "";
                    // AJAX
                    jQuery.ajax({
                        url: base_url + "admin/media/upload",
                        type: "post",
                        data: fd,
                        contentType: false,
                        processData: false,
                        async: false,
                        success: function (response) {
                            var response = jQuery.parseJSON(response)
                            filename = response.file_name;
                        }
                    });

                    reader.onload = function (e) {
                        callback(base_url + "uploads/media/2022/" + filename);
                    };
                    // console.log(reader);
                    reader.readAsDataURL(file);

                });
                input.click();
            }
        },
        setup: function (editor) {
            editor.on("change keyup", function (e) {
                //tinyMCE.triggerSave(); // updates all instances
                editor.save(); // updates this instance's textarea
                $(editor.getElement()).trigger('change'); // for garlic to detect change
            });
        }
    });
});

// select 2 js select brands
$(".admin_brand_list").select2({
    ajax: {
        url: base_url + 'admin/product/get_brands_data',
        type: "GET",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                search: params.term, // search term
            };
        },
        processResults: function (response) {
            return {
                results: response
            };
        },
        cache: true
    },
    minimumInputLength: 1,
    theme: 'bootstrap4',
    placeholder: 'Search for brands',
});

// select 2 js select brands
$(".brand_list").select2({
    ajax: {
        url: base_url + 'seller/product/get_brands_data',
        type: "GET",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                search: params.term, // search term
            };
        },
        processResults: function (response) {
            return {
                results: response
            };
        },
        cache: true
    },
    minimumInputLength: 1,
    theme: 'bootstrap4',
    placeholder: 'Search for brands',
});


$(document).on('click', '.delete-brand', function () {
    var brand_id = $(this).data('id');
    Swal.fire({
        title: 'Are You Sure!',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'admin/brand/delete_brand',
                    data: {
                        id: brand_id
                    },
                    dataType: 'json'
                })
                    .done(function (response, textStatus) {
                        if (response.error == true) {
                            Swal.fire('Deleted!', 'Brand deleted successfully', 'success');
                            $('table').bootstrapTable('refresh');
                            csrfName = response['csrfName'];
                            csrfHash = response['csrfHash'];
                        } else {
                            Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                            $('table').bootstrapTable('refresh');
                            csrfName = response['csrfName'];
                            csrfHash = response['csrfHash'];
                        }

                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                        csrfName = response['csrfName'];
                        csrfHash = response['csrfHash'];
                    });
            });
        },
        allowOutsideClick: false
    });
});

$(document).on('click', '#add_attribute_value', function (e) {
    e.preventDefault();
    load_attribute_section();

});

$(document).on('change', '.swatche_type', function () {
    if ($(this).val() == '1') {
        $(this).siblings('.color_picker').show();
        $(this).siblings('.upload_media').hide();
        $(this).siblings('.grow').hide();
    }
    if ($(this).val() == "2") {
        $(this).siblings(".color_picker").hide();
        $(this).siblings(".color_picker").attr('name', null);
        $(this).siblings(".upload_media").show();
        $(this).siblings(".grow").show();
    }
    if ($(this).val() == "0") {
        $(".color_picker").hide();
        $(".upload_media").hide();
        $('.grow').hide();
    }
});

function load_attribute_section() {

    var html = ' <div class="form-group  row">' +
        '<div class="col-sm-4">' +
        '<input type="text" step="any"  class="form-control"  placeholder="Enter Attribute Value" name="attribute_value[]" >' +
        '</div>' +
        '<div class="col-sm-4">' +
        '<select class="form-control swatche_type"  name="swatche_type[]">' +
        '<option value="0"> Default </option>' +
        '<option value="1"> Color </option >' +
        '<option value="2"> Image </option >' +
        '</select >' +
        '<input type="color" class="form-control color_picker" id="swatche_value" name="swatche_value[]" style="display: none;">' +
        '<a style="display: none;" class="uploadFile img btn btn-primary text-white btn-sm upload_media" data-input="swatche_value[]" name="attribute_img[]" data-isremovable="0" data-is-multiple-uploads-allowed="0" data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class="fa fa-upload"></i> Upload</a></div>' +
        '<div class="col-sm-2"> ' +
        '<button type="button" class="btn btn-tool remove_attribute_section" > <i class="text-danger far fa-times-circle fa-2x "></i> </button>' +
        '</div>' +
        '<div class="container-fluid row image-upload-section">' +
        '<div style="display: none;" class="shadow p-3 mb-5 bg-white rounded m-4 text-center grow">' +
        '<div class="image-upload-div"><img class="img-fluid mb-2 image" src="" alt="Image Not Found"></div>' +
        '<input type="hidden" value="">' +
        '</div>' +
        '</div>' +
        '</div>';

    $('#attribute_section').append(html);

    $('.swatche_type').each(function () {
        $('.swatche_type').select2({
            theme: 'bootstrap4',
            width: $('.swatche_type').data('width') ? $('.swatche_type').data('width') : $('.swatche_type').hasClass('w-100') ? '100%' : 'style',
            placeholder: $('.swatche_type').data('placeholder'),
            allowClear: Boolean($('.swatche_type').data('allow-clear')),
        });
    });
}

$(document).on('click', '.remove_attribute_section', function () {
    $(this).closest('.row').remove();
});

// $(".system_timezone").select2({
//     theme: 'bootstrap4',
//     width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
// }); width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
// });// Header colour change
((window, document, Math) => {
    const ctx = document.createElement("canvas").getContext("2d");
    const currentColor = {
        r: 0,
        g: 0,
        b: 0,
        h: 0,
        s: 0,
        v: 0,
        a: 1,
    };
    let picker,
        colorArea,
        colorAreaDims,
        colorMarker,
        colorPreview,
        colorValue,
        clearButton,
        hueSlider,
        hueMarker,
        alphaSlider,
        alphaMarker,
        currentEl,
        currentFormat,
        oldColor;

    // Default settings
    const settings = {
        el: "[data-coloris]",
        parent: null,
        theme: "light",
        wrap: true,
        margin: 2,
        format: "hex",
        formatToggle: false,
        swatches: [],
        alpha: true,
        clearButton: {
            show: false,
            label: "Clear",
        },
        a11y: {
            open: "Open color picker",
            close: "Close color picker",
            marker: "Saturation: {s}. Brightness: {v}.",
            hueSlider: "Hue slider",
            alphaSlider: "Opacity slider",
            input: "Color value field",
            format: "Color format",
            swatch: "Color swatch",
            instruction: "Saturation and brightness selector. Use up, down, left and right arrow keys to select.",
        },
    };

    /**
     * Configure the color picker.
     * @param {object} options Configuration options.
     */
    function configure(options) {
        if (typeof options !== "object") {
            return;
        }

        for (const key in options) {
            switch (key) {
                case "el":
                    bindFields(options.el);
                    if (options.wrap !== false) {
                        wrapFields(options.el);
                    }
                    break;
                case "parent":
                    settings.parent = document.querySelector(options.parent);
                    if (settings.parent) {
                        settings.parent.appendChild(picker);
                    }
                    break;
                case "theme":
                    picker.className = `clr-picker clr-${options.theme
                        .split("-")
                        .join(" clr-")}`;
                    break;
                case "margin":
                    options.margin *= 1;
                    settings.margin = !isNaN(options.margin) ?
                        options.margin :
                        settings.margin;
                    break;
                case "wrap":
                    if (options.el && options.wrap) {
                        wrapFields(options.el);
                    }
                    break;
                case "format":
                    settings.format = options.format;
                    break;
                case "formatToggle":
                    getEl("clr-format").style.display = options.formatToggle ?
                        "block" :
                        "none";
                    if (options.formatToggle) {
                        settings.format = "auto";
                    }
                    break;
                case "swatches":
                    if (Array.isArray(options.swatches)) {
                        const swatches = [];

                        options.swatches.forEach((swatch, i) => {
                            swatches.push(
                                `<button id="clr-swatch-${i}" aria-labelledby="clr-swatch-label clr-swatch-${i}" style="color: ${swatch};">${swatch}</button>`
                            );
                        });

                        if (swatches.length) {
                            getEl("clr-swatches").innerHTML = `<div>${swatches.join(
                                ""
                            )}</div>`;
                        }
                    }
                    break;
                case "alpha":
                    settings.alpha = !!options.alpha;
                    picker.setAttribute("data-alpha", settings.alpha);
                    break;
                case "clearButton":
                    let display = "none";

                    if (options.clearButton.show) {
                        display = "block";
                    }

                    if (options.clearButton.label) {
                        clearButton.innerHTML = options.clearButton.label;
                    }

                    clearButton.style.display = display;
                    break;
                case "a11y":
                    const labels = options.a11y;
                    let update = false;

                    if (typeof labels === "object") {
                        for (const label in labels) {
                            if (labels[label] && settings.a11y[label]) {
                                settings.a11y[label] = labels[label];
                                update = true;
                            }
                        }
                    }

                    if (update) {
                        const openLabel = getEl("clr-open-label");
                        const swatchLabel = getEl("clr-swatch-label");

                        openLabel.innerHTML = settings.a11y.open;
                        swatchLabel.innerHTML = settings.a11y.swatch;
                        colorPreview.setAttribute("aria-label", settings.a11y.close);
                        hueSlider.setAttribute("aria-label", settings.a11y.hueSlider);
                        alphaSlider.setAttribute("aria-label", settings.a11y.alphaSlider);
                        colorValue.setAttribute("aria-label", settings.a11y.input);
                        colorArea.setAttribute("aria-label", settings.a11y.instruction);
                    }
            }
        }
    }

    /**
     * Bind the color picker to input fields that match the selector.
     * @param {string} selector One or more selectors pointing to input fields.
     */
    function bindFields(selector) {
        // Show the color picker on click on the input fields that match the selector
        addListener(document, "click", selector, (event) => {
            const parent = settings.parent;
            const coords = event.target.getBoundingClientRect();
            const scrollY = window.scrollY;
            let reposition = {
                left: false,
                top: false,
            };
            let offset = {
                x: 0,
                y: 0,
            };
            let left = coords.x;
            let top = scrollY + coords.y + coords.height + settings.margin;

            currentEl = event.target;
            oldColor = currentEl.value;
            currentFormat = getColorFormatFromStr(oldColor);
            picker.classList.add("clr-open");

            const pickerWidth = picker.offsetWidth;
            const pickerHeight = picker.offsetHeight;

            // If the color picker is inside a custom container
            // set the position relative to it
            if (parent) {
                const style = window.getComputedStyle(parent);
                const marginTop = parseFloat(style.marginTop);
                const borderTop = parseFloat(style.borderTopWidth);

                offset = parent.getBoundingClientRect();
                offset.y += borderTop + scrollY;
                left -= offset.x;
                top -= offset.y;

                if (left + pickerWidth > parent.clientWidth) {
                    left += coords.width - pickerWidth;
                    reposition.left = true;
                }

                if (top + pickerHeight > parent.clientHeight - marginTop) {
                    top -= coords.height + pickerHeight + settings.margin * 2;
                    reposition.top = true;
                }

                top += parent.scrollTop;

                // Otherwise set the position relative to the whole document
            } else {
                if (left + pickerWidth > document.documentElement.clientWidth) {
                    left += coords.width - pickerWidth;
                    reposition.left = true;
                }

                if (
                    top + pickerHeight - scrollY >
                    document.documentElement.clientHeight
                ) {
                    top = scrollY + coords.y - pickerHeight - settings.margin;
                    reposition.top = true;
                }
            }

            picker.classList.toggle("clr-left", reposition.left);
            picker.classList.toggle("clr-top", reposition.top);
            picker.style.left = `${left}px`;
            picker.style.top = `${top}px`;
            colorAreaDims = {
                width: colorArea.offsetWidth,
                height: colorArea.offsetHeight,
                x: picker.offsetLeft + colorArea.offsetLeft + offset.x,
                y: picker.offsetTop + colorArea.offsetTop + offset.y,
            };

            setColorFromStr(oldColor);
            colorValue.focus({
                preventScroll: true,
            });
        });

        // Update the color preview of the input fields that match the selector
        addListener(document, "input", selector, (event) => {
            const parent = event.target.parentNode;

            // Only update the preview if the field has been previously wrapped
            if (parent.classList.contains("clr-field")) {
                parent.style.color = event.target.value;
            }
        });
    }

    /**
     * Wrap the linked input fields in a div that adds a color preview.
     * @param {string} selector One or more selectors pointing to input fields.
     */
    function wrapFields(selector) {
        document.querySelectorAll(selector).forEach((field) => {
            const parentNode = field.parentNode;

            if (!parentNode.classList.contains("clr-field")) {
                const wrapper = document.createElement("div");

                wrapper.innerHTML = `<button aria-labelledby="clr-open-label"></button>`;
                parentNode.insertBefore(wrapper, field);
                wrapper.setAttribute("class", "clr-field");
                wrapper.style.color = field.value;
                wrapper.appendChild(field);
            }
        });
    }

    /**
     * Close the color picker.
     * @param {boolean} [revert] If true, revert the color to the original value.
     */
    function closePicker(revert) {
        if (currentEl) {
            // Revert the color to the original value if needed
            if (revert && oldColor !== currentEl.value) {
                currentEl.value = oldColor;

                // Trigger an "input" event to force update the thumbnail next to the input field
                currentEl.dispatchEvent(
                    new Event("input", {
                        bubbles: true,
                    })
                );
            }

            if (oldColor !== currentEl.value) {
                currentEl.dispatchEvent(
                    new Event("change", {
                        bubbles: true,
                    })
                );
            }

            picker.classList.remove("clr-open");
            currentEl.focus({
                preventScroll: true,
            });
            currentEl = null;
        }
    }

    /**
     * Set the active color from a string.
     * @param {string} str String representing a color.
     */
    function setColorFromStr(str) {
        const rgba = strToRGBA(str);
        const hsva = RGBAtoHSVA(rgba);

        updateMarkerA11yLabel(hsva.s, hsva.v);
        updateColor(rgba, hsva);

        // Update the UI
        hueSlider.value = hsva.h;
        picker.style.color = `hsl(${hsva.h}, 100%, 50%)`;
        hueMarker.style.left = `${(hsva.h / 360) * 100}%`;

        colorMarker.style.left = `${(colorAreaDims.width * hsva.s) / 100}px`;
        colorMarker.style.top = `${colorAreaDims.height - (colorAreaDims.height * hsva.v) / 100
            }px`;

        alphaSlider.value = hsva.a * 100;
        alphaMarker.style.left = `${hsva.a * 100}%`;
    }

    /**
     * Guess the color format from a string.
     * @param {string} str String representing a color.
     * @return {string} The color format.
     */
    function getColorFormatFromStr(str) {
        const format = str.substring(0, 3).toLowerCase();

        if (format === "rgb" || format === "hsl") {
            return format;
        }

        return "hex";
    }

    /**
     * Copy the active color to the linked input field.
     * @param {number} [color] Color value to override the active color.
     */
    function pickColor(color) {
        if (currentEl) {
            currentEl.value = color !== undefined ? color : colorValue.value;
            currentEl.dispatchEvent(
                new Event("input", {
                    bubbles: true,
                })
            );
        }
    }

    /**
     * Set the active color based on a specific point in the color gradient.
     * @param {number} x Left position.
     * @param {number} y Top position.
     */
    function setColorAtPosition(x, y) {
        const hsva = {
            h: hueSlider.value * 1,
            s: (x / colorAreaDims.width) * 100,
            v: 100 - (y / colorAreaDims.height) * 100,
            a: alphaSlider.value / 100,
        };
        const rgba = HSVAtoRGBA(hsva);

        updateMarkerA11yLabel(hsva.s, hsva.v);
        updateColor(rgba, hsva);
        pickColor();
    }

    /**
     * Update the color marker's accessibility label.
     * @param {number} saturation
     * @param {number} value
     */
    function updateMarkerA11yLabel(saturation, value) {
        let label = settings.a11y.marker;

        saturation = saturation.toFixed(1) * 1;
        value = value.toFixed(1) * 1;
        label = label.replace("{s}", saturation);
        label = label.replace("{v}", value);
        colorMarker.setAttribute("aria-label", label);
    }

    //
    /**
     * Get the pageX and pageY positions of the pointer.
     * @param {object} event The MouseEvent or TouchEvent object.
     * @return {object} The pageX and pageY positions.
     */
    function getPointerPosition(event) {
        return {
            pageX: event.changedTouches ? event.changedTouches[0].pageX : event.pageX,
            pageY: event.changedTouches ? event.changedTouches[0].pageY : event.pageY,
        };
    }

    /**
     * Move the color marker when dragged.
     * @param {object} event The MouseEvent object.
     */
    function moveMarker(event) {
        const pointer = getPointerPosition(event);
        let x = pointer.pageX - colorAreaDims.x;
        let y = pointer.pageY - colorAreaDims.y;

        if (settings.parent) {
            y += settings.parent.scrollTop;
        }

        x = x < 0 ? 0 : x > colorAreaDims.width ? colorAreaDims.width : x;
        y = y < 0 ? 0 : y > colorAreaDims.height ? colorAreaDims.height : y;

        colorMarker.style.left = `${x}px`;
        colorMarker.style.top = `${y}px`;

        setColorAtPosition(x, y);

        // Prevent scrolling while dragging the marker
        event.preventDefault();
        event.stopPropagation();
    }

    /**
     * Move the color marker when the arrow keys are pressed.
     * @param {number} offsetX The horizontal amount to move.
     * * @param {number} offsetY The vertical amount to move.
     */
    function moveMarkerOnKeydown(offsetX, offsetY) {
        const x = colorMarker.style.left.replace("px", "") * 1 + offsetX;
        const y = colorMarker.style.top.replace("px", "") * 1 + offsetY;

        colorMarker.style.left = `${x}px`;
        colorMarker.style.top = `${y}px`;

        setColorAtPosition(x, y);
    }

    /**
     * Update the color picker's input field and preview thumb.
     * @param {Object} rgba Red, green, blue and alpha values.
     * @param {Object} [hsva] Hue, saturation, value and alpha values.
     */
    function updateColor(rgba = {}, hsva = {}) {
        let format = settings.format;

        for (const key in rgba) {
            currentColor[key] = rgba[key];
        }

        for (const key in hsva) {
            currentColor[key] = hsva[key];
        }

        const hex = RGBAToHex(currentColor);
        const opaqueHex = hex.substring(0, 7);

        colorMarker.style.color = opaqueHex;
        alphaMarker.parentNode.style.color = opaqueHex;
        alphaMarker.style.color = hex;
        colorPreview.style.color = hex;

        // Force repaint the color and alpha gradients as a workaround for a Google Chrome bug
        colorArea.style.display = "none";
        colorArea.offsetHeight;
        colorArea.style.display = "";
        alphaMarker.nextElementSibling.style.display = "none";
        alphaMarker.nextElementSibling.offsetHeight;
        alphaMarker.nextElementSibling.style.display = "";

        if (format === "mixed") {
            format = currentColor.a === 1 ? "hex" : "rgb";
        } else if (format === "auto") {
            format = currentFormat;
        }

        switch (format) {
            case "hex":
                colorValue.value = hex;
                break;
            case "rgb":
                colorValue.value = RGBAToStr(currentColor);
                break;
            case "hsl":
                colorValue.value = HSLAToStr(HSVAtoHSLA(currentColor));
                break;
        }

        // Select the current format in the format switcher
        document.querySelector(`.clr-format [value="${format}"]`).checked = true;
    }

    /**
     * Set the hue when its slider is moved.
     */
    function setHue() {
        const hue = hueSlider.value * 1;
        const x = colorMarker.style.left.replace("px", "") * 1;
        const y = colorMarker.style.top.replace("px", "") * 1;

        picker.style.color = `hsl(${hue}, 100%, 50%)`;
        hueMarker.style.left = `${(hue / 360) * 100}%`;

        setColorAtPosition(x, y);
    }

    /**
     * Set the alpha when its slider is moved.
     */
    function setAlpha() {
        const alpha = alphaSlider.value / 100;

        alphaMarker.style.left = `${alpha * 100}%`;
        updateColor({
            a: alpha,
        });
        pickColor();
    }

    /**
     * Convert HSVA to RGBA.
     * @param {object} hsva Hue, saturation, value and alpha values.
     * @return {object} Red, green, blue and alpha values.
     */
    function HSVAtoRGBA(hsva) {
        const saturation = hsva.s / 100;
        const value = hsva.v / 100;
        let chroma = saturation * value;
        let hueBy60 = hsva.h / 60;
        let x = chroma * (1 - Math.abs((hueBy60 % 2) - 1));
        let m = value - chroma;

        chroma = chroma + m;
        x = x + m;
        m = m;

        const index = Math.floor(hueBy60) % 6;
        const red = [chroma, x, m, m, x, chroma][index];
        const green = [x, chroma, chroma, x, m, m][index];
        const blue = [m, m, x, chroma, chroma, x][index];

        return {
            r: Math.round(red * 255),
            g: Math.round(green * 255),
            b: Math.round(blue * 255),
            a: hsva.a,
        };
    }

    /**
     * Convert HSVA to HSLA.
     * @param {object} hsva Hue, saturation, value and alpha values.
     * @return {object} Hue, saturation, lightness and alpha values.
     */
    function HSVAtoHSLA(hsva) {
        const value = hsva.v / 100;
        const lightness = value * (1 - hsva.s / 100 / 2);
        let saturation;

        if (lightness > 0 && lightness < 1) {
            saturation = Math.round(
                ((value - lightness) / Math.min(lightness, 1 - lightness)) * 100
            );
        }

        return {
            h: hsva.h,
            s: saturation || 0,
            l: Math.round(lightness * 100),
            a: hsva.a,
        };
    }

    /**
     * Convert RGBA to HSVA.
     * @param {object} rgba Red, green, blue and alpha values.
     * @return {object} Hue, saturation, value and alpha values.
     */
    function RGBAtoHSVA(rgba) {
        const red = rgba.r / 255;
        const green = rgba.g / 255;
        const blue = rgba.b / 255;
        const xmax = Math.max(red, green, blue);
        const xmin = Math.min(red, green, blue);
        const chroma = xmax - xmin;
        const value = xmax;
        let hue = 0;
        let saturation = 0;

        if (chroma) {
            if (xmax === red) {
                hue = (green - blue) / chroma;
            }
            if (xmax === green) {
                hue = 2 + (blue - red) / chroma;
            }
            if (xmax === blue) {
                hue = 4 + (red - green) / chroma;
            }
            if (xmax) {
                saturation = chroma / xmax;
            }
        }

        hue = Math.floor(hue * 60);

        return {
            h: hue < 0 ? hue + 360 : hue,
            s: Math.round(saturation * 100),
            v: Math.round(value * 100),
            a: rgba.a,
        };
    }

    /**
     * Parse a string to RGBA.
     * @param {string} str String representing a color.
     * @return {object} Red, green, blue and alpha values.
     */
    function strToRGBA(str) {
        const regex =
            /^((rgba)|rgb)[\D]+([\d.]+)[\D]+([\d.]+)[\D]+([\d.]+)[\D]*?([\d.]+|$)/i;
        let match, rgba;

        // Default to black for invalid color strings
        ctx.fillStyle = "#000";

        // Use canvas to convert the string to a valid color string
        ctx.fillStyle = str;
        match = regex.exec(ctx.fillStyle);

        if (match) {
            rgba = {
                r: match[3] * 1,
                g: match[4] * 1,
                b: match[5] * 1,
                a: match[6] * 1,
            };
        } else {
            match = ctx.fillStyle
                .replace("#", "")
                .match(/.{2}/g)
                .map((h) => parseInt(h, 16));
            rgba = {
                r: match[0],
                g: match[1],
                b: match[2],
                a: 1,
            };
        }

        return rgba;
    }

    /**
     * Convert RGBA to Hex.
     * @param {object} rgba Red, green, blue and alpha values.
     * @return {string} Hex color string.
     */
    function RGBAToHex(rgba) {
        let R = rgba.r.toString(16);
        let G = rgba.g.toString(16);
        let B = rgba.b.toString(16);
        let A = "";

        if (rgba.r < 16) {
            R = "0" + R;
        }

        if (rgba.g < 16) {
            G = "0" + G;
        }

        if (rgba.b < 16) {
            B = "0" + B;
        }

        if (settings.alpha && rgba.a < 1) {
            const alpha = (rgba.a * 255) | 0;
            A = alpha.toString(16);

            if (alpha < 16) {
                A = "0" + A;
            }
        }

        return "#" + R + G + B + A;
    }

    /**
     * Convert RGBA values to a CSS rgb/rgba string.
     * @param {object} rgba Red, green, blue and alpha values.
     * @return {string} CSS color string.
     */
    function RGBAToStr(rgba) {
        if (!settings.alpha || rgba.a === 1) {
            return `rgb(${rgba.r}, ${rgba.g}, ${rgba.b})`;
        } else {
            return `rgba(${rgba.r}, ${rgba.g}, ${rgba.b}, ${rgba.a})`;
        }
    }

    /**
     * Convert HSLA values to a CSS hsl/hsla string.
     * @param {object} hsla Hue, saturation, lightness and alpha values.
     * @return {string} CSS color string.
     */
    function HSLAToStr(hsla) {
        if (!settings.alpha || hsla.a === 1) {
            return `hsl(${hsla.h}, ${hsla.s}%, ${hsla.l}%)`;
        } else {
            return `hsla(${hsla.h}, ${hsla.s}%, ${hsla.l}%, ${hsla.a})`;
        }
    }

    /**
     * Init the color picker.
     */
    //Custom header colour
    function init() {
        // Render the UI
        picker = document.createElement("div");
        picker.setAttribute("id", "clr-picker");
        picker.className = "clr-picker";
        picker.innerHTML =
            `<input id="clr-color-value" class="clr-color" type="text" value="" aria-label="${settings.a11y.input}">` +
            `<div id="clr-color-area" class="clr-gradient" role="application" aria-label="${settings.a11y.instruction}">` +
            '<div id="clr-color-marker" class="clr-marker" tabindex="0"></div>' +
            "</div>" +
            '<div class="clr-hue">' +
            `<input id="clr-hue-slider" type="range" min="0" max="360" step="1" aria-label="${settings.a11y.hueSlider}">` +
            '<div id="clr-hue-marker"></div>' +
            "</div>" +
            '<div class="clr-alpha">' +
            `<input id="clr-alpha-slider" type="range" min="0" max="100" step="1" aria-label="${settings.a11y.alphaSlider}">` +
            '<div id="clr-alpha-marker"></div>' +
            "<span></span>" +
            "</div>" +
            '<div id="clr-format" class="clr-format">' +
            '<fieldset class="clr-segmented">' +
            `<legend>${settings.a11y.format}</legend>` +
            '<input id="clr-f1" type="radio" name="clr-format" value="hex">' +
            '<label for="clr-f1">Hex</label>' +
            '<input id="clr-f2" type="radio" name="clr-format" value="rgb">' +
            '<label for="clr-f2">RGB</label>' +
            '<input id="clr-f3" type="radio" name="clr-format" value="hsl">' +
            '<label for="clr-f3">HSL</label>' +
            "<span></span>" +
            "</fieldset>" +
            "</div>" +
            '<div id="clr-swatches" class="clr-swatches"></div>' +
            `<button id="clr-clear" class="clr-clear">${settings.clearButton.label}</button>` +
            `<button id="clr-color-preview" class="clr-preview" aria-label="${settings.a11y.close}"></button>` +
            `<span id="clr-open-label" hidden>${settings.a11y.open}</span>` +
            `<span id="clr-swatch-label" hidden>${settings.a11y.swatch}</span>`;

        // Append the color picker to the DOM
        document.body.appendChild(picker);

        // Reference the UI elements
        colorArea = getEl("clr-color-area");
        colorMarker = getEl("clr-color-marker");
        clearButton = getEl("clr-clear");
        colorPreview = getEl("clr-color-preview");
        colorValue = getEl("clr-color-value");
        hueSlider = getEl("clr-hue-slider");
        hueMarker = getEl("clr-hue-marker");
        alphaSlider = getEl("clr-alpha-slider");
        alphaMarker = getEl("clr-alpha-marker");

        // Bind the picker to the default selector
        bindFields(settings.el);
        wrapFields(settings.el);

        addListener(picker, "mousedown", (event) => {
            picker.classList.remove("clr-keyboard-nav");
            event.stopPropagation();
        });

        addListener(colorArea, "mousedown", (event) => {
            addListener(document, "mousemove", moveMarker);
        });

        addListener(colorArea, "touchstart", (event) => {
            document.addEventListener("touchmove", moveMarker, {
                passive: false,
            });
        });

        addListener(colorMarker, "mousedown", (event) => {
            addListener(document, "mousemove", moveMarker);
        });

        addListener(colorMarker, "touchstart", (event) => {
            document.addEventListener("touchmove", moveMarker, {
                passive: false,
            });
        });

        addListener(colorValue, "change", (event) => {
            setColorFromStr(colorValue.value);
            pickColor();
        });

        addListener(clearButton, "click", (event) => {
            pickColor("");
            closePicker();
        });

        addListener(colorPreview, "click", (event) => {
            pickColor();
            closePicker();
        });

        addListener(document, "click", ".clr-format input", (event) => {
            currentFormat = event.target.value;
            updateColor();
            pickColor();
        });

        addListener(picker, "click", ".clr-swatches button", (event) => {
            setColorFromStr(event.target.textContent);
            pickColor();
        });

        addListener(document, "mouseup", (event) => {
            document.removeEventListener("mousemove", moveMarker);
        });

        addListener(document, "touchend", (event) => {
            document.removeEventListener("touchmove", moveMarker);
        });

        addListener(document, "mousedown", (event) => {
            picker.classList.remove("clr-keyboard-nav");
            closePicker();
        });

        addListener(document, "keydown", (event) => {
            if (event.key === "Escape") {
                closePicker(true);
            } else if (event.key === "Tab") {
                picker.classList.add("clr-keyboard-nav");
            }
        });

        addListener(document, "click", ".clr-field button", (event) => {
            event.target.nextElementSibling.dispatchEvent(
                new Event("click", {
                    bubbles: true,
                })
            );
        });

        addListener(colorMarker, "keydown", (event) => {
            const movements = {
                ArrowUp: [0, -1],
                ArrowDown: [0, 1],
                ArrowLeft: [-1, 0],
                ArrowRight: [1, 0],
            };

            if (Object.keys(movements).indexOf(event.key) !== -1) {
                moveMarkerOnKeydown(...movements[event.key]);
                event.preventDefault();
            }
        });

        addListener(colorArea, "click", moveMarker);
        addListener(hueSlider, "input", setHue);
        addListener(alphaSlider, "input", setAlpha);
    }

    /**
     * Shortcut for getElementById to optimize the minified JS.
     * @param {string} id The element id.
     * @return {object} The DOM element with the provided id.
     */
    function getEl(id) {
        return document.getElementById(id);
    }

    /**
     * Shortcut for addEventListener to optimize the minified JS.
     * @param {object} context The context to which the listener is attached.
     * @param {string} type Event type.
     * @param {(string|function)} selector Event target if delegation is used, event handler if not.
     * @param {function} [fn] Event handler if delegation is used.
     */
    function addListener(context, type, selector, fn) {
        const matches =
            Element.prototype.matches || Element.prototype.msMatchesSelector;

        // Delegate event to the target of the selector
        if (typeof selector === "string") {
            context.addEventListener(type, (event) => {
                if (matches.call(event.target, selector)) {
                    fn.call(event.target, event);
                }
            });

            // If the selector is not a string then it's a function
            // in which case we need regular event listener
        } else {
            fn = selector;
            context.addEventListener(type, fn);
        }
    }

    /**
     * Call a function only when the DOM is ready.
     * @param {function} fn The function to call.
     * @param {array} args Arguments to pass to the function.
     */
    function DOMReady(fn, args) {
        args = args !== undefined ? args : [];

        if (document.readyState !== "loading") {
            fn(...args);
        } else {
            document.addEventListener("DOMContentLoaded", () => {
                fn(...args);
            });
        }
    }

    // Polyfill for Nodelist.forEach
    if (
        NodeList !== undefined &&
        NodeList.prototype &&
        !NodeList.prototype.forEach
    ) {
        NodeList.prototype.forEach = Array.prototype.forEach;
    }

    // Expose the color picker to the global scope
    window.Coloris = (() => {
        const methods = {
            set: configure,
            wrap: wrapFields,
            close: closePicker,
        };

        function Coloris(options) {
            DOMReady(() => {
                if (options) {
                    if (typeof options === "string") {
                        bindFields(options);
                    } else {
                        configure(options);
                    }
                }
            });
        }

        for (const key in methods) {
            Coloris[key] = (...args) => {
                DOMReady(methods[key], args);
            };
        }

        return Coloris;
    })();

    // Init the color picker when the DOM is ready
    DOMReady(init);
})(window, document, Math);
Coloris({
    el: ".coloris",
});


$('.get_blog_category').select2({
    ajax: {
        url: base_url + 'admin/blogs/get_blog_category',
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                search: params.term // search term
            }
        },
        processResults: function (response) {
            return {
                results: response
            }
        },
        cache: true
    },
    minimumInputLength: 1,
    theme: 'bootstrap4',
    placeholder: 'Search for products'
})

//forgot password

$(document).on('click', "#forgot_password_link", function (e) {
    e.preventDefault();
    $('.auth-modal').find('header a').removeClass('active');
    $('#forgot_password_div').removeClass('hide');
    $('#recaptcha-container-2').html('');
    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container-2');
    window.recaptchaVerifier.render().then(function (widgetId) {
        grecaptcha.reset(widgetId);
    });
    var telInput = $("#forgot_password_number");
    // initialise plugin
    telInput.intlTelInput({

        allowExtensions: true,
        formatOnDisplay: true,
        autoFormat: true,
        autoHideDialCode: true,
        autoPlaceholder: true,
        defaultCountry: "in",
        ipinfoToken: "yolo",

        nationalMode: false,
        numberType: "MOBILE",
        preferredCountries: ['in', 'ae', 'qa', 'om', 'bh', 'kw', 'ma'],
        preventInvalidNumbers: true,
        separateDialCode: true,
        initialCountry: "auto",
        geoIpLookup: function (callback) {
            $.get("https://ipinfo.io", function () { }, "jsonp").always(function (resp) {
                var countryCode = (resp && resp.country) ? resp.country : "";
                callback(countryCode);
            });
        },
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"
    });
});

function is_user_exist(phone_number = '') {
    if (phone_number == '') {
        var phoneNumber = $('#phone-number').val();
    } else {
        var phoneNumber = phone_number;
    }
    var response;
    $.ajax({
        type: 'POST',
        async: false,
        url: base_url + 'auth/verify_user',
        data: { mobile: phoneNumber, [csrfName]: csrfHash },
        dataType: 'json',
        success: function (result) {
            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];
            response = result
        }
    });
    return response;
}


$(document).ready(function () {
    console.log("here in load event");
    $('#recaptcha-container-2').html('');
    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container-2');
    window.recaptchaVerifier.render().then(function (widgetId) {
        grecaptcha.reset(widgetId);
    });
    var telInput = $("#forgot_password_number"),
            errorMsg = $("#error-msg"),
            validMsg = $("#valid-msg");

        // initialise plugin
        telInput.intlTelInput({

            allowExtensions: true,
            formatOnDisplay: true,
            autoFormat: true,
            autoHideDialCode: true,
            autoPlaceholder: true,
            defaultCountry: "in",
            ipinfoToken: "yolo",

            nationalMode: false,
            numberType: "MOBILE",
            preferredCountries: ['in', 'ae', 'qa', 'om', 'bh', 'kw', 'ma'],
            preventInvalidNumbers: true,
            separateDialCode: true,
            initialCountry: "auto",
            geoIpLookup: function (callback) {
                $.get("https://ipinfo.io", function () { }, "jsonp").always(function (resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"
        });

        var reset = function () {
            telInput.removeClass("error");
            errorMsg.addClass("hide");
            validMsg.addClass("hide");
        };
});

$(document).on('submit', '#send_forgot_password_otp_form', function (e) {
    e.preventDefault();
    var send_otp_btn = $('#forgot_password_send_otp_btn').html();
    $('#forgot_password_send_otp_btn').html('Please Wait...').attr('disabled', true);
    var phoneNumber = $('.selected-dial-code').html() + $('#forgot_password_number').val();
    var appVerifier = window.recaptchaVerifier;
    var response = is_user_exist($('#forgot_password_number').val());
    if (response.error == false) {
        $('#forgot_pass_error_box').html("You have not registered using this number.");
        $('#forgot_password_send_otp_btn').html(send_otp_btn).attr('disabled', false);
    } else {
        firebase.auth().signInWithPhoneNumber(phoneNumber, appVerifier).then(function (confirmationResult) {
            resetRecaptcha();
            $('#verify_forgot_password_otp_form').removeClass('d-none');
            $('#send_forgot_password_otp_form').hide();
            $('#forgot_pass_error_box').html(response.message);
            $('#forgot_password_send_otp_btn').html(send_otp_btn).attr('disabled', false);
            $(document).on('submit', '#verify_forgot_password_otp_form', function (e) {
                e.preventDefault();
                var reset_pass_btn_html = $('#reset_password_submit_btn').html();
                var code = $('#forgot_password_otp').val();
                var formdata = new FormData(this);
                var url = base_url + "admin/home/reset-password";
                $('#reset_password_submit_btn').html('Please Wait...').attr('disabled', true);
                confirmationResult.confirm(code).then(function (result) {
                    formdata.append(csrfName, csrfHash);
                    formdata.append('mobile', $('#forgot_password_number').val());
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: formdata,
                        processData: false,
                        contentType: false,
                        cache: false,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#reset_password_submit_btn').html('Please Wait...').attr('disabled', true);
                        },
                        success: function (result) {
                            csrfName = result.csrfName;
                            csrfHash = result.csrfHash;
                            $('#reset_password_submit_btn').html(reset_pass_btn_html).attr('disabled', false);
                            $("#set_password_error_box").html(result.message).show();
                            if (result.error == false) {
                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000)
                            }
                        }
                    });
                }).catch(function (error) {
                    $('#reset_password_submit_btn').html(reset_pass_btn_html).attr('disabled', false);
                    $("#set_password_error_box").html("Invalid OTP. Please Enter Valid OTP").show();
                });
            });
        }).catch(function (error) {
            $("#forgot_pass_error_box").html(error.message).show();
            $('#forgot_password_send_otp_btn').html(send_otp_btn).attr('disabled', false);
            resetRecaptcha();
        });
    }
})

function resetRecaptcha() {
    return window.recaptchaVerifier.render().then(function (widgetId) {
        grecaptcha.reset(widgetId);
    });
}
$(".auth-modal").on('click', 'header a', function (event) {
    event.preventDefault();
    window.signingIn = true;
    var index = $(this).index();
    $(this).addClass('active').siblings('a').removeClass('active');
    $(this).parents("div").find("section").eq(index).removeClass('hide').siblings('section').addClass('hide');

    if ($(this).index() === 0) {
        $(".auth-modal .iziModal-content .icon-close").css('background', '#ddd');
    } else {
        $(".auth-modal .iziModal-content .icon-close").attr('style', '');
    }
});

// $(document).on('opening', '.auth-modal', function (e) {
//     $(this).removeClass('d-none');
//     e.preventDefault();
//     closeNav();
//     $('.send-otp-form')[0].reset();
//     $('.send-otp-form').show();
//     $('.sign-up-form')[0].reset();
//     $('.sign-up-form').hide();
//     $('#is-user-exist-error').html('');
//     $('#sign-up-error').html('');

//     $('#recaptcha-container').html('');
//     window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
//     window.recaptchaVerifier.render().then(function (widgetId) {
//         grecaptcha.reset(widgetId);
//     });

//     var telInput = $("#phone-number"),
//         errorMsg = $("#error-msg"),
//         validMsg = $("#valid-msg");

//     // initialise plugin
//     telInput.intlTelInput({

//         allowExtensions: true,
//         formatOnDisplay: true,
//         autoFormat: true,
//         autoHideDialCode: true,
//         autoPlaceholder: true,
//         defaultCountry: "in",
//         ipinfoToken: "yolo",

//         nationalMode: false,
//         numberType: "MOBILE",
//         preferredCountries: ['in', 'ae', 'qa', 'om', 'bh', 'kw', 'ma'],
//         preventInvalidNumbers: true,
//         separateDialCode: true,
//         initialCountry: "auto",
//         geoIpLookup: function (callback) {
//             $.get("https://ipinfo.io", function () { }, "jsonp").always(function (resp) {
//                 var countryCode = (resp && resp.country) ? resp.country : "";
//                 callback(countryCode);
//             });
//         },
//         utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"
//     });

//     var reset = function () {
//         telInput.removeClass("error");
//         errorMsg.addClass("hide");
//         validMsg.addClass("hide");
//     };

//     // on blur: validate
//     telInput.blur(function () {
//         reset();
//         if ($.trim(telInput.val())) {
//             if (telInput.intlTelInput("isValidNumber")) {
//                 validMsg.removeClass("hide");
//             } else {
//                 telInput.addClass("error");
//                 errorMsg.removeClass("hide");
//             }
//         }
//     });
//     // on keyup / change flag: reset
//     telInput.on("keyup change", reset);


// });

"use strict";

/* POS - Point of Sale system starts */
if (document.readyState == 'loading') {
    document.addEventListener('DOMContentLoaded', ready);
} else {
    ready();
}

function ready() {
    display_cart();
    var addToCartButtons = document.getElementsByClassName('shop-item-button');
    for (var i = 0; i < addToCartButtons.length; i++) {
        var button = addToCartButtons[i];
        button.addEventListener('click', add_to_cart);
    }
}

function purchaseClicked() {
    var cartItems = document.getElementsByClassName('cart-items')[0];
    while (cartItems.hasChildNodes()) {
        cartItems.removeChild(cartItems.firstChild);
    }
    update_cart_total();
    update_final_cart_total();

}
$(document).on("click", ".remove-cart-item", function(e) {
    console.log(e);
    console.log(e.delegateTarget.activeElement.classList.value.includes("remove-cart-item"));
    e.preventDefault();
    if (e.delegateTarget.activeElement.classList.value.includes("remove-cart-item")) {
        iziToast.error({
            message: ['Product removed from cart'],
        });
        var variant_id = $(this).data("variant_id");
        $(this).parent().parent().remove();
        var cart = localStorage.getItem("cart");
        cart = (localStorage.getItem("cart") !== null) ? JSON.parse(cart) : null;
        if (cart) {
            var new_cart = cart.filter(function(item) { return item.variant_id != variant_id });
            localStorage.setItem("cart", JSON.stringify(new_cart));
            display_cart();
        }
    }
});

$(document).on("click", ".cart-quantity-input", function(e) {

    var operation = $(this).data("operation");
    var variant_id = $(this).siblings().val();
    var input = (operation == "plus") ? $(this).siblings()[1] : $(this).siblings()[2];
    var qty = $(this).parent().siblings('.item-quantity').find('.cart-quantity-input').val();
    var qty = parseInt(input.value, 10);
    var data = input.value = (operation == "minus") ? qty - 1 : qty + 1;

    update_quantity(data, variant_id);
});

$(document).on("change", ".cart-quantity-input-new", function(e) {

    var variant_id = $(this).siblings().val();
    var quantity = $(this).val();
    var data = quantity;

    update_quantity(data, variant_id);
});

function update_quantity(data, variant_id) {
    if (isNaN(data) || data <= 0) {
        data = 1;
    }
    var cart = localStorage.getItem("cart");
    cart = (localStorage.getItem("cart") !== null) ? JSON.parse(cart) : null;
    if (cart) {
        var i = cart.map(i => i.variant_id).indexOf(variant_id);
        cart[i].quantity = data;
        localStorage.setItem("cart", JSON.stringify(cart));
        display_cart();
    }
}

function SafeParseFloat(val) {
    if (isNaN(val)) {
        if ((val = val.match(/([0-9\.,]+\d)/g))) {
            val = val[0].replace(/[^\d\.]+/g, '');
        }
    }
    return parseFloat(val);
}

function add_to_cart(e) {

    var cartRow = document.createElement('div');
    cartRow.classList.add('cart-row');
    var button = e.target;
    var shopItem = button.parentElement.parentElement;
    var variant_dropdown = shopItem.children[0].children[4];
    var display_price = variant_dropdown.value;
    var product_id = shopItem.getElementsByClassName('shop-item-id')[0].innerText;
    var variant_id = variant_dropdown.options[variant_dropdown.selectedIndex].dataset.variant_id;
    var seller_id = shopItem.getElementsByClassName('shop-item-partner-id')[0].innerText
    var variant_values = variant_dropdown.options[variant_dropdown.selectedIndex].dataset.variant_values;
    var special_price = variant_dropdown.options[variant_dropdown.selectedIndex].dataset.special_price;
    var price = variant_dropdown.options[variant_dropdown.selectedIndex].dataset.price;
    var title = shopItem.getElementsByClassName('shop-item-title')[0].innerText;
    var image = shopItem.getElementsByClassName('item-image')[0].src;
    /* create JSON array object */
    var cart_item = { "product_id": product_id.trim(), "seller_id": seller_id.trim(), "variant_id": variant_id, "title": title, "variant": variant_values, "image": image, "display_price": display_price.trim(), "quantity": 1, "special_price": special_price, "price": price };
    var cart = localStorage.getItem("cart");
    cart = (localStorage.getItem("cart") !== null) ? JSON.parse(cart) : null;
    if (cart !== null && cart !== undefined) {
        if (cart.find((item) => item.variant_id === variant_id)) {
            alert("This item is already present in your cart");
            return;
        } else {
            iziToast.success({
                message: ['Product added to cart'],
            });
        }
        cart.push(cart_item);
    } else {
        cart = [cart_item];
    }
    localStorage.setItem("cart", JSON.stringify(cart));
    display_cart();
}

function display_cart() {
    var cart = localStorage.getItem("cart");
    cart = (localStorage.getItem("cart") !== null) ? JSON.parse(cart) : null;
    var currency = $(".cart-total-price").attr('data-currency');
    var cartRowContents = "";
    if (cart !== null && cart.length > 0) {
        cart.forEach((item) => {
            cartRowContents += `
            <div class="container">
                <div class="row">
                    <div class="col">
                    <div class="cart-image">
                        <img class="mr-4" src="${item.image}">
                    </div>
                        <p class="cart-item-title ">${item.title}</p>
                    </div>
                    <div class="col">
                        <span class="cart-price">${currency + parseFloat(item.display_price).toLocaleString()}</span>
                    </div>
                    <div class="col">
                    <div class="input-group-prepend">
                        <input type="hidden" class="product-variant" name="variant_ids[]" type="number" value=${item.variant_id}>
                        <button type="button" class="cart-quantity-input btn btn-xs btn-secondary" data-operation="plus">+</button>
                            <input class="cart-quantity-input-new form-control text-center p-0" name="quantity[]"value="${item.quantity}">
                        <button type="button" class="cart-quantity-input btn btn-xs btn-secondary" data-operation="minus">-</button>
                        </div>
                    </div>
                    <div class="col">
                        <button class="btn btn-xs btn-danger remove-cart-item"  data-variant_id=${item.variant_id}><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>`
        })
    } else {
        cartRowContents = `
        <div class="container">
            <div class="row">
                <div class="col mt-4 d-flex justify-content-center text-primary h5">No items in cart</div>
            </div>
        </div>`;
    }
    $(".cart-items").html(cartRowContents);
    update_cart_total();
    update_final_cart_total();
}

function get_cart_total() {
    var cart = localStorage.getItem("cart");
    var cart = (cart !== null && cart !== undefined) ? JSON.parse(cart) : null;
    var cart_total = 0;
    if (cart !== null && cart !== undefined) {
        cart_total = cart.reduce((cart_total, item) =>
            cart_total + (parseFloat(item.display_price) * parseFloat(item.quantity)), 0);
    }
    var currency = $('#cart-total-price').attr('data-currency');
    var total = { "currency": currency, "cart_total": cart_total, "cart_total_formated": parseFloat(cart_total).toLocaleString() }
    return total;
}

function update_cart_total() {
    var total = get_cart_total();
    $('#cart-total-price').html(total.currency + "" + total.cart_total_formated);
    return;
}


//final total
function get_final_cart_total() {
    var cart = localStorage.getItem("cart");
    var cart = (cart !== null && cart !== undefined) ? JSON.parse(cart) : null;
    var cart_total = 0;
    if (cart !== null && cart !== undefined) {
        cart_total = cart.reduce((cart_total, item) =>
            cart_total + (parseFloat(item.display_price) * parseFloat(item.quantity)), 0);
    }
    var currency = $('#cart-total-price').attr('data-currency');
    var total = {
        "currency": currency,
        "total": cart_total,
        "cart_total_formated": parseFloat(cart_total).toLocaleString()
    }
    return total;
}

$(document).on("change", ".delivery_charge_service", function(e) {
    e.preventDefault();
    update_final_cart_total();
    return;
});

$(document).on("change", ".discount_service", function(e) {
    update_final_cart_total();
    return;
});

function update_final_cart_total() {
    var cart = get_cart_total();
    var sub_total = cart.cart_total;
    var delivery_charges = $(".delivery_charge_service").val();
    var discount = $("#discount_service").val();
    var final_total = sub_total;
    var currency = $('#cart-total-price').attr('data-currency');

    if (delivery_charges != 0 && delivery_charges != null) {
        final_total = parseFloat(final_total) + parseFloat(delivery_charges);
    }

    if (discount != 0 && discount != null) {
        final_total = parseFloat(final_total) - parseFloat(discount);
    }

    var res = {
        "currency": currency,
        "total": final_total,
        "cart_total": parseFloat(final_total).toLocaleString()
    }
    $('#final_total').html(final_total.currency + "" + final_total.cart_total_formated);
    $('#final_total').html(res.currency + "" + res.cart_total);
    return;
}

$(".final_total").on("click", function() {
    final_total();
    update_final_cart_total();
});
$(".final_total").on("change", function() {
    final_total();
    update_final_cart_total();
});


// get products
function get_products(category_id = '', limit = 2, offset = 0, search_parameter = '') {
    $.ajax({
        type: 'GET',
        url: `${base_url}seller/point_of_sale/get_products?category_id=${category_id}&limit=${limit}&offset=${offset}&search=${search_parameter}`,
        dataType: 'json',
        beforeSend: function() {
            $("#get_products").html(`<div class="text-center" style='min-height:450px;' ><h4>Please wait.. . loading products..</h4></div>`);
        },
        success: function(data) {
            if (data.error == false) {
                $("#total_products").val(data.products.total);
                $('#get_products').empty();
                display_products(data.products);
                var total = $("#total_products").val();
                var current_page = $("#current_page").val();
                var limit = $("#limit").val();
                var search_parameter = $("#search_products").val();
                paginate(total, current_page, limit, search_parameter);
            } else {
                $('#get_products').html(data.message);
                $('#get_products').empty();
            }

        }
    });
}

// display products
function display_products(products) {
    var display_products = '';
    var i;
    var j;
    var products = products.product;
    if (products !== null && products.length > 0) {
        for (i = 0; i < products.length; i++) {
            // console.log(products[i]);
            display_products += '<div class="text-center col-lg-4">' +
                '<div class="shop-item m-3">' +
                '<span class="d-none shop-item-id">' +
                ' <b>' + products[i].id + '</b>' +
                ' </span>' +
                '<span class="shop-item-title ">' +
                ' <a href=' + base_url + 'seller/product/view_product?edit_id=' + products[i].id + ' target="_BLANK"><b class="text text-dark">' + products[i].name + '</b></a>' +
                ' </span>' +
                '<div class="shop-item-image d-flex justify-content-center align-item-center">' +
                '  <img class="item-image" src="' + products[i].image + '">' +
                '</div>' +
                '<span class="d-none shop-item-partner-id">' + products[i].seller_id + '</span>' +
                '<select class="form-control mt-4 product-variants variant_value" id="change">';
            var total_price = document.getElementById('cart-total-price');
            var currency = "";
            if ($('#cart-total-price').length) {
                currency = total_price.getAttribute('data-currency');
            }
            var variants = products[i]['variants'];
            for (j = 0; j < variants.length; j++) {
                var variant_values = (variants[j]['variant_values']) ? variants[j]['variant_values'] + ' - ' : "";

                var variant_price = variants[j]['special_price'] > 0 ? variants[j]['special_price'] : variants[j]['price'];
                display_products += '<option data-variant_values="' + variants[j]['variant_values'] + '" data-price="' + variants[j]['price'] + '" data-special_price="' + variants[j]['special_price'] + '" data-variant_id="' + variants[j]['id'] + '" value="' + variant_price + " " + '" class="shop-item-price" > ' + variant_values + currency + " " + parseFloat(variant_price).toLocaleString() + '</option > ';
            }
            display_products += '</select></div>' +
                ' <div class ="shop-item-details justify-content-center">' +
                ' <button class ="btn btn-xs btn-info shop-item-button p-2" onclick="add_to_cart(event)" type ="button">Add to Cart</button>' +
                ' </div>' +
                ' </div>' +
                '</div >' +
                '</div > ';
        }
        $('#get_products').append(display_products)
    } else {
        $("#get_products").html(`<div class="text-center" style='min-height:450px;' ><h4> No products available in this category...</h4></div>`);
    }
}
var session_user_id = $('#session_user_id').val()
var cart = localStorage.getItem('cart')
cart = (cart != null || cart == '') ? JSON.parse(cart) : ''
if (cart != '') {
    if (cart.find(item => item.seller_id != session_user_id)) {
        localStorage.removeItem('cart')
        display_cart()
    }
}
$(document).ready(function() {
    var category_id = $('#product_categories').val();
    var limit = $('#limit').val();
    var offset = $('#offset').val();
    get_products(category_id, limit, offset);
});

// category wise product change
$('#product_categories').on("change", function() {
    var category_id = $('#product_categories').val();
    var limit = $('#limit').val();
    $('#current_page').val("0");
    get_products(category_id, limit, 0);
});

$(document).ready(function() {
    $("#product_categories").on("change", function() {
        $("#get_products").empty();
    });
});

// transaction id input 
$(document).ready(function() {
    $('.transaction_id').hide();
    $('.payment_method_name').hide();
});

/* payment method selected event  */
$(".payment_method").on('click', function() {
    var payment_method = $(this).val();
    var exclude_txn_id = ["COD"];
    var include_payment_method_name = ["other"];

    if (exclude_txn_id.includes(payment_method)) {
        $(".transaction_id").hide();
    } else {
        $(".transaction_id").show();
    }

    if (include_payment_method_name.includes(payment_method)) {
        $('.payment_method_name').show();
    } else {
        $('.payment_method_name').hide();
    }
});

// select 2 js select user
$(".select_user").select2({
    ajax: {
        url: base_url + 'seller/point_of_sale/get_users',
        type: "GET",
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                search: params.term, // search term
            };
        },
        processResults: function(response) {
            return {
                results: response
            };
        },
        cache: true
    },
    minimumInputLength: 1,
    theme: 'bootstrap4',
    placeholder: 'Search for user',
});
// clear selected values in select2

$("#clear_user_search").on('click', function() { $(".select_user").empty(); });

// Register in pos

$(document).on('submit', '#register_form', function(e) {
    e.preventDefault();
    var name = $('#name').val();
    var mobile = $('#mobile').val();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        dataType: 'json',
        data: formData,
        processData: false,
        contentType: false,

        beforeSend: function() {
            $('#save-register-result-btn').html('Please Wait..');
            $('#save-register-result-btn').attr('disabled', true);
        },
        success: function(result) {
            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];
            if (result.error == false) {
                iziToast.success({
                    message: result.message,
                });
                $('#register_form')[0].reset();
            } else {
                iziToast.error({
                    message: result.message,
                });
            }
            $('#save-register-result-btn').html('Register').attr('disabled', false);
        }
    });
});

var pos_user_id = 0;
$('#select_user_id').on('change', function() {
    pos_user_id = ($('#select_user_id').val());
});

$('#pos_form').on('submit', function(e) {
    e.preventDefault();
    if (confirm('Are you sure? want to check out.')) {
        var cart = localStorage.getItem("cart");
        if (cart == null || !cart) {
            var message = "Please add items to cart";
            show_message("Oops!", message, "error");
            return;
        }
        // console.log(cart);
        var delivery_charges = $('.delivery_charge_service').val();
        if (!delivery_charges) {
            delivery_charges = '';
        }
        var discount = $('.discount_service').val();
        if (!discount) {
            discount = '';
        }
        // var cart = get_cart_total();
        // console.log(delivery_charges);
        var payment_method = $('.payment_method:checked').val();
        var self_pickup = ($('.self_pickup:checked').length > 0) ? $('.self_pickup:checked').val() : 0;

        if (!payment_method) {
            var message = "Please choose a payment method";
            show_message("Oops!", message, "error");
            return;
        }
        var txn_id = $('#transaction_id').val();
        if (!txn_id && payment_method != 'COD') {
            // txn_id = '';
            var message = "Please enter  transaction id";
            show_message("Oops!", message, "error");
            return;
        }
        var payment_method_name = $('#payment_method_name').val();
        if (!payment_method_name) {
            payment_method_name = '';
        }
        const request_body = {
            [csrfName]: csrfHash,
            data: cart,
            payment_method: payment_method,
            self_pickup: self_pickup,
            user_id: pos_user_id,
            txn_id: txn_id,
            delivery_charges: delivery_charges,
            discount: discount,
            payment_method_name: payment_method_name
        }
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: request_body,
            dataType: 'json',
            success: function(result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result.error == true) {
                    iziToast.error({
                        message: '<span>' + result.message + '</span> ',
                    });
                } else {
                    iziToast.success({
                        message: '<span style="text-transform:capitalize">' + result.message + '</span> ',
                    });
                    delete_cart_items();
                    setTimeout(function() { location.reload(); }, 600);
                }
            }
        });
    }
});

// Clear Cart

$(document).on("click", ".btn-clear_cart", function(e) {
    e.preventDefault();
    delete_cart_items();
});

function delete_cart_items() {
    localStorage.removeItem("cart");
    display_cart();
}

function show_message(prefix = "Great!", message, type = 'success') {
    Swal.fire(prefix, message, type);
}

function paginate(total, current_page, limit) {
    var number_of_pages = total / limit;
    var i = 0;
    var pagination = `<div class="row p-2">
    <div class="col-12">
        <div class="d-flex justify-content-center">
            <ul class="pagination mb-0">`;
    pagination += `<li class="page-item"><a class="page-link" href="javascript:prev_page()" >Previous</a></li>`;
    var active = "";
    while (i < number_of_pages) {
        active = (current_page == i) ? "active" : "";
        pagination += `<li class="page-item ${active}"><a class="page-link" href="javascript:go_to_page(${limit},${i})" >${++i}</a></li>`;
    }
    pagination += `<li class="page-item"><a class="page-link" href="javascript:next_page()">Next</a></li>
                </ul>
            </div>
        </div>
    </div>`;
    $(".pagination-container").html(pagination);
}

function go_to_page(limit, page_number) {
    var total = $("#total_products").val();
    var category_id = $("#product_categories").val();
    var offset = page_number * limit;

    get_products(category_id, limit, offset);
    paginate(total, page_number, limit);

    $("#limit").val(limit);
    $("#offset").val(offset);
    $("#current_page").val(page_number);
}

function prev_page() {
    var current_page = $("#current_page").val();
    var total = $("#total_products").val();
    var limit = $("#limit").val();
    var prev_page = parseFloat(current_page) - 1;

    if (prev_page >= 0) {
        go_to_page(limit, prev_page);
    }
}

function next_page() {
    var current_page = $("#current_page").val();
    var total = $("#total_products").val();
    var limit = $("#limit").val();

    var number_of_pages = total / limit;
    var next_page = parseFloat(current_page) + 1;

    if (next_page < number_of_pages) {
        go_to_page(limit, next_page);
    }
}

// search products 
$('#search_products').on('keyup', function(e) {
    e.preventDefault();
    var search = $(this).val();
    get_products('', 25, 0, search)
});

/* POS - Point of Sale system ends */
"use strict";
var custom_url = location.href;
var quickViewgalleryThumbs;
var mobile_image_swiper;
var quickViewgalleryTop;
var is_rtl = $('#body').data("is-rtl");
var mode = (is_rtl == 1) ? "right" : "left";
const is_loggedin = $('#is_loggedin').val();

//form-submit-event
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
})

function queryParams(p) {
    return {
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
$(document).on('submit', '.form-submit-event', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    var form_id = $(this).attr("id");
    var error_box = $('#error_box', this);
    var submit_btn = $(this).find('.submit_btn');
    var btn_html = $(this).find('.submit_btn').html();
    var btn_val = $(this).find('.submit_btn').val();
    var button_text = (btn_html != '' || btn_html != 'undefined') ? btn_html : btn_val;


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
            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];
            if (result['error'] == true) {
                error_box.addClass("rounded p-3 alert alert-danger").removeClass('d-none alert-success');
                error_box.show().delay(5000).fadeOut();
                error_box.html(result['message']);
                submit_btn.html(button_text);
                submit_btn.attr('disabled', false);
            } else {
                error_box.addClass("rounded p-3 alert alert-success").removeClass('d-none alert-danger');
                error_box.show().delay(3000).fadeOut();
                error_box.html(result['message']);
                submit_btn.html(button_text);
                submit_btn.attr('disabled', false);
                $('.form-submit-event')[0].reset();
                if (form_id == 'login_form') {
                    cart_sync();
                }
                setTimeout(function () { location.reload(); }, 600);

            }
        }
    });
});

/**
 * Set up UI event listeners and registering Firebase auth listeners.
 */
window.onload = function () {
    // Event bindings.
    document.getElementById('send-otp-form').addEventListener('submit', onSignInSubmit);
    document.getElementById('phone-number').addEventListener('keyup', updateSignInButtonUI);
    document.getElementById('phone-number').addEventListener('change', updateSignInButtonUI);
};
/**
 * Function called when clicking the Login/Logout button.
 */
function onSignInSubmit(e) {
    e.preventDefault();
    if (isPhoneNumberValid()) {
        $('#send-otp-button').html('Please Wait...');
        var response = is_user_exist();
        updateSignInButtonUI();
        if (response.error == true) {
            $('#is-user-exist-error').html(response.message);
            $('#send-otp-button').html('Send OTP');
        } else {
            window.signingIn = true;

            var phoneNumber = getPhoneNumberFromUserInput();
            var appVerifier = window.recaptchaVerifier;
            firebase.auth().signInWithPhoneNumber(phoneNumber, appVerifier).then(function (confirmationResult) {
                $('#send-otp-button').html('Send OTP');
                $('.send-otp-form').unblock();
                window.signingIn = false;
                updateSignInButtonUI();
                resetRecaptcha();
                $('#send-otp-form').hide();
                $('#otp_div').show();
                $('#verify-otp-form').removeClass('d-none');
                $(document).on('submit', '#verify-otp-form', function (e) {
                    e.preventDefault();
                    $("#registration-error").html('');
                    var code = $('#otp').val();
                    var formdata = new FormData(this);
                    var url = $(this).attr('action');
                    $('#register_submit_btn').html('Please Wait...').attr('disabled', true);
                    confirmationResult.confirm(code).then(function (result) {
                        formdata.append(csrfName, csrfHash);
                        formdata.append('mobile', $('#phone-number').val());
                        formdata.append('country_code', $('.selected-dial-code').text());
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: formdata,
                            processData: false,
                            contentType: false,
                            cache: false,
                            dataType: 'json',
                            beforeSend: function () {
                                $('#register_submit_btn').html('Please Wait...').attr('disabled', true);
                            },
                            success: function (result) {
                                csrfName = result.csrfName;
                                csrfHash = result.csrfHash;
                                $('#register_submit_btn').html('Submit').attr('disabled', false);
                                $("#registration-error").html(result.message).show();
                            }
                        });
                    }).catch(function (error) {
                        $('#register_submit_btn').html('Please Wait...').attr('disabled', true);
                        $("#registration-error").html("Invalid OTP. Please Enter Valid OTP").show();
                    });
                });
            }).catch(function (error) {
                window.signingIn = false;
                $("#is-user-exist-error").html(error.message).show();
                $('#send-otp-button').html('Send OTP');
                updateSignInButtonUI();
                resetRecaptcha();
            });
        }
    }
}
$(document).on('click', '#resend-otp', function (e) {
    e.preventDefault();
});
/**
 * Reads the phone number from the user input.
 */
function getPhoneNumberFromUserInput() {
    var countrycode = $('.selected-dial-code').html();
    var phonenumber = $('#phone-number').val();
    return countrycode + phonenumber;
}

/**
 * Returns true if the phone number is valid.
 */
function isPhoneNumberValid() {
    var pattern = /^\+[0-9\s\-\(\)]+$/;
    var phoneNumber = getPhoneNumberFromUserInput();
    return phoneNumber.search(pattern) !== -1;
}

/**
 * This resets the recaptcha widget.
 */
function resetRecaptcha() {
    return window.recaptchaVerifier.render().then(function (widgetId) {
        grecaptcha.reset(widgetId);
    });
}

/**
 * Updates the Sign-in button state depending on ReCaptcha and form values state.
 */
function updateSignInButtonUI() { }

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

$(document).on('submit', '.sign-up-form', function (e) {
    e.preventDefault();
    var countrycode = $('.selected-dial-code').html();
    $phonenumber = $('#phone-number').val();
    $username = $('input[name="username"]').val();
    $email = $('input[name="email"]').val();
    $passwd = $('input[name="password"]').val();

    $.ajax({
        type: 'POST',
        url: base_url + 'auth/register_user',
        data: { country_code: countrycode, mobile: $phonenumber, name: $username, email: $email, password: $passwd, [csrfName]: csrfHash },
        dataType: 'json',
        success: function (result) {
            if (result.error == true) {
                $('#sign-up-error').html('<span class="text-danger" >' + response.message + '</span>');
            }
        }
    });
});

function formatRepo(repo) {
    if (repo.loading) return repo.text;
    var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'><img src='" + repo.image_sm + "' /></div>" +
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'>" + repo.name + "</div>";

    if (repo.category_name) {
        markup += "<div class='select2-result-repository__description'> In " + repo.category_name + "</div>";
    }

    return markup;
}

function formatRepoSelection(repo) {
    return repo.name || repo.text;
}

var search_products = $(".search_product").select2({
    ajax: {
        url: base_url + 'home/get_products',
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
    minimumInputLength: 1,
    templateResult: formatRepo,
    templateSelection: formatRepoSelection,
    theme: 'adwitt',
    placeholder: 'Search for products'
});

search_products.on('select2:select', function (e) {
    var data = e.params.data;
    if (data.link != undefined && data.link != null) {
        window.location.href = data.link;
    }
});

$("#leftside-navigation .sub-menu > a").click(function (e) {
    $("#leftside-navigation ul ul").slideUp();

    (!$('#leftside-navigation .sub-menu > a').next().is(":visible")) ? $('#leftside-navigation .sub-menu > a').find('.arrow').removeClass('fa-angle-down').addClass('fa-angle-left') : '';
    $(this).find('.arrow').hasClass('fa-angle-left') ? $(this).find('.arrow').removeClass('fa-angle-left').addClass('fa-angle-down') : $(this).find('.arrow').removeClass('fa-angle-down').addClass('fa-angle-left');
    $(this).next().is(":visible") || $(this).next().slideDown();
    e.stopPropagation();
})

$('li.has-ul').click(function () {
    $(this).children('.sub-ul').slideToggle(500);
    $(this).toggleClass('active');
    event.preventDefault();
});


$('.add-to-fav-btn').on('click', function (e) {
    e.preventDefault();
    var formdata = new FormData();
    var product_id = $(this).data('product-id');
    var fav_btn = $(this);
    formdata.append(csrfName, csrfHash);
    formdata.append('product_id', product_id);
    $.ajax({
        type: 'POST',
        url: base_url + 'my-account/manage-favorites',
        data: formdata,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.error == true) {
                Toast.fire({
                    icon: 'error',
                    title: result.message
                });
            } else {
                if (fav_btn.hasClass('far')) {
                    fav_btn.removeClass('far').addClass('fa text-danger');
                } else {
                    fav_btn.removeClass('fa text-danger').addClass('far');
                    fav_btn.css('color', '#adadad');
                }
            }
        }
    });
});

$(document).on('click', '#add_to_favorite_btn', function (e) {
    e.preventDefault();
    var formdata = new FormData();
    var product_id = $(this).data('product-id');
    var fav_btn = $(this);
    var fav_btn_html = $(this).html();
    formdata.append(csrfName, csrfHash);
    formdata.append('product_id', product_id);
    $.ajax({
        type: 'POST',
        url: base_url + 'my-account/manage-favorites',
        data: formdata,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        beforeSend: function () {
            fav_btn.attr('disabled', true);
            fav_btn.find('span').text('Please wait');
        },
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            fav_btn.attr('disabled', false);
            fav_btn.html(fav_btn_html);
            if (result.error == true) {
                Toast.fire({
                    icon: 'error',
                    title: result.message
                });
            } else {
                if (fav_btn.hasClass('add-fav')) {
                    fav_btn.removeClass('add-fav').addClass('remove-fav');
                    fav_btn.find('span').text('Remove from Favorite');
                } else {
                    fav_btn.removeClass('remove-fav').addClass('add-fav');
                    fav_btn.find('span').text('Add to Favorite');
                }
            }
        }
    });
});

$(function () {
    /* Instantiating iziModal */
    $(".auth-modal").iziModal({
        overlayClose: false,
        overlayColor: 'rgba(0, 0, 0, 0.6)',
    });

    if ($('#user-review-images').length) {
        var review_title = "";
        review_title = $('#review-image-title').data("review-title");
        var id = $('#review-image-title').data("product-id");
        var u = 0;
        var d = 0;
        var image_data = "";
        $("#user-review-images").iziModal({
            overlayClose: false,
            overlayColor: 'rgba(0, 0, 0, 0.6)',
            title: review_title,
            headerColor: '#f44336c4',
            arrowKeys: false,
            fullscreen: true,
            onOpening: function (modal) {
                modal.startLoading();
                var limit = $('#review-image-title').data("review-limit");
                var offset = $('#review-image-title').data("review-offset");
                var reached_end = $('#review-image-title').data("reached-end");
                $('#load_more_div').html('<div id="load_more"></div>');
                if (reached_end == false) {
                    load_review_images(id, limit, offset);
                }

                modal.stopLoading();
            },
            onOpened: function () {
                $("div").bind('wheel', function (e) {
                    if ($('#load_more').length) {
                        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                            var id = $('#review-image-title').data("product-id");
                            var limit = $('#review-image-title').data("review-limit");
                            var offset = $('#review-image-title').data("review-offset");
                            var reached_end = $('#review-image-title').data("reached-end");
                            if (reached_end == false) {
                                load_review_images(id, limit, offset);
                            }
                        }
                    }
                });
            },
        });
    }

    if ($('#seller_info').length) {
        $("#seller_info").iziModal({
            overlayClose: true,
            overlayColor: 'rgba(0, 0, 0, 0.6)',
            title: "Sold By",
            headerColor: '#f44336c4',
            arrowKeys: false,
            fullscreen: true,
            onOpening: function (modal) {
                modal.startLoading();
                modal.stopLoading();
            }
        });
    }


    function load_review_images(product_id, limit, offset, target = '#user_image_data') {
        $('#review-image-title').data('review-offset', offset + limit);
        $.getJSON(base_url + 'products/get_rating?product_id=' + product_id + '&has_images=1&limit=' + limit + '&offset=' + offset, function (data) {
            $('#review-image-title').data('review-offset', offset + limit);
            image_data = "";
            var obj = 0;
            if (data.error == false) {
                for (var i = 0; i < data.data.product_rating.length; i++) {
                    obj = data.data.product_rating[i];
                    for (var j = 0; j < obj.images.length; j++) {
                        var obj3 = obj.images;
                        image_data += "<div class='review-box '><a href='" + obj3[j] + "' data-lightbox='review-images-12345' data-title='<font >" + obj.rating + " &#9733;</font></br>" + obj.user_name + "<br>" + obj.comment + "'><img src='" + obj3[j] + "' alt='Review Image'></a></div>";
                    }
                }
            } else {
                $('#review-image-title').data('reached-end', 'true');
            }
            $(target).append(image_data);
        });
    }

    $("#quick-view").iziModal({
        overlayClose: false,
        overlayColor: 'rgba(0, 0, 0, 0.6)',
        width: 1000,

        onOpening: function (modal) {
            modal.startLoading();
            $('#modal-product-tags').html('');
            $.getJSON(base_url + 'products/get-details/' + modal.$element.data('dataProductId'), function (data) {
                var total_images = 0;
                $('#modal-add-to-cart-button').attr('data-product-id', data.id);
                if (data.type == "simple_product" || data.type == "digital_product") {
                    $('#modal-add-to-cart-button').attr('data-product-variant-id', data.variants[0].id);
                } else {
                    $('#modal-add-to-cart-button').attr('data-product-variant-id', '');
                }
                if (data.minimum_order_quantity != 1 && data.minimum_order_quantity != '' && data.minimum_order_quantity != 'undefined') {
                    $(".in-num").attr({
                        "data-min": data.minimum_order_quantity // values (or variables) here
                    });
                    $(".minus").attr({
                        "data-min": data.minimum_order_quantity // values (or variables) here
                    });
                    $("#modal-add-to-cart-button").attr({
                        "data-min": data.minimum_order_quantity // values (or variables) here
                    });
                } else {
                    $(".in-num").attr({
                        "data-min": 1 // values (or variables) here
                    });
                    $(".minus").attr({
                        "data-min": 1 // values (or variables) here
                    });
                    $("#modal-add-to-cart-button").attr({
                        "data-min": 1 // values (or variables) here
                    });

                }
                if (data.quantity_step_size != 1 && data.quantity_step_size != '' && data.quantity_step_size != 'undefined') {
                    $(".in-num").attr({
                        "data-step": data.quantity_step_size // values (or variables) here
                    });
                    $(".minus").attr({
                        "data-step": data.quantity_step_size // values (or variables) here
                    })
                    $(".plus").attr({
                        "data-step": data.quantity_step_size // values (or variables) here

                    })
                    $("#modal-add-to-cart-button").attr({
                        "data-step": data.quantity_step_size // values (or variables) here
                    })


                } else {
                    $(".in-num").attr({
                        "data-step": 1 // values (or variables) here
                    });
                    $(".minus").attr({
                        "data-step": 1 // values (or variables) here
                    })
                    $(".plus").attr({
                        "data-step": 1 // values (or variables) here
                    })
                    $("#modal-add-to-cart-button").attr({
                        "data-step": 1 // values (or variables) here
                    })

                }
                if (data.total_allowed_quantity != '' && data.total_allowed_quantity != 'undefined' && data.total_allowed_quantity != null) {
                    $(".in-num").attr({
                        "data-max": data.total_allowed_quantity // values (or variables) here
                    });
                    $(".plus").attr({
                        "data-max": data.total_allowed_quantity // values (or variables) here
                    })
                    $("#modal-add-to-cart-button").attr({
                        "data-max": data.total_allowed_quantity // values (or variables) here
                    })
                } else {
                    $(".in-num").attr({
                        "data-max": 1 // values (or variables) here
                    });
                    $(".plus").attr({
                        "data-max": 1 // values (or variables) here
                    });
                    $("#modal-add-to-cart-button").attr({
                        "data-max": 1 // values (or variables) here
                    })

                }
                $("#modal-product-quantity").val(data.minimum_order_quantity);
                $('#modal-product-title').text(data.name);
                $('#modal-product-short-description').text(data.short_description);
                $('#modal-product-rating').rating('update', data.rating);
                var price = data.get_price.range
                $('#modal-product-price').html(price);
                //Quick View Product Modal Gallery Swiper
                quickViewgalleryThumbs = new Swiper('.gallery-thumbs', {
                    spaceBetween: 10,
                    slidesPerView: 4,
                    freeMode: true,
                    watchSlidesVisibility: true,
                    watchSlidesProgress: true,
                });
                quickViewgalleryTop = new Swiper('.gallery-top', {
                    spaceBetween: 10,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    thumbs: {
                        swiper: quickViewgalleryThumbs
                    },
                    clickable: true
                });

                //preview-image-swiper 
                mobile_image_swiper = new Swiper('.mobile-image-swiper', {
                    pagination: {
                        el: '.mobile-image-swiper-pagination',
                    },
                    clickable: true
                });

                quickViewgalleryThumbs.removeAllSlides();
                quickViewgalleryTop.removeAllSlides();
                mobile_image_swiper.removeAllSlides();
                var thumb_images = '<div class="swiper-slide text-center">' +
                    '<div class="product-view-grid">' +
                    '<div class="product-view-image">' +
                    '<div class="product-view-image-container">' +
                    '<img src="' + data.image_md + '" data-zoom-image="">' +
                    '</div></div></div></div>';
                var main_images = '<div class="swiper-slide text-center">' +
                    '<div class="product-view-grid">' +
                    '<div class="product-view-image">' +
                    '<div class="product-view-image-container">' +
                    '<img src="' + data.image_md + '">' +
                    '</div></div></div></div>';
                var mobile_slider_image = '<div class="swiper-slide text-center"><img src="' + data.image_md + '"></div>';
                var variant_images_md = data.variants.map(function (value, index) {
                    return value.images_md;
                });

                $.each(variant_images_md, function (i, images) {
                    if (images != null && images != '') {
                        $.each(images, function (i, url) {
                            thumb_images += '<div class="swiper-slide text-center">' +
                                '<div class="product-view-grid">' +
                                '<div class="product-view-image">' +
                                '<div class="product-view-image-container">' +
                                '<img src="' + url + '" data-zoom-image="">' +
                                '</div></div></div></div>';

                            main_images += '<div class="swiper-slide text-center">' +
                                '<div class="product-view-grid">' +
                                '<div class="product-view-image">' +
                                '<div class="product-view-image-container">' +
                                '<img src="' + url + '">' +
                                '</div></div></div></div>';

                            mobile_slider_image += '<div class="swiper-slide text-center"><img src="' + url + '"></div>';
                        });
                    }
                });
                $.each(data.other_images_md, function (i, url) {
                    total_images++;
                    thumb_images += '<div class="swiper-slide text-center">' +
                        '<div class="product-view-grid">' +
                        '<div class="product-view-image">' +
                        '<div class="product-view-image-container">' +
                        '<img src="' + url + '" data-zoom-image="">' +
                        '</div></div></div></div>';

                    main_images += '<div class="swiper-slide text-center">' +
                        '<div class="product-view-grid">' +
                        '<div class="product-view-image">' +
                        '<div class="product-view-image-container">' +
                        '<img src="' + url + '">' +
                        '</div></div></div></div>';

                    mobile_slider_image += '<div class="swiper-slide text-center"><img src="' + url + '"></div>';
                });

                quickViewgalleryThumbs.addSlide(1, thumb_images);
                quickViewgalleryTop.addSlide(1, main_images);
                mobile_image_swiper.addSlide(1, mobile_slider_image);
                var variant_attributes = '';
                var is_image = 0;
                var is_color = 0;
                $.each(data.variant_attributes, function (i, e) {
                    var attribute_ids = e.ids.split(',');
                    var attribute_values = e.values.split(',');
                    var swatche_types = e.swatche_type.split(',');
                    var swatche_values = e.swatche_value.split(',');
                    var style = '<style> .product-page-details .btn-group>.active { border: 1px solid black;}</style>';
                    variant_attributes += '<h4>' + e.attr_name + '</h4><div class="btn-group btn-group-toggle" data-toggle="buttons">';
                    $.each(attribute_ids, function (j, id) {
                        var color_code = "";
                        if (swatche_types[j] == "1") {
                            is_color = 1;
                            color_code = 'style="background-color:' + swatche_values[j] + '";';
                            variant_attributes += '<style> .product-page-details .btn-group>.active { border: 1px solid black;}</style>' + '<button class="btn fullCircle" ' + color_code + '>' +
                                '<input type="radio" name="' + e.attr_name + '" value="' + id + '" class="modal-product-attributes" autocomplete="off"><br>' +
                                '</button>';
                        } else if (swatche_types[j] == "2") {
                            is_image = 1;
                            variant_attributes += '<style> .product-page-details .btn-group>.active { color: #000000; border: 1px solid black;}</style>' + '<label class="btn text-center bg-transparent">' +
                                '<img class="swatche-image" src="' + swatche_values[j] + '">' +
                                '<input type="radio" name="' + e.attr_name + '" value="' + id + '" class="modal-product-attributes" autocomplete="off"><br>' +
                                '</label>';
                        } else {
                            var style1 = '<style> .product-page-details .btn-group>.active { background-color: var(--primary-color);color: white!important;}</style>';
                            variant_attributes += style1 + '<label class="btn btn-default text-center">' +
                                '<input type="radio" name="' + e.attr_name + '" value="' + id + '" class="modal-product-attributes" autocomplete="off">' + attribute_values[j] + '<br>' +
                                '</label>';
                        }
                    });
                    variant_attributes += '</div>';
                });
                var className = (data.is_deliverable == false) ? "danger" : "success";
                var is_not = (data.is_deliverable == false) ? "not" : "";
                var err_msg = (data.zipcode != "" && typeof data.zipcode !== 'undefined') ? '<b class="text-' + className + '">Product is ' + is_not + ' delivarable on &quot; ' + data.zipcode + ' &quot; </b>' : "";

                if (data.type != "digital_product") {
                    variant_attributes += '<form class="mt-2 validate_zipcode_quick_view "   method="post" >' +
                        '<div class="d-flex">' +
                        '<div class=" col-md-6 pl-0">' +
                        '<input type="hidden" name="product_id" value="' + data.id + '">' +
                        '<input type="hidden" name="' + csrfName + '" value="' + csrfHash + '">' +
                        '<input type="text" class="form-control" id="zipcode" placeholder="Zipcode" name="zipcode" required value="' + data.zipcode + '">' +
                        '</div>' +
                        '<button type="submit" class="button button-primary-outline m-0" data-product_id="' + data.id + '"  data-zipcode="' + data.zipcode + '"  id="validate_zipcode">Check Availability</button>' +
                        '</div>' +
                        '<div class="mt-2" id="error_box1">' +
                        err_msg +
                        ' </div>' +
                        ' </form>';
                } else {
                    variant_attributes += '<form class="mt-2 validate_zipcode_quick_view "   method="post" >' +
                        '<div class="d-flex">' +
                        '<div class=" col-md-6 pl-0">' +
                        '<input type="hidden" name="product_id" value="' + data.id + '">' +
                        '<input type="hidden" name="' + csrfName + '" value="' + csrfHash + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="mt-2" id="error_box1">' +
                        err_msg +
                        ' </div>' +
                        ' </form>';
                }
                $('#modal-product-variant-attributes').html(variant_attributes);
                if (data.is_deliverable == false && data.zipcode != "" && typeof data.zipcode !== 'undefined') {
                    $('#modal-add-to-cart-button').attr('disabled', 'true');
                } else {
                    $('#modal-add-to-cart-button').removeAttr('disabled');
                }
                var variants = '';
                total_images = 1;
                $.each(data.variants, function (i, e) {
                    variants += '<input type="hidden" class="modal-product-variants" data-image-index="' + total_images + '" name="variants_ids" data-name="' + data.name + '" value="' + e.variant_ids + '" data-id="' + e.id + '" data-price="' + e.price + '" data-special_price="' + e.special_price + '">';
                    total_images += e.images.length;
                });
                $('#modal-product-variants-div').html(variants);
                $('#add_to_favorite_btn').attr('data-product-id', data.id);
                if (data.is_favorite == 1) {
                    $('#add_to_favorite_btn').addClass('remove-fav');
                    $('#add_to_favorite_btn').find('span').text('Remove From Favorite');
                } else {
                    $('#add_to_favorite_btn').addClass('add-fav');
                    $('#add_to_favorite_btn').find('span').text('Add to Favorite');
                }
                $('#compare').attr('data-product-id', data.id);
                if (data.type == "simple_product") {
                    $('#compare').attr('data-product-variant-id', data.variants[0].id);
                } else {
                    $('#compare').attr('data-product-variant-id', '');
                }
                var compare = '';
                $.each(data, function (i, e) {
                    compare += '<button type="button" name="compare" class="buttons btn-6-6 extra-small m-0 compare" id="compare" data-product-id="' + data.id + '" data-product-variant-id="' + data.variants.id + '"><i class="fa fa-random"></i> Compare</button>';
                });
                $('#modal-product-no-of-ratings').text(data.no_of_ratings);
                if (!$.isEmptyObject(data.tags)) {
                    var tags = 'Tags ';
                    $.each(data.tags, function (i, e) {
                        tags += '<a href="' + base_url + 'products/tags/' + e + '" target="_blank"><span class="badge badge-secondary p-1 mr-1">' + e + '</span></a>';
                    });
                    $('#modal-product-tags').html(tags);
                }
                var seller_info = "";
                if (data.seller_name) {
                    var seller_info = '<p> <span class="text-secondary"> Sold by </span> <a class="text text-primary" target="_blank" href="' + base_url + 'products?seller=' + data.seller_slug + '">' + data.seller_name + '</a> <span class="badge badge-success ">' + data.seller_rating + ' <i class="fa fa-star"></i></span> <small class="text-muted"> Out of</small> <b> ' + data.seller_no_of_ratings + ' </b></p>';
                    $('#modal-product-sellers').html(seller_info);
                }
                modal.stopLoading();
            });
        }
    });

    //Modal Product Variant Selection Event
    $(document).on('change', '.modal-product-attributes', function (e) {
        e.preventDefault();
        var selected_attributes = [];
        var attributes_length = "";
        var price = "";
        var is_variant_available = false;
        var variant = [];
        var prices = [];
        var variant_prices = [];
        var variants = [];
        var variant_ids = [];
        var image_indexes = [];
        var selected_image_index;
        $('.modal-product-variants').each(function () {
            prices = {
                price: $(this).data('price'),
                special_price: $(this).data('special_price')
            };
            variant_ids.push($(this).data('id'));
            variant_prices.push(prices);
            variant = $(this).val().split(',');
            variants.push(variant);
            image_indexes.push($(this).data('image-index'));
        });
        attributes_length = variant.length;
        $('.modal-product-attributes').each(function () {
            if ($(this).prop('checked')) {
                selected_attributes.push($(this).val());

                if (selected_attributes.length == attributes_length) {
                    /* compare the arrays */
                    prices = [];
                    var selected_variant_id = '';
                    $.each(variants, function (i, e) {
                        if (arrays_equal(selected_attributes, e)) {
                            is_variant_available = true;
                            prices.push(variant_prices[i]);
                            selected_variant_id = variant_ids[i];
                            selected_image_index = image_indexes[i];
                        }
                    });
                    if (is_variant_available) {
                        quickViewgalleryTop.slideTo(selected_image_index, 500, false);
                        mobile_image_swiper.slideTo(selected_image_index, 500, false);
                        if (prices[0].special_price < prices[0].price && prices[0].special_price != 0) {
                            price = prices[0].special_price;
                            $('#modal-product-price').text(currency + ' ' + price);
                            $('#modal-product-special-price').text(currency + ' ' + prices[0].price);
                            $('#modal-add-to-cart-button').attr('data-product-variant-id', selected_variant_id);
                            $('#modal-product-special-price-div').show();
                        } else {
                            price = prices[0].price;
                            $('#modal-product-price').html(currency + ' ' + price);
                            $('#modal-product-special-price-div').hide();
                            $('#modal-add-to-cart-button').attr('data-product-variant-id', selected_variant_id);
                        }
                    } else {
                        $('#modal-product-special-price-div').hide();
                    }
                }
            }
        });
    });

    $('#modal-add-to-cart-button').on('click', function (e) {
        e.preventDefault();
        var qty = $("#modal-product-quantity").val();
        var title = $('#modal-product-title').text();
        var description = $('#modal-product-short-description').text();
        var image = $('.product-view-image-container img').attr('src');
        var price = $('#modal-product-price').text().replace(/\D/g, '');

        $('#quick-view').data('data-product-id', $(this).data('productId'));
        var product_variant_id = $(this).attr('data-product-variant-id');
        var product_type = $(this).attr('data-product-type');
        console.log(product_type);
        var min = $(this).attr('data-min');
        var max = $(this).attr('data-max');
        var step = $(this).attr('data-step');
        var btn = $(this);
        var btn_html = $(this).html();

        if (!product_variant_id) {
            Toast.fire({
                icon: 'error',
                title: "Please select variant"
            });
            return;
        }
        $.ajax({
            type: 'POST',
            url: base_url + 'cart/manage',
            data: {
                'product_variant_id': product_variant_id,
                'qty': $('#modal-product-quantity').val(),
                'is_saved_for_later': false,
                [csrfName]: csrfHash,
            },
            dataType: 'json',
            beforeSend: function () {
                btn.html('Please Wait').text('Please Wait').attr('disabled', true);
            },
            success: function (result) {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
                btn.html(btn_html).attr('disabled', false);
                if (result.error == false) {
                    Toast.fire({
                        icon: 'success',
                        title: result.message
                    });
                    $('#cart-count').text(result.data.cart_count);
                    var html = '';
                    $.each(result.data.items, function (i, e) {
                        var variant = typeof (e.product_variants.variant_values) != 'undefined' && e.product_variants.variant_values != null ? e.product_variants.variant_values : '';
                        var price = e.special_price < e.price && e.special_price != 0 ? e.special_price : e.price;
                        html += '<div class="row">' +
                            '<div class="cart-product product-sm col-md-12">' +
                            '<div class="product-image">' +
                            '<img class="pic-1" src="' + base_url + e.image + '" alt="Not Found">' +
                            '</div>' +
                            '<div class="product-details">' +
                            '<div class="product-title">' + e.name + '</div>' +
                            '<span>' + variant + '</span>' +
                            '<p class="product-descriptions">' + e.short_description + '</p>' +
                            '</div>' +
                            '<div class="product-pricing d-flex py-2 px-1 w-100">' +
                            '<div class="product-price align-self-center">' + currency + ' ' + price + '</div>' +
                            '<div class="product-sm-quantity px-1">' +
                            '<input type="number" class="form-input" value="' + min + '"  data-id="' + e.product_variant_id + '" data-price="' + e.price + '" min="' + min + '" max="' + max + '" step="' + step + '" >' +
                            '</div>' +
                            '<div class="product-sm-removal align-self-center">' +
                            '<button class="remove-product button button-danger" data-id="' + e.product_variant_id + '">' +
                            '<i class="fa fa-trash"></i>' +
                            '</button>' +
                            '</div>' +
                            '<div class="product-line-price align-self-center px-1">' + currency + ' ' + (e.qty * price).toLocaleString(undefined, { minimumFractionDigits: 2 }) + '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>';
                    });
                    $('#cart-item-sidebar').html(html);
                } else {
                    if (is_loggedin == 0) {
                        Toast.fire({
                            icon: 'success',
                            title: "Item added to cart"
                        });
                        var cart_item = { "product_variant_id": product_variant_id.trim(), "title": title, "description": description, "qty": qty, "image": image, "price": price.trim(), "min": min, "step": step };
                        var cart = localStorage.getItem("cart");
                        console.log(cart);
                        cart = (localStorage.getItem("cart") !== null) ? JSON.parse(cart) : null;
                        if (cart !== null && cart !== undefined) {
                            cart.push(cart_item);
                        } else {
                            cart = [cart_item];
                        }
                        localStorage.setItem("cart", JSON.stringify(cart));
                        display_cart(cart);
                        return;
                    }
                    Toast.fire({
                        icon: 'error',
                        title: result.message
                    });
                }
            }
        })
    });

    /* JS inside the modal */
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

    $(document).on('opening', '.auth-modal', function (e) {
        $(this).removeClass('d-none');
        e.preventDefault();
        closeNav();
        $('.send-otp-form')[0].reset();
        $('.send-otp-form').show();
        $('.sign-up-form')[0].reset();
        $('.sign-up-form').hide();
        $('#is-user-exist-error').html('');
        $('#sign-up-error').html('');

        $('#recaptcha-container').html('');
        window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
        window.recaptchaVerifier.render().then(function (widgetId) {
            grecaptcha.reset(widgetId);
        });

        var telInput = $("#phone-number"),
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

        // on blur: validate
        telInput.blur(function () {
            reset();
            if ($.trim(telInput.val())) {
                if (telInput.intlTelInput("isValidNumber")) {
                    validMsg.removeClass("hide");
                } else {
                    telInput.addClass("error");
                    errorMsg.removeClass("hide");
                }
            }
        });
        // on keyup / change flag: reset
        telInput.on("keyup change", reset);


    });

    $("#quick-view").on('click', '.submit', function (event) {
        event.preventDefault();

        var fx = "wobble", //wobble shake
            $modal = $(this).closest('.iziModal');

        if (!$modal.hasClass(fx)) {
            $modal.addClass(fx);
            setTimeout(function () {
                $modal.removeClass(fx);
            }, 1500);
        }
    });
    $("#quick-view").on('click', 'header a', function (event) {
        event.preventDefault();
        var index = $(this).index();
        $(this).addClass('active').siblings('a').removeClass('active');
        $(this).parents("div").find("section").eq(index).removeClass('hide').siblings('section').addClass('hide');

        if ($(this).index() === 0) {
            $("#quick-view .iziModal-content .icon-close").css('background', '#ddd');
        } else {
            $("#quick-view .iziModal-content .icon-close").attr('style', '');
        }
    });

    $("#quick-view").on('click', '.submit', function (event) {
        event.preventDefault();

        var fx = "wobble", //wobble shake
            $modal = $(this).closest('.iziModal');

        if (!$modal.hasClass(fx)) {
            $modal.addClass(fx);
            setTimeout(function () {
                $modal.removeClass(fx);
            }, 1500);
        }
    });
    $("#quick-view").on('click', 'header a', function (event) {
        event.preventDefault();
        var index = $(this).index();
        $(this).addClass('active').siblings('a').removeClass('active');
        $(this).parents("div").find("section").eq(index).removeClass('hide').siblings('section').addClass('hide');

        if ($(this).index() === 0) {
            $("#quick-view .iziModal-content .icon-close").css('background', '#ddd');
        } else {
            $("#quick-view .iziModal-content .icon-close").attr('style', '');
        }
    });

});

(function () {
    function logElementEvent(eventName, element) { }

    var callback_enter = function (element) { };
    var callback_exit = function (element) { };
    var callback_loading = function (element) { };
    var callback_loaded = function (element) { };
    var callback_error = function (element) {
        "https://via.placeholder.com/440x560/?text=Error+Placeholder";
    };
    var callback_finish = function () { };
    var callback_cancel = function (element) { };

    var ll = new LazyLoad({
        threshold: 0,
        // Assign the callbacks defined above
        callback_enter: callback_enter,
        callback_exit: callback_exit,
        callback_cancel: callback_cancel,
        callback_loading: callback_loading,
        callback_loaded: callback_loaded,
        callback_error: callback_error,
        callback_finish: callback_finish
    });
})();
(function () {

    var parent = document.querySelector(".range-slider");
    if (!parent) return;

    var
        rangeS = parent.querySelectorAll("input[type=range]"),
        numberS = parent.querySelectorAll("input[type=number]");

    rangeS.forEach(function (el) {
        el.oninput = function () {
            var slide1 = parseFloat(rangeS[0].value),
                slide2 = parseFloat(rangeS[1].value);

            if (slide1 > slide2) {
                [slide1, slide2] = [slide2, slide1];
            }

            numberS[0].value = slide1;
            numberS[1].value = slide2;

            custom_url = setUrlParameter(location.href, 'min-price', slide1);
            custom_url = setUrlParameter(custom_url, 'max-price', slide2);
        }
    });

    numberS.forEach(function (el) {
        el.oninput = function () {
            var number1 = parseFloat(numberS[0].value),
                number2 = parseFloat(numberS[1].value);

            if (number1 > number2) {
                var tmp = number1;
                numberS[0].value = number2;
                numberS[1].value = tmp;
            }

            rangeS[0].value = number1;
            rangeS[1].value = number2;

        }
    });

})();


//Qty Counter
$(document).on('change', 'input.in-num', function (e) {
    e.preventDefault();
    var $input = $(this);
    if ($input.val() == null || typeof $input.val() == "string") {
        if (!$.isNumeric($input.val())) {
            $input.val(1);
        } else {
            if ($input.val() == '0') {
                $input.val(1);
            }
        }
    }
});
$(document).on('focusout', '.in-num', function (e) {
    e.preventDefault();
    var value = $(this).val();
    var min = $(this).data('min');
    var step = $(this).data('step');
    var max = $(this).data('max');
    if (value < min) {
        $(this).val(min);
        Toast.fire({
            icon: 'error',
            title: 'Minimum allowed quantity is ' + min
        });

    } else if (value > max) {
        $(this).val(max);
        Toast.fire({
            icon: 'error',
            title: 'Maximum allowed quantity is ' + max
        });
    }
});
$(document).on('click', '.num-block .num-in span', function (e) {
    e.preventDefault();

    var $input = $(this).parents('.num-block').find('input.in-num');
    if ($input.val() == null) {
        $input.val(1);
    }
    if ($(this).hasClass('minus')) {
        var step = $(this).data('step');
        var count = parseFloat($input.val()) - step;
        var min = $(this).data('min');
        if (count >= min) {
            $input.val(count);
        } else {
            $input.val(min);
            Toast.fire({
                icon: 'error',
                title: 'Minimum allowed quantity is ' + min
            });
        }

    } else {
        var step = $(this).data('step');
        var max = $(this).data('max');
        var count = parseFloat($input.val()) + step
        if (max != 0) {
            if (count <= max) {
                $input.val(count);
                if (count > 1) {
                    $(this).parents('.num-block').find(('.minus')).removeClass('dis');
                }

            } else {
                $input.val(max);
                Toast.fire({
                    icon: 'error',
                    title: 'Maximum allowed quantity is ' + max
                });
            }
        } else {
            $input.val(count);
        }

    }
    $input.change();
    return false;
});

$(document).ready(function () {
    $('.kv-fa').rating({
        theme: 'krajee-fa',
        filledStar: '<i class="fas fa-star"></i>',
        emptyStar: '<i class="far fa-star"></i>',
        showClear: false,
        showCaption: false,
        size: 'md',
    });

    /* Set rates + misc */
    var taxRate = 0.05;
    var shippingRate = 15.00;
    var fadeTime = 300;

    /* Assign actions */
    $(document).on('change', '.product-quantity input,.product-sm-quantity input,.itemQty', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var price = $(this).data('price');
        var qty = $(this).val();
        var temp = $(this);
        let step;
        if ($(this).attr("step")) {
            step = $(this).attr("step");
        } else {
            step = $(this).data("step");
        }
        var min = $(this).attr("min");
        if (qty <= 0) {
            Toast.fire({
                icon: 'error',
                title: `Oops! Please set minimum ${min} quantity for product`
            });
            return;
        }
        if (qty % step == 0) {
            if (is_loggedin == 1) {
                $.ajax({
                    url: base_url + "cart/manage",
                    type: "POST",
                    data: {
                        product_variant_id: id,
                        qty: qty,
                        [csrfName]: csrfHash,
                    },
                    dataType: 'json',
                    success: function (result) {
                        csrfName = result.csrfName;
                        csrfHash = result.csrfHash;
                        if (result.error == false) {
                            updateQuantity(temp, price);
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: result.message
                            });
                        }
                    }
                });
            } else {
                updateQuantity(temp, price);
            }
        } else {
            Toast.fire({
                icon: 'error',
                title: `Oops! you can only set quantity in step size of ${step}`
            });
        }
    });

    //Remove from Cart.
    $(document).on('click', '.product-removal button,.product-removal i,.product-sm-removal button', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var is_save_for_later = typeof ($(this).data('is-save-for-later')) != 'undefined' && $(this).data('is-save-for-later') == 1 ? '1' : '0';
        var product = $(this).parent().parent().parent();
        if (confirm("Are you sure want to remove this?")) {
            if (is_loggedin == 1) {
                // remove from server
                $.ajax({
                    url: base_url + 'cart/remove',
                    type: "POST",
                    data: {
                        product_variant_id: id,
                        is_save_for_later: is_save_for_later,
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function (result) {
                        csrfName = result['csrfName'];
                        csrfHash = result['csrfHash'];
                        if (result.error == false) {
                            var cart_count = $('#cart-count').text();
                            cart_count--;
                            $('#cart-count').text(cart_count);
                            removeItem(product);
                            // location.reload();
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: result.message
                            });
                        }
                    }
                });
            } else {
                // remove from local storage
                removeItem(product);
                var cart = localStorage.getItem("cart");
                cart = (localStorage.getItem("cart") !== null) ? JSON.parse(cart) : null;
                if (cart) {
                    var new_cart = cart.filter(function (item) { return item.product_variant_id != id });
                    localStorage.setItem("cart", JSON.stringify(new_cart));
                    if (cart)
                        display_cart(new_cart);
                }

            }
        }
    });


    /* Recalculate cart */
    function recalculateCart() {
        var subtotal = 0;

        /* Sum up row totals */
        $('.product').each(function () {
            subtotal += parseFloat($(this).children('.product-line-price').text());
        });

        /* Calculate totals */
        var tax = subtotal * taxRate;
        var shipping = (subtotal > 0 ? shippingRate : 0);
        var total = subtotal + tax + shipping;

        /* Update totals display */
        $('.totals-value').fadeOut(fadeTime, function () {
            $('#cart-subtotal').html(subtotal.toFixed(2));
            $('#cart-tax').html(tax.toFixed(2));
            $('#cart-shipping').html(shipping.toFixed(2));
            $('#cart-total').html(total.toFixed(2));
            if (total == 0) {
                $('.checkout').fadeOut(fadeTime);
            } else {
                $('.checkout').fadeIn(fadeTime);
            }
            $('.totals-value').fadeIn(fadeTime);
        });
    }

    /* Update quantity */
    function updateQuantity(quantityInput, price) {
        /* Calculate line price */
        if (quantityInput.data('page') == "cart") {
            var productRow = $(quantityInput).parent().parent().parent().siblings('.total-price');
        } else {
            var productRow = $(quantityInput).parent().parent();
        }
        var quantity = $(quantityInput).val();
        var linePrice = price * quantity;
        /* Update line price display and recalc cart totals */
        productRow.children('.product-line-price').each(function () {
            $(this).fadeOut(fadeTime, function () {
                $(this).text(currency + ' ' + linePrice.toFixed(2));
                recalculateCart();
                usercartTotal();
                $(this).fadeIn(fadeTime);
            });
        });
    }

    /* Remove item from cart */
    function removeItem(removeProduct) {
        /* Remove row from DOM and recalc cart total */
        var productRow = $(removeProduct);
        productRow.slideUp(fadeTime, function () {
            productRow.remove();
            recalculateCart();
            usercartTotal;
        });
    }

});

$('.js-menu').on('click', () => {
    $('.js-menu').toggleClass('active');
    $('.js-filter-nav').toggleClass('open');
    $('.js-filter-nav__list').toggleClass('show');
    if ($('body').css('overflow').toLowerCase() == 'hidden') {
        $('body').css('overflow', 'scroll');
    } else {
        $('body').css('overflow', 'hidden');
    }
});

jQuery(document).ready(function ($) {
    function morphDropdown(element) {
        this.element = element;
        this.mainNavigation = this.element.find('.main-nav');
        this.mainNavigationItems = this.mainNavigation.find('.has-dropdown');
        this.dropdownList = this.element.find('.dropdown-list');
        this.dropdownWrappers = this.dropdownList.find('.dropdown');
        this.dropdownItems = this.dropdownList.find('.content');
        this.dropdownBg = this.dropdownList.find('.bg-layer');
        this.mq = this.checkMq();
        this.bindEvents();
    }

    morphDropdown.prototype.checkMq = function () {
        //check screen size
        var self = this;
        return window.getComputedStyle(self.element.get(0), '::before').getPropertyValue('content').replace(/'/g, "").replace(/"/g, "").split(', ');
    };

    morphDropdown.prototype.bindEvents = function () {
        var self = this;
        //hover over an item in the main navigation
        this.mainNavigationItems.mouseenter(function (event) {
            //hover over one of the nav items -> show dropdown
            self.showDropdown($(this));
        }).mouseleave(function () {
            setTimeout(function () {
                //if not hovering over a nav item or a dropdown -> hide dropdown
                if (self.mainNavigation.find('.has-dropdown:hover').length == 0 && self.element.find('.dropdown-list:hover').length == 0) self.hideDropdown();
            }, 50);
        });

        //hover over the dropdown
        this.dropdownList.mouseleave(function () {
            setTimeout(function () {
                //if not hovering over a dropdown or a nav item -> hide dropdown
                (self.mainNavigation.find('.has-dropdown:hover').length == 0 && self.element.find('.dropdown-list:hover').length == 0) && self.hideDropdown();
            }, 50);
        });

        //click on an item in the main navigation -> open a dropdown on a touch device
        this.mainNavigationItems.on('touchstart', function (event) {
            var selectedDropdown = self.dropdownList.find('#' + $(this).data('content'));
            if (!self.element.hasClass('is-dropdown-visible') || !selectedDropdown.hasClass('active')) {
                event.preventDefault();
                self.showDropdown($(this));
            }
        });
    };

    morphDropdown.prototype.showDropdown = function (item) {
        this.mq = this.checkMq();
        if (this.mq == 'desktop') {
            var self = this;
            var selectedDropdown = this.dropdownList.find('#' + item.data('content'));
            var selectedDropdownHeight = selectedDropdown.innerHeight() + 18;
            var width = selectedDropdown.children('.content').children("ul").children('li').length * 180;
            if (width > 540) {
                width = 540;
            }
            var selectedDropdownWidth = parseInt(width),
                selectedDropdownLeft = item.offset().left + item.innerWidth() / 2 - selectedDropdownWidth / 2;
            var dropdownWrapperLeft = item[0].offsetParent.offsetLeft;
            //update dropdown position and size            
            this.updateDropdown(selectedDropdown, parseInt(selectedDropdownHeight), selectedDropdownWidth, parseInt(selectedDropdownLeft));
            //add active class to the proper dropdown item
            this.element.find('.active').removeClass('active');
            this.element.find('.morph-dropdown-wrapper').css({
                '-moz-transform': 'translateX(-' + dropdownWrapperLeft + 'px)',
                '-webkit-transform': 'translateX(-' + dropdownWrapperLeft + 'px)',
                '-ms-transform': 'translateX(-' + dropdownWrapperLeft + 'px)',
                '-o-transform': 'translateX(-' + dropdownWrapperLeft + 'px)',
                'transform': 'translateX(-' + dropdownWrapperLeft + 'px)',
            });

            selectedDropdown.addClass('active').removeClass('move-left move-right').prevAll().addClass('move-left').end().nextAll().addClass('move-right');
            item.addClass('active');
            //show the dropdown wrapper if not visible yet
            if (!this.element.hasClass('is-dropdown-visible')) {
                setTimeout(function () {
                    self.element.addClass('is-dropdown-visible');
                }, 10);
            }
        }
    };

    morphDropdown.prototype.updateDropdown = function (dropdownItem, height, width, left) {
        this.dropdownList.css({
            '-moz-transform': 'translateX(' + left + 'px)',
            '-webkit-transform': 'translateX(' + left + 'px)',
            '-ms-transform': 'translateX(' + left + 'px)',
            '-o-transform': 'translateX(' + left + 'px)',
            'transform': 'translateX(' + left + 'px)',
            'width': width + 'px',
            'height': height + 'px'
        });

        this.dropdownBg.css({
            '-moz-transform': 'scaleX(' + width + ') scaleY(' + height + ')',
            '-webkit-transform': 'scaleX(' + width + ') scaleY(' + height + ')',
            '-ms-transform': 'scaleX(' + width + ') scaleY(' + height + ')',
            '-o-transform': 'scaleX(' + width + ') scaleY(' + height + ')',
            'transform': 'scaleX(' + width + ') scaleY(' + height + ')'
        });
    };

    morphDropdown.prototype.hideDropdown = function () {
        this.mq = this.checkMq();
        if (this.mq == 'desktop') {
            this.element.removeClass('is-dropdown-visible').find('.active').removeClass('active').end().find('.move-left').removeClass('move-left').end().find('.move-right').removeClass('move-right');
        }
    };

    morphDropdown.prototype.resetDropdown = function () {
        this.mq = this.checkMq();
        if (this.mq == 'mobile') {
            this.dropdownList.removeAttr('style');
        }
    };

    var morphDropdowns = [];
    if ($('.cd-morph-dropdown').length > 0) {
        $('.cd-morph-dropdown').each(function () {
            //create a morphDropdown object for each .cd-morph-dropdown
            morphDropdowns.push(new morphDropdown($(this)));
        });

        var resizing = false;

        //on resize, reset dropdown style property
        updateDropdownPosition();
        $(window).on('resize', function () {
            if (!resizing) {
                resizing = true;
                (!window.requestAnimationFrame) ? setTimeout(updateDropdownPosition, 300) : window.requestAnimationFrame(updateDropdownPosition);
            }
        });

        function updateDropdownPosition() {
            morphDropdowns.forEach(function (element) {
                element.resetDropdown();
            });

            resizing = false;
        };
    }
});




$('.navbar-top-search-box input').on('focus', function () {
    $('.navbar-top-search-box .input-group-text').css('border-color', '#0e7dd1');
});
$('.navbar-top-search-box input').on('blur', function () {
    $('.navbar-top-search-box .input-group-text').css('border', '1px solid #ced4da');
});

// Initialize Swiper 
//Swiper1 For Home Page Slider
var swiper = new Swiper('.swiper1', {
    loop: true,
    preloadImages: false,
    lazy: true,
    autoplay: {
        delay: 6000,
        disableOnInteraction: false
    },
    pagination: {
        el: '.swiper1-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
});

var swiperheader = new Swiper('.imageSliderHeader', {
    autoplay: {
        delay: 6000,
    },
    autoplay: {
        delay: 6000,
        disableOnInteraction: false
    },
    pagination: {
        el: '.imageSliderHeader-pagination',
        clickable: true,
    },
    loop: true,
    grabCursor: true,
});

//preview-image-swiper 
var swiperF = new Swiper('.preview-image-swiper', {
    pagination: {
        el: '.preview-image-swiper-pagination',
    },
});

//banner-swiper
var swiperV = new Swiper('.banner-swiper', {
    preloadImages: false,
    lazy: true,
    autoplay: true,
    pagination: {
        el: '.banner-swiper-pagination',
    },
    loop: true,
    // Navigation arrows
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
});

//category-swiper 
var swiperS = new Swiper('.category-swiper', {
    slidesPerView: 5,
    preloadImages: false,
    lazyLoading: true,
    updateOnImagesReady: false,
    lazyLoadingInPrevNextAmount: 0,
    pagination: {
        el: '.category-swiper-pagination',
        clickable: true,
    },
    breakpoints: {
        350: {
            slidesPerView: 4,
            spaceBetweenSlides: 10
        },
        400: {
            slidesPerView: 4,
            spaceBetweenSlides: 10
        },
        499: {
            slidesPerView: 4,
            spaceBetweenSlides: 10
        },
        550: {
            slidesPerView: 1,
            spaceBetweenSlides: 10
        },
        600: {
            slidesPerView: 2,
            spaceBetweenSlides: 10
        },
        700: {
            slidesPerView: 3,
            spaceBetweenSlides: 10
        },
        800: {
            slidesPerView: 4,
            spaceBetweenSlides: 10
        },
        999: {
            slidesPerView: 5,
            spaceBetweenSlides: 10
        },
        1900: {
            slidesPerView: 6,
            spaceBetweenSlides: 10
        },
        1900: {
            slidesPerView: 6,
            spaceBetweenSlides: 10
        }
    }
});

document.querySelectorAll('.product-image-swiper').forEach(function (elem) {

    new Swiper(elem, {
        grabCursor: true,
        preloadImages: false,
        lazyLoading: true,
        updateOnImagesReady: false,
        lazyLoadingInPrevNextAmount: 1,
        navigation: {
            nextEl: elem.nextElementSibling,
            prevEl: elem.nextElementSibling.nextElementSibling,
        },
        breakpoints: {
            350: {
                slidesPerView: 1,
                spaceBetweenSlides: 10
            },
            400: {
                slidesPerView: 1,
                spaceBetweenSlides: 10
            },
            499: {
                slidesPerView: 1,
                spaceBetweenSlides: 10
            },
            550: {
                slidesPerView: 1,
                spaceBetweenSlides: 10
            },
            600: {
                slidesPerView: 2,
                spaceBetweenSlides: 10
            },
            700: {
                slidesPerView: 3,
                spaceBetweenSlides: 10
            },
            800: {
                slidesPerView: 4,
                spaceBetweenSlides: 10
            },
            999: {
                slidesPerView: 5,
                spaceBetweenSlides: 10
            },
            1900: {
                slidesPerView: 6,
                spaceBetweenSlides: 10
            },
            1900: {
                slidesPerView: 6,
                spaceBetweenSlides: 10
            }
        }
    });
});


var swiperH = new Swiper('.swiper2', {
    slidesPerView: 'auto',
    grabCursor: true,
    spaceBetween: 20,
    pagination: {
        el: '.swiper2-pagination',
        clickable: true,
    },

});

//Gallery Swiper
var galleryThumbs = new Swiper('.gallery-thumbs-1', {
    spaceBetween: 10,
    slidesPerView: 4,
    freeMode: true,
    watchSlidesVisibility: true,
    watchSlidesProgress: true,
});
var galleryTop = new Swiper('.gallery-top-1', {
    spaceBetween: 10,
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    thumbs: {
        swiper: galleryThumbs
    }
});

$(document).ready(function () {

    // Using custom configuration
    var zoomConfig = {
        zoomWindowFadeIn: 500,
        zoomLensFadeIn: 500,
        gallery: 'gal1',
        imageCrossfade: true,
        zoomWindowWidth: 411,
        zoomWindowHeight: 274,
        zoomWindowOffsetX: 10,
        scrollZoom: true,
        cursor: 'pointer',
        tint: true,
        tintColour: '#0E7DD1',
        tintOpacity: 0.5
    };
    var zoomImage = $('img#img_01');
    zoomImage.ezPlus();

    zoomImage.bind('click', function (e) {
        var ez = $('#img_01').data('ezPlus');
        return false;
    });
});




function openNav() {
    $('.block-div').css('width', '100%');
    $('body').css('overflow', 'hidden');
    $('#mySidenav').removeClass('is-closed-left');
}

function openCartSidebar() {
    $('.block-div').css('width', '100%');
    $('body').css('overflow', 'hidden');
    $('.shopping-cart-sidebar').removeClass('is-closed-right');
}

function closeNav() {
    $('.block-div').css('width', '0%');
    $('body').css('overflow', 'unset');
    $('.shopping-cart-sidebar').addClass('is-closed-right');
    $('#mySidenav').addClass('is-closed-left');
};

$(document).ready(function () {

    jQuery(document).ready(function () {
        jQuery("#jquery-accordion-menu").jqueryAccordionMenu();
        jQuery(".colors a").click(function () {
            if ($(this).attr("class") != "default") {
                $("#jquery-accordion-menu").removeClass();
                $("#jquery-accordion-menu").addClass("jquery-accordion-menu").addClass($(this).attr("class"));
            } else {
                $("#jquery-accordion-menu").removeClass();
                $("#jquery-accordion-menu").addClass("jquery-accordion-menu");
            }
        });
    });
});;
(function ($, window, document, undefined) {
    var pluginName = "jqueryAccordionMenu";
    var defaults = {
        speed: 300,
        showDelay: 0,
        hideDelay: 0,
        singleOpen: true,
        clickEffect: true
    };

    function Plugin(element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init()
    };
    $.extend(Plugin.prototype, {
        init: function () {
            this.openSubmenu();
            this.submenuIndicators();
            if (defaults.clickEffect) {
                this.addClickEffect()
            }
        },
        openSubmenu: function () {
            $(this.element).children("ul").find("li").bind("click touchstart", function (e) {
                e.stopPropagation();
                e.preventDefault();
                if ($(this).children(".submenu").length > 0) {
                    if ($(this).children(".submenu").css("display") == "none") {
                        $(this).children(".submenu").show(defaults.speed);
                        $(this).children(".submenu").siblings("a").addClass("submenu-indicator-minus");
                        if (defaults.singleOpen) {
                            $(this).siblings().children(".submenu").hide(defaults.speed);
                            $(this).siblings().children(".submenu").siblings("a").removeClass("submenu-indicator-minus")
                        }
                        return false
                    } else {
                        $(this).children(".submenu").delay(defaults.hideDelay).hide(defaults.speed)
                    }
                    if ($(this).children(".submenu").siblings("a").hasClass("submenu-indicator-minus")) {
                        $(this).children(".submenu").siblings("a").removeClass("submenu-indicator-minus")
                    }
                }
                window.location.href = $(this).children("a").attr("href")
            })
        },
        submenuIndicators: function () {
            if ($(this.element).find(".submenu").length > 0) {
                $(this.element).find(".submenu").siblings("a").append("<span class='submenu-indicator'>+</span>")
            }
        },
        addClickEffect: function () {
            var ink, d, x, y;

            $(this.element).find("a > .submenu-indicator").on("click touchstart", function (e) {
                $(".ink").remove();
                if ($(this).children(".ink").length === 0) {
                    $(this).prepend("<span class='ink'></span>")
                }
                ink = $(this).find(".ink");
                ink.removeClass("animate-ink");
                if (!ink.height() && !ink.width()) {
                    d = Math.max($(this).outerWidth(), $(this).outerHeight());
                    ink.css({
                        height: d,
                        width: d
                    })
                }
                x = e.pageX - $(this).offset().left - ink.width() / 2;
                y = e.pageY - $(this).offset().top - ink.height() / 2;
                ink.css({
                    top: y + 'px',
                    left: x + 'px'
                }).addClass("animate-ink")
            })
        }
    });
    $.fn[pluginName] = function (options) {
        this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options))
            }
        });
        return this
    }
})(jQuery, window, document);



document.addEventListener("DOMContentLoaded", function (event) {


    const cartButtons = document.querySelectorAll('.cart-button');

    cartButtons.forEach(button => {

        button.addEventListener('click', cartClick);

    });

    function cartClick() {
        let button = this;
        button.classList.add('clicked');
    }



});


// timer
var timer;

var compareDate = new Date();
compareDate.setDate(compareDate.getDate() + 7); //just for this demo today + 7 days

timer = setInterval(function () {
    timeBetweenDates(compareDate);
}, 1000);

function timeBetweenDates(toDate) {
    var dateEntered = toDate;
    var now = new Date();
    var difference = dateEntered.getTime() - now.getTime();

    if (difference <= 0) {

        // Timer done
        clearInterval(timer);

    } else {

        var seconds = Math.floor(difference / 1000);
        var minutes = Math.floor(seconds / 60);
        var hours = Math.floor(minutes / 60);
        var days = Math.floor(hours / 24);

        hours %= 24;
        minutes %= 60;
        seconds %= 60;

        $("#days").text(days);
        $("#hours").text(hours);
        $("#minutes").text(minutes);
        $("#seconds").text(seconds);
    }
}

// back-to-top

$(window).scroll(function () {
    if ($(this).scrollTop() > 50) {
        $('.back-to-top:hidden').stop(true, true).fadeIn();
    } else {
        $('.back-to-top').stop(true, true).fadeOut();
    }
});
$(function () {
    $(".scroll").click(function () {
        $("html,body").animate({
            scrollTop: $(".sidenav").offset().top
        }, "1000");
        return false
    })
})

// newsletter

$('#newsletter-modal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var recipient = button.data('whatever') // Extract info from data-* attributes
})

// client swipet slider

var swiper = new Swiper('.swiper-container-client', {
    loop: true,
    // Add the slides to loop
    loopedSlides: 10,
    autoheight: true,
    slidesPerView: 2,
    spaceBetween: 30,
    autoplay: {
        delay: 6000,
        disableOnInteraction: false,
    },
    breakpoints: {
        600: {
            slidesPerView: 6,
            spaceBetween: 20
        },
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
});

// color switcher
jQuery(document).ready(function ($) {

    $("ul.color-style .default").click(function () {
        $("#color-switcher").attr("href", base_url + "assets/front_end/classic/css/colors/default.css");
        return false;
    });

    $("ul.color-style .peach").click(function () {
        $("#color-switcher").attr("href", base_url + "assets/front_end/classic/css/colors/peach.css");
        return false;
    });

    $("ul.color-style .yellow").click(function () {
        $("#color-switcher").attr("href", base_url + "assets/front_end/classic/css/colors/yellow.css");
        return false;
    });

    $("ul.color-style .green").click(function () {
        $("#color-switcher").attr("href", base_url + "assets/front_end/classic/css/colors/green.css");
        return false;
    });

    $("ul.color-style .purple").click(function () {
        $("#color-switcher").attr("href", base_url + "assets/front_end/classic/css/colors/purple.css");
        return false;
    });
    $("ul.color-style .red").click(function () {
        $("#color-switcher").attr("href", base_url + "assets/front_end/classic/css/colors/red.css");
        return false;
    });
    $("ul.color-style .dark-blue").click(function () {
        $("#color-switcher").attr("href", base_url + "assets/front_end/classic/css/colors/dark-blue.css");
        return false;
    });
    $("ul.color-style .orange").click(function () {
        $("#color-switcher").attr("href", base_url + "assets/front_end/classic/css/colors/orange.css");
        return false;
    });
    $("ul.color-style .cyan-dark").click(function () {
        $("#color-switcher").attr("href", base_url + "assets/front_end/classic/css/colors/cyan-dark.css");
        return false;
    });

    $("ul.color-style li a").click(function (e) {
        e.preventDefault();
        $(this).parent().parent().find("a").removeClass("active");
        $(this).addClass("active");
    })

    $("#colors-switcher .color-bottom a.settings").click(function (e) {
        e.preventDefault();
        var div = $("#colors-switcher");
        if (div.css(mode) === "-189px") {
            $("#colors-switcher").animate({
                [mode]: "0px"
            });
        } else {
            $("#colors-switcher").animate({
                [mode]: "-189px"
            });
        }
    })
    $("#colors-switcher").animate({
        [mode]: "-189px"
    });
});

/**
 * Product Listing Page Starts
 */
$('#back_to_top').on('click', function () {
    $("html, body").animate({ scrollTop: 0 }, "slow");
});
$('#per_page_products a').on('click', function (e) {
    e.preventDefault();
    var per_page = $(this).data('value');
    $(this).parent().siblings('a.dropdown-toggle').text($(this).text());
    location.href = setUrlParameter(location.href, 'per-page', per_page);
});
$('#per_page_sellers a').on('click', function (e) {
    e.preventDefault();
    var per_page = $(this).data('value');
    $(this).parent().siblings('a.dropdown-toggle').text($(this).text());
    location.href = setUrlParameter(location.href, 'per-page', per_page);
});
$('#product_sort_by').on('change', function (e) {
    e.preventDefault();
    var sort = $(this).val();
    location.href = setUrlParameter(location.href, 'sort', sort);
});
$('#seller_search').on('keyup', function (e) {
    e.preventDefault();
    var search = $(this).val();
    location.href = setUrlParameter(location.href, 'seller_search', search);
});

$('.sub-category').on('click', function (e) {
    e.preventDefault();
    var category = $(this).data('value');
    custom_url = setUrlParameter(custom_url, 'category', category);
    location.href = custom_url;
});

$(document).on('change', '.product_attributes', function (e) {
    e.preventDefault();
    var attribute_name = $(this).data('attribute');
    attribute_name = 'filter-' + attribute_name;
    var get_param = getUrlParameter(attribute_name);
    var current_param_value = $(this).val();
    if (get_param == undefined) {
        get_param = '';
    }
    if (this.checked) {
        var param = buildUrlParameterValue(attribute_name, current_param_value, 'add', custom_url)
    } else {
        var param = buildUrlParameterValue(attribute_name, current_param_value, 'remove', custom_url);
    }
    custom_url = setUrlParameter(custom_url, attribute_name, param);
});
$('.product_filter_btn').on('click', function (e) {
    e.preventDefault();
    location.href = custom_url;
});

function buildUrlParameterValue(paramName, paramValue, action, custom_url = '') {
    if (custom_url != '') {
        var param = getUrlParameter(paramName, custom_url);
    } else {
        var param = getUrlParameter(paramName);
    }
    if (action == "add") {
        if (param == undefined) {
            param = paramValue;
        } else {
            param += "|" + paramValue;
        }
        return param;
    } else if (action == "remove") {
        if (param != undefined) {
            param = param.split('|');
            param.splice($.inArray(paramValue, param), 1);
            return param.join('|');
        } else {
            return '';
        }
    }
}

function getUrlParameter(sParam, custom_url = '') {
    sParam = sParam.replace(/\s+/g, '-');
    if (custom_url != '') {
        if (custom_url.indexOf('?') > -1) {
            var sPageURL = custom_url.substring(custom_url.indexOf('?') + 1);
        } else {
            return undefined;
        }
    } else {
        var sPageURL = window.location.search.substring(1);
    }

    var sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
}

function checkUrlHasParam(custom_url = '') {
    if (custom_url == '') {
        custom_url = window.location.href;
    }

    if (custom_url.indexOf('?') > -1) {
        return true;
    } else {
        return undefined;
    }
}

function setUrlParameter(url, paramName, paramValue) {
    paramName = paramName.replace(/\s+/g, '-');
    if (paramValue == null || paramValue == '') {
        return url.replace(new RegExp('[?&]' + paramName + '=[^&#]*(#.*)?$'), '$1')
            .replace(new RegExp('([?&])' + paramName + '=[^&]*&'), '$1');
    }
    var pattern = new RegExp('\\b(' + paramName + '=).*?(&|#|$)');
    if (url.search(pattern) >= 0) {
        return url.replace(pattern, '$1' + paramValue + '$2');
    }
    url = url.replace(/[?#]$/, '');
    return url + (url.indexOf('?') > 0 ? '&' : '?') + paramName + '=' + paramValue;
}

//Set URL in Product Listing Page Style buttons
var type_url = ''
type_url = setUrlParameter(custom_url, 'type', null);
$('#product_grid_view_btn').attr('href', type_url);
type_url = setUrlParameter(custom_url, 'type', 'list');
$('#product_list_view_btn').attr('href', type_url);
if (getUrlParameter('type') == "list") {
    $('#product_list_view_btn').addClass('active');
} else {
    $('#product_grid_view_btn').addClass('active');
}

/**
 * Product Listing Page Ends
 */
$('#category_parent').each(function() {
    $(this).select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
        dropdownCssClass: "test",
        templateResult: function(data) {
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
$('#category_parent').on('change', function (e) {
    e.preventDefault();
    var category_id = $(this).val();
    location.href = setUrlParameter(location.href, 'category_id', category_id);
});
$('#blog_search').on('keyup', function (e) {
    e.preventDefault();
    var blog_search = $(this).val();
    location.href = setUrlParameter(location.href, 'blog_search', blog_search);
});


/**
 * Login and Register Model
 */
$('.auth_model').on('click', function (e) {
    e.preventDefault();
    var value = $(this).data('value');
    $('#forgot_password_div').addClass('hide');
    if (value == "login") {
        $('#login_div').removeClass('hide');
        $('#login').addClass('active');

        $('#register_div').addClass('hide');
        $('#register').removeClass('active');
    } else if (value == "register") {
        $('#login_div').addClass('hide');
        $('#login').removeClass('active');

        $('#register_div').removeClass('hide');
        $('#register').addClass('active');
    }
});

// Product Details Page.
$('.attributes').on('change', function (e) {
    e.preventDefault();
    var selected_attributes = [];
    var attributes_length = "";
    var price = "";
    var is_variant_available = false;
    var variant = [];
    var prices = [];
    var variant_prices = [];
    var variants = [];
    var variant_ids = [];
    var image_indexes = [];
    var selected_image_index;
    $('.variants').each(function () {
        prices = {
            price: $(this).data('price'),
            special_price: $(this).data('special_price')
        };
        variant_ids.push($(this).data('id'));
        variant_prices.push(prices);
        variant = $(this).val().split(',');
        variants.push(variant);
        image_indexes.push($(this).data('image-index'));
    });
    attributes_length = variant.length;
    $('.attributes').each(function (i, e) {
        if ($(this).prop('checked')) {
            selected_attributes.push($(this).val());
            if (selected_attributes.length == attributes_length) {
                /* compare the arrays */
                prices = [];
                var selected_variant_id = '';
                $.each(variants, function (i, e) {
                    if (arrays_equal(selected_attributes, e)) {
                        is_variant_available = true;
                        prices.push(variant_prices[i]);
                        selected_variant_id = variant_ids[i];
                        selected_image_index = image_indexes[i];
                    }
                });
                if (is_variant_available) {
                    $('#add_cart').attr('data-product-variant-id', selected_variant_id);
                    galleryTop.slideTo(selected_image_index, 500, false);
                    swiperF.slideTo(selected_image_index, 500, false);
                    if (prices[0].special_price < prices[0].price && prices[0].special_price != 0) {
                        price = prices[0].special_price;
                        $('#price').html(currency + ' ' + price);
                        $('#striped-price').html(currency + ' ' + prices[0].price);
                        $('#striped-price-div').show();
                        $('#add_cart').removeAttr('disabled');
                    } else {
                        price = prices[0].price;
                        $('#price').html(currency + ' ' + price);
                        $('#striped-price-div').hide();
                        $('#add_cart').removeAttr('disabled');
                    }
                } else {
                    price = '<small class="text-danger h5">No Variant available!</small>';
                    $('#price').html(price);
                    $('#striped-price-div').hide();
                    $('#striped-price').html('');
                    $('#add_cart').attr('disabled', 'true');
                }
            }
        }
    });
    variants = "";
});

function arrays_equal(_arr1, _arr2) {
    if (!Array.isArray(_arr1) ||
        !Array.isArray(_arr2) ||
        _arr1.length !== _arr2.length
    ) {
        return false;
    }

    const arr1 = _arr1.concat().sort();
    const arr2 = _arr2.concat().sort();

    for (let i = 0; i < arr1.length; i++) {
        if (arr1[i] !== arr2[i]) {
            return false;
        }
    }

    return true;
}

$(document).on('click', '.add_to_cart', function (e) {
    e.preventDefault();
    var qty = $('[name="qty"]').val();
    $('#quick-view').data('data-product-id', $(this).data('productId'));
    var product_variant_id = $(this).attr('data-product-variant-id');
    var product_type = $(this).attr('data-product-type');
    var user_id = $(this).attr('data-user-id');
    var title = $(this).attr('data-product-title');
    var image = $(this).attr('data-product-image');
    var price = $(this).attr('data-product-price');
    var description = $(this).attr('data-product-description');
    var min = $(this).attr('data-min');
    var max = $(this).attr('data-max');
    var step = $(this).attr('data-step');
    var btn = $(this);
    var btn_html = $(this).html();
    var izi_modal = $(this).attr('data-izimodal-open');

    if (!product_variant_id) {
        Toast.fire({
            icon: 'error',
            title: "Please select variant"
        });
        return;
    }
    if (izi_modal == "" || izi_modal == undefined) {
        $.ajax({
            type: 'POST',
            url: base_url + 'cart/manage',
            data: {
                'product_variant_id': product_variant_id,
                'qty': qty,
                'is_saved_for_later': false,
                [csrfName]: csrfHash,
            },
            dataType: 'json',
            beforeSend: function () {
                btn.html('Please Wait').text('Please Wait').attr('disabled', true);
            },
            success: function (result) {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
                btn.html(btn_html).attr('disabled', false);
                if (result.error == false) {
                    Toast.fire({
                        icon: 'success',
                        title: result.message
                    });
                    $('#cart-count').text(result.data.cart_count);
                    var html = '';
                    $.each(result.data.items, function (i, e) {
                        var variant = typeof (e.product_variants.variant_values) != 'undefined' && e.product_variants.variant_values != null ? e.product_variants.variant_values : '';
                        var price = e.special_price < e.price && e.special_price != 0 ? e.special_price : e.price;
                        html += '<div class="row">' +
                            '<div class="cart-product product-sm col-md-12">' +
                            '<div class="product-image">' +
                            '<img class="pic-1" src="' + base_url + e.image + '" alt="Not Found">' +
                            '</div>' +
                            '<div class="product-details">' +
                            '<div class="product-title">' + e.name + '</div>' +
                            '<span>' + variant + '</span>' +
                            '<p class="product-descriptions">' + e.short_description + '</p>' +
                            '</div>' +
                            '<div class="product-pricing d-flex py-2 px-1 w-100">' +
                            '<div class="product-price align-self-center">' + currency + ' ' + price + '</div>' +
                            '<div class="product-sm-quantity px-1">' +
                            '<input type="number" class="form-input" value="' + min + '"  data-id="' + e.product_variant_id + '" data-price="' + price + '" min="' + min + '" max="' + max + '" step="' + step + '" >' +
                            '</div>' +
                            '<div class="product-sm-removal align-self-center">' +
                            '<button class="remove-product button button-danger" data-id="' + e.product_variant_id + '">' +
                            '<i class="fa fa-trash"></i>' +
                            '</button>' +
                            '</div>' +
                            '<div class="product-line-price align-self-center px-1">' + currency + ' ' + (e.qty * price).toLocaleString(undefined, { minimumFractionDigits: 2 }) + '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>';
                    });
                    $('#cart-item-sidebar').html(html);
                } else {
                    if (is_loggedin == 0) {
                        Toast.fire({
                            icon: 'success',
                            title: "Item added to cart"
                        });
                        var cart_item = { "product_variant_id": product_variant_id.trim(), "title": title, "description": description, "qty": min, "image": image, "price": price.trim(), "min": min, "step": step };
                        var cart = localStorage.getItem("cart");
                        cart = (localStorage.getItem("cart") !== null) ? JSON.parse(cart) : null;
                        console.log(cart);

                        if (cart !== null && cart !== undefined) {
                            cart.push(cart_item);
                        } else {
                            cart = [cart_item];
                        }
                        localStorage.setItem("cart", JSON.stringify(cart));
                        display_cart(cart);
                        return;
                    }
                    Toast.fire({
                        icon: 'error',
                        title: result.message
                    });
                }
            }
        })
    }
});

$(document).ready(function () {
    var cart = localStorage.getItem("cart");
    cart = (localStorage.getItem("cart") !== null) ? JSON.parse(cart) : null;
    if (cart) {
        display_cart(cart);
    }
});

function display_cart(cart) {
    if (is_loggedin == 0) {
        var cart_count = (cart.length) ? cart.length : "";
        $('#cart-count').text(cart_count);
        var html = '';
        if (cart !== null && cart.length > 0) {
            cart.forEach((item) => {
                html += '<div class="row">' +
                    '<div class="cart-product product-sm col-md-12">' +
                    '<div class="product-image">' +
                    '<img class="pic-1" src="' + item.image + '" alt="Not Found">' +
                    '</div>' +
                    '<div class="product-details">' +
                    '<div class="product-title">' + item.title + '</div>' +
                    '<p class="product-descriptions">' + item.description + '</p>' +
                    '</div>' +
                    '<div class="product-pricing d-flex py-2 px-1 w-100">' +
                    '<div class="product-price align-self-center">' + currency + ' ' + item.price + '</div>' +
                    '<div class="product-sm-quantity px-1">' +
                    '<input type="number" class="form-input" value="' + item.qty + '"  data-id="' + item.product_variant_id + '" data-price="' + item.price + '"min="' + item.min + '"  step="' + item.step + '">' +
                    '</div>' +
                    '<div class="product-sm-removal align-self-center">' +
                    '<button class="remove-product button button-danger" data-id="' + item.product_variant_id + '">' +
                    '<i class="fa fa-trash"></i>' +
                    '</button>' +
                    '</div>' +
                    '<div class="product-line-price align-self-center px-1">' + currency + ' ' + (item.qty * item.price).toLocaleString(undefined, { minimumFractionDigits: 2 }) + '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
            });
        }
        $('#cart-item-sidebar').html(html);
    }
}

function cart_sync() {
    var cart = localStorage.getItem("cart");
    if (cart == null || !cart) {
        var message = "No items in cart so it will not be sync";
        console.log(message);
        return;
    }
    $.ajax({
        type: 'POST',
        url: base_url + 'cart/cart_sync',
        data: {
            [csrfName]: csrfHash,
            data: cart,
            'is_saved_for_later': false,
        },
        dataType: 'json',
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.error == false) {
                Toast.fire({
                    icon: 'success',
                    title: result.message
                });
                localStorage.removeItem("cart");
                return true;
            }
        }
    });
}


$(document).ready(function () {
    $(document).on('click', '#clear_cart', function () {
        if (confirm("Are you sure want to Clear Cart?")) {
            $.ajax({
                type: "POST",
                data: {
                    [csrfName]: csrfHash
                },
                url: base_url + 'cart/clear',
                success: function (result) {
                    csrfName = result['csrfName'];
                    csrfHash = result['csrfHash'];
                    location.reload();
                }
            });
        }
    });

    $(document).on('click', '#checkout', function (e) {
        if (!confirm("Are You Sure want to Checkout?")) {
            e.preventDefault();
        }
    });
});

$('.quick-view-btn').on('click', function () {
    $('#quick-view').data('data-product-id', $(this).data('productId'));
})
$('.save-for-later').on('click', function (e) {
    e.preventDefault();
    var formdata = new FormData();
    var product_variant_id = $(this).data('id');
    var qty = $(this).parent().siblings('.item-quantity').find('.itemQty').val();
    var product = $(this);
    formdata.append(csrfName, csrfHash);
    formdata.append('product_variant_id', product_variant_id);
    formdata.append('is_saved_for_later', 1);
    formdata.append('qty', qty);
    $.ajax({
        type: 'POST',
        url: base_url + 'cart/manage',
        data: formdata,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.error == false) {
                window.location.reload();
            } else {
                Toast.fire({
                    icon: 'error',
                    title: result.message
                });
            }

        }
    });
})

$('.move-to-cart').on('click', function (e) {
    e.preventDefault();
    var formdata = new FormData();
    var product_variant_id = $(this).data('id');
    var qty = $(this).parent().parent().siblings('.itemQty').text();
    var product = $(this);
    formdata.append(csrfName, csrfHash);
    formdata.append('product_variant_id', product_variant_id);
    formdata.append('is_saved_for_later', 0);
    formdata.append('qty', qty);
    $.ajax({
        type: 'POST',
        url: base_url + 'cart/manage',
        data: formdata,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.error == false) {
                window.location.reload();
            } else {
                Toast.fire({
                    icon: 'error',
                    title: result.message
                });
            }

        }
    });
})

$('.update-order-item').on('click', function (e) {
    e.preventDefault();
    var formdata = new FormData();
    var order_item_id = $(this).data('item-id');
    var status = $(this).data('status');
    var t = $(this);
    var btn_text = t.text();
    formdata.append(csrfName, csrfHash);
    formdata.append('order_item_id', order_item_id);
    formdata.append('status', status);
    $.ajax({
        type: 'POST',
        url: base_url + 'my-account/update-order-item-status',
        data: formdata,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        beforeSend: function () {
            t.html('Please Wait').attr('disabled', true);
        },
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.error == false) {
                Toast.fire({
                    icon: 'success',
                    title: result.message
                });
                setTimeout(function () {
                    window.location.reload();
                }, 3000)

            } else {
                Toast.fire({
                    icon: 'error',
                    title: result.message
                });
            }
            t.html(btn_text).attr('disabled', false);
        }
    });
})
$('.update-order').on('click', function (e) {
    e.preventDefault();
    var formdata = new FormData();
    var order_id = $(this).data('order-id');
    var status = $(this).data('status');
    var temp = '';
    if (status == "cancelled") {
        temp = "Cancel";
    } else {
        temp = 'Return';
    }
    if (confirm('Are you sure you want to ' + temp + ' this order ?')) {
        var t = $(this);
        var btn_text = t.text();
        formdata.append(csrfName, csrfHash);
        formdata.append('order_id', order_id);
        formdata.append('status', status);
        $.ajax({
            type: 'POST',
            url: base_url + 'my-account/update-order',
            data: formdata,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function () {
                t.html('Please Wait').attr('disabled', true);
            },
            success: function (result) {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
                if (result.error == false) {
                    Toast.fire({
                        icon: 'success',
                        title: result.message
                    });
                    setTimeout(function () {
                        window.location.reload();
                    }, 3000)
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: result.message
                    });
                }
                t.html(btn_text).attr('disabled', false);
            }
        });
    }
})
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
                html += '<option value="">--Select Area--</option>';
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
$('#area').on('change', function (e) {
    e.preventDefault();
    $.ajax({
        type: 'POST',
        data: {
            'area_id': $(this).val(),
            [csrfName]: csrfHash,
        },
        url: base_url + 'my-account/get-zipcode',
        dataType: 'json',
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.error == false) {
                $('#pincode').val(result.data[0].zipcode);
            } else {
                Toast.fire({
                    icon: 'error',
                    title: result.message
                });
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

$(document).on('click', '.default-address', function (e) {
    e.preventDefault();
    if (confirm('Are you sure ? You want to set this address as default?')) {
        $.ajax({
            type: 'POST',
            data: {
                'id': $(this).data('id'),
                [csrfName]: csrfHash,
            },
            url: base_url + 'my-account/set-default-address',
            dataType: 'json',
            success: function (result) {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
                if (result.error == false) {
                    $('#address_list_table').bootstrapTable('refresh');
                    Toast.fire({
                        icon: 'success',
                        title: result.message
                    });
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

$(document).on('click', "#forgot_password_link", function (e) {
    e.preventDefault();
    $('.auth-modal').find('header a').removeClass('active')
    $('#forgot_password_div').removeClass('hide').siblings('section').addClass('hide');
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

$(document).on('submit', '#send_forgot_password_otp_form', function (e) {
    e.preventDefault();
    var send_otp_btn = $('#forgot_password_send_otp_btn').html();
    $('#forgot_password_send_otp_btn').html('Please Wait...').attr('disabled', true);
    var phoneNumber = $('.selected-dial-code').html() + $('#forgot_password_number').val();
    var response = is_user_exist($('#forgot_password_number').val());
    if (response.error == false) {
        $('#forgot_pass_error_box').html("You have not registered using this number.");
        $('#forgot_password_send_otp_btn').html(send_otp_btn).attr('disabled', false);
    } else {
        var appVerifier = window.recaptchaVerifier;
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
                var url = base_url + "home/reset-password";
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

function customer_wallet_query_params(p) {
    return {
        transaction_type: 'wallet',
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
$('#contact-us-form').on('submit', function (e) {
    e.preventDefault();
    var submit_btn_html = $("#contact-us-submit-btn").html();
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
            $('#contact-us-submit-btn').html('Please Wait...').attr('disabled', true);
        },
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.error == false) {
                Toast.fire({
                    icon: 'success',
                    title: result.message
                });
                $('#contact-us-form')[0].reset();
            } else {
                Toast.fire({
                    icon: 'error',
                    title: result.message
                });
            }
            $('#contact-us-submit-btn').html(submit_btn_html).attr('disabled', false);
        }
    })
})

$('#product-rating-form').on('submit', function (e) {
    e.preventDefault();
    var submit_btn_html = $('#rating-submit-btn').html();
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
            $('#rating-submit-btn').html('Please Wait...').attr('disabled', true);
        },
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.error == false) {
                Toast.fire({
                    icon: 'success',
                    title: result.message
                });
                $('#product-rating-form')[0].reset();
                window.location.reload();
            } else {
                Toast.fire({
                    icon: 'error',
                    title: result.message
                });
            }
            $('#rating-submit-btn').html(submit_btn_html).attr('disabled', false);
        }
    })
})

$('#delete_rating').on('click', function (e) {
    e.preventDefault();
    if (confirm("Are you sure want to Delete Rating ?")) {
        var rating_id = $(this).data('rating-id');
        $.ajax({
            type: "POST",
            data: {
                [csrfName]: csrfHash,
                'rating_id': rating_id,
            },
            url: $(this).attr('href'),
            dataType: 'json',
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result.error == false) {
                    Toast.fire({
                        icon: 'success',
                        title: result.message
                    });
                    $('#delete_rating').parent().parent().parent().remove();
                    $('#no_ratings').text(result.data.rating[0].no_of_rating);
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: result.message
                    });
                }
            }
        });
    }
});
$('#edit_link').on('click', function (e) {
    e.preventDefault();
    $('#rating-box').removeClass('d-none');
});

$('#load-user-ratings').on('click', function (e) {
    e.preventDefault();
    var limit = $(this).attr('data-limit');
    var offset = $(this).attr('data-offset');
    var product_id = $(this).attr('data-product');
    var btn_html = $(this).html();
    var btn = $(this);
    var html = "";
    $.ajax({
        type: "GET",
        data: {
            'limit': limit,
            'offset': offset,
            'product_id': product_id,
        },
        url: base_url + "products/get-rating",
        dataType: 'json',
        beforeSend: function () {
            $(this).html('Please wait..').attr('disabled', true);
        },
        success: function (result) {
            $(this).html(btn_html).attr('disabled', false);
            if (result.error == false) {

                $.each(result.data.product_rating, function (i, e) {
                    html += '<li class="review-container">' +
                        '<div class="review-image">' +
                        '<img src="' + base_url + 'assets/front_end/modern/images/user.png" alt="" width="65" height="65">' +
                        '</div>' +
                        '<div class="review-comment">' +
                        '<div class="rating-list">' +
                        '<div class="product-rating">' +
                        '<input type="text" class="kv-fa" value="' + e.rating + '" data-size="xs" title="" readonly>' +
                        '</div>' +
                        '</div>' +
                        '<div class="review-info">' +
                        '<h4 class="reviewer-name">' + e.user_name + '</h4>' +
                        ' <span class="review-date text-muted">' + e.data_added + '</span>' +
                        '</div>' +
                        '<div class="review-text">' +
                        '<p class="text-muted">' + e.comment + '</p>' +
                        '</div>' +
                        '<div class="row reviews">';
                    $.each(e.images, function (j, image) {
                        html += '<div class="col-md-2">' +
                            '<div class="review-box">' +
                            '<a href="' + image + '" data-lightbox="review-images">' +
                            '<img src="' + image + '" alt="' + image + '">' +
                            '</a>' +
                            '</div>' +
                            '</div>'
                    });

                    html += '</div>' +
                        '</div>' +
                        '</li>';
                });
                offset += limit;
                $('#review-list').append(html);
                $(".kv-fa").rating('create', { filledStar: '<i class="fas fa-star"></i>', emptyStar: '<i class="far fa-star"></i>', size: 'xs', showCaption: false });
                btn.attr('data-offset', offset);
            } else {
                Toast.fire({
                    icon: 'error',
                    title: result.message
                });
            }
        }
    });
});

$('#edit_city').on('change', function (e, area_id) {
    e.preventDefault();
    var city_id = $(this).val();
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
                    var is_selected = (e.city_id == city_id && e.id == area_id) ? "selected" : "";
                    html += '<option value=' + e.id + ' ' + is_selected + '>' + e.name + '</option>';
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

$('#edit_area').on('change', function (e, area_id) {
    e.preventDefault();
    var area_id1 = (area_id == "" || area_id == "undefined") ? $(this).val() : area_id;
    $.ajax({
        type: 'POST',
        data: {
            'area_id': area_id1,
            [csrfName]: csrfHash,
        },
        url: base_url + 'my-account/get-zipcode',
        dataType: 'json',
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.error == false) {
                $('#edit_pincode').val(result.data[0].zipcode);
            } else {
                Toast.fire({
                    icon: 'error',
                    title: result.message
                });
            }
        }
    })
});
if ($('#product-filters').length) {
    if (!checkUrlHasParam()) {
        sessionStorage.setItem($('#product-filters').data('key'), $('#product-filters').val());
        var filters = sessionStorage.getItem($('#product-filters').data('key'));
        filters = filters.replace(/\\/g, "");
        print_filters(filters, 'Desktop', '#product-filters-desktop');
        print_filters(filters, 'Mobile', '#product-filters-mobile');
    } else {
        if (sessionStorage.getItem($('#product-filters').data('key')) == undefined) {
            sessionStorage.setItem($('#product-filters').data('key'), $('#product-filters').val());
        }
        var filters = sessionStorage.getItem($('#product-filters').data('key'));
        filters = filters.replace(/\\/g, "");
        print_filters(filters, 'Desktop', '#product-filters-desktop');
        print_filters(filters, 'Mobile', '#product-filters-mobile');
    }
}

function print_filters(filters, prefix = '', target) {
    var html = '';
    var attribute_values_id;
    var attribute_values;
    var new_attr_val;
    var attr_name;
    var collapse_status;
    var selected_attributes;
    var attr_checked_status;
    var e_name;
    if (filters != "") {
        $.each(JSON.parse(filters), function (i, e) {
            e_name = e.name.replace(' ', '-').toLowerCase();
            e_name = decodeURIComponent(e_name);
            attr_name = getUrlParameter('filter-' + e_name);
            collapse_status = (attr_name == undefined) ? " " : "show";
            selected_attributes = (attr_name != undefined) ? attr_name.split('|') : "";
            html += '<div class="card-custom">' +
                '<div class="card-header-custom" id="h' + i + '">' +
                '<h2 class="clearfix mb-0">' +
                '<a class="collapse-arrow btn btn-link collapsed" data-toggle="collapse" data-target="#' + prefix + i + '" aria-expanded="true" aria-controls="#' + prefix + i + '">' + e.name + '<i class="fa fa-angle-down rotate"></i></a>' +
                '</h2>' +
                '</div>' +
                '<div id="' + prefix + i + '" class="collapse ' + collapse_status + '" aria-labelledby="h' + i + '" data-parent="#accordionExample">' +
                '<div class="card-body-custom">';
            attribute_values_id = e.attribute_values_id.split(',');
            attribute_values = e.attribute_values.split(',');

            $.each(attribute_values, function (j, v) {
                attr_checked_status = ($.inArray(v, selected_attributes) !== -1) ? "checked" : "";
                new_attr_val = e_name + ' ' + v;
                html += '<div class="input-container d-flex">' +
                    '<input type="checkbox" name="' + v + '" value="' + v + '" class="toggle-input product_attributes" id="' + prefix + new_attr_val + '" data-attribute="' + e_name + '" ' + attr_checked_status + '>' +
                    '<label class="toggle checkbox" for="' + prefix + new_attr_val + '">' +
                    '<div class="toggle-inner"></div>' +
                    '</label>' +
                    '<label for="' + prefix + new_attr_val + '" class="text-label">' + v + '</label></div>';

            });
            html += '</div>' +
                '</div>' +
                '</div>';
        });
    }
    $(target).html(html);
}
$(document).on('closed', '#quick-view', function (e) {
    $("#modal-product-special-price").html('');
});

window.addEventListener('load', addDarkmodeWidget);
const options = {
    time: '0.5s', // default: '0.3s'
    mixColor: '#fff', // default: '#fff'
    backgroundColor: '#fff', // default: '#fff'
    buttonColorDark: '#100f2c', // default: '#100f2c'
    buttonColorLight: '#fff', // default: '#fff'
    label: '🌕', // default: ' 🌙'
    autoMatchOsTheme: false // default: true
}

function addDarkmodeWidget() {
    new Darkmode(options).showWidget();
}
$(document).ready(function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    }

    function showPosition(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;
        sessionStorage.setItem("latitude", latitude);
        sessionStorage.setItem("longitude", longitude);
    }

    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                if (sessionStorage.getItem("latitude") !== null) {
                    sessionStorage.removeItem("latitude");
                }
                if (sessionStorage.getItem("longitude") !== null) {
                    sessionStorage.removeItem("longitude");
                }
                break;
            case error.POSITION_UNAVAILABLE:
                console.log("Location information is unavailable.");
                break;
            case error.TIMEOUT:
                console.log("The request to get user location timed out.");
                break;
            case error.UNKNOWN_ERROR:
                console.log("An unknown error occurred.");
                break;
        }
    }
});

$('#send_bank_receipt_form').on('submit', function (e) {
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
            $('#submit_btn').html('Send').attr('disabled', false);
            if (result.error == false) {
                $('table').bootstrapTable('refresh');
                Toast.fire({
                    icon: 'success',
                    title: result.message
                });
                window.location.reload();
            } else {
                Toast.fire({
                    icon: 'error',
                    title: result.message
                });
            }
        }
    });
});


$(document).ready(function () {
    if ($('.hrDiv').length) {
        $('.hrDiv p').addClass('hrDiv');
        $("div").css({ 'font-size': '', 'font': '' });
    }
});

$('#validate-zipcode-form').on('submit', function (e) {
    e.preventDefault();
    var formdata = new FormData(this);
    formdata.append(csrfName, csrfHash);

    $.ajax({
        type: 'POST',
        url: base_url + 'products/check_zipcode',
        data: formdata,
        beforeSend: function () {
            $('#validate_zipcode').html('Please Wait..').attr('disabled', true);
        },
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (result) {
            csrfHash = result.csrfHash;
            $('#validate_zipcode').html('Check Availability').attr('disabled', false);
            if (result.error == false) {
                $('#add_cart').removeAttr('disabled');
                $('#error_box').html(result.message);
            } else {
                $('#add_cart').attr('disabled', 'true');
                $('#error_box').html(result.message);
            }
        }
    });
});
$(document).on('submit', '.validate_zipcode_quick_view', function (e) {
    e.preventDefault();
    var formdata = new FormData(this);
    formdata.append(csrfName, csrfHash);

    $.ajax({
        type: 'post',
        url: base_url + "products/check-zipcode",
        data: formdata,
        beforeSend: function () {
            $('#validate_zipcode').html('Please Wait..').attr('disabled', true);
        },
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (result) {
            csrfHash = result.csrfHash;
            $('#validate_zipcode').html('Check Availability').attr('disabled', false);
            if (result.error == false) {
                $('#modal-add-to-cart-button').removeAttr('disabled');
                $('#error_box1').html(result.message);
            } else {
                $('#modal-add-to-cart-button').attr('disabled', 'true');
                $('#error_box1').html(result.message);
            }
        }
    });
});
$(".view_cart_button").click(function () {
    if (is_loggedin == 0) {
        $('#modal-custom').iziModal('open');
        $('#login_div').removeClass('hide');
        $('#login').addClass('active');
        $('#register_div').addClass('hide');
        $('#register').removeClass('active');
        return false;
    } else {
        return true;
    }
});

function usercartTotal() {
    var cartTotal = 0;
    $("#cart_item_table > tbody > tr > .total-price  > .product-line-price").each(function (i) {
        cartTotal = parseFloat(cartTotal) + parseFloat($(this).text().replace(/[^\d\.]/g, ''));
    });
    $("#final_total").text(cartTotal.toFixed(2));
}

// var viewportWidth = $(window).width(); // original width 

// const refresh_window_html = '<div class="refresh-page"><h2 class="text-white text-center">Please refresh the page...<br> Since, the page has been resized </h2></div>';
// $(window).on('resize', function () {
//     if ($(".refresh-page").length == 0 && (viewportWidth != $(this).width())) {
//         $("body").prepend(refresh_window_html);
//     }
// });

window.editAddress = {
    'click .edit-address': function (e, value, row, index) {
        $("#address_id").val(row.id);
        $("#edit_name").val(row.name);
        $("#edit_mobile").val(row.mobile);
        $("#edit_address").val(row.address);
        $("#edit_state").val(row.state);
        $("#edit_country").val(row.country);
        $("#edit_city").val(row.city_id).trigger('change', [row.area_id]);
        $("#edit_area").val(row.area_id).trigger('change', [row.area_id]);
        $('input[type=radio][value=' + row.type.toLowerCase() + ']').attr('checked', true);
    }
};

$(document).ready(function () {
    if (localStorage.getItem("compare")) {
        var compare = localStorage.getItem("compare").length;
        compare = (compare !== null) ? JSON.parse(compare) : null;
        if (compare) {
            display_compare();
        }
    }
});
$(document).on('click', '.compare', function (e) {
    e.preventDefault();
    var product_id = $(this).attr('data-product-id');
    var product_variant_id = $(this).attr('data-product-variant-id');
    var compare_item = {
        "product_id": product_id.trim(),
        "product_variant_id": product_variant_id.trim(),
    };
    var compare = localStorage.getItem('compare');
    Toast.fire({
        icon: 'success',
        title: "products added to compare list"
    });
    compare = (compare !== null) ? JSON.parse(compare) : null;
    if (compare !== null && compare !== undefined) {
        if (compare.find((item) => item.product_id === product_id)) {
            Toast.fire({
                icon: 'error',
                title: "This item is already present in your compare list"
            });
            return;
        }
        compare.push(compare_item);
    } else {
        compare = [compare_item];
    }
    localStorage.setItem("compare", JSON.stringify(compare));
    var compare_count = (compare.length) ? compare.length : "";
    $('#compare_count').text(compare_count);
    if (compare !== null && compare_count <= 1) {
        Toast.fire({
            icon: 'error',
            title: "Please select 1 more item to compare"
        });
        return false;
    }
});

function display_compare() {
    var compare = localStorage.getItem('compare');
    compare = (localStorage.getItem('compare') !== null) ? compare : null;
    $.ajax({
        type: 'POST',
        url: base_url + 'compare/add_to_compare',
        data: {
            "product_id": compare,
            "product_variant_id": compare,
            [csrfName]: csrfHash,
        },
        dataType: 'json',
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            var compare_count = (compare.length) ? compare.length : "base_url()";
            $('#compare_count').text(result.data.total);
            var comp = '';
            if (result.error == false) {
                if (compare !== null && compare_count > 0) {
                    comp += '<div class="text-right">' +
                        '<div class="compare-removal"><button class="remove-compare button button-danger" >Clear Compare</button></div></div>' +
                        '</div>' +
                        '<table class="compare-table">' +
                        '<tbody>' +
                        '<tr>' +
                        '<th class="compare-field"> </th>';
                    $.each(result.data.product, function (i, e) {
                        var variant_price = (e.variants[0]['special_price'] > 0 && e.variants[0]['special_price'] != '') ? e.variants[0]['special_price'] : e.variants[0]['price'];
                        var data_min = e.minimum_order_quantity ? e.minimum_order_quantity : 1;
                        var data_step = e.minimum_order_quantity && e.quantity_step_size ? e.quantity_step_size : 1;
                        var data_max = e.total_allowed_quantity ? e.total_allowed_quantity : 1;
                        comp += '<td class="compare_item text-center text-justify">' +
                            '<div class="text-right"><a class="remove-compare-item"' +
                            'data-product-id="' + e.id + '" style="padding: 4px 8px border:0px !important" >' +
                            '<i class="fa-times fa-times-plus fa-lg fa link-color"></i></a></div><br>' +
                            '<div class="product-grid" style="border:1px !important; padding:0 0 0px;"><div class="product-image"><div class="product-image-container"><a href="products/details/' + e.slug + '"><img class="pic-1" src="' + e.image + '"></a></div></div><div itemscope itemtype="https://schema.org/Product">';
                        if (e.rating && e.no_of_rating != "") {
                            comp += '<div class="col-md-12 mb-3 product-rating-small" dir="ltr" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating"><meta itemprop="reviewCount" content="' + e.no_of_rating + '" /><meta itemprop="ratingValue" content="' + e.rating + '" /><input type="text" class="kv-fa rating-loading" value="' + e.rating + '" data-size="sm" title="" readonly> <span class="my-auto mx-3"> ( ' + e.no_of_ratings + ' reviews) </span></div>';
                        } else {
                            comp += '<div class="col-md-12 mb-3 product-rating-small" dir="ltr"><input type="text" class="kv-fa rating-loading" value="' + e.rating + '" data-size="sm" title="" readonly> <span class="my-auto mx-3"> ( ' + e.no_of_ratings + ' reviews) </span></div>';
                        }

                        comp += '</div>';
                        comp += ' <h3 class="data-product-title" ><a href="products/details/' + e.slug + '">' + e.name + '</a></h3>   <div class="price mb-1">' + currency + ' ' + (e.type == "simple_product" ? e.variants[0]['price'] :
                            (e.min_max_price.max_special_price) + ' - ' + e.min_max_price.max_price) + ' </div>';
                        if (e.type == "simple_product") {
                            var variant_id = e.variants[0]['id']
                            var modal = "";
                        } else {
                            var variant_id = ""
                            var modal = "#quick-view";
                        }
                        comp += '  <a href="#" class="add-to-cart add_to_cart" data-product-id="' + e.id + '" data-product-variant-id="' + variant_id + '" data-izimodal-open="' + modal + '" data-product-title="' + e.name + '" data-product-image="' + e.image + '" data-product-description="' + e.short_description + '"  data-product-price="' + variant_price + '" data-min="' + data_min + '" data-max="' + data_max + '" data-step="' + data_step + '"><i class="fas fa-cart-plus"></i> Add to Cart</a>';
                        '</td>';
                    });
                    comp += '</tr>';
                    comp += '<tr>' +
                        '<th class="compare-field text-center text-justify">Description </th>';
                    $.each(result.data.product, function (i, e) {
                        comp += '<td class="text-center text-justify" data-title="Availability">' + (e.short_description ? e.short_description :
                            e.short_description = "-") + '</td>';
                    });
                    comp += '</tr>';
                    comp += '<tr>' +
                        '<th class="compare-field text-center text-justify">variants </th>';
                    $.each(result.data.product, function (i, e) {
                        var attribute_name = e.variants[0]['attr_name'].split(',');
                        var attribute_values = e.variants[0]['variant_values'].split(',');
                        if (e.type == "variable_product") {
                            comp += '<td class="text-center text-justify" data-title="variants">';
                            for (var i = 0; i < attribute_name.length; i++) {
                                if (attribute_name[i] !== attribute_values[i]) {
                                    comp += attribute_name[i] + ' : ' + attribute_values[i] + '<br>';
                                }
                            }
                            comp += '</td>';
                        } else {
                            comp += '<td class="text-center text-justify" data-title="variants">-</td>';
                        }
                    });
                    comp += '</tr>';
                    comp += '<tr>' +
                        '<th class="compare-field text-center text-justify">Availability </th>';
                    $.each(result.data.product, function (i, e) {
                        comp += '<td class="text-center text-justify" data-title="Availability">' + (e.availability == "1" ? e.availability = "In Stock" :
                            e.availability = "-") + '</td>';
                    });
                    comp += '</tr>';
                    comp += '<tr>' +
                        '<th class="compare-field text-center text-justify">Made In </th>';
                    $.each(result.data.product, function (i, e) {
                        comp += '<td class="text-center text-justify" data-title="made in">' + (e.made_in ? e.made_in : '-') + '</td>';
                    });
                    comp += '</tr>';
                    comp += '<tr>' +
                        '<th class="compare-field text-center text-justify">Warranty</th>';
                    $.each(result.data.product, function (i, e) {
                        comp += '<td class="text-center text-justify" data-title="warranty period">' + (e.warranty_period ? e.warranty_period : '-') + '</td>';
                    });
                    comp += '</tr>';
                    comp += '<tr>' +
                        '<th class="compare-field text-center text-justify">Gurantee</th>';
                    $.each(result.data.product, function (i, e) {
                        comp += '<td class="text-center text-justify" data-title="warranty period">' + (e.guarantee_period ? e.guarantee_period : '-') + '</td>';
                    });
                    comp += '</tr>';
                    comp += '<tr>' +
                        '<th class="compare-field text-center text-justify">Returnable</th>';
                    $.each(result.data.product, function (i, e) {
                        comp += '<td class="text-center text-justify" data-title="Returnable">' + (e.is_returnable == "1" ? e.is_returnable = "Yes" :
                            e.is_returnable = "No") + '</td>';
                    });
                    comp += '</tr>';
                    comp += '<tr>' +
                        '<th class="compare-field text-center text-justify">Cancelable</th>';
                    $.each(result.data.product, function (i, e) {
                        comp += '<td class="text-center text-justify" data-title="cancelable">' + (e.is_cancelable == "1" ? e.is_cancelable = "Yes" :
                            e.is_cancelable = "No") + '</td>';
                    });
                    comp += '</tr>';
                    comp += '</tbody>' +
                        '</table>';
                }
                $('#compare-items').html(comp);
                $('.kv-fa').rating({
                    theme: 'krajee-fa',
                    filledStar: '<i class="fas fa-star"></i>',
                    emptyStar: '<i class="far fa-star"></i>',
                    showClear: false,
                    showCaption: false,
                    size: 'md',
                });
            } else {
                Toast.fire({
                    icon: 'error',
                    title: result.message
                });
            }
        }
    });
}
$(document).on('click', '.remove-compare-item', function (e) {
    e.preventDefault();
    var product_id = $(this).attr('data-product-id');
    if (confirm("Are you sure want to remove this?")) {
        var compare_count = $('#compare_count').text();
        compare_count--;
        $('#compare_count').text(compare_count);
        if (compare_count < 1) {
            $(this).parent().parent().remove();
            location.reload();
        } else {
            $(this).parent().parent().remove();
        }
        var compare = localStorage.getItem("compare");
        compare = (compare !== null) ? JSON.parse(compare) : null;
        if (compare) {
            var new_compare = compare.filter(function (item) {
                return item.product_id != product_id
            });
            localStorage.setItem("compare", JSON.stringify(new_compare));
            display_compare();
        }
    }
});
// Clear  compare item.
$(document).on('click', '.compare-removal button', function (e) {
    e.preventDefault();
    var id = $(this).attr('data-product-id');
    var compare = $(this).parent().parent().parent();
    if (confirm("Are you sure want to remove this?")) {
        localStorage.removeItem("compare");
        location.reload();
        var compare = localStorage.getItem("compare");
        compare = (localStorage.getItem("compare") !== null) ? JSON.parse(compare) : null;
        if (compare) {
            var new_compare = compare.filter(function (item) {
                return item.id != id
            });
            localStorage.setItem("compare", JSON.stringify(new_compare));
            if (compare)
                display_compare(new_compare);
        }
    }
});

$(document).on('submit', '#add-faqs', function (e) {
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
                Toast.fire({
                    icon: 'success',
                    title: result.message
                });
                $('#add-faqs')[0].reset();
            } else {
                Toast.fire({
                    icon: 'error',
                    title: result.message
                });
            }
            setTimeout(function () { location.reload(); }, 1000);
        }
    });
});

// select 2 js select countries
$(".search_faqs").select2({
    ajax: {
        url: base_url + 'products/get_faqs_data',
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
    placeholder: 'Search for faqs',
});

// Enable/Disable inspect element
$(function () {
    var data = $("#inspect_value").data('value');
    // console.log(data);
    return false;
    if (data == 1) {
        $(this).bind("contextmenu", function (e) {
            e.preventDefault();
        });
        document.onkeydown = function (e) {
            if (event.keyCode == 123) {
                return false;
            }
            if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
                return false;
            }
            if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
                return false;
            }
            if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
                return false;
            }
            if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
                return false;
            }
        }
    } else { }
});
"use strict";
var stripe1;
var fatoorah_url = '';
$(document).ready(function() {
    var addresses = [];

    function midtrans_setup(midtrans_transaction_token) {

        // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
        window.snap.pay(midtrans_transaction_token, {
            onSuccess: function(result) {
                /* You may add your own implementation here */
                // alert("payment success!");
                console.log(result);
                place_order().done(function(result) {
                    if (result.error == false) {
                        setTimeout(function() {
                            location.href = base_url + 'payment/success';
                        }, 3000);
                    }
                });
            },
            onPending: function(result) {
                /* You may add your own implementation here */
                alert("wating your payment!");
                console.log(result);
            },
            onError: function(result) {
                /* You may add your own implementation here */
                alert("payment failed!");
                $('#place_order_btn').attr('disabled', false).html('Place Order');
                console.log(result);
            },
            onClose: function() {
                /* You may add your own implementation here */
                $('#place_order_btn').attr('disabled', false).html('Place Order');
                alert('you closed the popup without finishing the payment');
            }
        });
    }





    function razorpay_setup(key, amount, app_name, logo, razorpay_order_id, username, user_email, user_contact) {
        var options = {
            "key": key, // Enter the Key ID generated from the Dashboard
            "amount": (amount * 100), // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
            "currency": "INR",
            "name": app_name,
            "description": "Product Purchase",
            "image": logo,
            "order_id": razorpay_order_id, //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
            "handler": function(response) {
                $('#razorpay_payment_id').val(response.razorpay_payment_id);
                $('#razorpay_signature').val(response.razorpay_signature);
                place_order().done(function(result) {
                    if (result.error == false) {
                        setTimeout(function() {
                            location.href = base_url + 'payment/success';
                        }, 3000);
                    }
                });
            },
            "prefill": {
                "name": username,
                "email": user_email,
                "contact": user_contact
            },
            "notes": {
                "address": app_name + " Purchase"
            },
            "theme": {
                "color": "#3399cc"
            },
            "escape": false,
            "modal": {
                "ondismiss": function() {
                    // $('#place_order_btn').attr('disabled', false).html('Place Order');
                }
            }
        };
        var rzp = new Razorpay(options);
        return rzp;
    }

    function paystack_setup(key, user_email, order_amount) {
        var handler = PaystackPop.setup({
            key: key,
            email: user_email,
            amount: (order_amount * 100),
            currency: "NGN",
            callback: function(response) {
                $('#paystack_reference').val(response.reference);
                if (response.status == "success") {
                    place_order().done(function(result) {
                        if (result.error == false) {
                            setTimeout(function() {
                                location.href = base_url + 'payment/success';
                            }, 3000);
                        }
                    });
                } else {
                    location.href = base_url + 'payment/cancel';
                }
            },
            onClose: function() {
                $('#place_order_btn').attr('disabled', false).html('Place Order');
            }
        });
        return handler;

    }

    function stripe_setup(key) {
        // A reference to Stripe.js initialized with a fake API key.
        // Sign in to see examples pre-filled with your key.
        var stripe = Stripe(key);
        // Disable the button until we have Stripe set up on the page
        var elements = stripe.elements();
        var style = {
            base: {
                color: "#32325d",
                fontFamily: 'Arial, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "16px",
                "::placeholder": {
                    color: "#32325d"
                }
            },
            invalid: {
                fontFamily: 'Arial, sans-serif',
                color: "#fa755a",
                iconColor: "#fa755a"
            }
        };

        var card = elements.create("card", {
            style: style
        });
        card.mount("#stripe-card-element");

        card.on("change", function(event) {
            // Disable the Pay button if there are no card details in the Element
            document.querySelector("button").disabled = event.empty;
            document.querySelector("#card-error").textContent = event.error ? event.error.message : "";
        });
        return {
            'stripe': stripe,
            'card': card
        };
    }

    function stripe_payment(stripe, card, clientSecret) {
        // Calls stripe.confirmCardPayment
        // If the card requires authentication Stripe shows a pop-up modal to
        // prompt the user to enter authentication details without leaving your page.
        stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: card
                }
            })
            .then(function(result) {
                if (result.error) {
                    // Show error to your customer
                    var errorMsg = document.querySelector("#card-error");
                    errorMsg.textContent = result.error.message;
                    setTimeout(function() {
                        errorMsg.textContent = "";
                    }, 4000);
                    Toast.fire({
                        icon: 'error',
                        title: result.error.message
                    });
                    $('#place_order_btn').attr('disabled', false).html('Place Order');
                } else {
                    // The payment succeeded!
                    place_order().done(function(result) {
                        if (result.error == false) {
                            setTimeout(function() {
                                location.href = base_url + 'payment/success';
                            }, 1000);
                        }
                    });
                }
            });
    };


    function flutterwave_payment() {
        var address_id = $("#address_id").val();
        if ($('#wallet_balance').is(":checked")) {
            var wallet_used = 1;
        } else {
            var wallet_used = 0;
        }

        var promo_set = $('#promo_set').val();
        var promo_code = '';
        if (promo_set == 1) {
            promo_code = $('#promocode_input').val();
        }
        var logo = $('#logo').val();
        var public_key = $('#flutterwave_public_key').val();
        var currency_code = $('#flutterwave_currency').val();
        switch (currency_code) {
            case 'KES':
                var country = 'KE';
                break;
            case 'GHS':
                var country = 'GH';
                break;
            case 'ZAR':
                var country = 'ZA';
                break;
            case 'TZS':
                var country = 'TZ';
                break;

            default:
                var country = 'NG';
                break;
        }
        $.post(base_url + "cart/pre-payment-setup", {
            [csrfName]: csrfHash,
            'payment_method': 'Flutterwave',
            'wallet_used': wallet_used,
            'address_id': address_id,
            'promo_code': promo_code
        }, function(data) {
            csrfName = data.csrfName;
            csrfHash = data.csrfHash;
            if (data.error == false) {
                var amount = data.final_amount;
                var phone_number = $('#user_contact').val();
                var email = $('#user_email').val();
                var name = $('#username').val();
                var title = $('#app_name').val();
                var d = new Date();
                var ms = d.getMilliseconds();
                var number = Math.floor(1000 + Math.random() * 9000);
                var tx_ref = title + '-' + ms + '-' + number
                FlutterwaveCheckout({
                    public_key: public_key,
                    tx_ref: tx_ref,
                    amount: amount,
                    currency: currency_code,
                    country: country,
                    payment_options: "card,mobilemoney,ussd",
                    customer: {
                        email: email,
                        phone_number: phone_number,
                        name: name,
                    },
                    callback: function(data) { // specified callback function
                        if (data.status == "successful") {
                            $("#flutterwave_transaction_id").val(data.transaction_id);
                            $("#flutterwave_transaction_ref").val(data.tx_ref);
                            place_order().done(function(result) {
                                if (result.error == false) {
                                    setTimeout(function() {
                                        location.href = base_url + 'payment/success';
                                    }, 3000);
                                }
                            });
                        } else {
                            location.href = base_url + 'payment/cancel';
                        }
                    },
                    customizations: {
                        title: title,
                        description: "Payment for product purchase",
                        logo: logo,
                    },
                });
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Something went wrong!'
                });
            }
        }, "json");
    }
    $("#checkout_form").on('submit', function(event) {
        event.preventDefault();
        var fatoorah_order_id = "";

        var address_id = $("#address_id").val();
        if ($('#wallet_balance').is(":checked")) {
            var wallet_used = 1;
        } else {
            var wallet_used = 0;
        }

        var promo_set = $('#promo_set').val();
        var promo_code = '';
        if (promo_set == 1) {
            promo_code = $('#promocode_input').val();
        }
        var final_total = $("#final_total").text();
        final_total = final_total.replace(',', '');
        var btn_html = $('#place_order_btn').html();
        $('#place_order_btn').attr('disabled', true).html('Please Wait...');
        if ($('#is_time_slots_enabled').val() == 1 && $('input[name="delivery_time"]').is(':checked') == false && $('#product_type').val() != 'digital_product') {
            Toast.fire({
                icon: 'error',
                title: "Please select Delivery Date & Time."
            });
            $('#place_order_btn').attr('disabled', false).html(btn_html);
            return false;
        }
        var address_id = $('#address_id').val();
        if ((address_id == null || address_id == undefined || address_id == '') && $('#product_type').val() != 'digital_product') {
            Toast.fire({
                icon: 'error',
                title: "Please add/choose address."
            });
            $('#place_order_btn').attr('disabled', false).html(btn_html);
            return false;
        }
        var payment_methods = $("input[name='payment_method']:checked").val();
        if (payment_methods == "Stripe") {
            $.post(base_url + "cart/pre-payment-setup", {
                [csrfName]: csrfHash,
                'payment_method': 'Stripe',
                'wallet_used': wallet_used,
                'address_id': address_id,
                'promo_code': promo_code
            }, function(data) {
                $('#stripe_client_secret').val(data.client_secret);
                $('#stripe_payment_id').val(data.id);
                var stripe_client_secret = data.client_secret;
                stripe_payment(stripe1.stripe, stripe1.card, stripe_client_secret);
                csrfName = data.csrfName;
                csrfHash = data.csrfHash;
            }, "json");

        } else if (payment_methods == "Paystack") {
            var key = $('#paystack_key_id').val();
            var user_email = $('#user_email').val();
            $.post(base_url + "cart/pre-payment-setup", {
                [csrfName]: csrfHash,
                'payment_method': 'Paystack',
                'wallet_used': wallet_used,
                'address_id': address_id,
                'promo_code': promo_code
            }, function(data) {
                csrfName = data.csrfName;
                csrfHash = data.csrfHash;
                if (data.error == false) {
                    var handler = paystack_setup(key, user_email, data.final_amount);
                    handler.openIframe();
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Something went wrong!'
                    });
                }

            }, "json");

        } else if (payment_methods == "Razorpay") {
            $.post(base_url + "cart/pre-payment-setup", {
                [csrfName]: csrfHash,
                'payment_method': 'Razorpay',
                'wallet_used': wallet_used,
                'address_id': address_id,
                'promo_code': promo_code
            }, function(data) {
                csrfName = data.csrfName;
                csrfHash = data.csrfHash;
                if (data.error == false) {
                    $('#razorpay_order_id').val(data.order_id);
                    var key = $('#razorpay_key_id').val();
                    var app_name = $('#app_name').val();
                    var logo = $('#logo').val();
                    var razorpay_order_id = $('#razorpay_order_id').val();
                    var username = $('#username').val();
                    var user_email = $('#user_email').val();
                    var user_contact = $('#user_contact').val();
                    var rzp1 = razorpay_setup(key, final_total, app_name, logo, razorpay_order_id, username, user_email, user_contact);
                    rzp1.open();
                    rzp1.on('payment.failed', function(response) {
                        location.href = base_url + 'payment/cancel';
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.message
                    });
                }
            }, "json");
        } else if (payment_methods == "Midtrans") {
            $.post(base_url + "cart/pre-payment-setup", {
                [csrfName]: csrfHash,
                'payment_method': 'Midtrans',
                'wallet_used': wallet_used,
                'address_id': address_id,
                'promo_code': promo_code
            }, function(data) {
                csrfName = data.csrfName;
                csrfHash = data.csrfHash;
                if (data.error == false) {
                    $('#midtrans_transaction_token').val(data.token);
                    $('#midtrans_order_id').val(data.order_id);
                    var key = $('#razorpay_key_id').val();
                    var app_name = $('#app_name').val();
                    var logo = $('#logo').val();
                    var midtrans_transaction_token = data.token;
                    var username = $('#username').val();
                    var user_email = $('#user_email').val();
                    var user_contact = $('#user_contact').val();
                    var midtrans_payment = midtrans_setup(midtrans_transaction_token);
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.message
                    });
                }
            }, "json");
        } else if (payment_methods == "my_fatoorah") {
            place_order().done(function(result) {
                $('#my_fatoorah_order_id').val(result.data.order_id);
                fatoorah_order_id = $('#my_fatoorah_order_id').val();
                // console.log($('#my_fatoorah_order_id').val());
                $('#csrf_token').val(csrfHash);


                $.post(base_url + "cart/pre-payment-setup", {
                        [csrfName]: csrfHash,
                        'payment_method': 'my_fatoorah',
                        'wallet_used': wallet_used,
                        'address_id': address_id,
                        'my_fatoorah_order_id': fatoorah_order_id,
                        'promo_code': promo_code

                    },

                    function(data) {

                        csrfName = data.csrfName;
                        csrfHash = data.csrfHash;
                        if (data.error == false) {
                            $('#my_fatoorah_order_id').val(data.order_id);
                            fatoorah_url = data.PaymentURL;
                            var my_fatoorah_payment = my_fatoorah_setup();
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: data.message
                            });
                        }
                    }, "json");
            });


        } else if (payment_methods == "Paypal") {
            place_order().done(function(result) {
                $('#paypal_order_id').val(result.data.order_id);
                $('#csrf_token').val(csrfHash);
                $('#paypal_form').submit();
            });
        } else if (payment_methods == "Paytm") {

            var amount = $("#amount").val();
            var user_id = $("#user_id").val();
            var address_id = $('#address_id').val();
            if ($('#wallet_balance').is(":checked")) {
                var wallet_used = 1;
            } else {
                var wallet_used = 0;
            }

            var promo_set = $('#promo_set').val();
            var promo_code = '';
            if (promo_set == 1) {
                promo_code = $('#promocode_input').val();
            }
            $.post(base_url + "payment/initiate-paytm-transaction", {
                [csrfName]: csrfHash,
                amount: amount,
                user_id: user_id,
                address_id: address_id,
                wallet_used: wallet_used,
                promo_code: promo_code
            }, function(data) {
                if (typeof(data.data.body.txnToken) != "undefined" && data.data.body.txnToken !== null) {
                    $('#paytm_transaction_token').val(data.data.body.txnToken)
                    $('#paytm_order_id').val(data.data.order_id)
                    var txn_token = $('#paytm_transaction_token').val();
                    var order_id = $('#paytm_order_id').val();
                    var app_name = $('#app_name').val();
                    var logo = $('#logo').val();
                    var username = $('#username').val();
                    var user_email = $('#user_email').val();
                    var user_contact = $('#user_contact').val();
                    paytm_setup(txn_token, order_id, data.final_amount, app_name, logo, username, user_email, user_contact);
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Something went wrong please try again later.'
                    });
                }
            }, "json");
        } else if (payment_methods == "Flutterwave") {
            flutterwave_payment();
        } else if (payment_methods == "COD" || payment_methods == "Direct Bank Transfer") {
            place_order().done(function(result) {
                if (result.error == false) {
                    window.location.reload();
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: result.message
                    });
                }
            });
        } else if (wallet_used == 1 && final_total == '0' || final_total == '0.00') {
            place_order().done(function(result) {
                if (result.error == false) {
                    window.location.reload();
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: result.message
                    });
                }

            });

        }

    });

    function my_fatoorah_setup() {
        window.location.replace(fatoorah_url)

    }

    function place_order() {
        let myForm = document.getElementById('checkout_form');
        var formdata = new FormData(myForm);
        formdata.append(csrfName, csrfHash);
        formdata.append('promo_code', $('#promocode_input').val());
        var latitude = sessionStorage.getItem("latitude") === null ? '' : sessionStorage.getItem("latitude");
        var longitude = sessionStorage.getItem("longitude") === null ? '' : sessionStorage.getItem("longitude");
        formdata.append('latitude', latitude);
        formdata.append('longitude', longitude);
        return $.ajax({
            type: 'POST',
            data: formdata,
            url: base_url + 'cart/place-order',
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#place_order_btn').attr('disabled', true).html('Please Wait...');
            },
            success: function(data) {
                csrfName = data.csrfName;
                csrfHash = data.csrfHash;
                $('#place_order_btn').attr('disabled', false).html('Place Order');
                if (data.error == false) {
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.message
                    });
                }
            }
        })
    }

    $("input[name='payment_method']").on('change', function(e) {
        e.preventDefault();
        var payment_method = $("input[name=payment_method]:checked").val();
        if (payment_method == "Stripe") {
            stripe1 = stripe_setup($('#stripe_key_id').val());
            $('#stripe_div').slideDown();
        } else {
            $('#stripe_div').slideUp();
        }
    });

    $("#redeem_btn").on('click', function(event) {
        event.preventDefault();
        var formdata = new FormData();
        formdata.append(csrfName, csrfHash);
        formdata.append('promo_code', $('#promocode_input').val());
        var address_id = $("#address_id").val();
        formdata.append('address_id', address_id);
        var wallet_used = $('.wallet_used').text();
        if (wallet_used == '') {
            wallet_used = 0;
        } else {
            wallet_used = wallet_used.replace(',', '');
        }
        $.ajax({
            type: 'POST',
            data: formdata,
            url: base_url + 'cart/validate-promo-code',
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                csrfName = data.csrfName;
                csrfHash = data.csrfHash;
                if (data.error == false) {
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });
                    var delivery_charge = $(".delivery-charge").text();
                    if (delivery_charge == '') {
                        delivery_charge = 0;
                    } else {
                        delivery_charge = delivery_charge.replace(',', '');
                    }
                    var final_total = data.data[0].final_total;
                    final_total = parseFloat(final_total) - parseFloat(wallet_used) + parseFloat(delivery_charge);
                    var final_discount = parseFloat(data.data[0].final_discount);
                    $('#promocode_div').removeClass('d-none');
                    $('#promocode').text('(' + data.data[0].promo_code + ')');
                    $('#promocode_amount').text(final_discount.toLocaleString(undefined, { maximumFractionDigits: 2 }));
                    $('#final_total').text(final_total.toLocaleString(undefined, { maximumFractionDigits: 2 }));
                    $('#amount').val(final_total);
                    $('#clear_promo_btn').removeClass('d-none');
                    $('#redeem_btn').hide();
                    $("#promo_set").val(1);

                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.message
                    });
                    $("#promo_set").val(0);
                    $('#promocode_input').val('');
                }
            }
        })
    });
    $("#clear_promo_btn").on('click', function(event) {
        event.preventDefault();
        $('#promocode_div').addClass('d-none');
        var wallet_used = $('.wallet_used').text();
        if (wallet_used == '') {
            wallet_used = 0;
        } else {
            wallet_used = wallet_used.replace(',', '');
        }
        var promocode_amount = $('#promocode_amount').text();
        if (promocode_amount == '') {
            promocode_amount = 0;
        } else {
            promocode_amount = promocode_amount.replace(',', '');
        }
        var sub_total = $('.sub_total').text();
        if (sub_total == '') {
            sub_total = 0;
        } else {
            sub_total = sub_total.replace(',', '');
        }
        var delivery_charge = $(".delivery-charge").text();
        if (delivery_charge == '') {
            delivery_charge = 0;
        } else {
            delivery_charge = delivery_charge.replace(',', '');
        }
        var new_final_total = parseFloat(sub_total) - parseFloat(wallet_used) + parseFloat(delivery_charge);
        $('#final_total').text(new_final_total.toLocaleString(undefined, { maximumFractionDigits: 2 }));
        $('#amount').val(new_final_total);
        $('#clear_promo_btn').addClass('d-none');
        $('#redeem_btn').show();
        $('#promocode_input').val('');
        $("#promo_set").val(0);
    });
    /* Instantiating iziModal */
    $(".address-modal").iziModal({
        overlayClose: false,
        overlayColor: 'rgba(0, 0, 0, 0.6)',
        onOpening: function(modal) {
            modal.startLoading();
            $.ajax({
                type: 'POST',
                data: {
                    [csrfName]: csrfHash,
                },
                url: base_url + 'my-account/get-address/',
                dataType: 'json',
                success: function(data) {
                    csrfName = data.csrfName;
                    csrfHash = data.csrfHash;
                    var html = '';
                    if (data.error == false) {
                        var address_id = $('#address_id').val();
                        var found = 0;
                        $.each(data.data, function(i, e) {
                            var checked = '';
                            if (e.id == address_id) {
                                found = 1;
                                checked = 'checked';
                            } else if (e.is_default == 1 && found == 0) {
                                checked = 'checked';
                            }
                            addresses.push(e);
                            html += '<label for="select-address-' + e.id + '"><li class="list-group-item d-flex justify-content-between lh-condensed mt-3">' +
                                '<div class="col-md-1 h-100 my-auto">' +
                                '<input type="radio" class="select-address" ' + checked + ' name="select-address" data-index=' + i + ' id="select-address-' + e.id + '" class="m-0"/>' +
                                '</div>' +
                                '<div class="col-11 row p-0">' +
                                '<div class="col-6 text-dark"><i class="fa fa-map-marker-alt"></i> ' + e.name + ' - ' + e.type + '</div>' +
                                '<small class="col-12 text-muted">' + e.area + ' , ' + e.city + ' , ' + e.state + ' , ' + e.country + ' - ' + e.pincode + '</small>' +
                                '<small class="col-12 text-muted">' + e.mobile + '</small>' +
                                '</div>' +
                                '</li></label>';
                        });

                        $('#address-list').html(html);
                    }
                    modal.stopLoading();
                }
            })
        }
    });

    $(".promo_code_modal").iziModal({
        overlayClose: false,
        overlayColor: 'rgba(0, 0, 0, 0.6)',
        onOpening: function(modal) {
            modal.startLoading();
            $.ajax({
                type: 'POST',
                data: {
                    [csrfName]: csrfHash,
                },
                url: base_url + 'my-account/get_promo_codes/',
                dataType: 'json',
                success: function(data) {
                    // console.log(data.promo_codes);
                    csrfName = data.csrfName;
                    csrfHash = data.csrfHash;
                    var html = '';
                    if ((data.promo_codes).length != 0) {
                        $.each(data.promo_codes, function(i, e) {
                            html += '<label for="promo-code-' + e.id + '"><li class="list-group-item d-flex justify-content-between lh-condensed mt-3">' +
                                '<img src="' + e.image + '" style="max-width:80px;max-height:80px;"/>' +
                                '<div class="col-11 row pl-2">' +
                                '<div class="col-6 text-dark">' + e.promo_code + '</div>' +
                                '<small class="col-12 text-muted">' + e.message + '</small>' +
                                '</div>' +
                                '</li></label>';
                        });
                    } else {
                        html += '<div class="col-12 text-dark d-flex justify-content-center">Opps...No Offers Avilable</small>';
                    }
                    $('#promocode-list').html(html);
                }
            })
            modal.stopLoading();
        }
    });

    $(".address-modal").on('click', '.submit', function(event) {
        event.preventDefault();
        var index = $('input[class="select-address"]:checked').data('index');
        var address = addresses[index];
        var sub_total = $('#sub_total').val();
        sub_total = sub_total.replace(',', '');
        var total = $('#temp_total').val();
        var promocode_amount = $('#promocode_amount').text();
        if (promocode_amount == '') {
            promocode_amount = 0;
        } else {
            promocode_amount = promocode_amount.replace(',', '');
        }
        $('#address-name-type').html(address.name + ' - ' + address.type);
        $('#address-full').html(address.area + ' , ' + address.city);
        $('#address-country').html(address.state + ' , ' + address.country + ' - ' + address.pincode);
        $('#address-mobile').html(address.mobile);
        $('#address_id').val(address.id);
        $('#mobile').val(address.mobile);
        $('.address-modal').iziModal('close');
        var address_id = $('#address_id').val();
        $.ajax({
            type: 'POST',
            data: {
                [csrfName]: csrfHash,
                'address_id': address_id,
                'total': total,
            },
            url: base_url + 'cart/get-delivery-charge',
            dataType: 'json',
            success: function(result) {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
                $('.delivery-charge').html(result.delivery_charge);
                var delivery_charge = result.delivery_charge;
                delivery_charge = delivery_charge.replace(',', '');
                var final_total = parseFloat(sub_total) + parseFloat(delivery_charge);
                var wallet_used = $('.wallet_used').text();
                if (wallet_used == '') {
                    wallet_used = 0;
                } else {
                    wallet_used = wallet_used.replace(',', '');
                }
                var final_total = parseFloat(sub_total) + parseFloat(delivery_charge) - parseFloat(wallet_used) - parseFloat(promocode_amount);
                final_total = final_total.toLocaleString(undefined, { maximumFractionDigits: 2 });
                $('#final_total').html(final_total);
                var final_total = final_total.replace(',', '');
                $('#amount').val(final_total);
                if (final_total != 0) {
                    $('#cod').prop('required', true);
                    $('#paypal').prop('required', true);
                    $('#razorpay').prop('required', true);
                    $('#paystack').prop('required', true);
                    $('#payumoney').prop('required', true);
                    $('#flutterwave').prop('required', true);
                    $('#stripe').prop('required', true);
                    $('#paytm').prop('required', true);
                    $('#bank_transfer').prop('required', true);
                    $('.payment-methods').show();
                }
            }
        });
        $.ajax({
            type: 'POST',
            data: {
                [csrfName]: csrfHash,
                'address_id': address_id,
            },
            url: base_url + 'cart/check-product-availability',
            dataType: 'json',
            success: function(result) {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;

                var className = (result.error == true) ? "danger" : "success";
                $('#deliverable_status').html("<b class='text-" + className + "'>" + result.message + "</b>");
                result.data.forEach(product => {
                    if (product.is_deliverable == false) {
                        $('#p_' + product.product_id).html("<b class='text-danger'>Not deliverable</b>");
                    }
                });
            }
        });

    });
});

$('#datepicker').attr({
    'placeholder': 'Preferred Delivery Date',
    'autocomplete': 'off'
});
$('#datepicker').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
    $('#start_date').val('');
});
$('#datepicker').on('apply.daterangepicker', function(ev, picker) {
    var drp = $('#datepicker').data('daterangepicker');
    var current_time = moment().format("HH:mm");
    if (moment(drp.startDate).isSame(moment(), 'd')) {
        $('.time-slot-inputs').each(function(i, e) {
            if ($(this).data('last_order_time') < current_time) {
                $(this).prop('checked', false).attr('required', false);
                $(this).parent().hide();
            } else {
                $(this).attr('required', true);
                $(this).parent().show();
            }
        });
    } else {
        $('.time-slot-inputs').each(function(i, e) {
            $(this).attr('required', true);
            $(this).parent().show();
        });
    }
    $('#start_date').val(drp.startDate.format('YYYY-MM-DD'));
    $('#delivery_date').val(drp.startDate.format('YYYY-MM-DD'));
    $(this).val(picker.startDate.format('MM/DD/YYYY'));
});
var mindate = '',
    maxdate = '';
if ($('#delivery_starts_from').val() != "") {
    mindate = moment().add(($('#delivery_starts_from').val() - 1), 'days');
} else {
    mindate = null;
}

if ($('#delivery_ends_in').val() != "") {
    maxdate = moment(mindate).add(($('#delivery_ends_in').val() - 1), 'days');
} else {
    maxdate = null;
}
$('#datepicker').daterangepicker({
    showDropdowns: false,
    alwaysShowCalendars: true,
    autoUpdateInput: false,
    singleDatePicker: true,
    minDate: mindate,
    maxDate: maxdate,
    locale: {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "cancelLabel": 'Clear',
        'label': 'Preferred Delivery Date'
    }
});
$(document).ready(function() {
    var address_id = $('#address_id').val();
    var sub_total = $('#sub_total').val();
    var total = $('#temp_total').val();
    $.ajax({
        type: 'POST',
        data: {
            [csrfName]: csrfHash,
            'address_id': address_id,
            'total': total,
        },
        url: base_url + 'cart/get-delivery-charge',
        dataType: 'json',
        success: function(result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $('.delivery-charge').html(result.delivery_charge);
            var delivery_charge = result.delivery_charge.replace(',', '');
            var final_total = parseFloat(sub_total) + parseFloat(delivery_charge);
            $("#amount").val(final_total);
            final_total = final_total.toLocaleString(undefined, { maximumFractionDigits: 2 });
            $('#final_total').html(final_total);

        }

    })
});
$(document).on('click', '#wallet_balance', function() {
    var current_wallet_balance = $('#current_wallet_balance').val();
    var wallet_balance = current_wallet_balance.replace(",", "");
    var final_total = $('#final_total').text();
    final_total = final_total.replace(",", "");
    var sub_total = $("#sub_total").val();
    var delivery_charge = $(".delivery-charge").text();
    if (delivery_charge == '') {
        delivery_charge = 0;
    } else {
        delivery_charge = delivery_charge.replace(',', '');
    }
    var promocode_amount = $('#promocode_amount').text();
    if (promocode_amount == '') {
        promocode_amount = 0;
    } else {
        promocode_amount = promocode_amount.replace(',', '');
    }
    var wallet_used = $('.wallet_used').text();
    if (wallet_used == '') {
        wallet_used = 0;
    } else {
        wallet_used = wallet_used.replace(',', '');
    }
    if ($(this).is(':checked')) {
        $("#wallet_used").val(1);
        wallet_balance = wallet_balance.replace(',', '');
        if (final_total - wallet_balance <= 0) {
            var available_balance = wallet_balance - final_total;
            available_balance = parseFloat(available_balance);
            $(".wallet_used").html(final_total.toLocaleString(undefined, { maximumFractionDigits: 2 }));
            $('#available_balance').html(available_balance.toLocaleString(undefined, { maximumFractionDigits: 2 }));
            $('#final_total').html('0.00');
            $('#cod').prop('required', false);
            $('#paypal').prop('required', false);
            $('#razorpay').prop('required', false);
            $('#midtrans').prop('required', false);
            $('#my_fatoorah').prop('required', false);
            $('#paystack').prop('required', false);
            $('#payumoney').prop('required', false);
            $('#flutterwave').prop('required', false);
            $('#paytm').prop('required', false);
            $('#bank_transfer').prop('required', false);
            $('#stripe').prop('required', false);
            $('#paytm').prop('required', false);
            $('#bank_transfer').prop('required', false);
            $('.payment-methods').hide();
        } else {
            $(".wallet_used").html(current_wallet_balance);
            $('#available_balance').html('0.00');
            final_total = parseFloat(sub_total) - parseFloat(wallet_balance) - parseFloat(promocode_amount) + parseFloat(delivery_charge);
            $('#final_total').html(final_total.toLocaleString(undefined, { maximumFractionDigits: 2 }));
            $('#amount').val(final_total);
            $('#cod').prop('required', true);
            $('#paypal').prop('required', true);
            $('#razorpay').prop('required', true);
            $('#paystack').prop('required', true);
            $('#payumoney').prop('required', true);
            $('#flutterwave').prop('required', true);
            $('#paytm').prop('required', true);
            $('#bank_transfer').prop('required', true);
            $('#stripe').prop('required', true);
            $('#paytm').prop('required', true);
            $('#bank_transfer').prop('required', true);
            $('.payment-methods').show();
        }

    } else {
        $("#wallet_used").val(1);
        var final_total = parseFloat(sub_total) + parseFloat(delivery_charge) - parseFloat(promocode_amount);
        $(".wallet_used").html('0.00');
        $('#final_total').html(final_total.toLocaleString(undefined, { maximumFractionDigits: 2 }));
        $('#amount').val(final_total);
        $('#available_balance').html(current_wallet_balance);
        $('.payment-methods').show();
        $('#cod').prop('required', true);
        $('#paypal').prop('required', true);
        $('#razorpay').prop('required', true);
        $('#paystack').prop('required', true);
        $('#payumoney').prop('required', true);
        $('#flutterwave').prop('required', true);
        $('#paytm').prop('required', true);
        $('#bank_transfer').prop('required', true);
        $('#stripe').prop('required', true);
        $('#paytm').prop('required', true);
        $('#bank_transfer').prop('required', true);

    }
});

function paytm_setup(txnToken, orderId, amount, app_name, logo, username, user_email, user_contact) {
    var config = {
        "root": "",
        "flow": "DEFAULT",
        "merchant": {
            "name": app_name,
            "logo": logo,
            redirect: false
        },
        "style": {
            "headerBackgroundColor": "#8dd8ff",
            "headerColor": "#3f3f40"
        },
        "data": {
            "orderId": orderId,
            "token": txnToken,
            "tokenType": "TXN_TOKEN",
            "amount": amount,
            "userDetail": {
                "mobileNumber": user_contact,
                "name": username
            }
        },
        "handler": {
            "notifyMerchant": function(eventName, data) {
                if (eventName == 'SESSION_EXPIRED') {
                    alert("Your session has expired!!");
                    location.reload();
                }
                if (eventName == 'APP_CLOSED') {
                    $('#place_order_btn').attr('disabled', false).html('Place Order');
                }

            },
            transactionStatus: function(data) {
                window.Paytm.CheckoutJS.close();
                if (data.STATUS == 'TXN_SUCCESS' || data.STATUS == 'PENDING') {
                    let myForm = document.getElementById('checkout_form');
                    var formdata = new FormData(myForm);
                    formdata.append(csrfName, csrfHash);
                    formdata.append('promo_code', $('#promocode_input').val());
                    var latitude = sessionStorage.getItem("latitude") === null ? '' : sessionStorage.getItem("latitude");
                    var longitude = sessionStorage.getItem("longitude") === null ? '' : sessionStorage.getItem("longitude");
                    formdata.append('latitude', latitude);
                    formdata.append('longitude', longitude);
                    $.ajax({
                        type: 'POST',
                        data: formdata,
                        url: base_url + 'cart/place-order',
                        dataType: 'json',
                        cache: false,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            $('#place_order_btn').attr('disabled', true).html('Please Wait...');
                        },
                        success: function(data) {
                            csrfName = data.csrfName;
                            csrfHash = data.csrfHash;
                            $('#place_order_btn').attr('disabled', false).html('Place Order');
                            if (data.error == false) {
                                Toast.fire({
                                    icon: 'success',
                                    title: data.message
                                });
                                setTimeout(function() {
                                    location.href = base_url + 'payment/success';
                                }, 3000);
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: data.message
                                });
                            }
                        }
                    })
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Something went wrong please try again!'
                    });
                }

            }


        }
    };

    if (window.Paytm && window.Paytm.CheckoutJS) {
        // initialze configuration using init method
        window.Paytm.CheckoutJS.init(config).then(function onSuccess() {

            // after successfully update configuration invoke checkoutjs
            window.Paytm.CheckoutJS.invoke();
        }).catch(function onError(error) {
            console.log("Error => ", error);
        });
    }
}

$("input[name='payment_method']").on('change', function(e) {
    e.preventDefault();
    var payment_method = $(this).val();
    if (payment_method == "Direct Bank Transfer") {
        $('#account_data').show();
        $('#bank_transfer_slide').slideDown();
    } else {
        $('#account_data').hide();
        $('#bank_transfer_slide').slideUp();
    }
});
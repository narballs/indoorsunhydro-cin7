
    <script>
    function qoute() {
        $('#my_quotes_detail_table').addClass('d-none');
        $('#my_quotes').addClass('d-none');
        $('#filter').removeClass('d-none');
        $('#all_qoutes').removeClass('d-none');
        $('#intro,#edit_address,#address_row').addClass('d-none');
        $('#whishlist').addClass('d-none');
        $('.nav-pills .active').removeClass('active');
        $('.nav-pills #qoutes').addClass('active');
        $('#edit_address').addClass('d-none');
        $('#address_row').addClass('d-none');
        $('#whish_lists').addClass('d-none');
        $('.order-detail-container').addClass('d-none');
        $('#customer-address').addClass('d-none');
        $('#orders').addClass('d-none');
        $('#qoute-heading').removeClass('d-none');
        $('#my_quotes_edit').addClass('d-none');
        $('#update_qoute').addClass('d-none');
        $('#additional-users').addClass('d-none');
    }

    function replaceEye(val) {
        $('#eye_icon_' + val).attr("src", "/theme/img/white_eye.png").css('width', '20px');
    }

    function replaceEye2(val) {
        $('#eye_icon_' + val).attr("src", "/theme/img/eye.png");
    }

    function showHidePassword(val) {
        if (val === "current_password") {
            var current_password = document.getElementById("current_password");
            if (current_password.type === "password") {
                current_password.type = "text";
            } else {
                current_password.type = "password";
            }
        }
        if (val === "new_password") {
            var new_password = document.getElementById("new_password");
            if (new_password.type === "password") {
                new_password.type = "text";
            } else {
                new_password.type = "password";
            }
        }
        if (val === "new_confirm_password") {
            var new_confirm_password = document.getElementById("new_confirm_password");
            if (new_confirm_password.type === "password") {
                new_confirm_password.type = "text";
            } else {
                new_confirm_password.type = "password";
            }
        }
    }

    function wishLists() {

        $('#whishlist').removeClass('d-none');
        $('#intro,#edit_address,#address_row').addClass('d-none');
        $('#my_quotes_edit').addClass('d-none');
        $('#all_qoutes').addClass('d-none');
        $('.nav-pills .active').removeClass('active');
        $('.nav-pills #wish_lists').addClass('active');
        $('#edit_address').addClass('d-none');
        $('#address_row').addClass('d-none');
        $('#my_quotes').addClass('d-none');
        $('.order-detail-container').addClass('d-none');
        $('#customer-address').addClass('d-none')
        $('#orders').addClass('d-none');
        $('#additional-users').addClass('d-none');
        var listitems = '';
        jQuery.ajax({
            url: "{{ url('/get-wish-lists/') }}",
            method: 'GET',
            data: {},
            success: function(images) {
                $('#fav_content').html(images);
                return;
                console.log(images[1]);
                listitems += '<div class="container p-0">';
                listitems += '<header class="text-center">' +
                    '<h1>My Favorites</h1>' +
                    '</header>' +

                    '<div class="row">' +
                    '<div class="col-md-8 col-sm-12 co-xs-12 gal-item">' +
                    '<div class="row h-50">' +
                    '<div class="col-md-12 col-sm-12 co-xs-12 gal-item">' +
                    '<div class="box buy-list-box">' +
                    '<img src="' + images[0] + '" class="img-ht img-fluid rounded">' +
                    '</div>' +
                    '</div>' +
                    '</div>' +

                    '<div class="row h-50 mt-3">' +
                    '<div class="col-md-6 col-sm-6 co-xs-12 gal-item pt-0">' +
                    '<div class="box buy-list-box">' +
                    '<img src="http://fakeimg.pl/748x177/" class="img-ht img-fluid rounded">' +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-6 col-sm-6 co-xs-12 gal-item pt-0">' +
                    '<div class="box buy-list-box">' +
                    '<img src="http://fakeimg.pl/371x370/" class="img-ht img-fluid rounded">' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-4 col-sm-6 co-xs-12 gal-item">' +
                    '<div class="col-md-12 col-sm-6 co-xs-12 gal-item h-25 pl-0 pr-0">' +
                    '<div class="box buy-list-box">' +
                    '<img src="http://fakeimg.pl/748x177/" class="img-ht img-fluid rounded">' +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-12 col-sm-6 co-xs-12 gal-item h-76 p-0">' +
                    '<div class="box buy-list-box">' +
                    '<img src="http://fakeimg.pl/748x177/" class="img-ht img-fluid rounded">' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<br/>' +
                    '</div>';
                $('#wishlist_content').html(listitems);
            }
        });
    }

    function change_password() {
        var first_name = $('input[name=first_name').val();
        var last_name = $('input[name=last_name').val();
        var email = $('input[name=email_address').val();
        var current_password = $('input[name=current_password]').val();
        var new_password = $('input[name=new_password]').val();
        var new_confirm_password = $('input[name=new_confirm_password').val();
        jQuery.ajax({
            method: 'POST',
            url: "{{ url('change-password') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "first_name": first_name,
                "last_name": last_name,
                "email": email,
                "current_password": current_password,
                "new_password": new_password,
                "new_confirm_password": new_confirm_password
            },
            success: function(response) {
                console.log(response);
                if (response.success == true) {
                    $('#password-match-fail').addClass('d-none');
                    $('#errors_password_comfimation').addClass('d-none');
                    $('#updated-success').html(response.msg);
                    $('.user_names').html('');
                    $('.user_names').html(first_name + ' ' + ' ' + last_name);
                    $('.user_names_dashboard').html('');
                    $('.user_names_dashboard').html(first_name);
                    $('#first_modal_name').val('');
                    $('#first_modal_name').val(first_name);
                    $('#last_modal_name').val('');
                    $('#last_modal_name').val(last_name);
                } else {
                    $('#password-match-fail').html('Current password is not valid');
                }
            },
            error: function(response) {
                console.log(response.responseJSON.errors.current_password[0] != '');
                if (response.responseJSON.errors.current_password) {
                    var current_password_error = response.responseJSON.errors.current_password[0];
                    $('#password-match-fail').html(current_password_error);
                }
                if (response.responseJSON.errors.new_confirm_password) {
                    var errors = response.responseJSON.errors.new_confirm_password[0];
                }
                $('#errors_password_comfimation').html(errors);
            }
        });
    }

    // function dashboard() {
    //     $('#my_quotes').addClass('d-none');
    //     $('#intro').removeClass('d-none');
    //     $('#edit_address').addClass('d-none');
    //     $('#address_row').addClass('d-none');
    //     $('.nav-pills .active').removeClass('active');
    //     $('.nav-pills #dashboard').addClass('active');
    //     $("#additional_users").removeClass("active");
    //     $('.order-detail-container').addClass('d-none');
    //     $('#customer-address').addClass('d-none')
    //     $('#orders').addClass('d-none');
    //     $('#additional-users').addClass('d-none');
    //     $('#additional-users').addClass('d-none');
    // }

    // function userOrderDetail(id) {
    //     $('#my_quotes').addClass('d-none');
    //     $('#address_row').removeClass('d-none');
    //     $('#order_details').removeClass('d-none');
    //     $('#lineitems').removeClass('d-none');
    //     $('#order-detail-container').removeClass('d-none');
    //     $('#detail-heading').removeClass('d-none');
    //     $('#additional-users').addClass('d-none');

    //     var id = id;
    //     $('#orders').addClass('d-none');
    //     //$('.order')
    //     jQuery.ajax({
    //         url: "{{ url('/user-order-detail') }}" + "/" + id,
    //         method: 'GET',
    //         data: {
    //             id: id,

    //         },
    //         success: function(resposne) {
    //             console.log(resposne);
    //             var result = resposne;
    //             console.log(result.user_address)
    //             var order_id = '';
    //             order_id += 'Order #' + '<strong>' + result.user_order.id + '</strong>' +
    //                 ' was placed on ' + '<strong>' + result.user_order.createdDate + '</strong>' +
    //                 ' and is currently ' + '<strong>' + result.user_order.status + '</strong>';
    //             $('#order_id').html(order_id);
    //             var lineitems = '';
    //             var product_total = '';
    //             var subtotal = 0;
    //             var retail_price = 1
    //             result.order_items.forEach(
    //                 function(item, index) {
    //                     var product_total = item.price * item.quantity;
    //                     subtotal = product_total + subtotal;
    //                     lineitems +=
    //                         '<tr class="border-bottom table-row-content" style="height:70px"><td style="width:491px"><a href="">' +
    //                         item.product.name + '</a>' +
    //                         '<td class="cart-basket d-flex align-items-center justify-content-center float-sm-end quantity-counter rounded-circle mt-4">' +
    //                         item.quantity +
    //                         '</td><td></td><td class="table-order-number text-dark text-end">$' + item
    //                         .price * item.quantity.toFixed(2) + '</td></tr>';

    //                     console.log(item.quantity)
    //                 });

    //             lineitems +=
    //                 '<tr class="border-bottom" style="height:70px"><td class="table-row-content">' +
    //                 'Subtotal' +
    //                 '</td><td></td><td></td><td class="table-order-number text-dark text-end">$' + subtotal
    //                 .toFixed(2) + '</td></tr>';
    //             lineitems +=
    //                 '<tr class="border-bottom" style="height:70px"><td class="table-row-content">' +
    //                 '<img src="theme/img/arrow_1.png">' + ' <span>Tax </span>' +
    //                 '</td><td></td><td></td><td class="table-order-number text-dark text-end">' + '$0.00' +
    //                 '</td></tr>';

    //             lineitems +=
    //                 '<tr class="border-bottom" style="height:70px"><td class="table-row-content">' +
    //                 '<img src="theme/img/arrow_1.png">' + ' <span>Delivery Method </span>' +
    //                 '</td><td><td></td></td><td class="table-order-number text-dark text-end">' + result
    //                 .user_order.paymentTerms + '</td><td class="table-order-number text-dark ">' + ' ' +
    //                 '</td></tr>';
    //             lineitems +=
    //                 '<tr class="border-bottom" style="height:70px"><td class="table-row-content">  ' +
    //                 '   Total' +
    //                 '</td><td></td><td></td><td class="table-order-number  text-end text-danger">$' +
    //                 subtotal.toFixed(2) + '</td><td class="table-order-number text-dark">' + ' ' +
    //                 '</td></tr>';
    //             var address = '';
    //             address = '<span class="address-user-details">' + '<strong>' + result.user_address
    //                 .firstName + '&nbsp' + result.user_address.lastName + '</strong>' + '</span>';
    //             address += '<span>  ' + result.user_address.postalAddress1 + '</span>';
    //             address += '<span>  ' + result.user_address.postalAddress2 + '</span>';
    //             address += '<span>  ' + result.user_address.postalCity + '</span>';
    //             address += '<span>  ' + result.user_address.postalState + '</span><br>';
    //             address += '<span>' + '&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp ' + result
    //                 .user_address.postalPostCode + '</span><br>';
    //             address += '<span>  ' + '<strong>' + result.user_address.mobile + '</strong>' + '</span>' +
    //                 '        ' + result.user_address.email;
    //             $('#lineitems').html(lineitems);
    //             $('#address_table').html(address);
    //             $('#shipping_table').html(address);
    //             $([document.documentElement, document.body]).animate({
    //                 scrollTop: $("#main-row").offset().top
    //             }, 1000);
    //         }
    //     });

    // }

    // function showOrders() {
    //     $('#my_quotes').addClass('d-none');
    //     $('#filter').addClass('d-none');
    //     $('#address_row').addClass('d-none');
    //     $('#all_qoutes').addClass('d-none');
    //     $('.nav-pills .active').removeClass('active');
    //     $('.nav-pills #recent_orders').addClass('active');
    //     $('#whishlist').addClass('d-none');
    //     $('#customer-address').addClass('d-none');
    //     $('#order-detail-container').addClass('d-none');
    //     $('#edit_address').addClass('d-none');
    //     $('#intro').addClass('d-none');
    //     $('#orders').show();
    //     $('#orders').removeClass('d-none');
    //     $('#lineitems').addClass('d-none');
    //     $('#additional-users').addClass('d-none');

    //     jQuery.ajax({
    //         url: "{{ url('/my-account/') }}",
    //         method: 'GET',
    //         success: function(response) {
    //             var data = response.user_orders;
    //             var can_approve_order = response.can_approve_order;
    //             var order_approver_for_company = response.order_approver_for_company;
    //             console.log(order_approver_for_company);
    //             var res = '';
    //             var total_items = 0;
    //             $.each(data, function(key, value) {
    //                 var total_items = 0;
    //                 $.each(value.api_order_item, function(key, value) {
    //                     console.log(value.quantity + '-----------' + value.order_id);
    //                     total_items = value.quantity + total_items;

    //                 });
    //                 console.log(total_items);
    //                 console.log(total_items);
    //                 console.log(value.order_id);
    //                 var temp = value.createdDate;
    //                 res +=
    //                     '<tr class="table-row-content border-bottom" id="' + value.id + '">' +
    //                     '<td class="table-order-number pl-0">#' + value.id + '</td>' +
    //                     '<td>' + temp + '</td>' +
    //                     '<td>' + value.status + '</td>' +
    //                     '<td>' + value.contact.company + '</td>' +
    //                     '<td><strong>' + '$' + value.total.toFixed(2) + '</strong>' + ' For (' +
    //                     total_items + ' ' + ' items)' + '</td>';
    //                 if (value.order_id != null) {
    //                     res += '<td id="status_' + value.id + '">' + 'Approved' + '</td>';

    //                 } else {
    //                     res += '<td>' + '<span id="status_' + value.id + '">Pending</span>' +
    //                         ' <input type="hidden" id="verify_order_' + value.id +
    //                         '" value=""></td>';
    //                 }



    //                 res += '<td class="pr-0">' + '<a onclick=userOrderDetail(' + value.id +
    //                     ') onmouseover=replaceEye(' + value.id + ') onmouseout= replaceEye2(' +
    //                     value.id + ');>' +
    //                     '<button class="btn btn-outline-success view-btn p-0" type="" style="width:100%;height:32px;"><img src="theme/img/eye.png" class="mr-1 mb-1" id="eye_icon_' +
    //                     value.id + '"></i>View</button>' + '</td></a>';
    //                 if (can_approve_order && value.isApproved != 1 && order_approver_for_company ===
    //                     true) {
    //                     res +=
    //                         '<td><button class="btn btn-outline-primary btn-sm" onclick="approveOrder(' +
    //                         value.id + ')" id="approve_' + value.id + '">Approve</button></td>' +
    //                         '</tr>';
    //                 }

    //             });

    //             $('#order_table').html(res);
    //         },

    //     });
    // }

    // function accountDetails() {
    //     $('#my_quotes').addClass('d-none');
    //     $('#filter').addClass('d-none');
    //     $('#orders').addClass('d-none');
    //     $('#whishlist').addClass('d-none');
    //     $('#detail-heading').addClass('d-none');
    //     $('#order_details').addClass('d-none');
    //     $('#address_row').addClass('d-none');
    //     $('.nav-pills .active').removeClass('active');
    //     $('.nav-pills #account_details').addClass('active');
    //     $('#edit_address').addClass('d-none')
    //     $('#intro').addClass('d-none');
    //     $('#customer-address').removeClass('d-none');
    //     $('#additional-users').addClass('d-none');
    //     $('#qoute-heading').addClass('d-none');

    //     jQuery.ajax({
    //         url: "{{ url('/user-addresses/') }}",
    //         method: 'GET',
    //         success: function(data) {
    //             console.log(data);
    //         },
    //     })
    // }

    // function additionalUsers() {
    //     $('#my_quotes').addClass('d-none');
    //     $("#additional_users").addClass("active");
    //     $('#filter').addClass('d-none');
    //     $('#orders').addClass('d-none');
    //     $('#whishlist').addClass('d-none');
    //     $('#detail-heading').addClass('d-none');
    //     $('#order_details').addClass('d-none');
    //     $('#address_row').addClass('d-none');
    //     $('.nav-pills .active').removeClass('active');
    //     $('.nav-pills #additional_users').addClass('active');
    //     $('#edit_address').addClass('d-none')
    //     $('#intro').addClass('d-none');
    //     $('#customer-address').addClass('d-none');
    //     $('#additional-users').removeClass('d-none');
    //     $('#qoute-heading').addClass('d-none');
    //     sessionStorage.removeItem("invitation");

    // }

    // function edit_address() {
    //     $('#my_quotes').addClass('d-none');
    //     $('#filter').addClass('d-none');
    //     $('#edit_address').removeClass('d-none');
    //     $('#whishlist').addClass('d-none');
    //     $('#address_row').addClass('d-none');
    //     $('.nav-pills .active').removeClass('active');
    //     $('.nav-pills #current_address').addClass('active');
    //     // $('#customer-address').addClass('d-none');
    //     $('#customer-address').addClass('d-none');
    //     $('#orders').addClass('d-none');
    //     $('#intro').addClass('d-none');
    //     $('#order-detail-container').addClass('d-none');
    //     $('#additional-users').addClass('d-none');
    //     $('#qoute-heading').addClass('d-none');


    // }

    function updateContact(user_id) {
        var first_name = $('input[name=firstName]').val();
        var last_name = $('input[name=lastName]').val();
        var company_name = $('input[name=company]').val();
        var phone = $('input[name=phone]').val();
        var address = $('input[name=address]').val();
        var address2 = $('input[name=address2]').val();
        var town_city = $('input[name=town_city]').val();
        var state = document.getElementById("state").value;
        var zip = $('input[name=zip]').val();
        var email = $('input[name=email]').val();


        jQuery.ajax({
            method: 'GET',
            data: {
                url: "{{ url('/user-addresses/') }}",

                "_token": "{{ csrf_token() }}",
                "user_id": user_id,
                "first_name": first_name,
                "last_name": last_name,
                "company_name": company_name,
                "phone": phone,
                "address": address,
                "address2": address2,
                "town_city": town_city,
                "state": state,
                "zip": zip,
                "email": email
            },
            success: function(response) {

                if (response.success == true) {
                    $('.modal-backdrop').remove()
                    $('#success_msg').removeClass('d-none');
                    $('#success_msg').html(response.msg);
                    window.location.reload();
                }
            },
            error: function(response) {
                var error_message = response.responseJSON;
                var error_text = '';
                if (typeof error_message.errors.first_name != 'undefined') {
                    error_text = error_message.errors.first_name;
                    $('#error_first_name').html(error_text);
                } else {
                    error_text = '';
                    $('#error_first_name').html(error_text);
                }
                if (typeof error_message.errors.last_name != 'undefined') {
                    var error_text = error_message.errors.last_name;
                    $('#error_last_name').html(error_text);
                } else {
                    error_text = '';
                    $('#error_last_name').html(error_text);
                }
                if (typeof error_message.errors.company_name != 'undefined') {
                    var error_text = error_message.errors.company_name;
                    $('#error_company').html(error_text);
                } else {
                    error_text = '';
                    $('#error_company').html(error_text);
                }
                if (typeof error_message.errors.address != 'undefined') {
                    var error_text = error_message.errors.address;
                    $('#error_address1').html(error_text);
                } else {
                    error_text = '';
                    $('#error_address1').html(error_text);
                }

                if (typeof error_message.errors.zip != 'undefined') {
                    var error_text = error_message.errors.zip;
                    $('#error_zip').html(error_text);
                } else {
                    error_text = '';
                    $('#error_zip').html(error_text);
                }
                if (typeof error_message.errors.town_city != 'undefined') {
                    var error_text = error_message.errors.town_city;
                    $('#error_city').html(error_text);
                } else {
                    error_text = '';
                    $('#error_city').html(error_text);
                }
                if (typeof error_message.errors.zip != 'undefined') {
                    var error_text = error_message.zip;
                    $('#error_zip').html(error_text);
                } else {
                    error_text = '';
                    $('#error_zip').html(error_text);
                }
                if (typeof error_message.errors.phone != 'undefined') {
                    var error_text = error_message.errors.phone;
                    $('#error_phone').html(error_text);
                } else {
                    error_text = '';
                    $('#error_phone').html(error_text);
                }

            }
        });
    }

    function createList(type) {
        if (type == 1) {
            var type = 'wishlist';
            var list_name = $('#whish_list_id').val();
        } else {
            var type = 'quote';
            var list_name = $('#quote_id').val();
        }
        var title = $('#quote_id').val();
        var description = 'Quote';
        var status = 'Public';
        jQuery.ajax({
            url: "{{ route('buy-list.store') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                title: list_name,
                description: description,
                status: status,
                type: type
            },
            success: function(response) {
                $("#list_title").append("<h4>" + title + "</h4>");
                $("#list_id").val(response.list_id);
                $("#title_errors").html('');
                $("#status_errors").html('');
                $("#description_errors").html('');
                $("#success_msg").html(response.success);
                $("#success_msg").removeClass('d-none');
                $(".btn-add-to-cart").prop('disabled', false);
                $("#list").removeClass('d-none');
                $('#additional-users').addClass('d-none');


            },
            error: function(response) {
                if (response.responseJSON.errors.title) {
                    $("#title_errors").html(response.responseJSON.errors.title);
                } else {
                    $("#title_errors").html('');
                }
                if (response.responseJSON.errors.status) {
                    $("#status_errors").html(response.responseJSON.errors.status);
                } else {
                    $("#status_errors").html('');
                }

                if (response.responseJSON.errors.description) {
                    $("#description_errors").html(response.responseJSON.errors.description);
                } else {
                    $("#description_errors").html('');
                }
            }
        });
    }

    function generatList() {
        var is_update = $('#is_update').val();
        var listItems = [];
        var list_id = $('#list_id').val();
        var grand_total = $('#grand_total').html();
        console.log(grand_total);
        $('.admin-buy-list').each(function() {
            var product_id = this.id;
            product_id = product_id.replace('product_row_', '');
            var retail_price = $('#retail_price_' + product_id).html();
            var option_id = $('#option_id_' + product_id).val();
            var quantity = $('#quantity_' + product_id).val();
            var subtotal = $('#subtotal_' + product_id).html();
            console.log(subtotal);
            listItems.push({
                product_id: product_id,
                option_id: option_id,
                quantity: quantity,
                subtotal: subtotal,
                grand_total: grand_total,
            });
        });
        console.log(listItems);
        jQuery.ajax({
            url: "{{ url('admin/generate-list') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                listItems: listItems,
                listId: list_id,
                is_update: is_update,
                type: 'Quote'
            },
            success: function(response) {
                console.log(response);
                $('.nav-pills #qoutes').addClass('active');
                $('#update_qoute').html('Updated Successfully');
                //window.location.href = "{{ route('buy-list.index') }}";
            }
        });
    }


    function deleteProduct(product_id) {
        var row = $('#product_row_' + product_id).length;
        if (row < 1) {
            $('#grand_total').html(0.00);
        }
        var subtotal_to_remove = parseFloat($('#subtotal_' + product_id).html());
        var grand_total = parseFloat($('#grand_total').html());
        var updated_total = 0;
        updated_total = parseFloat(grand_total) - parseFloat(subtotal_to_remove);
        $('#subtotal_' + product_id).val();
        $('#product_row_' + product_id).remove();
        $('#grand_total').html(updated_total);
    }

    function handleQuantity(product_id) {
        var difference = 0;
        var subtotal_before_update = parseFloat($('#subtotal_' + product_id).html());
        console.log('difference => ' + difference);
        console.log('sub total before update  => ' + subtotal_before_update);

        var retail_price = parseFloat($('#retail_price_' + product_id).html());
        var quantity = parseFloat($('#quantity_' + product_id).val());
        var subtotal = parseFloat($('#subtotal_' + product_id).html());


        subtotal = retail_price * quantity;
        difference = subtotal_before_update - subtotal;

        console.log('difference => ' + difference);

        var grand_total = $('#grand_total').html();
        grand_total = parseFloat(grand_total);

        console.log('Grand Total => ' + grand_total);


        grand_total = grand_total - difference;
        $('#grand_total').html(grand_total);

        console.log('Grand Total => ' + grand_total);

        $('#quantity_' + product_id).val(quantity);
        $('#subtotal_' + product_id).html(subtotal);
    }

    function myQoutes() {
        $('#filter').addClass('d-none');
        $('#my_quotes').removeClass('d-none');
        $('#all_qoutes').addClass('d-none');
        $('#qoute-heading').removeClass('d-none');
        $('#additional-users').addClass('d-none');

        jQuery.ajax({
            url: "{{ url('/my-qoutes/') }}",
            method: 'GET',
            data: {

            },
            success: function(data) {
                console.log(data);
                var res = '';
                var total_items = 0;
                $.each(data.data, function(key, value) {
                    console.log(value.title);
                    console.log(total_items);
                    var temp = value.createdDate;
                    res +=
                        '<tr class="table-row-content border-bottom">' +
                        '<td>' + value.title + '</td>' +
                        '<td>' + value.status + '</td>' +
                        '<td class="pr-0">' + '<a onclick=userQouteDetail(' + value.id +
                        ') onmouseover=replaceEye(' + value.id + ') onmouseout= replaceEye2(' +
                        value.id + ');>' +
                        '<button class="btn btn-outline-success view-btn p-0" type="" style="width:100%;height:32px;"><img src="theme/img/eye.png" class="mr-1 mb-1" id="eye_icon_' +
                        value.id + '"></i>View</button>' + '</td></a>' + '<td class="pr-0">' +
                        '<a onclick=userQouteEdit(' + value.id + ') onmouseover=replaceEye(' + value
                        .id + ') onmouseout= replaceEye2(' + value.id + ');>' +
                        '<button class="btn btn-outline-success view-btn p-0" type="" style="width:100%;height:32px;"><img src="theme/img/eye.png" class="mr-1 mb-1" id="eye_icon_' +
                        value.id + '"></i>Edit</button>' + '</td></a>' +
                        '</tr>';

                });
                //console.log(res);
                $('#my_quotes_table').html(res);
            },
        });
    }

    function sendInvitation(email) {
        var addnew = sessionStorage.setItem('invitation', 1);
        var secondory_email = email;
        jQuery.ajax({
            url: "{{ url('admin/send-invitation-email') }}",
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                secondory_email: secondory_email

            },
            success: function(response) {

                if (response.status == 200) {
                    window.location.reload();

                }
            }

        });

    }

    function userQouteDetail(id) {
        $('#my_quotes_detail_table').removeClass('d-none');
        jQuery.ajax({
            url: "{{ url('/my-qoutes-details') }}" + "/" + id,
            method: 'GET',
            success: function(html) {
                $('#my_quotes').addClass('d-none');
                $('filter').addClass('d-none');
                console.log(html);
                $('#my_quotes_detail_table').append(html);
                dataType: 'html'
            },
        });
    }

    function userQouteEdit(id) {
        $('#filter').addClass('d-block');
        $('#additional-users').addClass('d-none');

        jQuery.ajax({
            url: "{{ url('/my-qoute-edit') }}" + "/" + id,
            method: 'GET',
            success: function(html) {
                console.log(html);
                $('#my_quotes').addClass('d-none');
                $('#qoute-heading').addClass('d-none');
                $('#user-qoute').append(html);
                $('#all_qoutes').removeClass('d-none');
                $('#my_quotes_edit').removeClass('d-none');
                $('#my_quotes').addClass('d-none');
                dataType: 'html'
            },
        });
    }


    function CreateSocodoryUser() {
        $('#spinner2').removeClass('d-none');
        var first_name = $('#first_name_secondary').val();
        var last_name = $('#last_name_secondary').val();
        var job_title = $('#job_title').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        jQuery.ajax({
            url: "{{ url('/create/secondary/user') }}",
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                'firstName': first_name,
                'lastName': last_name,
                'jobTitle': job_title,
                'email': email,
                'phone': phone
            },
            success: function(response) {
                $('#spinner2').addClass('d-none');
                console.log(response.secondary_contact.company);
                // $("#secondary_user").html(response);
                $('#staticBackdrop').modal('hide');
                $('#sample_form').trigger("reset");
                $('#auto_click').trigger('click');
            },

            error: function(response) {
                var error_message = response.responseJSON;
                var error_text = '';
                if (typeof error_message.errors.email != 'undefined') {
                    error_text = error_message.errors.email;
                    $('#secondary_user_email_errors').html(error_text);
                } else {
                    error_text = '';
                    $('#secondary_user_email_errors').html(error_text);
                }

                if (typeof error_message.errors.firstName != 'undefined') {
                    error_text = error_message.errors.firstName;
                    $('#first_name_secondary_errors').html(error_text);
                } else {
                    error_text = '';
                    $('#first_name_secondary_errors').html(error_text);
                }

                if (typeof error_message.errors.lastName != 'undefined') {
                    error_text = error_message.errors.lastName;
                    $('#last_name_secondary_errors').html(error_text);
                } else {
                    error_text = '';
                    $('#last_name_secondary_errors').html(error_text);
                }

            }
        });

    }

    function switch_company(contactId) {
        var company = $('#company_switch').val();
        jQuery.ajax({
            url: "{{ url('/switch-company/') }}",
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                'companyId': company
            },
            success: function(response) {
                window.location.reload();
            }
        });
    }

    function approveOrder(id) {
        var order_id = id;
        jQuery.ajax({
            url: "{{ url('/user-order-approve/') }}",
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "order_id": order_id
            },
            success: function(response) {
                console.log(response);
                $('#verify_order_' + order_id).val(1);
                setInterval(function() {
                    verify_order(order_id);
                }, 1000);

            },
        });

        function verify_order(order_id) {
            if ($('#verify_order_' + order_id).val() == '0') {
                //console.log('loop stopped');
                return false;
            }
            console.log('processing loop ...');

            jQuery.ajax({
                url: "{{ url('/verify-order/') }}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "order_id": order_id
                },
                success: function(data) {
                    if (data.order_id) {
                        $('#verify_order_' + order_id).val(0);
                        $('#status_' + order_id).html('Approved');
                        $('#approve_' + order_id).addClass('d-none');
                        $.ajax({
                            type: "post",
                            url: "{{ url('/send-order-approval-email/') }}",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "order_id": order_id
                            },
                            success: function(data) {

                            }
                        });
                    }
                },
            });
        }

    }

</script>


<div class="modal fade" id="address_modal_id" data-dismiss="modal" data-backdrop="false"  aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalToggleLabel">Update Address</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
                   <div class="update-address-section" id="address-form-update">
  
                  <form class="needs-validation mt-4 novalidate" action="{{url('order')}}" method="POST">
                  @csrf
                  <div class="alert alert-success mt-3 d-none" id="success_msg"></div>
                  <div class="row">
                      <div class="col-md-6 mb-3">
                          <label for="firstName" >First name</label>
                          <input type="text" class="form-control bg-light" name="firstName" placeholder="First name" value="{{$user_address->firstName}}" required>
                       <div id="error_first_name" class="text-danger">
  
                          </div>
                      </div>
  
                      <div class="col-md-6 mb-3">
                          <label for="lastName">Last name</label>
                          <input type="text" class="form-control bg-light" name="lastName" placeholder="" value="{{$user_address->lastName}}" required>
                          <div id="error_last_name" class="text-danger">
  
                          </div>
                      </div>
                  </div>
  
                  <div class="mb-3">
                      <label for="company">Company Name(optional)</label>
                      <div class="input-group">
                          <input type="text" class="form-control bg-light" name="company" placeholder="Enter you company name" value="{{$user_address->company}}" required>
                         
                      </div>
                       <div id="error_company" class="text-danger">
  
                      </div>
                  </div>
  
                  <div class="mb-3">
                      <label for="username">Country</label>&nbsp;<span>United States</span>
                      <input type="hidden" name="country" value="United States">
                  </div>
  
  
                  <div class="mb-3">
                      <label for="address">Street Address</label>
                      <input type="text" class="form-control bg-light" name="address" value="{{$user_address->postalAddress1}}" placeholder="House number and street name" required>
                   
                  </div>
                  <div id="error_address1" class="text-danger">
  
                  </div>
  
                  <div class="mb-3">
                      <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                      <input type="text" class="form-control bg-light" name="address2" value="{{$user_address->postalAddress2}}" placeholder="Apartment, suite, unit etc (optional)">
                  </div>
                  <div id="error_address2" class="text-danger">
  
                  </div>
                  <div class="mb-3">
                      <label for="town">Town/City <span class="text-muted">(Optional)</span></label>
                      <input type="text" class="form-control bg-light" name="town_city" value="{{$user_address->postalCity}}" placeholder="Enter your town">
                  </div>
                  <div id="error_city" class="text-danger">
  
                  </div>
                  <div class="row">
                      <div class="col-md-6 mb-3">
                          <label for="state">State</label>
  
                          <select class="form-control bg-light" name="state" id="state">
                              @foreach($states as $state)
                                  <?php 
                                      if($user_address->postalState == $state->name){
                                              $selected = 'selected';
  
                                      }
                                      else
                                      {
                                           $selected = '';
                                      }
                                  
                                  ?>
                                  <option value="{{$state->name}}" <?php echo  $selected;?>>{{$state->name}}</option>
                              @endforeach
                          </select>
                       <!--    <input type="text" class="form-control bg-light" name="state" value="{{$user_address->postalState}}" placeholder="Enter State" value="" required> -->
                          <div class="invalid-feedback">
                              Valid first name is required.
                          </div>
                      </div>
                      <div class="col-md-6 mb-3">
                          <label for="zip">Zip</label>
                          <input type="text" class="form-control bg-light" name="zip" placeholder="Enter zip code" value="{{$user_address->postalPostCode}}" required>
                          <div id="error_zip" class="text-danger">
                             
                          </div>
                      </div>
                  </div>
  
  
                  <div class="row">
                      <div class="col-md-6 mb-3">
                          <label for="phone">Phone</label>
                          <input type="text" class="form-control bg-light" name="phone" placeholder="Enter your phone" value="{{$user_address->phone}}" required>
                         <div id="error_phone" class="text-danger"></div>
                      
                      
                  
                  </div>
              
                  <!-- <div>
                      <button calss="btn btn-primary" onclick="updateContact('{{auth()->user()->id}}')">Update</button>
                  </div> -->
              </div>
          </form>
              </div>
  
  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn button-cards primary" onclick="updateContact('{{auth()->user()->id}}')">Update</button>
        </div>
      </div>
    </div>
  </div>
  {{-- <a class="ms-3" data-bs-toggle="modal" href="#address_modal_id" role="button"><img src="/theme/img/edit_pen.png"></a> --}}
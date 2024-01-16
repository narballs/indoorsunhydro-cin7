<script>
    function handleSortBY() {
        var sort_by = jQuery('#handle_sort_by').val();
        var basic_url = `/my-account`;
        if (sort_by != '') {
            basic_url = basic_url+`?sort_by=${sort_by}`;
        }
        window.location.href = basic_url
    }
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

    

    // function updateContact(user_id) {
    //     $('#address_loader').removeClass('d-none');
    //     var first_name = $('input[name=firstName]').val();
    //     var last_name = $('input[name=lastName]').val();
    //     var company_name = $('input[name=company]').val();
    //     var phone = $('input[name=phone]').val();
    //     var address = $('input[name=address]').val();
    //     var address2 = $('input[name=address2]').val();
    //     var town_city = $('input[name=town_city]').val();
    //     var state = document.getElementById("state").value;
    //     var zip = $('input[name=zip]').val();
    //     var email = $('input[name=email]').val();
    //     var contact_id = $('#contact_id_val').val();
    //     var secondary_id = $('input[name=secondary_id]').val();
    //     console.log(secondary_id)

    //     jQuery.ajax({
    //         method: 'GET',
    //         url: "{{ url('/my-account-user-addresses/') }}",
    //         data: {
    //             "_token": "{{ csrf_token() }}",
    //             "user_id": user_id,
    //             "first_name": first_name,
    //             "last_name": last_name,
    //             "company_name": company_name,
    //             "phone": phone,
    //             "address": address,
    //             "address2": address2,
    //             "town_city": town_city,
    //             "state": state,
    //             "zip": zip,
    //             "email": email,
    //             'contact_id': contact_id,
    //             'secondary_id': secondary_id,
    //         },
    //         success: function(response) {
    //             if (response.success == true) {
    //                 $('#address_loader').addClass('d-none');
    //                 $('.modal-backdrop').remove()
    //                 $('#success_msg').removeClass('d-none');
    //                 $('#success_msg').html(response.msg);
    //                 window.location.reload();
    //             }else {
    //                 $('#address_loader').addClass('d-none');
    //                 $('.modal-backdrop').remove()
    //                 $('#error_msg').removeClass('d-none');
    //                 $('#error_msg').html(response.msg);
    //                 window.location.reload();
    //             }
    //         },
    //         error: function(response) {
    //             $('#address_loader').addClass('d-none');
    //             var error_message = response.responseJSON;
    //             var error_text = '';
    //             if (typeof error_message.errors.first_name != 'undefined') {
    //                 error_text = error_message.errors.first_name;
    //                 $('#error_first_name').html(error_text);
    //             } else {
    //                 error_text = '';
    //                 $('#error_first_name').html(error_text);
    //             }
    //             if (typeof error_message.errors.last_name != 'undefined') {
    //                 var error_text = error_message.errors.last_name;
    //                 $('#error_last_name').html(error_text);
    //             } else {
    //                 error_text = '';
    //                 $('#error_last_name').html(error_text);
    //             }
    //             if (typeof error_message.errors.company_name != 'undefined') {
    //                 var error_text = error_message.errors.company_name;
    //                 $('#error_company').html(error_text);
    //             } else {
    //                 error_text = '';
    //                 $('#error_company').html(error_text);
    //             }
    //             if (typeof error_message.errors.address != 'undefined') {
    //                 var error_text = error_message.errors.address;
    //                 $('#error_address1').html(error_text);
    //             } else {
    //                 error_text = '';
    //                 $('#error_address1').html(error_text);
    //             }

    //             if (typeof error_message.errors.zip != 'undefined') {
    //                 var error_text = error_message.errors.zip;
    //                 $('#error_zip').html(error_text);
    //             } else {
    //                 error_text = '';
    //                 $('#error_zip').html(error_text);
    //             }
    //             if (typeof error_message.errors.town_city != 'undefined') {
    //                 var error_text = error_message.errors.town_city;
    //                 $('#error_city').html(error_text);
    //             } else {
    //                 error_text = '';
    //                 $('#error_city').html(error_text);
    //             }
    //             if (typeof error_message.errors.zip != 'undefined') {
    //                 var error_text = error_message.zip;
    //                 $('#error_zip').html(error_text);
    //             } else {
    //                 error_text = '';
    //                 $('#error_zip').html(error_text);
    //             }
    //             if (typeof error_message.errors.phone != 'undefined') {
    //                 var error_text = error_message.errors.phone;
    //                 $('#error_phone').html(error_text);
    //             } else {
    //                 error_text = '';
    //                 $('#error_phone').html(error_text);
    //             }

    //         }
    //     });
    // }

    

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
        console.log(company);
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
    //main multi function 
    function handlePerPage() {
        var per_page = jQuery('#per_page_favorite').val();
        var basic_url = `/my-account/my-favorites`;
        if (per_page != '') {
        basic_url = basic_url+`?per_page=${per_page}`;
        }
        window.location.href = basic_url
    }
    //main multi function 
    function add_multi_to_cart(all_fav) {
        $.ajax({
            url: "{{ url('/multi-favorites-to-cart/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                all_fav: all_fav,
                quantity: 1
            },
            success: function(response) {
                if (response.status == 'success') {
                    var cart_items = response.cart_items;
                    var cart_total = 0;
                    var total_cart_quantity = 0;

                    for (var key in cart_items) {
                        var item = cart_items[key];

                        var product_id = item.prd_id;
                        var price = parseFloat(item.price);
                        var quantity = parseFloat(item.quantity);

                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$' + subtotal);
                    }
                    $('#top_cart_quantity').html(total_cart_quantity);
                    $('#cart_items_quantity').html(total_cart_quantity);
                    $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                    var total = document.getElementById('#top_cart_quantity');
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: 'Product(s) added to cart successfully',
                        timer: 2000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
                    });
                }
            }
        });
    }
    //add all favorites to cart
    function add_all_to_cart() {
        var all_fav = [];
        var all_check = $('.single_fav_check');
        all_check.each(function() {
            var id = $(this).attr('id');
            var id = id.split('_');
            var product_id = id[1];
            var option_id = id[2];
            all_fav.push({
                product_id: product_id,
                option_id: option_id
            });
        });
        add_multi_to_cart(all_fav);
    }
    //add selected favorites to cart
    function add_selected_to_cart() {
        var selected_check = $('.single_fav_check:checked');
        if (selected_check.length == 0) {
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'Please select at least one product to add it into your cart.',
                timer: 1000,
                showConfirmButton: false,
                position: 'top',
                timerProgressBar: true
            });
        } else {
            var all_fav = [];
            selected_check.each(function() {
                if ($(this).is(':checked')) {
                    var id = $(this).attr('id');
                    var id = id.split('_');
                    var product_id = id[1];
                    var option_id = id[2];
                    all_fav.push({
                        product_id: product_id,
                        option_id: option_id
                    });
                }
            });
            add_multi_to_cart(all_fav);
            setTimeout(() => {
                selected_check.prop('checked', false);
            }, 1000);
        }
    }

    function remove_from_favorite(id) {
        var product_buy_list_id = id;
        var option_id = $(this).attr('data-option');
        var contact_id = $(this).attr('data-contact');
        var user_id = $(this).attr('data-user');
        var list_id = $(this).attr('data-list');
        var title = $(this).attr('data-title');
        $.ajax({
            url: "{{ url('/delete/favorite/product') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                product_buy_list_id,
                option_id,
                contact_id,
                user_id,
                list_id,
                title
            },
            success: function(response) {
                if (response.status == 'success') {
                    wishLists();
                    Swal.fire('Success!', 'Product removed from your favorites.');
                }
            }
        });
    }

    function add_favorite_to_cart(id, option_id) {
        jQuery.ajax({
            url: "{{ url('/add-to-cart/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                p_id: id,
                option_id: option_id,
                quantity: 1,
            },
            success: function(response) {
                if (response.status == 'success') {
                    var cart_items = response.cart_items;
                    var cart_total = 0;
                    var total_cart_quantity = 0;

                    for (var key in cart_items) {
                        var item = cart_items[key];

                        var product_id = item.prd_id;
                        var price = parseFloat(item.price);
                        var quantity = parseFloat(item.quantity);

                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        var product_name = document.getElementById('prd_name_' + id).value;
                    }

                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: quantity + ' X ' + document.getElementById('prd_name_' + id).value +
                            ' added to your cart',
                        timer: 3000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
                    });
                }
                $('#top_cart_quantity').html(total_cart_quantity);

                $('#cart_items_quantity').html(total_cart_quantity);
                $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                var total = document.getElementById('#top_cart_quantity');
            }
        });
    }
    // buy again functionality 
    function add_products_to_cart(order_id) {
        $.ajax({
            url: '/order/items/' + order_id,
            method: 'get',
            success: function(response) {
                if(response.status == 'success') {
                    if(response.order_items != null) {
                        var ordered_products = [];
                        response.order_items.api_order_item.forEach(function(item) {
                            console.log(item);
                            ordered_products.push({
                                product_id: item.product.id,
                                option_id: item.option_id,
                                quantity: item.quantity
                            });
                        });
                        buy_items_again(ordered_products);
                    } else {
                        Swal.fire({
                            toast: true,
                            icon: 'error',
                            title: 'Something went wrong',
                            timer: 2000,
                            showConfirmButton: false,
                            position: 'top',
                            timerProgressBar: true
                        });
                    }
                    
                }
            }
        });
    }

    function buy_items_again(ordered_products) {
        $.ajax({
            url: "{{ url('/buy/order/items/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                ordered_products: ordered_products,
                // quantity: 1
            },
            success: function(response) {
                if (response.status == 'success') {
                    var cart_items = response.cart_items;
                    var cart_total = 0;
                    var total_cart_quantity = 0;

                    for (var key in cart_items) {
                        var item = cart_items[key];

                        var product_id = item.prd_id;
                        var price = parseFloat(item.price);
                        var quantity = parseFloat(item.quantity);

                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$' + subtotal);
                    }
                    $('#top_cart_quantity').html(total_cart_quantity);
                    $('#cart_items_quantity').html(total_cart_quantity);
                    $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                    var total = document.getElementById('#top_cart_quantity');
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: 'Product(s) added to cart successfully',
                        timer: 2000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
                    });
                    window.location.href = '/checkout';
                }
            }
        });
    }

    function updateContact(type , user_id) {
        
        if (type === 'update shipping address') {
            $('#address_loader_shipping').removeClass('d-none');
            var companyNameShipping = $('.companyNameShipping:checked').val();
            var first_name_shipping = $('#shipping_first_name').val();
            var last_name_shipping = $('#shipping_last_name').val();
            var shipping_address_1 = $('.shipping_address_1').val();
            var shipping_address_2 = $('.shipping_address_2').val();
            var shipping_city = $('.shipping_city').val();
            var shipping_state = $('.shipping_state').val();
            var post_code = $('.shipping_post_code').val();
            var shipping_email = $('.shipping_email').val();
            var shipping_phone = $('.shipping_phone').val();

            var company_name = companyNameShipping;
            var first_name = first_name_shipping;
            var last_name = last_name_shipping;
            var address = shipping_address_1;
            var address2 = shipping_address_2;
            var town_city = shipping_city;
            var state = shipping_state;
            var zip = post_code;
            var email = shipping_email;
            var phone = shipping_phone;

            if (companyNameShipping == '' || companyNameShipping == null) {
                $('#error_company_shipping').html('Please select location');
                $('#address_loader_shipping').addClass('d-none');
                return false;
            } 
            else {
                $('#error_company_shipping').html('');
            }
        } else {
            $('#address_loader').removeClass('d-none');
            var companyNameBilling = $('.companyNameBilling:checked').val();
            var first_name_billing = $('#billing_first_name').val();
            var last_name_billing = $('#billing_last_name').val();
            var billing_address_1 = $('.billing_address_1').val();
            var billing_address_2 = $('.billing_address_2').val();
            var billing_city = $('.billing_city').val();
            var billing_state = $('.billing_state').val();
            var post_code = $('.billing_post_code').val();
            var billing_email = $('.billing_email').val();
            var billing_phone = $('.billing_phone').val();

            var company_name = companyNameBilling;
            var first_name = first_name_billing;
            var last_name = last_name_billing;
            var address = billing_address_1;
            var address2 = billing_address_2;
            var town_city = billing_city;
            var state = billing_state;
            var zip = post_code;
            var email = billing_email;
            var phone = billing_phone;

            if (companyNameBilling == '' || companyNameBilling == null) {
                $('#error_company_billing').html('Please select location');
                $('#address_loader').addClass('d-none');
                return false;
            } 
            else {
                $('#error_company_billing').html('');
            }
        }
        
        var companyName = $('.companyName:checked').val();
        // var first_name = $('input[name=firstName]').val();
        // var last_name = $('input[name=lastName]').val();
        // var phone = $('input[name=phone]').val();
        // var address = $('input[name=address]').val();
        // var address2 = $('input[name=address2]').val();
        // var town_city = $('input[name=town_city]').val();
        // var state = document.getElementById("state").value;
        // var zip = $('input[name=zip]').val();
        // var email = $('input[name=email]').val();
        var contact_id = $('#contact_id_val').val();
        var secondary_id = $('input[name=secondary_id]').val();
        
        
        jQuery.ajax({
            method: 'GET',
            url: "{{ url('/my-account-user-addresses/') }}",
            data: {
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
                "email": email,
                'contact_id': contact_id,
                'secondary_id': secondary_id,
                // 'company_name': companyName,
                'type': type
            },
            success: function(response) {
                console.log(response.data.ticket.id );
                if (response.data.ticket.id != '' || response.data.ticket.id != null) {
                    $('#address_loader').addClass('d-none');
                    $('#address_loader_shipping').addClass('d-none');
                    $('.modal-backdrop').remove()
                    if (type === 'update shipping address') { 
                        $('#success_msg_shipping').removeClass('d-none');
                        $('#success_msg_shipping').html(response.msg);
                    } else {

                        $('#success_msg').removeClass('d-none');
                        $('#success_msg').html(response.msg);
                    }
                    setTimeout(function() {
                        window.location.href = "{{ url('my-account/address') }}";
                    }, 2000);
                }   else {
                    $('#address_loader').addClass('d-none');
                    $('#address_loader_shipping').addClass('d-none');
                    $('.modal-backdrop').remove();
                    if (type === 'update shipping address') {  
                        $('#error_msg_shipping').removeClass('d-none');
                        $('#error_msg_shipping').html('Something went wrong');
                    } else {

                        $('#error_msg').removeClass('d-none');
                        $('#error_msg').html('Something went wrong');
                    }
                    setTimeout(function() {
                        window.location.href = "{{ url('my-account/address') }}";
                    }, 2000);
                }
            }
        });
    }

   
   
</script>




{{-- billing address --}}
<div class="modal fade" id="address_modal_id" data-dismiss="modal" data-backdrop="false" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel">Update Billing Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="update-address-section" id="address-form-update">

                    <form class="needs-validation mt-4 novalidate" action="{{ url('order') }}" method="POST">
                        @if(!empty($address_user->contact_id))
                        <input type="hidden" value="{{$address_user->contact_id}}" name="contact_id" id="contact_id_val">
                        @elseif(!empty($address_user->secondary_id))
                        <input type="hidden" value="{{$address_user->secondary_id}}" name="secondary_id" id="secondary_id_val">
                        @endif
                        @csrf
                        <div class="alert alert-success mt-3 d-none" id="success_msg"></div>
                        <div class="alert alert-danger mt-3 d-none" id="error_msg"></div>
                        <input type="hidden" name="email" id="billing_email" value="{{!empty($address_user->email) ? $address_user->email : ''}}">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">First name</label>
                                <input type="text" class="form-control bg-light" name="firstName" id="billing_first_name"
                                    placeholder="First name" value="{{!empty($address_user->firstName) ? $address_user->firstName : ''}}" required>
                                <div id="error_first_name" class="text-danger">

                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="lastName">Last name</label>
                                <input type="text" class="form-control bg-light" name="lastName" id="billing_last_name" placeholder=""
                                    value="{{!empty($address_user->lastName) ? $address_user->lastName : ''}}" required>
                                <div id="error_last_name" class="text-danger">

                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="company">Select Location</label>
                            @php
                                $companies = Session::get('companies');
                                $session_company = Session::get('company');
                            @endphp
                            <div class="input-group">
                                <div class="row">
                                    @if ($companies)
                                    @foreach ($companies as $company)
                                        @php
                                            if ($company->contact_id) {
                                                $contact_id = $company->contact_id;
                                                $primary = '(primary)';
                                            } else {
                                                $contact_id = $company->secondary_id;
                                                $primary = '(secondary)';
                                            }
                                            if ($company->status == 0) {
                                                $disabled = 'disabled';
                                                $disable_text = '(Disabled)';
                                                $muted = 'text-muted';
                                            } else {
                                                $disabled = '';
                                                $disable_text = '';
                                                $muted = '';
                                            }
                                        @endphp
                                        @if($company->type != "Supplier")
                                            <div class="col-md-12">
                                                <input  onclick="change_company_billing(this  , {{ $contact_id }})" type="radio" {{!empty($session_company) && $session_company === $company->company ? 'checked' : ''}} value="{{ $company->company }}" class="companyName companyNameBilling" name="company" id="companyName" {{ $disabled }} {{ $muted }}>
                                                <label for="" {{ $disabled }} {{ $muted }}>{{ $company->company }}
                                                    <span
                                                    style="font-size: 9px;font-family: 'Poppins';"
                                                    class="{{ $muted }}">{{ $primary }}
                                                </span>
                                                </label>
                                            </div>
                                        @endif
                                        {{-- <input type="text" class="form-control bg-light" name="company" id="companyName" placeholder="Enter you company name" value="{{ $address_user->company }}" required> --}}
                                    @endforeach
                                @endif
                                </div>
                            </div>
                            <div id="error_company_billing" class="text-danger"> </div>
                        </div>

                        <div class="mb-3">
                            <label for="username">Country</label>&nbsp;<span>United States</span>
                            <input type="hidden" name="country" value="United States">
                        </div>


                        <div class="mb-3">
                            <label for="address">Street Address</label>
                            <input type="text" class="form-control bg-light billing_address_1 " name="address" id="address1"
                            value="{{ !empty($address_user->postalAddress1) ?  $address_user->postalAddress1 : '' }}" placeholder="House number and street name"
                            required>
                            <div id="error_address1" class="text-danger"></div>
                        </div>
                        <div class="mb-3">
                            <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control bg-light billing_address_2" name="address2"
                                value="{{ !empty($address_user->postalAddress2) ?  $address_user->postalAddress2 : '' }}"
                                placeholder="Apartment, suite, unit etc (optional)">
                                <div id="error_address2" class="text-danger"></div>
                        </div>
                       
                        <div class="mb-3">
                            <label for="town">Town/City <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control bg-light billing_city" name="town_city"
                                value="{{ !empty($address_user->postalCity) ? $address_user->postalCity : '' }}" placeholder="Enter your town">
                                <div id="error_city" class="text-danger"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="state">State</label>

                                <select class="form-control bg-light billing_state"  name="state" id="state">
                                    @if (empty($address_user->postalState)) <option value="">Select State</option>@endif
                                    @foreach ($states as $state)
                                        <?php
                                        if (!empty($address_user->postalState) && ($address_user->postalState == $state->state_name)) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        
                                        ?>
                                        <option value="{{ $state->state_name }}" <?php echo $selected; ?>>{{ $state->state_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Valid first name is required.
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="zip">Zip</label>
                                <input type="text" class="form-control bg-light billing_post_code" name="zip"
                                    placeholder="Enter zip code" value="{{ !empty($address_user->postalPostCode) ? $address_user->postalPostCode : ''}}"
                                    required>
                                <div id="error_zip" class="text-danger">

                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control bg-light billing_phone" name="phone"
                                    placeholder="Enter your phone" value="{{!empty($address_user->phone) ? $address_user->phone  : ''}}" required>
                                <div id="error_phone" class="text-danger"></div>



                            </div>

                        </div>
                    </form>
                </div>


            </div>
            <div class="modal-footer justify-content-center">
                <div class="spinner-border text-primary d-none" role="status" id="address_loader">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <button type="button" class="btn button-cards primary"
                    onclick="updateContact('{{'update billing address'}}' , '{{ auth()->user()->id }}'  )">Update Billing</button>
            </div>
        </div>
    </div>
</div>
{{-- shipping address --}}
<div class="modal fade" id="address_modal_id_shipping" data-dismiss="modal" data-backdrop="false" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel">Update Shipping Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="update-address-section" id="address-form-update">

                    <form class="needs-validation mt-4 novalidate" action="{{ url('order') }}" method="POST">
                        @if(!empty($address_user->contact_id))
                        <input type="hidden" value="{{$address_user->contact_id}}" name="contact_id" id="contact_id_val">
                        @elseif(!empty($address_user->secondary_id))
                        <input type="hidden" value="{{$address_user->secondary_id}}" name="secondary_id" id="secondary_id_val">
                        @endif
                        @csrf
                        <div class="alert alert-success mt-3 d-none" id="success_msg_shipping"></div>
                        <div class="alert alert-danger mt-3 d-none" id="error_msg_shipping"></div>
                        <input type="hidden" name="email" id="shipping_email" value="{{!empty($address_user->email) ? $address_user->email : ''}}">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">First name</label>
                                <input type="text" class="form-control bg-light" id="shipping_first_name" name="firstName"
                                    placeholder="First name" value="{{!empty($address_user->firstName) ? $address_user->firstName  : ''}}" required>
                                <div id="error_first_name" class="text-danger">

                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="lastName">Last name</label>
                                <input type="text" class="form-control bg-light" id="shipping_last_name" name="lastName" placeholder=""
                                    value="{{!empty($address_user->lastName) ? $address_user->lastName : '' }}" required>
                                <div id="error_last_name" class="text-danger">

                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="company">Select Location</label>
                            @php
                                $companies = Session::get('companies');
                                $session_company = Session::get('company');
                            @endphp
                            <div class="input-group">
                                <div class="row">
                                    @if ($companies)
                                    @foreach ($companies as $company)
                                        @php
                                            if ($company->contact_id) {
                                                $contact_id = $company->contact_id;
                                                $primary = '(primary)';
                                            } else {
                                                $contact_id = $company->secondary_id;
                                                $primary = '(secondary)';
                                            }
                                            if ($company->status == 0) {
                                                $disabled = 'disabled';
                                                $disable_text = '(Disabled)';
                                                $muted = 'text-muted';
                                            } else {
                                                $disabled = '';
                                                $disable_text = '';
                                                $muted = '';
                                            }
                                        @endphp
                                        @if($company->type != "Supplier")
                                            <div class="col-md-12">
                                                <input type="radio"  {{!empty($session_company) && $session_company === $company->company ? 'checked' : ''}} value="{{ $company->company }}" name="company" onclick="change_company_shipping(this  , {{ $contact_id }})" class="companyName companyNameShipping" id="companyName" {{ $disabled }} {{ $muted }}>
                                                <label for="" {{ $disabled }} {{ $muted }}>{{ $company->company }}
                                                    <span
                                                    style="font-size: 9px;font-family: 'Poppins';"
                                                    class="{{ $muted }}">{{ $primary }}
                                                </span>
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                                </div>
                            </div>
                            <div id="error_company_shipping" class="text-danger"> </div>
                        </div>

                        <div class="mb-3">
                            <label for="username">Country</label>&nbsp;<span>United States</span>
                            <input type="hidden" name="country" value="United States">
                        </div>


                        <div class="mb-3">
                            <label for="address">Street Address</label>
                            <input type="text" class="form-control bg-light shipping_address_1" name="address"  id="address1"
                            value="{{ !empty($address_user->address1) ? $address_user->address1 : '' }}" placeholder="House number and street name"
                            required>
                            <div id="error_address1" class="text-danger"></div>
                        </div>
                        <div class="mb-3">
                            <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control bg-light shipping_address_2" name="address2"
                                value="{{ !empty($address_user->address2) ? $address_user->address2 : '' }}"
                                placeholder="Apartment, suite, unit etc (optional)">
                                <div id="error_address2" class="text-danger"></div>
                        </div>
                       
                        <div class="mb-3">
                            <label for="town">Town/City <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control bg-light shipping_city" name="town_city"
                                value="{{ !empty($address_user->city) ?  $address_user->city  : ''}}" placeholder="Enter your town">
                                <div id="error_city" class="text-danger"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="state">State</label>

                                <select class="form-control bg-light" class="shipping_state" name="state" id="state">
                                    @if (empty($address_user->state)) <option value="">Select State</option>@endif
                                    @foreach ($states as $state)
                                        <?php
                                        if (!empty($address_user->state ) && ($address_user->state == $state->state_name)) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        
                                        ?>
                                        <option value="{{ $state->state_name }}" <?php echo $selected; ?>>{{ $state->state_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Valid first name is required.
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="zip">Zip</label>
                                <input type="text" class="form-control bg-light shipping_post_code" name="zip"
                                    placeholder="Enter zip code" value="{{ !empty($address_user->postCode) ?  $address_user->postCode: ''}}"
                                    required>
                                <div id="error_zip" class="text-danger">

                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control bg-light shipping_phone" name="phone"
                                    placeholder="Enter your phone" value="{{!empty($address_user->phone) ? $address_user->phone : '' }}" required>
                                <div id="error_phone" class="text-danger"></div>



                            </div>
                        </div>
                    </form>
                </div>


            </div>
            <div class="modal-footer justify-content-center">
                <div class="spinner-border text-primary d-none" role="status" id="address_loader_shipping">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <button type="button" class="btn button-cards primary"
                    onclick="updateContact('{{'update shipping address'}}'  , '{{ auth()->user()->id }}' )">Update Shipping</button>
            </div>
        </div>
    </div>
</div>
{{-- <a class="ms-3" data-bs-toggle="modal" href="#address_modal_id" role="button"><img src="/theme/img/edit_pen.png"></a> --}}


<script>
    function change_company_shipping (e , contact_id) {
        $('.companyNameShipping').prop('checked', false);
        $(e).prop('checked', true);
        var company = contact_id;
        jQuery.ajax({
            url: "{{ url('/switch-company/') }}",
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                'companyId': company
            },
            success: function(response) {
                if (response.success == true) {
                    $('#shipping_first_name').val(response.update_address.firstName);
                    $('#shipping_last_name').val(response.update_address.lastName);
                    $('.shipping_address_1').val(response.update_address.address1);
                    $('.shipping_address_2').val(response.update_address.address2);
                    $('.shipping_city').val(response.update_address.city);
                    $('.shipping_state').val(response.update_address.state);
                    $('.shipping_post_code').val(response.update_address.postCode);
                    $('.shipping_phone').val(response.update_address.phone);
                }
            }
        });
    }
    function change_company_billing (e ,contact_id) {
        $('.companyNameBilling').prop('checked', false);
        $(e).prop('checked', true);
        var company = contact_id;
        jQuery.ajax({
            url: "{{ url('/switch-company/') }}",
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                'companyId': company
            },
            success: function(response) {
                console.log(response);
                if (response.success == true) {
                    $('#billing_first_name').val(response.update_address.firstName);
                    $('#billing_last_name').val(response.update_address.lastName);
                    $('.billing_address_1').val(response.update_address.postalAddress1);
                    $('.billing_address_2').val(response.update_address.postalAddress2);
                    $('.billing_city').val(response.update_address.postalCity);
                    $('.billing_state').val(response.update_address.postalState);
                    $('.billing_post_code').val(response.update_address.postalPostCode);
                    $('.billing_phone').val(response.update_address.phone);
                }
            }
        });
    }
    
</script>
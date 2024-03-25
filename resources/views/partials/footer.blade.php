<input type="hidden" id="zendesk_first_name" value="{{auth()->user() && !empty(auth()->user()->onlycontact->firstName) ? auth()->user()->onlycontact->firstName : ''}}">
<input type="hidden" id="zendesk_last_name" value="{{auth()->user() && !empty(auth()->user()->onlycontact->lastName) ? auth()->user()->onlycontact->lastName : ''}}">
<input type="hidden" id="zendesk_email" value="{{auth()->user() ? auth()->user()->email : ''}}">
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="{{ asset('//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/theme/bootstrap5/js/bootstrap.js') }}"></script>
<script src="{{ asset('//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js') }}"></script>

<script src="https://kit.fontawesome.com/ec19ec29f3.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script id="ze-snippet" src="{{asset('zendesk.js?key=c226feaf-aefa-49d4-ae97-5b83a096f475')}}"></script>
<script>
	$(document).ready(function() {
		var order_id = parseInt($('.getorderID').html());
		var currency = 'USD';
		var orderTotal = $('.getorderTotal').val();
		var send_to = 'AW-11475808917/uLuQCJO2t40ZEJXli-Aq';
		console.log(order_id , currency , orderTotal , send_to);
		if (window.location.pathname.match('/thankyou/')) {
			gtag('event', 'conversion', {
				'send_to': 'AW-11475808917/uLuQCJO2t40ZEJXli-Aq',
				'value':orderTotal,
				'currency': 'USD',
				'transaction_id': order_id
			});
		}

	});
</script>
<script>
    function adding_quantity(product_id , option_id) {
        updateBodyClickEventStatus(false);
        var plus = parseInt($('.swap_qty_number_' + product_id).val()) === '' ? 1 : parseInt($('.swap_qty_number_' + product_id).val());
        var stock_number = $('.swap_qty_number_'+product_id).attr('max');
        var result = plus + 1;
        if (result > stock_number) {
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'Maximum stock limit reached',
                timer: 3000,
                showConfirmButton: false,
                position: 'top',
                timerProgressBar: true
            });
            $('.swap_qty_number_' + product_id).val(stock_number);
            $('.cart-total-number-' + product_id).html(stock_number);
            return false;
        }
        else {
            var new_qty = $('.swap_qty_number_' + product_id).val(result);
            increasingQuantity(product_id , option_id)
            $('.swap_qty_number_' + product_id).val(result)
        } 
    }
    function subtracting_quantity(product_id , option_id) {
        updateBodyClickEventStatus(false);
        var minus = $('.swap_qty_number_' + product_id).val() == '' ? 1 : parseInt($('.swap_qty_number_' + product_id).val());
        if (minus > 1) {
            var result = minus - 1;
            $('.swap_qty_number_' + product_id).val(result);
            decreasingQuantity(product_id , option_id)
        } else {
            var result = minus - 1;
            
            if (minus == 1) {
                result = 1;
                // $('.cart-total-number-' + product_id).css('display' , 'none');
                $('.button_swap_quantity_'+product_id).addClass('d-none');
                $('.original_cart_btn_'+product_id).removeClass('d-none');
                $('.swap_qty_number_' + product_id).val(0);
                decreasingQuantity(product_id , option_id)
            }
            else{
                // $('.cart-total-number-' + product_id).css('display' , 'none');
                $('.button_swap_quantity_'+product_id).addClass('d-none');
                $('.original_cart_btn_'+product_id).removeClass('d-none');
                $('.swap_qty_number_' + product_id).val(0);
            
            }
        }
         
    }
    function increasingQuantity(id, option_id) {
        // $('#ajaxSubmit_'+id).addClass('d-none');
        // $('#button_swap_'+id).removeClass('d-none');
        // $('#swap_qty_number_'+id).val(1);
        jQuery.ajax({
            url: "{{ url('/update-product-cart/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                p_id: jQuery('#p_' + id).val(),
                option_id: option_id,
                quantity: 1,
                'action': 'addition'
            },
            success: function(response) {
                if (response.status == 'error') {
                    var cart_items = response.cart_items;
                    var cart_total = 0;
                    var total_cart_quantity = 0;
                    var product_quantity = 0;
                    for (var key in cart_items) {
                        var item = cart_items[key];
                        var product_id = item.prd_id;
                        product_quantity = item.quantity;
                        var price = parseFloat(item.price);
                        var quantity = parseFloat(item.quantity);
                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        var product_name = document.getElementById("product_name_" + jQuery('#p_' + id)
                            .val()).innerHTML;
                    }
                    // jQuery('.cart-total-' + id).html($('#swap_qty_number_' + id).val());
                    // jQuery('.cart-total-number-' + id).html($('.swap_qty_number_' + id).val());
                    jQuery('.cart-total-number-' + id).html(response.actual_stock);
                    jQuery('.swap_qty_number_'+id).val(response.actual_stock);
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: response.message,
                        timer: 3000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
                    });
                }
                if (response.status == 'success') {
                    var cart_items = response.cart_items;
                    var cart_total = 0;
                    var total_cart_quantity = 0;
                    var product_quantity = 0;
                    for (var key in cart_items) {
                        var item = cart_items[key];
                        var product_id = item.prd_id;
                        product_quantity = item.quantity;
                        var price = parseFloat(item.price);
                        var quantity = parseFloat(item.quantity);
                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        var product_name = document.getElementById("product_name_" + jQuery('#p_' + id)
                            .val()).innerHTML;
                    }
                    // jQuery('.cart-total-' + id).html($('#swap_qty_number_' + id).val());
                    jQuery('.cart-total-number-' + id).html($('.swap_qty_number_' + id).val());
                    // Swal.fire({
                    //     toast: true,
                    //     icon: 'success',
                    //     title: jQuery('#quantity').val() + ' X ' + product_name +
                    //         ' added to your cart',
                    //     timer: 3000,
                    //     showConfirmButton: false,
                    //     position: 'top',
                    //     timerProgressBar: true
                    // });
                }
                $('#top_cart_quantity').html(total_cart_quantity);
                $('#cart_items_quantity').html(total_cart_quantity);
                $('.cartQtyipad').html(total_cart_quantity);
                $('.cartQtymbl').html(total_cart_quantity);
                $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                $('.topbar_cart_total_ipad').html('$'+parseFloat(cart_total).toFixed(2));
                var total = document.getElementById('#top_cart_quantity');
            }
        });
        return false;
    }
    function decreasingQuantity(id, option_id) {
        // $('#ajaxSubmit_'+id).addClass('d-none');
        // $('#button_swap_'+id).removeClass('d-none');
        // $('#swap_qty_number_'+id).val(1);
        var stock_number = $('.swap_qty_number_'+id).attr('max');
        jQuery.ajax({
            url: "{{ url('/update-product-cart/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                p_id: jQuery('#p_' + id).val(),
                option_id: option_id,
                quantity: 1,
                'action': 'subtraction'
            },
            success: function(response) {
                if (response.status == 'error') {
                    var cart_items = response.cart_items;
                    var cart_total = 0;
                    var total_cart_quantity = 0;
                    var product_quantity = 0;
                    for (var key in cart_items) {
                        var item = cart_items[key];
                        var product_id = item.prd_id;
                        product_quantity = item.quantity;
                        var price = parseFloat(item.price);
                        var quantity = parseFloat(item.quantity);
                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        var product_name = document.getElementById("product_name_" + jQuery('#p_' + id)
                            .val()).innerHTML;
                    }
                    // jQuery('.cart-total-' + id).html($('#swap_qty_number_' + id).val());
                    // jQuery('.cart-total-number-' + id).html(stock_number);
                    jQuery('.cart-total-number-' + id).html(response.actual_stock);
                    jQuery('.swap_qty_number_'+id).val(response.actual_stock);
                    // Swal.fire({
                    //     toast: true,
                    //     icon: 'error',
                    //     title: jQuery('#quantity').val() > 1 ? jQuery('#quantity').val() + ' X ' + product_name : 'Product' +
                    //         ' removed from your  cart',
                    //     timer: 3000,
                    //     showConfirmButton: false,
                    //     position: 'top',
                    //     timerProgressBar: true
                    // });
                }
                if (response.status == 'success') {
                    var cart_items = response.cart_items;
                    var cart_total = 0;
                    var total_cart_quantity = 0;
                    var product_quantity = 0;
                    for (var key in cart_items) {
                        var item = cart_items[key];
                        var product_id = item.prd_id;
                        product_quantity = item.quantity;
                        var price = parseFloat(item.price);
                        var quantity = parseFloat(item.quantity);
                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        console.log(item.name);
                        var product_name = document.getElementById("product_name_" + jQuery('#p_' + id)
                            .val()).innerHTML;
                    }
                    // jQuery('.cart-total-' + id).html($('#swap_qty_number_' + id).val());
                    jQuery('.cart-total-number-' + id).html($('.swap_qty_number_' + id).val());
                    // Swal.fire({
                    //     toast: true,
                    //     icon: 'error',
                    //     title: jQuery('#quantity').val() > 1 ? jQuery('#quantity').val() + ' X ' + product_name : 'Product' +
                    //         ' removed from your  cart',
                    //     timer: 3000,
                    //     showConfirmButton: false,
                    //     position: 'top',
                    //     timerProgressBar: true
                    // });
                }
                $('#top_cart_quantity').html(total_cart_quantity);
                $('#cart_items_quantity').html(total_cart_quantity);
                $('.cartQtyipad').html(total_cart_quantity);
                $('.cartQtymbl').html(total_cart_quantity);
                $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                $('.topbar_cart_total_ipad').html('$'+parseFloat(cart_total).toFixed(2));
                var total = document.getElementById('#top_cart_quantity');
            }
        });
        return false;
    }
    function update_qty_text(id , option_id) {
        updateBodyClickEventStatus(false);
        var stock_number = $('.swap_qty_number_'+id).attr('max');
        var qty = parseInt($('.swap_qty_number_' + id).val()) == 0 || $('.swap_qty_number_' + id).val() === '' ? 1 : parseInt($('.swap_qty_number_' + id).val());
        $('.swap_qty_number_' + id).val(qty);
        if (qty > stock_number) {
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'Maximum stock limit reached',
                timer: 3000,
                showConfirmButton: false,
                position: 'top',
                timerProgressBar: true
            });
            $('.button_swap_quantity_'+id).addClass('d-none');
            $('.original_cart_btn_'+id).removeClass('d-none');
            $('.cart-total-' + id).addClass('added-to-cart-on-change');
            $('.swap_qty_number_' + id).val(0);
            $('.cart-total-number-' + id).html(0);
            return false;
        }
        else {
            jQuery.ajax({
                url: "{{ url('/add-to-cart/') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    p_id: jQuery('#p_' + id).val(),
                    option_id: option_id,
                    quantity: qty
                },
                success: function(response) {
                    if (response.status == 'error') {
                        var cart_items = response.cart_items;
                        var cart_total = 0;
                        var total_cart_quantity = 0;
                        var product_quantity = 0;

                        for (var key in cart_items) {
                            var item = cart_items[key];

                            var product_id = item.prd_id;
                            var price = parseFloat(item.price);
                            product_quantity = item.quantity;
                            var quantity = parseFloat(item.quantity);

                            var subtotal = parseFloat(price * quantity);
                            var cart_total = cart_total + subtotal;
                            var total_cart_quantity = total_cart_quantity + quantity;
                            $('#subtotal_' + product_id).html('$' + subtotal);
                            var product_name = document.getElementById("product_name_" + jQuery('#p_' + id)
                                .val()).innerHTML;
                        }

                        // jQuery('.cart-total-' + id).html($('#swap_qty_number_' + id).val());
                        // jQuery('.cart-total-number-' + id).html($('.swap_qty_number_' + id).val());
                        jQuery('.cart-total-number-' + id).html(response.actual_stock);
                        jQuery('.swap_qty_number_'+id).val(response.actual_stock);
                        Swal.fire({
                            toast: true,
                            icon: 'error',
                            title: response.message,
                            timer: 3000,
                            showConfirmButton: false,
                            position: 'top',
                            timerProgressBar: true
                        });
                    }
                    if (response.status == 'success') {
                        var cart_items = response.cart_items;
                        var cart_total = 0;
                        var total_cart_quantity = 0;
                        var product_quantity = 0;

                        for (var key in cart_items) {
                            var item = cart_items[key];

                            var product_id = item.prd_id;
                            var price = parseFloat(item.price);
                            product_quantity = item.quantity;
                            var quantity = parseFloat(item.quantity);

                            var subtotal = parseFloat(price * quantity);
                            var cart_total = cart_total + subtotal;
                            var total_cart_quantity = total_cart_quantity + quantity;
                            $('#subtotal_' + product_id).html('$' + subtotal);
                            var product_name = document.getElementById("product_name_" + jQuery('#p_' + id)
                                .val()).innerHTML;
                        }

                        // jQuery('.cart-total-' + id).html($('#swap_qty_number_' + id).val());
                        jQuery('.cart-total-number-' + id).html($('.swap_qty_number_' + id).val());

                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            title: qty + ' X ' + product_name +
                                ' added to your cart',
                            timer: 3000,
                            showConfirmButton: false,
                            position: 'top',
                            timerProgressBar: true
                        });
                    }
                    $('#top_cart_quantity').html(total_cart_quantity);
                    $('#cart_items_quantity').html(total_cart_quantity);
                    $('.cartQtyipad').html(total_cart_quantity);
                    $('.cartQtymbl').html(total_cart_quantity);
                    $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                    $('.topbar_cart_total_ipad').html('$'+parseFloat(cart_total).toFixed(2));
                    var total = document.getElementById('#top_cart_quantity');
                }
            });
        }
        
    }
    function button_swap_quantity (id , option_id) {
        updateBodyClickEventStatus(false);
    }
</script>

<script type="text/javascript">
    var popover = new bootstrap.Popover(document.querySelector('.cart-price'), {
        container: 'body',
        html: true
    });
    feather.replace();
</script>
<script>
    // delete employee ajax request
    $(document).on('click', '.deleteIcon', function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        let csrf = '{{ csrf_token() }}';
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('secondary_user.delete') }}',
                    method: 'delete',
                    data: {
                        id: id,
                        _token: csrf
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                        $('#row-' + id).remove();
                    }
                });
            }
        })
    });
</script>
<script type="text/javascript">
    let dropdowns = document.querySelectorAll('.dropdown-toggle')
    dropdowns.forEach((dd) => {
        dd.addEventListener('click', function(e) {
            var el = this.nextElementSibling
            el.style.display = el.style.display === 'block' ? 'none' : 'block'
        })
    });

    function bodyClickHandler() {
        $('.added-to-cart').css('display', 'inline-flex');
        $('.button_swap_quantity').css('display', 'none');
        $('.quantity_count_circle').each(function() {
            var html = $(this);
            var spanContent = $(html).find('span');
            if (parseInt($(html).find('span').html()) > 0) {
                spanContent.parent().css('display', 'inline-flex');
            } else {
                spanContent.parent().css('display', 'none');
            }
        });
    }
    function updateBodyClickEventStatus(newStatus) {
      bodyClickEventActive = newStatus;
    }

    $(document).ready(function() {
        $('body').click(function() {
            if (bodyClickEventActive) {
                bodyClickHandler();
            }

        });

        $('body').on('click', function() {
            updateBodyClickEventStatus(true);
        });

        $(document).on('click', '#copyUrl', function() {
            $('#custom_loader').removeClass('d-none');
            let textValue = $(this).attr('data-id');
            var temp = $("<input>");
            $("body").append(temp);
            temp.val(textValue).select();
            document.execCommand("copy");
            temp.remove();
            console.timeEnd('time1');
            $('#custom_loader').addClass('d-none');
        });
        $('.login-info-box').fadeOut();
        $('.login-show').addClass('show-log-panel');
    });
    $('.login-reg-panel input[type="radio"]').on('change', function() {
        if ($('#log-login-show').is(':checked')) {
            $('.register-info-box').fadeOut();
            $('.login-info-box').fadeIn();
            $('.white-panel').addClass('right-log');
            $('.register-show').addClass('show-log-panel');
            $('.login-show').removeClass('show-log-panel');
        } else if ($('#log-reg-show').is(':checked')) {
            $('.register-info-box').fadeIn();
            $('.login-info-box').fadeOut();
            $('.white-panel').removeClass('right-log');
            $('.login-show').addClass('show-log-panel');
            $('.register-show').removeClass('show-log-panel');
        }
    });

    //set active class on click
    var path = window.location.href;
    $('.account_navigation li a').each(function() {
        if (this.href == path) {
            $(this).parent().addClass('active');
        }
    });
</script>
</script>
<script type="text/javascript">
    $(document).ready(function() {
        // popovers initialization - on hover
        $('[data-toggle="popover-click"]').popover({
            html: true,
            trigger: 'clcik',
            placement: 'top',
            sanitize: false,
            content: function() {
                return '<img src="' + $(this).data('img') + '" />';
            }
        });

        // popovers initialization - on click
        $('[data-toggle="popover-hover"]').popover({

            html: true,
            trigger: 'hover',
            placement: 'top',
            sanitize: false,
            content: $('#popover-form').html(),
        });
        var invitation_sent = sessionStorage.getItem('invitation');
        if (invitation_sent == 1) {
            $("#additional_users").addClass("active");
            $("#additional-users").removeClass("d-none");
            $('#intro').addClass('d-none');
            $('.nav-pills #dashboard').addClass('active');
            $('#dashboard').removeClass('active');
        } else {
            $("#additional_users").removeClass("active");
        }
    });
</script>
<script>
    $(document).ready(function() {
        var input = $('.mobileFormat').html();
        var formattedInput = formatPhoneNumber(input);
        $('.mobileFormat').html(formattedInput);
    });

    function formatPhoneNumber(input) {
        // Remove all non-digit characters from the input
        var digits = input.replace(/\D/g, '');

        // Format the digits as (555) 123-4567
        var formattedInput = '';
        for (var i = 0; i < digits.length; i++) {
            if (i === 0) {
                formattedInput += '(';
            } else if (i === 3) {
                formattedInput += ') ';
            } else if (i === 6) {
                formattedInput += '-';
            }
            formattedInput += digits.charAt(i);
        }

        return formattedInput;
    }
</script>
<script>
    $(document).ready(function() {
        var list_id = $("#list_id").val();
        if (list_id == '') {
            $(".btn-add-to-cart").prop('disabled', true);
        } else {
            $(".btn-add-to-cart").prop('disabled', false);
        }
        $('body').on('click', '.btn-add-to-cart', function() {
            var id = $(this).attr('id');
            var product_id = id.replace('btn_', '');
            var row = $('#product_row_' + product_id).length;

            if (row > 0) {
                var difference = 0;
                var subtotal_before_update = parseFloat($('#subtotal_' + product_id).html());
                console.log('difference => ' + difference);
                console.log('sub total before update  => ' + subtotal_before_update);

                var retail_price = parseFloat($('#retail_price_' + product_id).html());
                var quantity = parseFloat($('#quantity_' + product_id).val());
                var subtotal = parseFloat($('#subtotal_' + product_id).html());

                quantity++;
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
                return false;
            }

            jQuery.ajax({
                url: "{{ url('admin/add-to-list') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    product_id: product_id,
                    //option_id: option_id
                },
                success: function(response) {
                    $('#product_list').append(response);
                    console.log(response);
                    var grand_total = $('#grand_total').html();
                    grand_total = parseFloat(grand_total);

                    var retail_price = $('#btn_' + product_id).attr('data-retail-price');
                    console.log(retail_price);

                    var subtotal = retail_price * 1;

                    grand_total = grand_total + subtotal;

                    $('#grand_total').html(grand_total);
                }
            });
        });
        $('.all_items:first').addClass('active');
    });
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js"></script>
    <script>
        $('#owl-carousel-landing-page').owlCarousel({
            rtl:false,
            loop:false,
            margin:10,
            dots:false,
            nav:true,
            navText: ["<i class='fas fa-angle-left'></i>", "<i class='fas fa-angle-right'></i>"],
            responsive:{
                0:{
                    items:1,
                    nav:false
                },
                600:{
                    items:2,
                    slideBy:2,
                    nav:false
                },
                1000:{
                    items:3,
                    slideBy:3,
                },
            }
        });
        $('.similar_products_owl_carasoul_blog').owlCarousel({
            rtl:false,
            loop:false,
            margin:10,
            dots:false,
            nav:true,
            navText: ["<i class='fas fa-angle-left'></i>", "<i class='fas fa-angle-right'></i>"],
            responsive:{
                0:{
                    items:1,
                   
                },
                600:{
                    items:2,
                    
                },
                1400:{
                    items:3,
                   
                },
            }
        });
        $(document).ready(function() {
            function calculateItemsToShow() {
            // Your logic to dynamically calculate the number of items
                var screenWidth = $(window).width();
                if (screenWidth > 0 && screenWidth < 600) {
                    return 1;
                } 
                if (screenWidth >= 600 && screenWidth < 1000) {
                    return 2;
                }
                if (screenWidth >= 1000 && screenWidth < 1200) {
                    return 3;
                }
                if (screenWidth >= 1200 && screenWidth < 1400) {
                    return 4;
                }
                if (screenWidth >= 1400 && screenWidth < 1800) {
                    return 6;
                }
                if (screenWidth >= 1800 && screenWidth < 2250) {
                    return 8;
                }
                if (screenWidth >= 2250) {
                    return 10;
                }
            }
            function updateOptions() {
                var totalItems = $('.similar_items_div').length;
                var itemsToShow = calculateItemsToShow();
                var centerItems = totalItems < itemsToShow;
                if (totalItems >= itemsToShow) {
                    $('.similar_products_owl_carasoul .owl-stage-outer').removeClass('d-flex');
                }
                $('.similar_products_owl_carasoul').owlCarousel({
                    rtl:false,
                    loop:false,
                    // center: centerItems,
                    margin:10,
                    nav:true,
                    navText: ["<i class='fas fa-angle-left'></i>", "<i class='fas fa-angle-right'></i>"],
                    responsive:{
                        0:{
                            items:calculateItemsToShow(),
                            nav:false,
                            slideBy:1,
                        },
                        600:{
                            items:calculateItemsToShow(),                    
                            nav:false,
                            slideBy:2,
                        },
                        1000:{
                            items:calculateItemsToShow(),                    
                            slideBy:3,
                        },
                        1200:{
                            items:calculateItemsToShow(),                    
                            slideBy:4,
                        },
                        1400: {
                            items:calculateItemsToShow(),                    
                            slideBy:6,
                        },
                        1800:{
                            items:calculateItemsToShow(),                    
                            slideBy:8,
                        },
                        2250:{
                            items:calculateItemsToShow(),
                            slideBy:10,
                        }
                    }
                });
            }
            updateOptions(); // Initial setup

            // Update options on window resize
            $(window).resize(function () {
                updateOptions();
            });

            var totalItems = $('.similar_items_div').length;
            var itemsToShow = calculateItemsToShow();
            var centerItems = totalItems < itemsToShow;
            if (totalItems >= itemsToShow) {
                $('.similar_products_owl_carasoul .owl-stage-outer').css('display', 'block');    
            }
        });
        function showZendesk() {
            var first_name = $('#zendesk_first_name').val();
            var last_name = $('#zendesk_last_name').val();
            var userName = first_name + ' ' + last_name;
            var userEmail = $('#zendesk_email').val();
            zE('webWidget', 'identify', {
                name: userName,
                email: userEmail
            });

            zE('webWidget', 'open');
        }
    </script>

    <!-- Modal -->
<div class="modal fade notify_popup_modal" id="notify_user_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Notify User About Product Stock</h5>
            <button type="button" class="close" onclick="close_notify_user_modal()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form>
                <div class="row">
                    <div class="form-group">
                        <label for="">Email <span class="text-danger">*</span></label>
                        <input type="hidden" name="sku" id="sku_value" class="productSku_value" value="">
                        <input type="hidden" name="product_id" id="product_id_value" class="productId_value" value="">
                        <div class="col-md-12">
                            <input type="text" name="notify_user_email" id="notify_user_email" class="form-control notifyEmail" value="" placeholder="Enter your email">
                            <div class="text-danger email_required_alert"></div>
                        </div>
    
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="spinner-border text-primary stock_spinner_modal d-none" role="status">
                <span class="sr-only"></span>
            </div>
            <button type="button" class="btn btn-secondary" onclick="notify_user_about_product_stock($('.productId_value').val() , $('.productSku_value').val())">Submit</button>
            <!-- You can add additional buttons here if needed -->
        </div>
        </div>
    </div>
</div>
<div class="modal fade notify_popup_modal_similar" id="notify_user_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Notify User About Product Stock</h5>
            <button type="button" class="close" onclick="close_notify_user_modal_similar()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form>
                <div class="row">
                    <div class="form-group">
                        <label for="">Email <span class="text-danger">*</span></label>
                        <input type="hidden" name="sku" id="sku_value" class="productSku_value" value="">
                        <input type="hidden" name="product_id" id="product_id_value" class="productId_value" value="">
                        <div class="col-md-12">
                            <input type="text" name="notify_user_email" id="notify_user_email" class="form-control similar_notifyEmail" value="" placeholder="Enter your email">
                            <div class="text-danger email_required_alert"></div>
                        </div>
    
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="spinner-border text-primary stock_spinner_modal d-none" role="status">
                <span class="sr-only"></span>
            </div>
            <button type="button" class="btn btn-secondary" onclick="notify_user_about_product_stock_similar($('.productId_value').val() , $('.productSku_value').val())">Submit</button>
            <!-- You can add additional buttons here if needed -->
        </div>
        </div>
    </div>
</div>
{{--  notify user pop up modal end --}}

<script>
    $(document).on('keydown', function(event) {
        // Check if the modal is open and the Enter key is pressed
        if ($('.notify_popup_modal').hasClass('show') && event.keyCode === 13) {
            // Prevent the default action of the Enter key
            event.preventDefault();
        }
        if ($('.notify_popup_modal_detail').hasClass('show') && event.keyCode === 13) {
            // Prevent the default action of the Enter key
            event.preventDefault();
        }
        if ($('.notify_popup_modal_similar').hasClass('show') && event.keyCode === 13) {
            // Prevent the default action of the Enter key
            event.preventDefault();
        }
        if ($('.notify_popup_modal_similar_portion').hasClass('show') && event.keyCode === 13) {
            // Prevent the default action of the Enter key
            event.preventDefault();
        }
    });
</script>
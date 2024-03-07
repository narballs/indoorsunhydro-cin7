@include('partials.header')
<body class="m-0 p-0" style="overflow-x: hidden">
    <main>
        @include('partials.top-bar')
        @include('partials.search-bar')
        @include('partials.banner')
        
        <div class="bg-light pb-5">
            <div class="container-sm bg-light">
                <div class="row mx-0">
                    <div class="col-md-8 pe-0 ps-0">
                        <div class="card mt-5">
                            <div class="card-body text-center">
                                <h4 class="text-center p-2 mt-3">We stock all superior brands!</h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row pl-4 pr-4">
                                            <div class="col thumb">
                                                <a class="thumbnail">
                                                    <img class="img-responsive img-fluid" src="theme/img/nutrients.png"
                                                        width="102px" height="86px">
                                                </a>
                                            </div>
                                            <div class="col p-0 my-4 thumb">
                                                <a class="thumbnail">
                                                    <img class="img-fluid" src="theme/img/mills.png"
                                                        class="img-responsive img-fluid" width="142px"
                                                        height="59px"></a>
                                            </div>
                                            <div class="col thumb my-5">
                                                <a class="thumbnail">
                                                    <img src="theme/img/troll.png" width="165px" height="24px"
                                                        class="img-responsive img-fluid">
                                                </a>
                                            </div>
                                            <div class="col thumb p-0">
                                                <a class="thumbnail">
                                                    <img src="theme/img/elite.png" width="79px" height="88px"
                                                        class="img-responsive img-fluid">
                                                </a>
                                            </div>
                                            <div class="col thumb my-5 p-0">
                                                <a class="thumbnail">
                                                    <img src="theme/img/lux.png" width="107px" height="33px"
                                                        class="img-responsive img-fluid">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row  pl-4 pr-4">
                                            <div class="col thumb">
                                                <a class="thumbnail">
                                                    <img class="img-responsive img-fluid" src="theme/img/titan.png"
                                                        width="130px" height="65px">
                                                </a>
                                            </div>
                                            <div class="col p-0 thumb">
                                                <a class="thumbnail">
                                                    <img class="img-fluid" src="theme/img/quest_small.png"
                                                        width="143px" height="44px"
                                                        class="img-responsive img-fluid"></a>
                                            </div>
                                            <div class="col thumb">
                                                <a class="thumbnail">
                                                    <img class="img-fluid" src="theme/img/hydroponics.png"
                                                        width="167px" height="72px" class="img-responsive img-fluid">
                                                </a>
                                            </div>
                                            <div class="col thumb p-0">
                                                <a class="thumbnail">
                                                    <img class="img-fluid" src="theme/img/botanicare.png" width="200px"
                                                        height="64px" class="img-responsive img-fluid">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row  pl-4 pr-1">
                                            <div class="col thumb p-0">
                                                <a class="thumbnail" href="#">
                                                    <img class="img-fluid" class="img-responsive img-fluid"
                                                        src="theme/img/godan.png" width="201px" height="84px">
                                                </a>
                                            </div>
                                            <div class="col p-0 thumb">
                                                <a class="thumbnail" href="#">
                                                    <img class="img-fluid" src="theme/img/gavita.png" width="117px"
                                                        height="63px" class="img-responsive img-fluid"></a>
                                            </div>
                                            <div class="col thumb">
                                                <a class="thumbnail" href="#">
                                                    <img class="img-fluid" src="theme/img/can-filter.png" width="125px"
                                                        height="57px" class="img-responsive img-fluid">
                                                </a>
                                            </div>
                                            <div class="col thumb p-0 my-3">
                                                <a class="thumbnail" href="#">
                                                    <img class="img-fluid" src="theme/img/clonex.png" width="156px"
                                                        height="28px" class="img-responsive img-fluid">
                                                </a>
                                            </div>
                                            <div class="col thumb p-0 mr-4">
                                                <a class="thumbnail" href="#">
                                                    <img class="img-fluid" src="theme/img/hand.png" width="59px"
                                                        height="67px" class="img-responsive img-fluid">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 home-page-product-section">
                        <div class="mt-5">
                            <a class="link-dark text-decoration-none text-white" href="{{ url('products') }}">
                                <img class="img-fluid home-page-product-img" src="theme/img/all_products.png">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-12 home-page-product-section">
                        <div class="row mt-3">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <a class="link-dark text-decoration-none text-white"
                                    href="{{ url('product-brand/Trolmaster') }}">
                                    <img class="img-fluid home-page-product-img" src="theme/img/troll_master.png">
                                </a>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <a class="link-dark text-decoration-none text-white"
                                    href="{{ url('products/253/ac-dehumidification-humidification') }}">
                                    <img class="img-fluid home-page-product-img" src="theme/img/quest.png">
                                </a>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <a class="link-dark text-decoration-none text-white"
                                    href="{{ url('product-brand/Advanced Nutrients') }}">
                                    <img class="img-fluid home-page-product-img" src="theme/img/nutrients_box.png">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 home-page-product-section">
                        <div class="row mt-3 gx-5">
                            <div class="col-md-4">
                                <a class="link-dark text-decoration-none text-white"
                                    href="{{ url('products/7/lighting') }}">
                                    <img class="img-fluid home-page-product-img" src="theme/img/lightening.png">
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a class="link-dark text-decoration-none text-white"
                                    href="{{ url('product-brand/Mills Nutrients') }}">
                                    <img class="img-fluid home-page-product-img" src="theme/img/can.png">
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a class="link-dark text-decoration-none text-white"
                                    href="{{ url('products/7/lighting') }}">
                                    <img class="img-fluid home-page-product-img" src="theme/img/lux_lightening.png">
                                </a>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            {{-- recent viewed products --}}
            @include('partials.recent_products_slider')
        </div>

            {{-- our advantages  --}}
        <div class="bg-white pb-5">
            <div class="container-sm bg-white">
                <div class="row">
                    <div class="col-md-12 home-page-product-section">
                        <div class="row advantages_div">
                            <div class="col-md-12">
                                <h1
                                    class="text-center our-advantages border-0 d-flex justify-content-center align-items-center our_advantages">
                                    Our advantages
                                </h1>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5 p-0">
                                <div class="d-flex align-items-center justify-content-center">
                                    <img src="/theme/img/National shipping.png" class="img-fluid" alt="">
                                </div>

                                <h4 class="thumbnail-items text-center mt-5">Nationwide Shipping</h4>
                                <p class="thumbnail-pra mt-3 nation_wide_para">with multiple warehouses you can get the supplies you need
                                    delivered to
                                    your door faster</p>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5 p-0">
                                <div class="d-flex align-items-center justify-content-center">
                                    <img src="/theme/img/Icon_lowest_prices.png" class="img-fluid" alt="">
                                </div>
                                <h4 class="thumbnail-items text-center mt-5">Lowest Prices</h4>
                                <p class="thumbnail-pra mt-3 nation_wide_para">we purchase in bulk so you don’t have to</p>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5 p-0">
                                <div class="d-flex align-items-center justify-content-center">
                                    <img src="/theme/img/Icon_quality.png" class="img-fluid" alt="">
                                </div>
                                <h4 class="thumbnail-items text-center mt-5">Quality Products</h4>
                                <p class="thumbnail-pra mt-3 nation_wide_para">we only carry products that we stand by, we honor all
                                    manufacturer
                                    warranties</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <style>

        .hover_effect:hover {
            background-color: #FFFFFF !important;
            color: #7BC533 !important;
            border: 1px solid #7BC533 !important;
        }
        .product_buys_count {
            font-size: 11px;
        }
        .margin-for-empty {
            margin-bottom: 1.4rem ;
        }
    
        .price-category-view-section {
            min-height: 7.7rem;
        }
    
    
        /* addign tooltip for title of product */
        /* .tooltip-product {
          position: relative;
          display: inline-block;
        } */
        .tooltip-product-text {
            display: none;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 500;
            position: absolute;
            top: 47%;
            text-align: center;
            font-size: 12px;
            /* box-shadow: 1px 1px 4px 4px rgba(0, 0, 0, 0.25); */
            box-shadow: 0px 4px 4px rgba(146, 130, 130, 0.25);
        }
    
        .tooltip-arrow {
            width: 0; 
            height: 0; 
            border-left: 7px solid transparent;
            border-right: 7px solid transparent;
            border-top: 7px solid #fff !important;
            position: absolute;
            top: 100%;
            left: 10%;
        }
        .tooltip-product:hover .tooltip-product-text {
          display: block;
        }

        .notify_stock_btn_class {
            font-size: 15px;
        }
        
        @media screen and (max-width:350px)  and (min-width: 280px){
            .add-to-cart-button-section {
                padding: 0px !important;
            }
        }
        @media screen and (max-width:550px)  and (min-width: 280px){
            .product_buys_count {
                font-size: 7.5px !important;
            }
            .margin-for-empty {
                margin-bottom: 0.95rem !important;
            }
    
            .ft-size {
                font-size: 0.5rem;
            }
    
            .price-category-view-section {
                min-height: 5.7rem;
            }
        }
    </style>
    <script>
         function show_notify_popup_modal (id , sku_value) {
            $('.notify_popup_modal').modal('show');
            $('.productId_value').val(id);
            $('.productSku_value').val(sku_value);
        } 
        function close_notify_user_modal () {
            $('.notify_popup_modal').modal('hide');
            $('.notify_stock_btn_class').each(function() {
                $(this).attr('disabled', false);
            });
        }
        
        function notify_user_about_product_stock (id , sku_value) {
            $('.notify_stock_btn_class').each(function() {
                var p_id = $(this).attr('data-product-id');
                if (p_id != id) {
                    $(this).attr('disabled', true);
                }
            });
            var email = $('.notifyEmail').val();
            var sku = sku_value;
            var product_id = id;
            $('.stock_spinner_modal').removeClass('d-none');
            $('.stock_spinner_'+product_id).removeClass('d-none');
            if (email != '') {
                $('.email_required_alert').html('');
            }
            if (email == '') {
                $('.email_required_alert').html('Email is Required');
                $('.stock_spinner_modal').addClass('d-none');
                $('.stock_spinner_'+product_id).addClass('d-none');
                return false;
            }
            else {
                $.ajax({
                    url: "{{ url('product-stock/notification') }}",
                    method: 'post',
                    data: {
                    "_token": "{{ csrf_token() }}",
                        email : email,
                        sku : sku,
                        product_id : product_id
                    },
                    success: function(response){

                        if (response.status === true) {
                            $('.stock_spinner_modal').addClass('d-none');
                            $('.stock_spinner_'+product_id).addClass('d-none');
                            $('.notify_user_div').removeClass('d-none');
                            close_notify_user_modal();
                            $('.notify_text').html(response.message);
                        } else {
                            $('.stock_spinner_modal').addClass('d-none');
                            $('.stock_spinner_'+product_id).addClass('d-none');
                            $('.notify_user_div').removeClass('d-none');
                            $('.notify_text').html('Something went wrong!');
                        }
                    },
                    error: function(response) {
                        var error_message = response.responseJSON;
                        $('.stock_spinner_modal').addClass('d-none');
                        $('.stock_spinner_'+product_id).addClass('d-none');
                        $('.notify_user_div').addClass('d-none');
                        var error_text  = error_message.errors.email[0];
                        $('.email_required_alert').html(error_text)
                    },
                    complete: function() {
                        // Re-enable all buttons with class 'notify_stock_btn_class'
                        $('.notify_stock_btn_class').prop('disabled', false);
                    }
                });
            }
        }
        
        function hide_notify_user_div() {
            $('.notify_text').html('');
            $('.notify_user_div').addClass('d-none');
        }
        function updateCart(id, option_id) {
            jQuery.ajax({
                url: "{{ url('/add-to-cart/') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    p_id: jQuery('#p_' + id).val(),
                    option_id: option_id,
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
    
                            var subtotal = parseInt(price * quantity);
                            var cart_total = cart_total + subtotal;
                            var total_cart_quantity = total_cart_quantity + quantity;
                            $('#subtotal_' + product_id).html('$' + subtotal);
                            console.log(item.name);
                            var product_name = document.getElementById("product_name_" + jQuery('#p_' + id)
                                .val()).innerHTML;
                        }
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            title: jQuery('#quantity').val() + ' X ' + product_name +
                                ' added to your cart',
                            timer: 3000,
                            showConfirmButton: false,
                            position: 'top',
                            timerProgressBar: true
                        });
                    }
                    $('#top_cart_quantity').html(total_cart_quantity);
                    $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                    var total = document.getElementById('#top_cart_quantity');
                }
            });
    
            return false;
        }
    
        function addToList(product_id, option_id, status) {
            var list_id = $("input[name='list_id']:checked").val();
            var option_id = option_id;
            $.ajax({
                url: "{{ url('/add-to-wish-list/') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    product_id,
                    option_id,
                    quantity: 1,
                    list_id,
                    status,
                },
    
    
                success: function(success) {
                    if (success.success == true) {
                        $('.fav-' + option_id).toggleClass('text-muted');
                    } else {
                        Swal.fire(
                            'Warning!',
                            'Please make sure you are logged in and selected a company.',
                            'warning',
                        );
                    }
    
                }
            });
            return false;
        }
    </script>
    @include('partials.product-footer')
    @include('partials.footer')
    
</body>
<script>
    $("#superior_brands").on('click', function() {
        window.location.href = '/products/';
    });
</script>

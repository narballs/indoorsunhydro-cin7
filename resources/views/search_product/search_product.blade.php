@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
{{-- <div class="mb-5">
	<p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
		SEARCHED PRODUCTS
	</p>
</div> --}}
<div class="row justify-content-center align-items-center" style="background-color: #008BD3;height:70px;">
	<p class="fw-bold fs-2 my-auto border-0 text-white text-center align-middle">
		SEARCHED PRODUCTS
	</p>
</div>
<?php $count = 0; ?>
@include('search_product.desktop_view')
@include('search_product.mobile_view')
@include('search_product.ipade_view')
@include('partials.recent_products_slider')
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
	function showAllItems() {
      $('#all-items').val('all-items');
      handleSelectChange();
   }
   function inStockOutstock() {
             var value = jQuery('#in-stock').val();
            if (value == 'in-stock') {
               jQuery('#in-stock').addClass('bg-danger');
               jQuery('#in-stock').addClass('out-of-stock');
               $("#in-stock").html("Out of Stock");
               $("#in-stock").prop("value", "out-of-stock");

            }
            else {
               jQuery('#in-stock').removeClass('bg-danger');
               jQuery('#in-stock').removeClass('out-of-stock');
               jQuery('#in-stock').addClass('in-stock');
               $("#in-stock").prop("value", "in-stock");
               $("#in-stock").html("In Stock");
               
            }

             
           
         }

         function handleSelectChange() {

            var selected_category = jQuery('#selected_cat').val();
            var brand = jQuery('#brand').val();
            var per_page = jQuery('#per_page').val();
            var stock = jQuery('#in-stock').val();
            var search_price = jQuery('#search_price').val();
            var category_id = jQuery('#category_id').val();
            var inventory = jQuery('#inventory').val();
            basic_url = `/`;
            // alert(`${selected_category}`);
            if (selected_category != '') {
               basic_url = `?selected_category=${selected_category}`;
            }
            if (brand != '') {
               basic_url = basic_url+`&brand_id=${brand}`;
            }
            // alert(basic_url);
            if (per_page != '') {
               basic_url = basic_url+`&per_page=${per_page}`;
            }
            if (search_price != '') {
               basic_url = basic_url+`&search_price=${search_price}`;
            }
            if (stock != '') {
               basic_url = basic_url+`&stock=${inventory }`;
            }
            window.location.href = basic_url
         }

            function showdetails(id) {
                window.location.href = '/product-detail/'+ id;

            }
       
            function updateCart(id, option_id) {
                var initial_free_shipping_value = parseInt($('.initial_free_shipping_value').val());
                var tax = 0;
                var tax_rate = parseFloat($('#tax_rate_number').val());
                updateBodyClickEventStatus(false);
                $('#last_button_clicked').val(id);

                $('.cart-total-' + id).addClass('added-to-cart');
                $('.button_swap_quantity_' + id).addClass('btn-added-to-cart');

                //$('.quantity_count_circle').css('visibility', 'visible');
                $('.added-to-cart').css('display', 'inline-flex');
                $('.btn-added-to-cart').css('display', 'none');

                $('.cart-total-' + id).css('display', 'none');
                $('.button_swap_quantity_' + id).css('display', 'block');
                
                // $('#ajaxSubmit_'+id).addClass('d-none');
                $('.original_cart_btn_'+id).addClass('d-none');
                // $('#button_swap_'+id).removeClass('d-none');
                $('.button_swap_quantity_'+id).removeClass('d-none');

                $('.swap_qty_number_'+id).val(1);

                $('.quantity_count_circle').each(function() {
                    var html = $(this);
                    var spanContent = $(html).find('span');
                    if (parseInt($(html).find('span').html()) > 0) {
                        spanContent.parent().css('display', 'inline-flex');
                    } else {
                        spanContent.parent().css('display', 'none');
                    }
                });
                jQuery.ajax({
               url: "{{ url('/add-to-cart/') }}",
               method: 'post',
               data: {
                 "_token": "{{ csrf_token() }}",
                  p_id: jQuery('#p_'+id).val(),
                  quantity: 1,
                  option_id: option_id
               },
               success: function(response){
                    if(response.status == 'error'){
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
                            $('#subtotal_' + product_id).html('$'+subtotal);
                            console.log(item.name);
                            var product_name = document.getElementById("product_name_"+jQuery('#p_'+id).val()).innerHTML;
                        }
                        // jQuery('.cart-total-' + id).html($('#swap_qty_number_' + id).val());
                        jQuery('.cart-total-number-' + id).html($('.swap_qty_number_' + id).val());
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
                    if(response.status == 'success'){
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
                            $('#subtotal_' + product_id).html('$'+subtotal);
                            console.log(item.name);
                            var product_name = document.getElementById("product_name_"+jQuery('#p_'+id).val()).innerHTML;
                        }
                        // jQuery('.cart-total-' + id).html($('#swap_qty_number_' + id).val());
                        jQuery('.cart-total-number-' + id).html($('.swap_qty_number_' + id).val());
                        
                        var grand_total = 0;
                        var grand_total = parseFloat(cart_total);
                        var tax = cart_total * (tax_rate / 100);
                        var grand_total_include_tax = 0;
                        grand_total_include_tax = (tax + grand_total).toFixed(2);
                        if (grand_total <= initial_free_shipping_value) {
                            $('.promotional_banner_div_congrats').addClass('d-none');
                            $('.promotional_banner_div').removeClass('d-none');
                            $('.promotional_banner_span').html('$' + (initial_free_shipping_value - grand_total_include_tax).toFixed(2));
                        } else {
                            $('.promotional_banner_div').addClass('d-none');
                            $('.promotional_banner_div_congrats').removeClass('d-none');
                        }
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            title: jQuery('#quantity').val() + ' X ' + product_name + ' added to your cart',
                            timer: 3000,
                            showConfirmButton: false,
                            position: 'top',
                            timerProgressBar: true
                        });
                    }
                    $('#top_cart_quantity').html(total_cart_quantity);
                    $('#cart_items_quantity').html(total_cart_quantity);
                    $('#topbar_cart_total').html('$'+parseFloat(cart_total).toFixed(2));
                    var total = document.getElementById('#top_cart_quantity');
               }});

                return false;
            }
            function swap_quantity_input(id) {
                updateBodyClickEventStatus(false);
                $('.quantity_count_circle').each(function() {
                    var html = $(this);
                    var spanContent = $(html).find('span');
                    if (parseInt($(html).find('span').html()) > 0) {
                        spanContent.parent().css('display', 'inline-flex');
                    } else {
                        spanContent.parent().css('display', 'none');
                    }
                });
                $('.cart-total-'+id).css('display', 'none');
                $('.btn-added-to-cart').css('display', 'none');
                $('.quantity_count_circle').each(function() {
                    var html = $(this);
                    var spanContent = $(html).find('span');
                    if (parseInt($(html).find('span').html()) > 0) {
                        spanContent.parent().css('display', 'inline-flex');
                    } else {
                        spanContent.parent().css('display', 'none');
                    }
                });
                $('.button_swap_quantity_'+id).css('display', 'block');
                $('.cart-total-'+id).css('display', 'none');
            }

         // mobile filter
         function handleSelectChangeMbl(searchedOption = '') {
            var basic_url = '/product/search';
            var selected_category = jQuery('#selected_cat_mbl').val();
            var brand = jQuery('#brand_mbl').val();
            var brand1 = jQuery('#brand_mbl option:selected').text();
            var per_page = jQuery('#per_page_mbl').val();
            var search_price = jQuery('#search_price_mbl').val();
            var childeren = jQuery('#childeren_mbl').val();
            var stock = jQuery('#in_stk').val();
            var category_id = jQuery('#category_id').val();
            var parent_category_slug = jQuery('#parent_category_slug').val();
            var emptyCategory = 0;
            var emptychildCategory = 0;
            
            
            if (selected_category != '') {
                  basic_url = `&selected_category=${selected_category}`;
            }
            else {
                  basic_url = `&selected_category=${emptyCategory}`;
            }

            if (brand != '') {
                  basic_url = basic_url + `&brand_id=${brand}`;
            }

            if (per_page != '') {
                  basic_url = basic_url + `&per_page=${per_page}`;
            }
            if (search_price != '') {
                  basic_url = basic_url + `&search_price=${search_price}`;
            }
            if (stock != '') {
                  basic_url = basic_url + `&stock=${stock }`;

            }
            
            basic_url = "?" + basic_url.slice(1);
            window.location.href = basic_url
      
         }
</script>

<script>
     // stock notification
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
    // end
</script>

@include('partials.product-footer')
<!-- End of .container -->
@include('partials.footer')
<script>
	$(document).ready(function() {
      $('.pagination').addClass('pagination-sm');
   });
</script>


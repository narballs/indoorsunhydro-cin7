
<style>
    #pagination-list > .pagination-link {
    margin: 0 2px;
}
@media screen and (min-width: 768px) {
    .product-detail-pagination-next {
        display: none;
    }

    .product-detail-pagination-previous {
        display: none;
    }
}


.ai-section-heading-title {
    font-size: 24px;
    font-weight: 600;
    font-family: 'Poppins';
    color: #FFFFFF;
    background-color: #008BD3;
    padding: 10px 20px;
    line-height: 43px;
}
.product-detail-new-image-div {
    width: 100%;
    height: 100%;
    border: 1px solid #DFDFDF66;
}

.no-border {
    border: none !important;
}

.product-detail-sku-head-new {
    font-size: 14px;
    font-weight: 500;
    font-family: 'Poppins';
    line-height: 21px;
}

.product-detail-sku-new {
    font-size: 14px;
    font-weight: 400;
    font-family: 'Poppins';
    line-height: 21px;
}

.sku-div {
    background-color: #7CC633;
    color: #fff;
    padding: 5px 10px;
    display: inline-flex;
}

.product-detail-heading-text-new {
    font-size: 28px;
    font-weight: 600;
    font-family: 'Poppins';
}

.stock_number_new {
    font-size: 18px;
    font-weight: 500;
    font-family: 'Poppins';
    color: #37B34A
}

.instock-label-new {
    font-size: 18px;
    font-weight: 400;
    font-family: 'Poppins';
    color: #37B34A;
}

.out-of-stock-label-new {
    font-size: 18px;
    font-weight: 400;
    font-family: 'Poppins';
    color: #DE1919;
}

.product-detail-quantity-increase-decrease-div {
    border: 1px solid #798490;
}

.product-detail-quantity-number-new {
    border: none !important;
    font-size: 20px;
    font-weight: 400;
    font-family: 'Poppins';
}

.product-detail-quantity-number-new:focus {
    box-shadow: 0 0 0 0rem rgba(0, 123, 255, .25)
}

.product-detail-quantity-increase,
.product-detail-quantity-decrease {
    font-size: 14px;
    color: #798490;
    cursor: pointer;
}

.product-detail-quantity-decrease::before {
    border: none !important;
}

.product-detail-quantity-increase::before {
    border: none !important;
}

.product-detail-call-to-order-new,
.product-detail-notify-new {
    background-color: #008BD3;
    color: #fff;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: 600;
    font-family: 'Poppins';
    cursor: pointer;
    text-transform: uppercase;
    border-radius: 0px;
    border: none;
}

.see-similar-order-button-new,
.product-detail-add-to-cart-new {
    background-color: #7CC633;
    color: #fff;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: 600;
    font-family: 'Poppins';
    cursor: pointer;
    text-transform: uppercase;
    border-radius: 0px !important;
}

.see-similar-order-button-new:hover {
    background-color: #7CC633;
    color: #fff;
    /* border: none; */
}

.bulk_discount_href:hover {
    text-decoration: none;
    color: #fff;
    /* border: none; */
}
.ai_row_footer {
    display: flex;
    flex-wrap: wrap; /* Allow wrapping to the next line when items exceed the container width */
    gap: 10px; /* Add spacing between items */
}

.ai_questions {
    padding: 6px;
    background-color: #7cc63e;
    border: 1px solid #7cc63e;
    /* border-radius: 5px; */
    cursor: pointer;
    white-space: nowrap; /* Prevent text from breaking */
}

.ai_questions:hover {
    background-color: #7cc63e;
    border: 1px solid #7cc63e;
}
.circle-right-ai {
    color: #7cc63e;
    font-size: 20px;
    font-family: 'poppins';
    font-weight: 400;
    
}
.ai_content {
    font-family: 'poppins';
    font-weight: 300;
    font-size: 14px;
}

.circle-right-ai:focus {
    border-color: transparent;
}

.ai_text_field {
    font-family: 'poppins';
    font-weight: 400;
    font-size: 14px;
    
}

.ai_spinner {
    color: #7cc63e;
    border: 1px solid #7cc63e;
}

.ai_text_field:focus  {
    border-color: #7cc63e;
    box-shadow: 0 0 0 0rem rgba(124, 198, 62, 0.25);
    
}
.clear_prompt , .clear_prompt:hover , .clear_prompt:focus , .clear_prompt:active {
    font-family: 'poppins';
    font-weight: 400;
    font-size: 14px;
    color: #7cc63e;
    border: 1px solid #7cc63e;
    border-radius: 20px;
    background: #fff;
}
.add_custom_question , .ai_question_strong {
    cursor: pointer;
    font-family: 'poppins';
    font-weight: 400;
    font-size: 12px;
    color: #FFFFFF;
}

.product-weight-heading {
    font-family: 'poppins';
    font-size: 16px;
    font-weight: 700;
    color: #2B3136;
}

.product-weight-data {
    font-family: 'poppins';
    font-size: 16px;
    font-weight: 400;
    color: #2B3136;
}

.product-dimension-heading {
    font-family: 'poppins';
    font-size: 16px;
    font-weight: 700;
    color: #2B3136;
}


.product-dimension-data {
    font-family: 'poppins';
    font-size: 16px;
    font-weight: 400;
    color: #2B3136;
}


@media only screen and (min-width:280px) and (max-width:319px) {
    .product-detail-quantity-number-new {
        font-size: 14px;
    }

    .product-detail-quantity-decrease , .product-detail-quantity-increase {
        font-size: 12px !important;
    }
}


.chat_gpt_search_footer {
    overflow-x: auto;
}



@media only screen and (max-width: 768px) {
    .chat_gpt_search_footer {
        overflow-x: scroll;
    }
    .ai_row {
        background-color: #fff !important;
        
    }
    .ai_row_title {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    .ai_row_card_body {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    .ai_row_footer {
        padding-left: 0rem !important;
        padding-right: 0rem !important;
    }

    .add_custom_question {
        margin-top : 0.5rem !important;
        margin-bottom : 0.5rem !important;
    }

    .product-detail-heading-text-new {
        font-size: 24px;
        margin-top: 1rem;
    }

    .sku-div {
        margin-top: 1rem;
    }

    .product-detail-price {
        font-size: 18px;
    }

    .stock_number_new {
        font-size: 16px;
    }

    .instock-label-new {
        font-size: 16px;
    }

    .out-of-stock-label-new {
        font-size: 16px;
    }

    .product-detail-quantity-number-new {
        font-size: 18px;
    }

    .product-detail-call-to-order-new, .product-detail-notify-new ,  .see-similar-order-button-new, .product-detail-add-to-cart-new {
        font-size: 14px;
    }

    .product-weight-heading {
        font-size: 14px;
        font-weight: 600;
    }

    .product-weight-data {
        font-size: 14px;
        font-weight: 400;
    }

    .product-dimension-heading {
        font-size: 14px;
        font-weight: 600;
    }


    .product-dimension-data {
        font-size: 14px;
        font-weight: 400;
    }

    .ai-section-heading-title {
        font-size: 14px;
        font-weight: 600;
        line-height: 18px;
    }
    
}
.bulk_close_btn {
    border: 1px solid #CDCDCD;
    border-radius: 6px;
    font-size: 18px;
    color: #8F8F8F;
    font-family: 'poppins';
    font-weight: 400;
}

.bulk_close_btn:hover {
    color: #8F8F8F;
}

.submit_bulk_discount:hover {
    background-color: #7CC633;
    border-color: #7CC633;
    color: #fff;
}

.submit_bulk_discount:focus {
    background-color: #7CC633;
    border-color: #7CC633;
    color: #fff;
}

.submit_bulk_discount:active {
    background-color: #7CC633;
    border-color: #7CC633;
    color: #fff;
}

.submit_bulk_discount {
    background-color: #7CC633;
    border-color: #7CC633;
    color: #fff;
}

.product_description,
p,
h5,
h4,
h3,
h2,
h1 {
    font-family: 'Poppins';
    border: none;
    font-style: normal;
}

.product_description,
h5,
h4,
h3,
h2,
h1 {
    font-size: 1rem;
    border: none;
    font-style: normal;
}

.product_description,
em {
    font-style: normal;
}

.product_description,
p {
    margin-bottom: 0rem !important;
}

.bulk_head {
    font-family: 'Poppins';
    font-size: 20px;
    font-weight: 600;
    color: #242424;

}

.bulk_paragraph {
    font-family: 'Poppins';
    font-size: 16px;
    font-weight: 400;
    color: #828282;
    paragraph-spacing: 16.02px;

}

.bulk_label {
    font-family: 'Poppins';
    font-size: 18px !important;
    font-weight: 500;
    color: #242424;
}

.bulk_input {
    font-family: 'Poppins';
    font-size: 18px;
    font-weight: 400;
    color: #828282;

}

.bulk_discount {
    font-family: 'Poppins';
    font-size: 22px;
    font-weight: 500;

}

.bulk_discount_text {
    font-family: 'Poppins';
    font-size: 18px;
    font-weight: 400;
}

.bulk_discount_href {
    font-family: 'Poppins';
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    color: #fff;
    background-color: #F4D130;
    border-radius: 0px;
    padding: 10px 20px;
}

.greyed {
    background: #eaeaea;
}

.update_inventory_number {
    color: #7bc533;
    font-family: 'poppins';
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 21px;
    letter-spacing: 0.55px;
    border: 1px solid #dae0e5;
}

.update_inventory_number:hover {
    color: #7bc533;
}

.buy_again_heading {
    color: #242424;
    font-family: 'Poppins';
    font-size: 20px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}

.product_name {
    color: #000;
    font-family: 'Poppins';
    font-size: 14.669px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}

.product_price {
    color: #DC4E41;
    font-family: 'Poppins';
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
}

.category_name {
    color: #8A8A8A;
    font-family: 'Poppins';
    font-size: 11.002px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.55px;
    text-transform: uppercase;
}

.buy_frequent_again_btn {
    flex-shrink: 0;
    border-radius: 6px;
    background: #7BC533;
    box-shadow: 0px 2.474916458129883px 3.712374687194824px 0px rgba(0, 0, 0, 0.08);
    color: #FFF;
    text-align: center;
    font-family: 'Poppins';
    font-size: 14px;
    font-style: normal;
    font-weight: 500;
    line-height: 21.037px;
    /* 150.263% */
}

.buy_frequent_again_btn_call_to_order {
    flex-shrink: 0;
    border-radius: 6px;
    background-color: #008bd3;
    box-shadow: 0px 2.474916458129883px 3.712374687194824px 0px rgba(0, 0, 0, 0.08);
    color: #FFF;
    text-align: center;
    font-family: 'Poppins';
    font-size: 14px;
    font-style: normal;
    font-weight: 500;
    line-height: 21.037px;
    /* 150.263% */
}

.border-div {
    height: 1.237px;
    background: #E1E1E1;
    width: 90% !important;
}

.buy_again_div {
    border: 1px solid #D7D7D721 !important;
}

.search_row_my_account_page {
    margin-top: 0px;
}

.buy_again_product_image {
    height: 80px;
    width: 80px;
}

.notify_stock_btn_class {
    border-radius: 6px;
}

@media screen and (max-width: 600px) {
    .update_inventory_number {
        color: #7bc533;
        font-family: 'poppins';
        font-size: 12px;
        font-style: normal;
        font-weight: 400;
        line-height: 21px;
        letter-spacing: 0.55px;
        padding: 3px;
    }

    .update_inventory_number:hover {
        color: #7bc533;
    }

    .bulk_discount {
        font-family: 'Poppins';
        font-size: 15px;
        font-weight: 500;

    }

    .bulk_discount_text {
        font-family: 'Poppins';
        font-size: 12px;
        font-weight: 400;
    }

    .bulk_discount_href {
        font-family: 'Poppins';
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        color: #fff;
        background-color: #F4D130;
        border-radius: 0px !important;
    }

    .bulk_head {
        font-family: 'Poppins';
        font-size: 15px;
        font-weight: 600;
        color: #242424;

    }

    .bulk_paragraph {
        font-family: 'Poppins';
        font-size: 12px;
        font-weight: 400;
        color: #828282;
        paragraph-spacing: 16.02px;

    }

    .bulk_label {
        font-family: 'Poppins';
        font-size: 12px !important;
        font-weight: 500;
        color: #242424;
    }

    .bulk_input {
        font-family: 'Poppins';
        font-size: 12px;
        font-weight: 400;
        color: #828282;

    }

}

.my-actions-class {
    margin: 0.2rem !important;
}
</style>
<script>
    // mark border green on focus
    function mark_arrow_border_green() {
        $('.input-group-text').css('border-color', '#7cc63e');
    }
    // stock notification for similar products
    function show_notify_popup_modal_similar_portion (id , sku_value) {
        $('.notify_popup_modal_similar_portion').modal('show');
        $('.similar_productId_value').val(id);
        $('.similar_productSku_value').val(sku_value);
    } 
    function close_notify_user_modal_similar () {
        $('.notify_popup_modal_similar_portion').modal('hide');
        $('.notify_stock_btn_class').each(function() {
            $(this).attr('disabled', false);
        });
    }
    
    function  notify_user_about_product_stock_similar_portion  (id , sku_value) {
        $('.notify_stock_btn_class').each(function() {
            var p_id = $(this).attr('data-product-id');
            if (p_id != id) {
                $(this).attr('disabled', true);
            }
        });
        var email = $('.similar_notifyEmail_sidebar').val();
        var sku = sku_value;
        var product_id = id;
        $('.stock_spinner_modal_similar').removeClass('d-none');
        $('.stock_spinner_'+product_id).removeClass('d-none');
        if (email != '') {
            $('.email_required_alert_similar').html('');
        }
        if (email == '') {
            $('.email_required_alert_similar').html('Email is Required');
            $('.stock_spinner_modal_similar').addClass('d-none');
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
                        $('.stock_spinner_modal_similar').addClass('d-none');
                        $('.stock_spinner_'+product_id).addClass('d-none');
                        $('.notify_user_div_detail').removeClass('d-none');
                        close_notify_user_modal_similar();
                        $('.notify_text_detail').html(response.message);
                    } else {
                        $('.stock_spinner_modal_similar').addClass('d-none');
                        $('.stock_spinner_'+product_id).addClass('d-none');
                        $('.notify_user_div_detail').removeClass('d-none');
                        $('.notify_text_detail').html('Something went wrong!');
                    }
                },
                error: function(response) {
                    var error_message = response.responseJSON;
                    $('.stock_spinner_modal_similar').addClass('d-none');
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
        $('.notify_text_detail').html('');
        $('.notify_user_div_detail').addClass('d-none');
    }


    document.getElementById('ai_text_field').addEventListener('focusout', function() {
        $('.circle-right-ai').css('border-color', '#ced4da');
    });


    jQuery(document).on('click', '#ajaxSubmit' , function(e) {
        var initial_free_shipping_value = parseInt($('.initial_free_shipping_value').val());
        var tax = 0;
        var tax_rate = parseFloat($('#tax_rate_number').val());
        $.ajaxSetup({
        });
        jQuery.ajax({
            url: "{{ url('add-to-cart') }}",
            method: 'post',
            data: {
            "_token": "{{ csrf_token() }}",
            p_id: jQuery('#p_id').val(),
            option_id: jQuery('#option_id').val(),
            quantity: jQuery('#quantity').val()
            },
            success: function(response){
            var cart_total = 0;
            var total_cart_quantity = 0;
            
            if(response.status == 'error'){
                var cart_items = response.cart_items;

                for (var key in cart_items) {
                    var item = cart_items[key];
                    var code =item.code;
                    var product_id = item.prd_id;
                    var price = parseFloat(item.price);
                    var quantity = parseInt(item.quantity);
                    var subtotal = parseFloat(price * quantity);
                    var cart_total = cart_total + subtotal;
                    var total_cart_quantity = total_cart_quantity + quantity;
                    $('#subtotal_' + product_id).html('$'+subtotal);
                    
                }
                $src = $('#main-image').attr('src');
                var product_name = document.getElementById("product_name").innerHTML;
                var product_price = document.getElementById("product_price").innerHTML;
                
                Swal.fire({
                    toast: false,
                    icon: 'error',
                    title: response.message,
                    position: 'center',
                    showConfirmButton: true,  // Show the confirm (OK) button
                    confirmButtonText: 'Confirm',
                    timerProgressBar: false,
                    allowOutsideClick: false, // Disable clicking outside to close the modal
                    allowEscapeKey: false, // Disable Esc key to close the modal
                    customClass: {
                        confirmButton: 'my-confirm-button',  // Class for the confirm button
                        popup: 'swal2-popup-class',  // Class for the actions container
                        actions: 'my-actions-class'  // Class for the actions container
                    }
                });
            }
            if(response.status == 'success'){
                var cart_items = response.cart_items;

                for (var key in cart_items) {
                    var item = cart_items[key];
                    var code =item.code;
                    var product_id = item.prd_id;
                    var price = parseFloat(item.price);
                    var quantity = parseInt(item.quantity);
                    var subtotal = parseFloat(price * quantity);
                    var cart_total = cart_total + subtotal;
                    var total_cart_quantity = total_cart_quantity + quantity;
                    $('#subtotal_' + product_id).html('$'+subtotal);
                    
                }
                $src = $('#main-image').attr('src');
                var product_name = document.getElementById("product_name").innerHTML;
                var product_price = document.getElementById("product_price").innerHTML;
                var productName = $('#product-detail-id').attr('data-title');
                var grand_total = 0;
                var grand_total = parseFloat(cart_total);
                var tax = cart_total * (tax_rate / 100);
                var grand_total_include_tax = 0;
                grand_total_include_tax = (tax + grand_total).toFixed(2);
                if (response.free_postal_state == true) {
                    if (grand_total <= initial_free_shipping_value) {
                        $('.promotional_banner_div_congrats').addClass('d-none');
                        $('.promotional_banner_div').removeClass('d-none');
                        $('.promotional_banner_span').html('$' + (initial_free_shipping_value - grand_total_include_tax).toFixed(2));
                    } else {
                        $('.promotional_banner_div').addClass('d-none');
                        $('.promotional_banner_div_congrats').removeClass('d-none');
                    }
                }
                else {
                    $('.promotional_banner_div').addClass('d-none');
                    $('.promotional_banner_div_congrats').addClass('d-none');
                } 
                Swal.fire({
                    toast: false,
                    icon: 'success',
                    title: jQuery('#quantity').val() + 'X ' + '<span class="text-dark toast_title">'+ productName+'</span>' + '<br/>'+ '<div class="added_tocart">Added to your cart</div>',
                    // customClass: {popup: 'short-toast-popup'}
                    position: 'center',
                    showConfirmButton: true,  // Show the confirm (OK) button
                    confirmButtonText: 'Confirm',
                    timerProgressBar: false,
                    allowOutsideClick: false, // Disable clicking outside to close the modal
                    allowEscapeKey: false, // Disable Esc key to close the modal
                    customClass: {
                        confirmButton: 'my-confirm-button',  // Class for the confirm button
                        popup: 'swal2-popup-class',  // Class for the actions container
                        actions: 'my-actions-class'  // Class for the actions container
                    }
                });
            }
            $('#top_cart_quantity').html(total_cart_quantity);
            $('#cart_items_quantity').html(total_cart_quantity);
            $('.cartQtyipad').html(total_cart_quantity);
            $('.cartQtymbl').html(total_cart_quantity);
            $('#topbar_cart_total').html('$'+parseFloat(cart_total).toFixed(2));
            var total = document.getElementById('#top_cart_quantity');
                
        }, 
        error: function(response) {
            console.log(response.responseJSON);
            var error_message = response.responseJSON;
                Swal.fire({
                    toast: false,
                    icon: 'error',
                    title: error_message.message,
                    position: 'center',
                    showConfirmButton: true,  // Show the confirm (OK) button
                    confirmButtonText: 'Confirm',
                    timerProgressBar: false,
                    allowOutsideClick: false, // Disable clicking outside to close the modal
                    allowEscapeKey: false, // Disable Esc key to close the modal
                    customClass: {
                        confirmButton: 'my-confirm-button',  // Class for the confirm button
                        popup: 'swal2-popup-class',  // Class for the actions container
                        actions: 'my-actions-class'  // Class for the actions container
                    }
                });
            }
        });
    });




    jQuery('.product-detail-quantity-increase-decrease-div').on('click', '.product-detail-quantity-increase, .product-detail-quantity-decrease', function () {
        var spinner = jQuery(this).closest('.product-detail-quantity-increase-decrease-div'),
            desktop_input = spinner.find('input[type="number"]'),
            btnUp = spinner.find('.product-detail-quantity-increase'),
            btnDown = spinner.find('.product-detail-quantity-decrease'),
            min = parseInt(desktop_input.attr('min')),
            max = parseInt(desktop_input.attr('max')),
            stock_number_new = parseInt(jQuery(".stock_number_new").html());

        var oldValue = parseInt(desktop_input.val());
        var newVal = oldValue;
        let wholesaleTerms = ($('#get_wholesale_terms').val() || "").trim().toLowerCase();

        if (jQuery(this).hasClass('product-detail-quantity-increase') && oldValue < max &&  wholesaleTerms === 'pay in advanced') {
            newVal = oldValue + 1;
        } 
        else if (jQuery(this).hasClass('product-detail-quantity-increase') && wholesaleTerms !== 'pay in advanced') {
            newVal = oldValue + 1;
        } 
        else { 
            if (jQuery(this).hasClass('product-detail-quantity-decrease') && oldValue > min) {
                newVal = oldValue - 1;
            }
        }

        desktop_input.val(newVal).trigger("change");
        // Toggle button state based on new value
        toggleButtonState(newVal);

        // Show error if quantity exceeds max
        if (newVal > max && wholesaleTerms === 'pay in advanced') {
            showErrorToast();
        }
    });

    // Handle manual input changes
    jQuery('.product-detail-quantity-increase-decrease-div input[type="number"]').change(function () {
        let wholesaleTerms = ($('#get_wholesale_terms').val() || "").trim().toLowerCase();

        var desktop_input = jQuery(this),
            input_qty = parseInt(desktop_input.val()),
            max = parseInt(desktop_input.attr('max'));

        if (input_qty > max && wholesaleTerms === 'pay in advanced') {
            desktop_input.val(max);
            showErrorToast();
        }
    });

    // Toggle button state based on quantity value
    function toggleButtonState(newVal) {
        var spinner = jQuery('.product-detail-quantity-increase-decrease-div');
        var btnUp = spinner.find('.product-detail-quantity-increase');
        var btnDown = spinner.find('.product-detail-quantity-decrease');
        var min = parseInt(spinner.find('input[type="number"]').attr('min'));
        var max = parseInt(spinner.find('input[type="number"]').attr('max'));

        // if (newVal === max) {
        //     btnUp.addClass('greyed');
        //     btnDown.removeClass('greyed');
        // } else if (newVal === min) {
        //     btnDown.addClass('greyed');
        //     btnUp.removeClass('greyed');
        // } else {
        //     btnUp.removeClass('greyed');
        //     btnDown.removeClass('greyed');
        // }
    }

    // Show error toast
    function showErrorToast() {
        Swal.fire({
            toast: false,
            icon: 'error',
            title: 'Quantity must be less than or equal to stock quantity',
            position: 'center',
            showConfirmButton: true,  // Show the confirm (OK) button
            confirmButtonText: 'Confirm',
            timerProgressBar: false,
            allowOutsideClick: false, // Disable clicking outside to close the modal
            allowEscapeKey: false, // Disable Esc key to close the modal
            customClass: {
                confirmButton: 'my-confirm-button',  // Class for the confirm button
                popup: 'swal2-popup-class',  // Class for the actions container
                actions: 'my-actions-class'  // Class for the actions container
            }
        });
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

    function similar_product_add_to_cart(id, option_id) {
        var initial_free_shipping_value = parseInt($('.initial_free_shipping_value').val());
        var tax = 0;
        var tax_rate = parseFloat($('#tax_rate_number').val());
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
                if (response.status == 'error') {
                    var cart_items = response.cart_items;
                    var cart_total = 0;
                    var total_cart_quantity = 0;

                    for (var key in cart_items) {
                        var item = cart_items[key];

                        var product_id = item.prd_id;
                        var price = parseFloat(item.price);
                        var quantity = parseInt(item.quantity);

                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        jQuery('#subtotal_' + product_id).html('$' + subtotal);
                        var product_name = jQuery('#prd_name_' + id).html();
                    }

                    Swal.fire({
                        toast: false,
                        icon: 'error',
                        title: response.message,
                        position: 'center',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Confirm',
                        timerProgressBar: false,
                        allowOutsideClick: false, // Disable clicking outside to close the modal
                        allowEscapeKey: false, // Disable Esc key to close the modal
                        customClass: {
                            confirmButton: 'my-confirm-button',  // Class for the confirm button
                            popup: 'swal2-popup-class',  // Class for the actions container
                            actions: 'my-actions-class'  // Class for the actions container
                        }
                    });
                }
                if (response.status == 'success') {
                    var cart_items = response.cart_items;
                    var cart_total = 0;
                    var total_cart_quantity = 0;

                    for (var key in cart_items) {
                        var item = cart_items[key];

                        var product_id = item.prd_id;
                        var price = parseFloat(item.price);
                        var quantity = parseInt(item.quantity);

                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        jQuery('#subtotal_' + product_id).html('$' + subtotal);
                        var product_name = jQuery('#prd_name_' + id).html();
                    }
                    var productName = jQuery('#prd_name_' + id).attr('data-title');
                    var grand_total = 0;
                    var grand_total = parseFloat(cart_total);
                    var tax = cart_total * (tax_rate / 100);
                    var grand_total_include_tax = 0;
                    grand_total_include_tax = (tax + grand_total).toFixed(2);
                    if (response.free_postal_state == true) {
                        if (grand_total <= initial_free_shipping_value) {
                            $('.promotional_banner_div_congrats').addClass('d-none');
                            $('.promotional_banner_div').removeClass('d-none');
                            $('.promotional_banner_span').html('$' + (initial_free_shipping_value - grand_total_include_tax).toFixed(2));
                        } else {
                            $('.promotional_banner_div').addClass('d-none');
                            $('.promotional_banner_div_congrats').removeClass('d-none');
                        }
                    }
                    else {
                        $('.promotional_banner_div').addClass('d-none');
                        $('.promotional_banner_div_congrats').addClass('d-none');
                    } 

                    Swal.fire({
                        toast: false,
                        icon: 'success',
                        title: 1 + 'X ' + '<span class="text-dark toast_title">'+ productName+'</span>' + '<br/>'+ '<div class="added_tocart">Added to your cart</div>',
                        // customClass: {popup: 'short-toast-popup'},
                        position: 'center',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Confirm',
                        timerProgressBar: false,
                        allowOutsideClick: false, // Disable clicking outside to close the modal
                        allowEscapeKey: false, // Disable Esc key to close the modal
                        customClass: {
                            confirmButton: 'my-confirm-button',  // Class for the confirm button
                            popup: 'swal2-popup-class',  // Class for the actions container
                            actions: 'my-actions-class'  // Class for the actions container
                        }
                    });
                }
                $('#top_cart_quantity').html(total_cart_quantity);

                $('#cart_items_quantity').html(total_cart_quantity);
                $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                var total = document.getElementById('#top_cart_quantity');
            }
        });
    }
    
    function saveBulkQuantityDiscount() {
        var product_name_bulk = $('#bulk_product_list').val();
        var quantity_bulk  =  $('#bulk_quantity').val();
        var phone_number_bulk =  $('#bulk_phone_number').val();
        var email_bulk =  $('#bulk_email').val();
        var username_bulk =  $('#bulk_name').val();
        var delievery = $('#bulk_delievery').val();

        if (product_name_bulk == '' || quantity_bulk == '' || phone_number_bulk == '' || email_bulk == '' || username_bulk == '') {
            Swal.fire({
                html: '<i class="fa-solid fa-circle-xmark" style="color:#d33;font-size:40px;margin-bottom:10px;"></i><br><span style="font-size:16px;font-family:poppins">Please fill all Required fields</span>',
                customClass: {
                    popup: 'my-popup-class',
                    actions: 'my-actions-class'
                },
                confirmButtonText: 'Try Again',
                confirmButtonColor: '#d33'
            });
            return;
        }

        $('.bulk_loader').removeClass('d-none');
        _token: '{{ csrf_token() }}'

        $.ajax({
            type: "POST",
            url: "{{ route('bulk_products_request') }}",
            data: {
                items_list: product_name_bulk,
                quantity: quantity_bulk,
                phone_number: phone_number_bulk,
                email: email_bulk,
                name: username_bulk,
                delievery: delievery,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#bulk_quantity_modal').modal('hide');
                $('.bulk_loader').addClass('d-none');
                Swal.fire({
                    html: '<i class="fa-solid fa-circle-check" style="color:#7CC633;font-size:40px;margin-bottom:10px;"></i><br><strong style="font-family:poppins">Bulk order request has been submitted.</strong><br/><span style="font-size:16px;font-family:poppins">We will get in touch with you as soon as we can.</span><br/><span style="font-size:16px;font-family:poppins">Thank you for your patience</span>',
                    customClass: {
                        popup: 'my-popup-class',
                        actions: 'my-actions-class'
                    },
                    confirmButtonText: 'Continue Shopping',
                    confirmButtonColor: '#7CC633'
                });
                $('.inventory_pop_over_form').removeClass('d-none');
                // Clear input fields
                // $('#bulk_product_list').val('');
                // $('#bulk_product_list').tagsinput('removeAll');
                $('#bulk_quantity').val('');
                // $('#bulk_phone_number').val('');
                // $('#bulk_email').val('');
                // $('#bulk_name').val('');
                $('#bulk_delievery').val('');
            },
            error: function(xhr, status, error) {
                $('.bulk_loader').addClass('d-none');
                Swal.fire({
                    html: '<i class="fa-solid fa-circle-xmark" style="color:#d33;font-size:40px;margin-bottom:10px;"></i><br><span style="font-size:16px;font-family:poppins">There was an error submitting the form</span>.<br/> <span style="font-size:16px;font-family:poppins"> Please try again later.</span>',
                    customClass: {
                        popup: 'my-popup-class',
                        actions: 'my-actions-class'
                    },
                    confirmButtonText: 'Try Again',
                    confirmButtonColor: '#d33'
                });
            }
        });
    }


    $(document).ready(function() {
        $('#bulk_discount_href').click(function(e) {
            e.preventDefault();
            $('.inventory_pop_over_form').addClass('d-none');
        });

        $('#close_bulk_model').click(function(e) {
            e.preventDefault();
            $('.inventory_pop_over_form').removeClass('d-none');
        });

        

        const p_id = document.getElementById('p_id').value || '';
        const option_id = document.getElementById('option_id').value || '';
        const slug = document.getElementById('product_slug').value || '';

        console.log(p_id, option_id, slug);

        const auth_user = $('.notifyEmail').val() || null;

        let itemsPerPage = window.innerWidth <= 767 ? 1 : 4;
        let currentPage = 1; // Track the current page

        // Load initial similar products
        loadSimilarProducts(currentPage);

        // Handle window resize event to reload products
        $(window).resize(function () {
            itemsPerPage = window.innerWidth <= 767 ? 1 : 4;
            loadSimilarProducts(1); // Reload with page 1 on resize
            updatePaginationForSmallScreens();
        });

        // Function to load similar products
        function loadSimilarProducts(page) {
            currentPage = page; // Update current page
            $.ajax({
                url: `/products/${p_id}/${option_id}/${slug}/get-similar-products?page=${page}&perPage=${itemsPerPage}`,
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.data && response.data.length > 0) {
                        const html = buildSimilarProductsHtml(response);
                        $('#products-container').html(html);
                        updateSimilarProductsPaginationLinks(response);
                    } else {
                        $('#products-container').html(`
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="buy_again_heading">No similar products found</p>
                                </div>
                            </div>
                        `);
                    }
                },
                error: function () {
                    console.error('Failed to load similar products.');
                },
            });
        }

        // Update pagination links and handle click events
        function updateSimilarProductsPaginationLinks(response) {
            $(document).off('click', '.pagination-link'); // Prevent duplicate bindings
            $(document).on('click', '.pagination-link', function (e) {
                e.preventDefault();
                const page = $(this).data('page');
                if (page && page !== currentPage) {
                    loadSimilarProducts(page);
                }
            });

            if (window.innerWidth <= 767) {
                updatePaginationForSmallScreens();
            }
        }

        // Update pagination for small screens
        function updatePaginationForSmallScreens() {
            const links = $('#pagination-list a');
            const totalLinks = links.length;

            if (totalLinks > 2) {
                links.slice(1, totalLinks - 1).addClass('d-none'); // Hide middle pages
            } else {
                links.removeClass('d-none');
            }
        }

        // Build the HTML structure for similar products
        function buildSimilarProductsHtml(response) {
            console.log(response);
            let html = `
                <div class="row">
                    <div class="col-md-12">
                        <p class="buy_again_heading">Similar Products</p>
                    </div>
                </div>
            `;

            var get_wholesale_terms = response.get_wholesale_terms || null;

            response.data.forEach(product => {
                if (product.options && product.options.length > 0) {
                    product.options.forEach(option => {
                        html += buildProductRow(product, option , get_wholesale_terms);
                    });
                } 
                // else {
                //     html += buildProductRow(product, null);
                // }
            });

            const paginationHtml = generatePaginationLinks(response.last_page, response.current_page);
            html += `<ul id="pagination-list" class="pagination">${paginationHtml}</ul>`;
            return html;
        }

        // Build individual product row
        function buildProductRow(productData, option , get_wholesale_terms) {
            return `
                <div class="row mt-4 mb-3">
                    <div class="col-md-12 py-3" style="border: 1px solid #DFDFDF59;">
                        <div class="row">
                            ${buildImageColumn(productData.images)}
                            ${buildDataColumn(productData, option , get_wholesale_terms)}
                        </div>
                        ${buildButtonRow(productData, option ,get_wholesale_terms)}
                    </div>
                </div>
            `;
        }

        // Build data column
        function buildDataColumn(productData, option , get_wholesale_terms) {
            const column = $('#get_column').val() || 'default';
            const products_to_hide = JSON.parse($('#products_to_hide').val() || '[]');
            const paymentTerms = $('#paymentTerms').val() == '' || $('#paymentTerms').val() == null || $('#paymentTerms').val() == 0 || $('#paymentTerms').val() === '0' ? false : true;
            const auth_value = $('#auth_value').val() == '' || $('#auth_value').val() == null || $('#auth_value').val() == 0 || $('#auth_value').val() === '0' ? false : true;
            let show_price = true;

            let dataHtml = `
                <div class="col-md-8 col-lg-8 col-xl-7 data-div data-div-account">
                    <div class="row">
                        <div class="col-md-10">
                            <p class="product_name mb-1">
                                <a class="product_name" id="prd_name_${productData.id}" data-title="${productData.name}" href="/product-detail/${productData.id}/${option?.option_id || ''}/${productData.slug}">
                                    ${productData.name}
                                </a>
                            </p>
                        </div>
            `;

            if (option) {
                let getWholesaleTerms = (get_wholesale_terms || "").trim().toLowerCase();
                var stock_label = option.stockAvailable;

                if (stock_label > 0) {
                    stock_label = 'In Stock';
                } else {
                    if (auth_value) {
                        if (getWholesaleTerms != '' &&  getWholesaleTerms === 'pay in advanced') {
                            stock_label = 'Out of Stock';
                        } else {
                            stock_label = 'On back order';
                        }
                    } else {
                        stock_label = 'Out of Stock';
                    }
                } 

                const text_class = option.stockAvailable > 0 ? 'text-success' : 'text-danger';
                // const retail_price = (option?.default_price?.[column]) ?? (option?.default_price?.retailUSD) ?? 0;
                const retail_price = 
                (option?.default_price?.[column] != null && option?.default_price?.[column] > 0) ? option.default_price[column] :
                (option?.default_price?.sacramentoUSD != null && option?.default_price?.sacramentoUSD > 0) ? option.default_price.sacramentoUSD :
                (option?.default_price?.retailUSD != null && option?.default_price?.retailUSD > 0) ? option.default_price.retailUSD :
                0;


                // Additional condition for add_to_cart based on product hiding and authorization/payment terms
                if (products_to_hide.includes(option.option_id)) {
                    if (auth_value == '' || auth_value == null || auth_value == false) {
                        show_price = false; // Cannot add to cart if user is not authorized
                    } else {
                        if (paymentTerms == '' || paymentTerms == null || paymentTerms == false) {
                            show_price = false; // Cannot add to cart if payment terms are not met
                        } else {
                            show_price = true; // Can add to cart if both conditions are met
                        }
                    }
                }

                dataHtml += `
                    <div class="col-md-10">
                        <p class="${text_class} mb-0">${stock_label}</p>
                    </div>
                    ${show_price ? `
                    <div class="col-md-10">
                        <p class="product_price mb-1">$${retail_price.toFixed(2)}</p>
                    </div>` : ''}
                `;
            }

            dataHtml += `
                    </div>
                </div>
            `;
            return dataHtml;
        }

        function buildButtonRow(productData, option, get_wholesale_terms) {
            const products_to_hide = JSON.parse($('#products_to_hide').val() || '[]');
            let add_to_cart = true;
            const paymentTerms = $('#paymentTerms').val() == '' || $('#paymentTerms').val() == null || $('#paymentTerms').val() == 0 || $('#paymentTerms').val() === '0' ? false : true;
            const auth_value = $('#auth_value').val() == '' || $('#auth_value').val() == null || $('#auth_value').val() == 0 || $('#auth_value').val() === '0' ? false : true;

            // Additional condition for add_to_cart based on product hiding and authorization/payment terms
            if (products_to_hide.includes(option.option_id)) {
                if (auth_value == '' || auth_value == null || auth_value == false) {
                    add_to_cart = false; // Cannot add to cart if user is not authorized
                } else {
                    if (paymentTerms == '' || paymentTerms == null || paymentTerms == false) {
                        add_to_cart = false; // Cannot add to cart if payment terms are not met
                    } else {
                        add_to_cart = true; // Can add to cart if both conditions are met
                    }
                }
            }

            // Return the appropriate button HTML based on conditions
            // return `
            //     <div class="row justify-content-center mt-4">
            //         <!-- Check if Add to Cart should be shown -->
            //         ${add_to_cart ? 
            //             (option?.stockAvailable > 0 ? `
            //                 <div class="col-md-10">
            //                     <button type="button" class="buy_frequent_again_btn border-0 w-100 p-2" 
            //                         onclick="similar_product_add_to_cart('${productData.id}', '${option.option_id}')">
            //                         Add to Cart
            //                     </button>
            //                 </div>` 
            //                 : `
            //                 <!-- If stock is 0, show Notify button based on login status -->
            //                 <div class="col-md-10">
            //                     ${auth_value ? `
            //                         <button type="button" id="notify_popup_modal_${option.option_id}" 
            //                             data-product-id="${productData.id}" 
            //                             onclick="notify_user_about_product_stock_similar_portion('${productData.id}', '${productData.code}')" 
            //                             class="w-100 ml-0 bg-primary h-auto product-detail-button-cards notify_stock_btn_class text-uppercase notify_popup_modal_btn rounded d-flex align-items-center justify-content-center">
            //                             <a class="text-white">Notify</a>
            //                             <div class="spinner-border text-white custom_stock_spinner stock_spinner_${productData.id} ml-1 d-none" role="status">
            //                                 <span class="sr-only"></span>
            //                             </div>
            //                         </button>`
            //                         : `
            //                         <button type="button" id="notify_popup_modal_${option.option_id}" 
            //                             onclick="show_notify_popup_modal_similar_portion('${productData.id}', '${productData.code}')" 
            //                             class="w-100 ml-0 bg-primary h-auto product-detail-button-cards notify_stock_btn_class text-uppercase notify_popup_modal_btn rounded">
            //                             <a class="text-white">Notify</a>
            //                         </button>`}
            //                 </div>`)
            //         : `
            //             <!-- If Add to Cart is false, show Call to Order button -->
            //             <div class="col-md-10">
            //                 <button type="button" class="buy_frequent_again_btn_call_to_order border-0 w-100 p-2">
            //                     Call To Order
            //                 </button>
            //             </div>`}
            //     </div>
            // `;

            let lowerCaseTerms = (get_wholesale_terms || "").trim().toLowerCase();
            let addToCartButton = `
                <div class="col-md-10">
                    <button type="button" class="buy_frequent_again_btn border-0 w-100 p-2" 
                        onclick="similar_product_add_to_cart('${productData.id}', '${option.option_id}')">
                        Add to Cart
                    </button>
                </div>
            `;

            let notifyButton = `
                <div class="col-md-10">
                    ${auth_value ? `
                        <button type="button" id="notify_popup_modal_${option.option_id}" 
                            data-product-id="${productData.id}" 
                            onclick="notify_user_about_product_stock_similar_portion('${productData.id}', '${productData.code}')" 
                            class="w-100 ml-0 bg-primary h-auto product-detail-button-cards notify_stock_btn_class text-uppercase notify_popup_modal_btn rounded d-flex align-items-center justify-content-center">
                            <a class="text-white">Notify</a>
                            <div class="spinner-border text-white custom_stock_spinner stock_spinner_${productData.id} ml-1 d-none" role="status">
                                <span class="sr-only"></span>
                            </div>
                        </button>`
                        : `
                        <button type="button" id="notify_popup_modal_${option.option_id}" 
                            onclick="show_notify_popup_modal_similar_portion('${productData.id}', '${productData.code}')" 
                            class="w-100 ml-0 bg-primary h-auto product-detail-button-cards notify_stock_btn_class text-uppercase notify_popup_modal_btn rounded">
                            <a class="text-white">Notify</a>
                        </button>`}
                </div>
            `;

            let callToOrderButton = `
                <div class="col-md-10">
                    <button type="button" class="buy_frequent_again_btn_call_to_order border-0 w-100 p-2">
                        Call To Order
                    </button>
                </div>
            `;

            // Determine which button to show
            let buttonHtml = '';

            if (add_to_cart) {
                if (auth_value) {
                    if ((lowerCaseTerms === "" || lowerCaseTerms !== "pay in advanced")) { 
                        buttonHtml = addToCartButton;
                    } else {
                        buttonHtml = option?.stockAvailable > 0 ? addToCartButton : notifyButton;
                    }
                } else {
                    buttonHtml = option?.stockAvailable > 0 ? addToCartButton : notifyButton;
                }
            } else {
                buttonHtml = callToOrderButton;
            }

            // Final Template String
            return `
                <div class="row justify-content-center mt-4">
                    ${buttonHtml}
                </div>
            `;
        }




        // Build image column
        function buildImageColumn(imageUrl) {
            const img = imageUrl || '/theme/img/image_not_available.png';
            return `
                <div class="col-md-4 col-lg-4 col-xl-5 image-div image-div-account">
                    <img src="${img}" alt="Product Image" class="img-fluid">
                </div>
            `;
        }

        // Generate pagination links
        function generatePaginationLinks(totalPages, currentPage) {
            let paginationLinks = '';
            if (currentPage > 1) {
                paginationLinks += `<a href="javascript:void(0)" class="product-detail-pagination-previous pagination-link" data-page="${currentPage - 1}">Previous</a>`;
            }
            for (let i = 1; i <= totalPages; i++) {
                paginationLinks += `<a href="javascript:void(0)" class="pagination-link ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</a>`;
            }
            if (currentPage < totalPages) {
                paginationLinks += `<a href="javascript:void(0)" class="product-detail-pagination-next pagination-link" data-page="${currentPage + 1}">Next</a>`;
            }
            return paginationLinks;
        }
    });

    function show_notify_popup_modal () {
        $('.notify_popup_modal_detail').modal('show');
    } 
    function close_notify_user_modal () {
        $('.notify_popup_modal_detail').modal('hide');
    }
    
    function notify_user_about_product_stock () {
        var email = $('.notify_user_email_input').val();
        var sku = $('.sku_value').val();
        var product_id = $('.product_id_value').val();
        $('.stock_spinner_modal').removeClass('d-none');
        $('.stock_spinner').removeClass('d-none');
        if (email != '') {
            $('.email_required_alert_detail').html('');
        }
        if (email == '') {
            $('.email_required_alert_detail').html('Email is Required');
            $('.stock_spinner_modal').addClass('d-none');
            $('.stock_spinner').addClass('d-none');
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
                        $('.stock_spinner').addClass('d-none');
                        $('.notify_user_div_detail').removeClass('d-none');
                        close_notify_user_modal();
                        $('.notify_text_detail').html(response.message);
                    } else {
                        $('.stock_spinner_modal').addClass('d-none');
                        $('.stock_spinner').addClass('d-none');
                        $('.notify_user_div_detail').removeClass('d-none');
                        $('.notify_text_detail').html('Something went wrong!');
                    }
                },
                error: function(response) {
                    var error_message = response.responseJSON;
                    $('.stock_spinner_modal').addClass('d-none');
                    $('.stock_spinner').addClass('d-none');
                    $('.notify_user_div_detail').addClass('d-none');
                    var error_text  = error_message.errors.email[0];
                    $('.email_required_alert_detail').html(error_text)
                }
            });
        }
    }
    
    function hide_notify_user_div() {
        $('.notify_text_detail').html('');
        $('.notify_user_div_detail').addClass('d-none');
    }

    function get_latest_inventory_number() {
        var url = window.location.href;
        url += '?latest_inventory_number=1'; // You can dynamically set the inventory number here
        window.location = url;
    }


    $('.circle-right-ai').click(function() {
        $('.ai_spinner').removeClass('d-none');
        var question  = $('.ai_text_field').val();
        var product_name_detail_page = $('.product_name_detail_page').attr('data-title');
        if (question == '') {
            $('.ai_spinner').addClass('d-none');
            // $('.ai_text_field').addClass('border-danger');
            $('.ai_error').html('Please enter a question');
            return false;
        } 
        else {
            $('.ai_text_field').prop('readonly', true);
            calling_ai_prompt(question , product_name_detail_page);
        }
    });

    function clear_prompt() {
        $('.ai_text_field').val('');
        $('.ai_content').text('');
        $('.ai_text_field').prop('readonly', false);
        $('.clear_prompt').addClass('d-none');
    }

    function add_custom_question(element) {
        $('.ai_spinner').removeClass('d-none');
        var question = $(element).find('.ai_question_strong').html();
        var product_name_detail_page = $('.product_name_detail_page').attr('data-title');
        if (question == '') {
            $('.ai_spinner').addClass('d-none');
            $('.ai_error').html('Please enter a question');
            return false;
        } 
        else {
            $('.ai_text_field').val(question);
            $('.ai_text_field').prop('readonly', true);
            calling_ai_prompt(question , product_name_detail_page);
        }
    }

    $('#ai_text_field').keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault(); // Prevent the form from submitting
            $('.ai_spinner').removeClass('d-none');
            var question  = $('.ai_text_field').val();
            var product_name_detail_page = $('.product_name_detail_page').attr('data-title');
            if (question == '') {
                $('.ai_spinner').addClass('d-none');
                $('.ai_error').html('Please enter a question');
                return false;
            } 
            else {
                $('.ai_text_field').prop('readonly', true);
                calling_ai_prompt(question , product_name_detail_page);
            }
        }
    });

    function calling_ai_prompt(question , product_name_detail_page) {
        $.ajax({
            url: "{{ url('ai-answer') }}",
            method: 'get',
            data: {
            "_token": "{{ csrf_token() }}",
                question : question,
                product_name : product_name_detail_page
            },
            success: function(response){

                if (response.status === 'success') {
                    $('.ai_spinner').addClass('d-none');
                    $('.ai_error').html('');
                    $('.ai_content').html(response.message);
                    $('.ai_text_field').prop('readonly', false);
                    $('.clear_prompt').removeClass('d-none');
                } else {
                    $('.ai_spinner').addClass('d-none');
                    $('.ai_error').html('');
                    $('.ai_content').html(response.message);
                    $('.ai_text_field').prop('readonly', false);
                    $('.clear_prompt').removeClass('d-none');
                }
            },
            error: function(response) {
                var error_message = response.responseJSON;
                $('.ai_spinner').addClass('d-none');
                $('.ai_error').html('');
                $('.ai_content').html(error_message.message);
                $('.ai_text_field').prop('readonly', false);
                $('.clear_prompt').removeClass('d-none');
            }
        });
    }


    function see_similar_products(product_id  , option_id) {
        var products_to_hide = JSON.parse($('#products_to_hide').val() || '[]');
        $.ajax({
            url: "{{ url('/see-similar-products/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                product_id,
                option_id,
                products_to_hide,
            },
            success: function(response) {
                if (response.status == 'success') {
                    if (response.products.length === 0) {
                        $('#see_similar_pop_up_detail').modal('hide');
                        Swal.fire({
                            toast: true,
                            icon: 'error',
                            title: response.message,
                            timer: 3000,
                            position: 'top',
                            showConfirmButton: true,  // Show the confirm (OK) button
                            confirmButtonText: 'Okay',
                            timerProgressBar: true,
                            customClass: {
                                confirmButton: 'my-confirm-button',  // Class for the confirm button
                                actions: 'my-actions-class'  // Class for the actions container
                            }
                        });
                    } else {
                        $('#see_similar_pop_up_detail').modal('show');
                        $('.similar_products_row-body_detail').html(generateProductsHtml(response , response.products));
                    }
                } else {
                    $('#see_similar_pop_up_detail').modal('hide');
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: response.message,
                        timer: 3000,
                        position: 'top',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Okay',
                        timerProgressBar: true,
                        customClass: {
                            confirmButton: 'my-confirm-button',  // Class for the confirm button
                            actions: 'my-actions-class'  // Class for the actions container
                        }
                    });
                }
            },

            error: function(response) {
                $('#see_similar_pop_up_detail').modal('hide');
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    title: 'No similar products found',
                    timer: 3000,
                    position: 'top',
                    showConfirmButton: true,  // Show the confirm (OK) button
                    confirmButtonText: 'Okay',
                    timerProgressBar: true,
                    customClass: {
                        confirmButton: 'my-confirm-button',  // Class for the confirm button
                        actions: 'my-actions-class'  // Class for the actions container
                    }
                });
            }
        });
    }

    function generateProductsHtml(response , products) {
        var price_column = response.price_column;
        var htmlContent = `<div class="owl-carousel owl-theme similar-products-carousel-ai-detail w-75">`;

        products.forEach(function(product , price_column) {
            var productHtml = `
                <div class="item">
                    <div class="d-flex align-self-stretch mt-2  pt-1 h-100">
                    <div class="p-2 shadow-sm w-100" style="background-color: #fff; background-clip: border-box; border: 1px solid rgba(0,0,0,.125); border-radius: 0.25rem;">
            `;

            // Add subscribe button if contact_id is present
            if (response.contact_id) {
                productHtml += generateSubscribeButton(product.product_id, product.option_id , response.user_buy_list_options);
            }

            // Add image or placeholder
            productHtml += generateProductImage(product);
            
            var productName = product.products.name;  // Get the text of the product name
            var shortenedName = productName.length > 30 ? productName.substring(0, 20) + '...' : productName;
            // Add the rest of the product details
            productHtml += `
                <div class="card-body d-flex flex-column text-center mt-1 prd_mbl_card_bdy p-2">
                    <h5 class="card-title card_product_title tooltip-product similar_product_name_${product.products.id}" style="font-weight: 500; font-size: 16px;" data-title="${product.products.name}" id="product_name_${product.products.id}">
                        <a class="product-row-product-title" href="${window.location.origin +'/product-detail/' + product.products.id + '/' + product.option_id + '/' + product.products.slug}">
                            ${shortenedName}
                            <div class="tooltip-product-text bg-white text-primary">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner bg-white text-primary">
                                    <span>${productName}</span>
                                </div>
                            </div>
                        </a>
                    </h5>
                    <input type="hidden" name="p_id" id="p_${product.products.id}" value="${product.products.id}">
                </div>
            `;
            if (product.show_price === true && product.default_price !== null) {
                if ((product.default_price[response.price_column]  != null) || (parseFloat(product.default_price[response.price_column]) > 0)) {
                    var formattedPrice = formatNumber(parseFloat(product.default_price[response.price_column]));
                } else if ((product.default_price.sacramentoUSD  != null) || (parseFloat(product.default_price.sacramentoUSD) > 0)) {
                    var formattedPrice = formatNumber(parseFloat(product.default_price.sacramentoUSD));
                } else {
                    var formattedPrice = formatNumber(parseFloat(product.default_price.retailUSD));
                }
                productHtml += `
                    <h4 class="text-uppercase mb-0 text-center p_price_resp mt-0 mb-2">
                        $${formattedPrice}
                    </h4>
                `;
            }

            if (product.add_to_cart === true) {
                productHtml += `
                    <div class="col-sm-12 mt-0 button_swap_quantity button_swap_quantity_${product.products.id} mb-2" id="button_swap_${product.products.id}">
                        <div class="input-group">
                            <div class="input-group-prepend custom-border qty_minus_mobile">
                                <button class="p-0 bg-transparent btn-sm border-0 qty_customize_btn" id="" onclick="subtracting_quantity('${product.products.id}', '${product.option_id}')"><i class="fa fa-minus minus_qty_font qty_font"></i></button>
                            </div>
                            
                            <input type="number" id="swap_qty_number_${product.products.id}" onchange="update_qty_text_new('${product.products.id}', '${product.option_id}' ,this)" name="swap_qty_number" value="1"  class="qty_number_mobile bg-white form-control text-dark form-control-sm text-center swap_qty_number_${product.products.id}"  style="font-weight: 500" min="1" max="${product.stockAvailable}">
                            <div class="input-group-prepend custom-border qty_plus_mobile">
                                <button class="p-0 bg-transparent btn-sm border-0 qty_customize_btn" id="" onclick="adding_quantity('${product.products.id}', '${product.option_id}')"><i class="fa fa-plus plus_qty_font qty_font"></i></button>
                            </div>
                        </div>
                    </div>
                    <button 
                        class="hover_effect prd_btn_resp  button-cards col w-100  mb-1 original_cart_btn   original_cart_btn_${product.products.id}" 
                        type="submit" 
                        style="max-height: 46px;" id="ajaxSubmit_${product.products.id}"
                        onclick="updateCartDetail('${product.products.id}', '${product.option_id}')">
                        Add to cart
                    </button>
                `;
            } else {
                productHtml += `
                    <div class="col-md-12">
                        <button 
                            class="w-100 ml-0 call-to-order-button text-uppercase" 
                            style="max-height: 46px;">
                            Call To Order
                        </button>
                    </div>
                `;
            }
            

            productHtml += `</div></div></div>`;
            htmlContent += productHtml;
        });

        htmlContent += `</div>`;
        return htmlContent;
    }

    function generateSubscribeButton(productId, optionId , user_buy_list_options) {
        return `
            <a style="width:20px !important;" href="javascript:void(0);" class="ml-2 mt-2 subscribe">
                <i class="fa-solid fav-${optionId} fa-heart ${user_buy_list_options[optionId] ? '' : 'text-muted'}"
                id="${optionId}" data-toggle="popover"
                onclick="addToList('${productId}', '${optionId}', '${user_buy_list_options[optionId]}')">
                </i>
            </a>
        `;
    }

    function generateProductImage(product) {
        var imageNotAvailableUrl = "{{ asset('/theme/img/image_not_available.png') }}";
        if (product.products.images != '') {
            return `
                <a href="${window.location.origin +'/product-detail/' + product.products.id + '/' + product.option_id + '/' + product.products.slug}">
                    <div class="image-height-mbl" style="min-height: 130px; max-height:130px;">
                        <span class="d-flex justify-content-center align-items-center">
                            <img src="${product.products.images}" class="img_responsive_mbl col-md-10 image-body offset-1 mt-2" style="min-height: 130px; max-height: 130px;" />
                        </span>
                    </div>
                </a>
            `;
        } else {
            return `
                <a href="${window.location.origin +'/product-detail/' + product.products.id + '/' + product.option_id + '/' + product.products.slug}">
                    <div class="image-height-mbl" style="min-height: 130px; max-height:130px;">
                        <span class="d-flex justify-content-center align-items-center">
                            <img src="${imageNotAvailableUrl}" class="img_responsive_mbl_not_available col-md-10 image-body offset-1 mt-2"  style="min-height: 130px; max-height: 130px;" />
                        </span>
                    </div>
                </a>
            `;
        }
    }

    function formatNumber(value) {
        return parseFloat(value).toFixed(2);
    }
    
    function updateCartDetail(id, option_id) {
        var initial_free_shipping_value = parseInt($('.initial_free_shipping_value').val());
        var tax = 0;
        var tax_rate = parseFloat($('#tax_rate_number').val());
        var itemnumberQuantity = $('.swap_qty_number_'+id).val();
        var newValue = itemnumberQuantity.replace(/^0+/, ''); // Remove leading zeros
        if (newValue === "") {
            newValue = 0; // Handle case where all characters were zeros
        }
        $('.swap_qty_number_'+id).val(newValue);
        var itemQuantity = $('.swap_qty_number_'+id).val();
        if (parseInt(itemQuantity) <= 0 || itemQuantity === '' || itemQuantity === null) {
            Swal.fire({
                toast: false,
                icon: 'error',
                title: 'Quantity must be greater than 0 and not Empty!',
                position: 'center',
                showConfirmButton: true,  // Show the confirm (OK) button
                confirmButtonText: 'Confirm',
                timerProgressBar: false,
                allowOutsideClick: false, // Disable clicking outside to close the modal
                allowEscapeKey: false, // Disable Esc key to close the modal
                customClass: {
                    confirmButton: 'my-confirm-button',  // Class for the confirm button
                    popup: 'swal2-popup-class',  // Class for the actions container
                    actions: 'my-actions-class'  // Class for the actions container
                }
            });
            $('.swap_qty_number_'+id).val(1);
            return false;
        }
        jQuery.ajax({
            url: "{{ url('/add-to-cart/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                p_id: id,
                option_id: option_id,
                // quantity: 1
                quantity: itemQuantity
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
                        product_quantity = parseInt(item.quantity);
                        var quantity = parseInt(item.quantity);

                        var subtotal = parseFloat(price * quantity);
                        
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        var product_name = $('.similar_product_name_' + id).attr('data-title');
                    }

                    // jQuery('.cart-total-' + id).html($('#swap_qty_number_' + id).val());
                    jQuery('.cart-total-number-' + id).html(response.actual_stock);
                    jQuery('.swap_qty_number_'+id).val(response.actual_stock);
                    Swal.fire({
                        toast: false,
                        icon: 'error',
                        title: response.message,
                        position: 'center',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Confirm',
                        timerProgressBar: false,
                        allowOutsideClick: false, // Disable clicking outside to close the modal
                        allowEscapeKey: false, // Disable Esc key to close the modal
                        customClass: {
                            confirmButton: 'my-confirm-button',  // Class for the confirm button
                            popup: 'swal2-popup-class',  // Class for the actions container
                            actions: 'my-actions-class'  // Class for the actions container
                        }
                    });
                }
                if (response.status == 'success') {
                    var cart_items = response.cart_items;
                    var cart_total = 0;
                    var total_cart_quantity = 0;

                    for (var key in cart_items) {
                        var item = cart_items[key];

                        var product_id = item.prd_id;
                        var price = parseFloat(item.price);
                        var quantity = parseInt(item.quantity);

                        var subtotal = parseInt(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        
                    }
                    var product_name = $('.similar_product_name_' + id).attr('data-title');
                    // jQuery('.cart-total-number-' + id).html($('#swap_qty_number_' + id).val());
                    jQuery('.cart-total-number-' + id).html(response.actual_stock);

                    var grand_total = 0;
                    var grand_total = parseFloat(cart_total);
                    var tax = cart_total * (tax_rate / 100);
                    var grand_total_include_tax = 0;
                    grand_total_include_tax = (tax + grand_total).toFixed(2);
                    if (response.free_postal_state == true) {
                        if (grand_total <= initial_free_shipping_value) {
                            $('.promotional_banner_div_congrats').addClass('d-none');
                            $('.promotional_banner_div').removeClass('d-none');
                            $('.promotional_banner_span').html('$' + (initial_free_shipping_value - grand_total_include_tax).toFixed(2));
                        } else {
                            $('.promotional_banner_div').addClass('d-none');
                            $('.promotional_banner_div_congrats').removeClass('d-none');
                        }
                    }
                    else {
                        $('.promotional_banner_div').addClass('d-none');
                        $('.promotional_banner_div_congrats').addClass('d-none');
                    } 
                    Swal.fire({
                        toast: false,
                        icon: 'success',
                        title:itemQuantity + 'X ' + '<span class="text-dark toast_title">'+ product_name+'</span>' + '<br/>'+ '<div class="added_tocart text-left">Added to your cart</div>',
                        position: 'center',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Confirm',
                        timerProgressBar: false,
                        allowOutsideClick: false, // Disable clicking outside to close the modal
                        allowEscapeKey: false, // Disable Esc key to close the modal
                        customClass: {
                            confirmButton: 'my-confirm-button',  // Class for the confirm button
                            popup: 'swal2-popup-class',  // Class for the actions container
                            actions: 'my-actions-class'  // Class for the actions container
                        }
                    });
                }
                $('#top_cart_quantity').html(total_cart_quantity);
                $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                var total = document.getElementById('#top_cart_quantity');
            },
            error: function(response) {
            var error_message = response.responseJSON;
                Swal.fire({
                    toast: false,
                    icon: 'error',
                    title: error_message.message,
                    position: 'center',
                    showConfirmButton: true,  // Show the confirm (OK) button
                    confirmButtonText: 'Confirm',
                    timerProgressBar: false,
                    allowOutsideClick: false, // Disable clicking outside to close the modal
                    allowEscapeKey: false, // Disable Esc key to close the modal
                    customClass: {
                        confirmButton: 'my-confirm-button',  // Class for the confirm button
                        popup: 'swal2-popup-class',  // Class for the actions container
                        actions: 'my-actions-class'  // Class for the actions container
                    }
                });
            }
        });

        return false;
    }

    $(document).on('shown.bs.modal', '#see_similar_pop_up_detail', function () {
        $('.similar-products-carousel-ai-detail').owlCarousel({
            loop: false,
            margin: 10,
            dots: false,
            nav: true,
            // autoplay: true,
            // autoplayTimeout: 3000,
            navText: [
                '<i class="fa fa-chevron-left"></i>', // Left arrow icon
                '<i class="fa fa-chevron-right"></i>' // Right arrow icon
            ],
            responsive: {
                0: {
                    items: 1,
                    nav: false
                },
                600: {
                    items: 2,
                    nav: false
                },
                1000: {
                    items: 3,
                    nav: true
                }
            }
        });
        $('.similar-products-carousel-ai-detail').trigger('refresh.owl.carousel');
    });

    
</script>
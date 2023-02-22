@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<div class="mb-5">
   <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
      SEARCHED PRODUCTS
   </p>
</div>
<?php $count = 0; ?>
@include('search_product.desktop_view')
@include('search_product.mobile_view')
@include('search_product.ipade_view')
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
         jQuery(document).ready(function(){
            jQuery('.ajaxSubmit11').click(function(e){ alert('xxxxxxxxxx')
               e.preventDefault();
                
               $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });

               jQuery.ajax({
                  url: "{{ url('/add-to-cart/') }}",
                  method: 'post',
                  data: {
                    "_token": "{{ csrf_token() }}",
                     p_id: jQuery('#p_id').val(),
                     quantity: jQuery('#quantity').val(),
                  },
                  success: function(result){
                     console.log(result);
                      jQuery('.alert').html(result.success);
                        // window.location.reload();
                  }});
               });
            });
</script>
@include('partials.product-footer')
<!-- End of .container -->
@include('partials.footer')
@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<div class="mb-5">
   <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
      SEARCHED PRODUCTS
   </p>
</div>
<?php $count = 0; ?>
<div class="container desktop-view">
   <form id="form-filter">
      <div class="col-md-12">
         <div class="row pl-5 pr-5 pb-4 pt-3" style="border: 1px solid rgba(0,0,0,.125);">
            <div class="col">
               <label>Sort by</label>
               <select class="form-select" id="search_price" onchange="handleSelectChange()">
                  <option value="0">Select Option</option>
                  <option class="form-group" value="best-selling" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='best-selling' ? 'selected="selected"' : '' }}>Best Selling</option>
                  <option class="form-group" value="price-low-to-high" {{ $price_creteria }} {{ isset($price_creteria)
                     && $price_creteria=='price-low-to-high' ? 'selected="selected"' : '' }}>Price Low to High</option>
                  <option class="form-group" value="price-high-to-low" {{ $price_creteria }} {{ isset($price_creteria)
                     && $price_creteria=='price-high-to-low' ? 'selected="selected"' : '' }}>Price High to Low</option>
                  <option class="form-group" value="brand-a-to-z" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='brand-a-to-z' ? 'selected="selected"' : '' }}>Brand A to Z</option>
                  <option class="form-group" value="brand-z-to-a" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='brand-z-to-a' ? 'selected="selected"' : '' }}>Brand Z to A</option>
               </select>
            </div>
            <div class="col">
               <?php //dd($category_id);?>
               <label>Categories</label>
               <select class="form-select" id="selected_cat" name="selected_cat"
                  onchange="handleSelectChange('category')">
                  <option value="0">Select Category</option>
                  @foreach($categories as $category)
                  <option value="{{$category->id}}" {{ isset($category_id) && $category_id==$category->id ?
                     'selected="selected"' : '' }}>{{ $category->name }}</option>
                  @endforeach
               </select>
            </div>
            <div class="col">
               <label>Brand</label>
               <select class="form-select" id="brand" name="brands[]" onchange="handleSelectChange()">
                  <option value="0">Select Brand</option>
                  @foreach($brands as $_brand_id => $brand_name)
                  <option value="{{ $_brand_id }}" {{ isset($brand_id) && $brand_id==$_brand_id ? 'selected="selected"'
                     : '' }}>{{ $brand_name }}</option>
                  @endforeach
               </select>
            </div>
            <div class="col">
               <label>Result per page</label>
               <select id="per_page" class="form-select" onchange="handleSelectChange()">
                  <option value="20" {{ $per_page }} {{ isset($per_page) && $per_page==20 ? 'selected="selected"' : ''
                     }}>20</option>
                  <option value="40" {{ $per_page }} {{ isset($per_page) && $per_page==40 ? 'selected="selected"' : ''
                     }}>40</option>
                  <option value="60" {{ $per_page }} {{ isset($per_page) && $per_page==60 ? 'selected="selected"' : ''
                     }}>60</option>
               </select>
            </div>
            <div class="col">
               <label>Show Only</label>
               <div id="stock">
                  <?php if(empty($stock) || $stock == 'in-stock') {
                     $text = 'In stock';
                     $danger = '';
                     $stock = 'in-stock';
                  }
                  else {
                     $text = 'Out of Stock';
                     $danger = 'bg-danger';
                     $stock = 'out-of-stock';
                  }   
                  ?>
                  <button class="{{ $stock ? $stock : 'in-stock'  }} {{$danger}} in-stock" type="button" id="in-stock"
                     onclick="inStockOutstock('instock'), handleSelectChange()" value="{{$stock}}">{{$text}}</button>
                  <!--  <button class="button-cards bg-danger d-none" type="button" id="out-stock" onclick="inStockOutstock('outstock')" style="width:133px !important; height:34px !important;" value="outstock">Out of Stock</button> -->

               </div>
            </div>
         </div>
      </div>
   </form>
   <div class="row">
      @foreach ($products as $key => $product)
      @foreach($product->options as $option)
      <?php $count ++; ?>
      @include('product_row')
      @endforeach
      @endforeach
   </div>
   <div class="row">
      <div class="container">
         <div class="col-md-6 m-auto">
            {{$products->appends(Request::all())->links()}}
         </div>
      </div>
   </div>
</div>
<div class="container mobile-view">
   <form id="form-filter">
      <div class="col-md-12">
         <div class="row pb-4 pt-3" style="border: 1px solid rgba(0,0,0,.125);">
            <div class="col-md-12">
               <label>Sort by</label>
               <select class="form-select" id="search_price" onchange="handleSelectChange()">
                  <option value="0">Select Option</option>
                  <option class="form-group" value="best-selling" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='best-selling' ? 'selected="selected"' : '' }}>Best Selling</option>
                  <option class="form-group" value="price-low-to-high" {{ $price_creteria }} {{ isset($price_creteria)
                     && $price_creteria=='price-low-to-high' ? 'selected="selected"' : '' }}>Price Low to High</option>
                  <option class="form-group" value="price-high-to-low" {{ $price_creteria }} {{ isset($price_creteria)
                     && $price_creteria=='price-high-to-low' ? 'selected="selected"' : '' }}>Price High to Low</option>
                  <option class="form-group" value="brand-a-to-z" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='brand-a-to-z' ? 'selected="selected"' : '' }}>Brand A to Z</option>
                  <option class="form-group" value="brand-z-to-a" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='brand-z-to-a' ? 'selected="selected"' : '' }}>Brand Z to A</option>
               </select>
            </div>
            <div class="col-md-12">
               <?php //dd($category_id);?>
               <label>Categories</label>
               <select class="form-select" id="selected_cat" name="selected_cat"
                  onchange="handleSelectChange('category')">
                  <option value="0">Select Category</option>
                  @foreach($categories as $category)
                  <option value="{{$category->id}}" {{ isset($category_id) && $category_id==$category->id ?
                     'selected="selected"' : '' }}>{{ $category->name }}</option>
                  @endforeach
               </select>
            </div>
            <div class="col-md-12">
               <label>Brand</label>
               <select class="form-select" id="brand" name="brands[]" onchange="handleSelectChange()">
                  <option value="0">Select Brand</option>
                  @foreach($brands as $_brand_id => $brand_name)
                  <option value="{{ $_brand_id }}" {{ isset($brand_id) && $brand_id==$_brand_id ? 'selected="selected"'
                     : '' }}>{{ $brand_name }}</option>
                  @endforeach
               </select>
            </div>
            <div class="col-md-12">
               <label>Result per page</label>
               <select id="per_page" class="form-select" onchange="handleSelectChange()">
                  <option value="20" {{ $per_page }} {{ isset($per_page) && $per_page==20 ? 'selected="selected"' : ''
                     }}>20</option>
                  <option value="40" {{ $per_page }} {{ isset($per_page) && $per_page==40 ? 'selected="selected"' : ''
                     }}>40</option>
                  <option value="60" {{ $per_page }} {{ isset($per_page) && $per_page==60 ? 'selected="selected"' : ''
                     }}>60</option>
               </select>
            </div>
            <div class="col-md-12 d-flex mt-3">
               <label>Show Only</label>
               <div id="stock" n style="padding-left: 139px !important">
                  <?php if(empty($stock) || $stock == 'in-stock') {
                     $text = 'In stock';
                     $danger = '';
                     $stock = 'in-stock';
                  }
                  else {
                     $text = 'Out of Stock';
                     $danger = 'bg-danger';
                     $stock = 'out-of-stock';
                  }   
                  ?>
                  <button class="{{ $stock ? $stock : 'in-stock'  }} {{$danger}} in-stock" type="button" id="in-stock"
                     onclick="inStockOutstock('instock'), handleSelectChange()" value="{{$stock}}">{{$text}}</button>
                  <!--  <button class="button-cards bg-danger d-none" type="button" id="out-stock" onclick="inStockOutstock('outstock')" style="width:133px !important; height:34px !important;" value="outstock">Out of Stock</button> -->

               </div>
            </div>
         </div>
      </div>
   </form>
   <div class="row">
      @foreach ($products as $key => $product)
      @foreach($product->options as $option)
      <?php $count ++; ?>
      @include('product_row')
      @endforeach
      @endforeach
   </div>
   <div class="row">
      <div class="container">
         <div class="col-md-6 m-auto">
            {{$products->appends(Request::all())->links()}}
         </div>
      </div>
   </div>
</div>
<script>
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
               basic_url = basic_url+`&stock=${stock}`;
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
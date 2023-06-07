@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
{{-- <div class="mb-5">
   <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
      PRODUCTS
   </p>
</div> --}}
<div class="row justify-content-center align-items-center" style="background-color: #008BD3;height:70px;">
   <p class="fw-bold fs-2 my-auto border-0 text-white text-center align-middle">
      PRODUCTS
   </p>
</div>
<div class="container desktop-view">
   <form id="form-filter">
      <div class="col-md-12">
         <div class="row pl-5 pr-5 pb-4 pt-3" style="border: 1px solid rgba(0,0,0,.125);">
            <input type="hidden" id="category_id" value="{{ $category_id }}">
            <input type="hidden" id="parent_category_slug" value="{{ $parent_category_slug }}">
            <div class="col">
               <label>Sort by</label>
               <select class="form-select" id="search_price" onchange="handleSelectChange('best_selling')">
                  <option value="0">Select Option</option>
                  <option class="form-group" value="best-selling" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='best-selling' ? 'selected="selected"' : '' }}>Best Selling</option>
                  <option class="form-group" value="price-low-to-high" {{ $price_creteria }} {{ isset($price_creteria)
                     && $price_creteria=='price-low-to-high' ? 'selected="selected"' : '' }}>Price Low to High</option>
                  <option class="form-group" value="price-high-to-low" {{ $price_creteria }} {{ isset($price_creteria)
                     && $price_creteria=='price-high-to-low' ? 'selected="selected"' : '' }}>Price High to Low</option>
                  <option class="form-group" value="brand-a-to-z" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='brand-a-to-z' ? 'selected="selected"' : '' }}>Product A to Z</option>
                  <option class="form-group" value="brand-z-to-a" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='brand-z-to-a' ? 'selected="selected"' : '' }}>Product Z to A</option>
               </select>
            </div>
            <div class="col">
               <?php //dd($category_id);?>
               <label>Categories</label>
               <select class="form-select" id="selected_cat" name="selected_cat"
                  onchange="handleSelectChange('category')">

                  @foreach($categories as $category)

                  <option value="{{$category->id}}/{{$category->slug}}" {{ isset($category_id) &&
                     $category_id==$category->id ? 'selected="selected"' : '' }}>{{ $category->name }}</option>
                  @endforeach
               </select>
            </div>

            <div class="col">
               <label>Sub Category</label>
               <select class="form-select" id="childeren" name="childeren[]" onchange="handleSelectChange('childeren')">
                  <option value="">Sub Category</option>
                  @foreach($childerens as $key => $childeren)
                  <option value="{{ $childeren->id }}" {{ isset($childeren_id) && $childeren_id==$childeren->id ?
                     'selected="selected"'
                     : '' }}>{{ $childeren->name }}</option>
                  @endforeach
               </select>
            </div>

            <div class="col">
               <label>Result per page</label>
               <select id="per_page" class="form-select" onchange="handleSelectChange('result_per_page')">
                  <option value="20" {{ $per_page }} {{ isset($per_page) && $per_page==20 ? 'selected="selected"' : ''
                     }}>20</option>
                  <option value="40" {{ $per_page }} {{ isset($per_page) && $per_page==40 ? 'selected="selected"' : ''
                     }}>40</option>
                  <option value="60" {{ $per_page }} {{ isset($per_page) && $per_page==60 ? 'selected="selected"' : ''
                     }}>60</option>
               </select>
            </div>
            <!-- <div class="col">
               <label>Show Only</label>

               <div id="stock">
                  <?php 
                     if (empty($stock) || $stock == 'in-stock') {
                     $text = 'In stock';
                     $danger = '';
                     //$stock = 'in-stock';
                  }
                  else {
                     $text = 'Out of Stock';
                     $danger = 'bg-danger';
                     //$stock = 'out-of-stock';
                  }   
                  ?>
                  <button class="{{ $stock ? $stock : 'in-stock'  }} {{$danger}}" type="button" id="in-stock"
                     onclick="inStockOutstock('instock'), handleSelectChange()" value="{{$stock}}">{{$text}}
                  </button>
               </div>
            </div> -->
            <div class="col">
               <label>Inventory</label>
               <select class="form-select" name="inventory" id="inventory" onchange="handleSelectChange()">
                  <option value="in-stock">In stock</option>
                  <option value="out-of-stock" {{ isset($stock) && $stock=='out-of-stock' ? 'selected="selected"' : ''
                     }}>Out of Stock</option>
                  <option value="all-items" {{ isset($stock) && $stock=='all-items' ? 'selected="selected"' : '' }}>All
                     Items</option>
               </select>
            </div>
            <!--  <div class="col">
                <label>Show All</label>

               <button type="button" class="all-items" id="btnAllItems"
                     onclick="showAllItems()" value={{"all-items"}}>All Items
               </button>
            </div> -->
         </div>
      </div>
   </form>
   <div class="row" id="product_rows">
      @if(count($products) == 0)
         <div class="col-md-12 mt-3">
               <div class="alert alert-danger">No Product Found</div>
         </div>
      @endif
      @foreach ($products as $key => $product)
      @foreach($product->options as $option)
      @include('product_row')
      @endforeach
      @endforeach
   </div>
   <!--    {{$products->links('pagination::bootstrap-4')}} -->
   {{$products->appends(Request::all())->links()}}
</div>
{{-- moible view start --}}
<div class="container mobile-view">
   <div class="mt-2">
      <p class="d-flex justify-content-start align-items-center mb-0">
         <button class="filler-and-sort btn  filler-and-sort p-0 filterMblbtn" type="button" data-bs-toggle="modal"
            data-bs-target="#filter_model" aria-expanded="false" aria-controls="" style="border: none !important;">
            <i class="fa fa-sliders filterIco_mbl"></i>
            <span class="search_filter_text">Search Filter </span>
            {{-- <img src="/theme/img/filler-icon.png" alt=""></span> --}}
         </button>
      </p>
   </div>
   <div class="collapse mt-5" id="collapseExample">
      <div class="card card-body p-0">
         <form id="form-filter">
            <div class="col-md-12">
               <div class="row pt-3" style="border: 1px solid rgba(0,0,0,.125);">
                  <input type="hidden" id="category_id" value="{{ $category_id }}">
                  <input type="hidden" id="parent_category_slug" value="{{ $parent_category_slug }}">
                  <div class="col-md-12">
                     <label>Sort by</label>
                     <select class="form-select" id="search_price" onchange="handleSelectChange('best_selling')">
                        <option value="0">Select Option</option>
                        <option class="form-group" value="best-selling" {{ $price_creteria }} {{ isset($price_creteria) &&
                           $price_creteria=='best-selling' ? 'selected="selected"' : '' }}>Best Selling</option>
                        <option class="form-group" value="price-low-to-high" {{ $price_creteria }} {{
                           isset($price_creteria) && $price_creteria=='price-low-to-high' ? 'selected="selected"' : '' }}>
                           Price Low to High</option>
                        <option class="form-group" value="price-high-to-low" {{ $price_creteria }} {{
                           isset($price_creteria) && $price_creteria=='price-high-to-low' ? 'selected="selected"' : '' }}>
                           Price High to Low</option>
                        <option class="form-group" value="brand-a-to-z" {{ $price_creteria }} {{ isset($price_creteria) &&
                           $price_creteria=='brand-a-to-z' ? 'selected="selected"' : '' }}>Product A to Z</option>
                        <option class="form-group" value="brand-z-to-a" {{ $price_creteria }} {{ isset($price_creteria) &&
                           $price_creteria=='brand-z-to-a' ? 'selected="selected"' : '' }}>Product Z to A</option>
                        <option class="form-group" value="price">Best Selling</option>
                        <option class="form-group" value="price-low-to-high" {{ $price_creteria }} {{
                           isset($price_creteria) && $price_creteria=='price-low-to-high' ? 'selected="selected"' : '' }}>
                           Price Low to High</option>
                        <option class="form-group" value="price-high-to-low" {{ $price_creteria }} {{
                           isset($price_creteria) && $price_creteria=='price-high-to-low' ? 'selected="selected"' : '' }}>
                           Price High to Low</option>
                        <option class="form-group" value="brand-a-to-z" {{ $price_creteria }} {{ isset($price_creteria) &&
                           $price_creteria=='brand-a-to-z' ? 'selected="selected"' : '' }}>Product A to Z</option>
                        <option class="form-group" value="brand-z-to-a" {{ $price_creteria }} {{ isset($price_creteria) &&
                           $price_creteria=='brand-z-to-a' ? 'selected="selected"' : '' }}>Product Z to A</option>

                     </select>
                  </div>
                  <div class="col-md-12">
                     <?php //dd($category_id);?>
                     <label>Categories</label>
                     <select class="form-select" id="selected_cat" name="selected_cat"
                        onchange="handleSelectChange('category')">

                        @foreach($categories as $category)

                        <option value="{{$category->id}}/{{$category->slug}}" {{ isset($category_id) &&
                           $category_id==$category->id ? 'selected="selected"' : '' }}>{{ $category->name }}</option>
                        <!-- <option value="{{$category->id}}/{{$category->slug}}" {{ isset($selected_category_id) && $selected_category_id == $selected_category_id ? 'selected="selected"' : '' }}>{{ $category->name }}</option> -->
                        @endforeach

                        <!-- @foreach($products as $key=>$product)
                           <option value="{{$product->brand_id}}" >{{$product->brand}}</option>
                     @endforeach -->
                     </select>
                  </div>

                  <div class="col-md-12">
                     <label>Brand</label>
                     <select class="form-select" id="brand" name="brands[]" onchange="handleSelectChange('brand')">
                        <option value="0">Select Brand</option>
                        @foreach($brands as $_brand_id => $brand_name)
                        <option value="{{ $_brand_id }}" {{ isset($brand_id) && $brand_id==$_brand_id
                           ? 'selected="selected"' : '' }}>{{ $brand_name }}</option>
                        @endforeach

                        <!-- @foreach($products as $key=>$product)
                           <option value="{{$product->brand_id}}" >{{$product->brand}}</option>
                     @endforeach -->
                     </select>
                  </div>

                  <div class="col-md-12">
                     <label>Result per page</label>
                     <select id="per_page" class="form-select" onchange="handleSelectChange('result_per_page')">
                        <option value="10" {{ $per_page }} {{ isset($per_page) && $per_page==10 ? 'selected="selected"'
                           : '' }}>10</option>
                        <option value="20" {{ $per_page }} {{ isset($per_page) && $per_page==20 ? 'selected="selected"'
                           : '' }}>20</option>
                        <option value="30" {{ $per_page }} {{ isset($per_page) && $per_page==30 ? 'selected="selected"'
                           : '' }}>30</option>
                     </select>
                  </div>
                  <div class="col-md-12">
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
                        <button class="{{ $stock ? $stock : 'in-stock'  }} {{$danger}}" type="button" id="in-stock"
                           onclick="inStockOutstock('instock'), handleSelectChange()" value="{{$stock}}">{{$text}}</button>
                     </div>
                  </div>
               </div>
            </div>
         </form>
         <input type="text" id="all-items" value="" />
      </div>
   </div>
   <div class="row" id="product_rows">
      @if(count($products) == 0)
         <div class="col-md-12 mt-3">
               <div class="alert alert-danger">No Product Found</div>
         </div>
      @endif
      @foreach ($products as $key => $product)
      @foreach($product->options as $option)
      @include('product_row')
      @endforeach
      @endforeach
   </div>
   <!--    {{$products->links('pagination::bootstrap-4')}} -->
   {{$products->appends(Request::all())->links()}}
</div>
{{-- moible view end --}}

{{-- ipad view start --}}
<div class="container ipad-view">
   <form id="form-filter">
      <div class="col-md-12">
         <div class="row  pb-4 pt-3" style="border: 1px solid rgba(0,0,0,.125);">
            <input type="hidden" id="category_id" value="{{ $category_id }}">
            <input type="hidden" id="parent_category_slug" value="{{ $parent_category_slug }}">
            <div class="col-md-6">
               <label>Sort by</label>
               <select class="form-select" id="search_price" onchange="handleSelectChange('best_selling')">
                  <option value="0">Select Option</option>
                  <option class="form-group" value="best-selling" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='best-selling' ? 'selected="selected"' : '' }}>Best Selling</option>
                  <option class="form-group" value="price-low-to-high" {{ $price_creteria }} {{ isset($price_creteria)
                     && $price_creteria=='price-low-to-high' ? 'selected="selected"' : '' }}>Price Low to High</option>
                  <option class="form-group" value="price-high-to-low" {{ $price_creteria }} {{ isset($price_creteria)
                     && $price_creteria=='price-high-to-low' ? 'selected="selected"' : '' }}>Price High to Low</option>
                  <option class="form-group" value="brand-a-to-z" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='brand-a-to-z' ? 'selected="selected"' : '' }}>Product A to Z</option>
                  <option class="form-group" value="brand-z-to-a" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='brand-z-to-a' ? 'selected="selected"' : '' }}>Product Z to A</option>
                  <option class="form-group" value="price">Best Selling</option>
                  <option class="form-group" value="price-low-to-high" {{ $price_creteria }} {{ isset($price_creteria)
                     && $price_creteria=='price-low-to-high' ? 'selected="selected"' : '' }}>Price Low to High</option>
                  <option class="form-group" value="price-high-to-low" {{ $price_creteria }} {{ isset($price_creteria)
                     && $price_creteria=='price-high-to-low' ? 'selected="selected"' : '' }}>Price High to Low</option>
                  <option class="form-group" value="brand-a-to-z" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='brand-a-to-z' ? 'selected="selected"' : '' }}>Product A to Z</option>
                  <option class="form-group" value="brand-z-to-a" {{ $price_creteria }} {{ isset($price_creteria) &&
                     $price_creteria=='brand-z-to-a' ? 'selected="selected"' : '' }}>Product Z to A</option>

               </select>
            </div>
            <div class="col-md-6">
               <?php //dd($category_id);?>
               <label>Categories</label>
               <select class="form-select" id="selected_cat" name="selected_cat"
                  onchange="handleSelectChange('category')">

                  @foreach($categories as $category)

                  <option value="{{$category->id}}/{{$category->slug}}" {{ isset($category_id) &&
                     $category_id==$category->id ? 'selected="selected"' : '' }}>{{ $category->name }}</option>
                  <!-- <option value="{{$category->id}}/{{$category->slug}}" {{ isset($selected_category_id) && $selected_category_id == $selected_category_id ? 'selected="selected"' : '' }}>{{ $category->name }}</option> -->
                  @endforeach

                  <!-- @foreach($products as $key=>$product)
                        <option value="{{$product->brand_id}}" >{{$product->brand}}</option>
                  @endforeach -->
               </select>
            </div>

            <div class="col-md-6">
               <label>Brand</label>
               <select class="form-select" id="brand" name="brands[]" onchange="handleSelectChange('brand')">
                  <option>Select Brand</option>
                  @foreach($brands as $_brand_id => $brand_name)
                  <option value="{{ $_brand_id }}" {{ isset($brand_id) && $brand_id==$_brand_id ? 'selected="selected"'
                     : '' }}>{{ $brand_name }}</option>
                  @endforeach

                  <!-- @foreach($products as $key=>$product)
                        <option value="{{$product->brand_id}}" >{{$product->brand}}</option>
                  @endforeach -->
               </select>
            </div>

            <div class="col-md-6">
               <label>Result per page</label>
               <select id="per_page" class="form-select" onchange="handleSelectChange('result_per_page')">
                  <option value="10" {{ $per_page }} {{ isset($per_page) && $per_page==10 ? 'selected="selected"' : ''
                     }}>10</option>
                  <option value="20" {{ $per_page }} {{ isset($per_page) && $per_page==20 ? 'selected="selected"' : ''
                     }}>20</option>
                  <option value="30" {{ $per_page }} {{ isset($per_page) && $per_page==30 ? 'selected="selected"' : ''
                     }}>30</option>
               </select>
            </div>
            <div class="col-md-12 d-flex mt-3">
               <label>Show Only</label>

               <div id="stock" style="padding-left: 488px !important">
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
                  <button class="{{ $stock ? $stock : 'in-stock'  }} {{$danger}}" type="button" id="in-stock"
                     onclick="inStockOutstock('instock'), handleSelectChange()" value="{{$stock}}">{{$text}}</button>
               </div>
            </div>
         </div>
      </div>
   </form>
   <div class="row" id="product_rows">
      @if(count($products) == 0)
         <div class="col-md-12 mt-3">
               <div class="alert alert-danger">No Product Found</div>
         </div>
      @endif
      @foreach ($products as $key => $product)
      @foreach($product->options as $option)
      @include('product_row')
      @endforeach
      @endforeach
   </div>
   <!--    {{$products->links('pagination::bootstrap-4')}} -->
   {{$products->appends(Request::all())->links()}}
</div>
{{-- ipad view end --}}

{{-- pop up filter mobile --}}

<div class="modal fade" id="filter_model" tabindex="-1" aria-labelledby="filter_content" aria-hidden="true"
   data-bs-backdrop="static">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header  mobile_filter_header">
            <h5 class="modal-title mobile_filter_title" id="filter_content">Search Filter</h5>
            <button type="button" class="bg-none mbl_filter_btn" data-bs-dismiss="modal" aria-label="Close">
               <i class="fa fa-times mbl_filter_close"></i>
            </button>
         </div>
         <div class="modal-body">
            <div class="card card-body p-0 border-0">
               <form id="form-filter-pop">
                  <input type="hidden" id="category_id" value="{{ $category_id }}">
                  <input type="hidden" id="parent_category_slug" value="{{ $parent_category_slug }}">
                  <div class="row pb-4 pt-2 border-0">
                     <div class="col-md-12">
                        <label class="filter_heading_mbl">Sort by</label>
                        <select class="form-select form-select-mbl" id="search_price_mbl">
                           <option class="filter_drop_down_mbl " value="0">Select Option</option>
                           <option class="filter_drop_down_mbl form-group" value="best-selling">Best Selling</option>
                           <option class="filter_drop_down_mbl form-group" value="price-low-to-high">Price Low to High
                           </option>
                           <option class="filter_drop_down_mbl form-group" value="price-high-to-low">Price High to Low
                           </option>
                           <option class="filter_drop_down_mbl form-group" value="brand-a-to-z">Brand A to Z</option>
                           <option class="filter_drop_down_mbl form-group" value="brand-z-to-a">Brand Z to A</option>
                        </select>
                     </div>
                     <div class="col-md-12 mt-2">
                        <?php //dd($category_id);
                                ?>
                        <label class="filter_heading_mbl">Categories</label>
                        <select class="form-select form-select-mbl" onchange="get_child_categories()"
                           id="selected_cat_mbl" name="selected_cat">
                           <option class="filter_drop_down_mbl" value="">Select Category</option>
                           @foreach ($categories as $category)
                           <option class="filter_drop_down_mbl" value="{{$category->id}}/{{$category->slug}}" {{ isset($category_id) &&
                              $category_id==$category->id ? 'selected="selected"' : '' }}>{{ $category->name }}
                              
                           </option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-12 mt-2">
                        <label class="filter_heading_mbl">Sub Category</label>
                        <select class="form-select form-select-mbl" id="childeren_mbl" name="childeren_category">
                           @foreach($childerens as $key => $childeren)
                           <option value="{{ $childeren->id }}" {{ isset($childeren_id) && $childeren_id==$childeren->id ?
                              'selected="selected"'
                              : '' }}>{{ $childeren->name }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-12 mt-2">
                        <label class="filter_heading_mbl">Brand</label>
                        <select class="form-select form-select-mbl" id="brand_mbl" name="brands[]">
                           <option class="filter_drop_down_mbl" value="0">Select Brand</option>
                           @foreach ($brands as $_brand_id => $brand_name)
                           <option class="filter_drop_down_mbl" value="{{ $_brand_id }}">{{ $brand_name }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-12 mt-2">
                        <label class="filter_heading_mbl">Result per page</label>
                        <select id="per_page_mbl" class="form-select form-select-mbl">
                           <option class="filter_drop_down_mbl" value="20">20</option>
                           <option class="filter_drop_down_mbl" value="40">40</option>
                           <option class="filter_drop_down_mbl" value="60">60</option>
                        </select>
                     </div>

                     <div class="col-md-12 mt-2">
                        <?php if (empty($stock) || $stock == 'in-stock') {
                                    $text = 'In stock';
                                    $danger = '';
                                    $stock = 'in-stock';
                                } else {
                                    $text = 'Out of Stock';
                                    $danger = 'bg-danger';
                                    $stock = 'out-of-stock';
                                }
                                ?>
                        <label class="filter_heading_mbl">Show Only</label>
                        <select id="in_stk" class="form-select form-select-mbl">
                           <option class="filter_drop_down_mbl" value="0">Show Only</option>
                           <option class="filter_drop_down_mbl" value="in-stock" {{ isset($stock) && $stock=='in-stock'
                              ? 'selected' : '' }}>In Stock</option>
                           <option class="filter_drop_down_mbl" value="out-of-stock" {{ isset($stock) &&
                              $stock=='out-of-stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                     </div>
                  </div>
                  <div class="row justify-content-center">
                     <button type="button" onclick="handleSelectChangeMbl()" class="btn btn-success filter-done-btn">
                        <span class="filter-done-btn-text">Done</span>
                     </button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>

{{-- pop up filter mobile end --}}

<script>
   
   $('#brand').select2({
    width: '100%',
    placeholder: "Select an Option",
    allowClear: true
  });
    $('#categories').select2({
    width: '100%',
    placeholder: "Select an Option",
    allowClear: true
  });
</script>
<script>
   function showAllItems() {
      $('#all-items').val('all-items');
      handleSelectChange();
   }
   function showdetails(id, option_id, slug) {
      window.location.href = '/product-detail/'+ id +'/'+option_id+'/'+slug;
   }
   function categoryChange() {
      // alert('kflsdflkdsflsdk');
      var categories = jQuery('#categories').val();
      window.location.href =  window.location.origin+'/products/'+category_id;
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

      jQuery('#all-items').val('');

   }
   function handleSelectChange(searchedOption = '') {
      var category_id = jQuery('#categories').val();
      var selected_cat_id = jQuery('#selected_cat').val();
      var price = jQuery('#search_price').val();
      var brand = jQuery('#brand').val();
      var childeren = jQuery('#childeren').val();
      var inventory = jQuery('#inventory').val();
      
      var per_page = jQuery('#per_page').val();
      var stock = jQuery('#in-stock').val();
      var all_items = jQuery('#all-items').val();
      var search_price = jQuery('#search_price').val();
      
      var category_id = jQuery('#category_id').val();
      var selected_category_id = jQuery('#categories').val();
      var parent_category_slug = jQuery('#parent_category_slug').val();
      if (searchedOption == 'category') {
         var brand = '';
      }
      if (selected_cat_id != ''){ 
         var slug = selected_cat_id;
         var basic_url = '/products/'+selected_cat_id + '/?';
         //window.location.href = basic_url;
         //var basic_url = `/products/${selected_cat_id}/${slug}`;
      }
      else {
      
         var slug = `${category_id}/${parent_category_slug}`
      }

      if (brand != '') {
         basic_url = `?brand_id=${brand}`;
      }

      if (childeren != '') {
         basic_url = `?childeren_id=${childeren}`;
      }

      if (per_page != '') {
         basic_url = basic_url+`&per_page=${per_page}`;
      }
      if (search_price != '') {
         basic_url = basic_url+`&search_price=${search_price}`;
      }
      if (selected_category_id != '') {
         basic_url = basic_url+`&selected_category_id=${selected_cat_id}`;
      }
      if (stock != '') {
         basic_url = basic_url+`&stock=${inventory }`;
      }
      if (all_items != '') {
         basic_url = basic_url+ `&all_items=${all_items}`;
      }
      window.location.href = basic_url;
   }

   //mobile filter get child category
   function get_child_categories() {
      var child_category = jQuery('#selected_cat_mbl').val();
      var parent_id = child_category.substring(0, child_category.indexOf('/'));;
      jQuery.ajax({
            url: '/child/categories/' + parent_id,
            method: 'get',
            success: function(response) {
               console.log(response);
               if(response.status == 'success') {
                  $('#childeren_mbl').html('');
                  $.each(response.child_categories, function(index, option) {
                     $('#childeren_mbl').append('<option value="' + option.id + '">' + option.name + '</option>');
                  });
               }
            }
      });
   } 

   //mobile filter
   function handleSelectChangeMbl(searchedOption = '') {
      var basic_url = '';
      var selected_category = jQuery('#selected_cat_mbl').val();
      var brand = jQuery('#brand_mbl').val();
      var per_page = jQuery('#per_page_mbl').val();
      var search_price = jQuery('#search_price_mbl').val();
      var childeren = jQuery('#childeren_mbl').val();
      var stock = jQuery('#in_stk').val();
      var category_id = jQuery('#category_id').val();
      var parent_category_slug = jQuery('#parent_category_slug').val();
      var emptyCategory = 0;
      var emptychildCategory = 0;

      if (selected_category != ''){ 
         var slug = selected_category;
         var basic_url = '/products/'+selected_category + '/?';
      }
      else {
      
         var slug = `${category_id}/${parent_category_slug}`
      }

      if (childeren != '') {
            basic_url = basic_url + `&childeren_id=${childeren}`;
      }
      else {
            basic_url = basic_url + `&childeren_id=${emptychildCategory}`;
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
      
      window.location.href = basic_url
   }
   function updateCart(id, option_id) {

      jQuery.ajax({
            url: "{{ url('/add-to-cart/') }}",
               method: 'post',
               data: {
                  "_token": "{{ csrf_token() }}",
                  p_id: jQuery('#p_'+id).val(),
                  option_id: option_id,
                  quantity: 1
               },
            success: function(response) {
            if(response.status == 'success') {
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
                        $('#subtotal_' + product_id).html('$'+subtotal);
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
                  $('.cartQtyipad').html(total_cart_quantity);
                  $('.cartQtymbl').html(total_cart_quantity);
                  $('#cart_items_quantity').html(total_cart_quantity);
                  $('#topbar_cart_total').html('$'+parseFloat(cart_total).toFixed(2));
                  $('.topbar_cart_total_ipad').html('$'+parseFloat(cart_total).toFixed(2));
                  var total = document.getElementById('#top_cart_quantity');
            }});

         return false;
   }
</script>
{{-- <script>
   jQuery(document).ready(function(){
      jQuery('.ajaxSubmit11').click(function(e){ alert('xxxxxxxxxx')
         e.preventDefault();
         //alert('here');
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
            }
         });
      });
   });
</script> --}}

<!-- Remove the container if you want to extend the Footer to full width. -->

@include('partials.product-footer')

<!-- End of .container -->
@include('partials.footer')
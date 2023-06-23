<div class="container ipad-view">
   <form id="form-filter">
      <div class="col-md-12">
         <div class="row pb-4 pt-3" style="border: 1px solid rgba(0,0,0,.125);">
            <div class="col-md-6">
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
            <div class="col-md-6">
               <?php //dd($category_id);?>
               <label>Categories</label>
               <select class="form-select" id="selected_cat" name="selected_cat"
                  onchange="handleSelectChange('category')">
                  <option value="0">Select Category</option>
                  @foreach($categories as $category)
                     @if($category->name != 'Not visable')
                     <option value="{{$category->id}}" {{ isset($category_id) && $category_id==$category->id ?
                        'selected="selected"' : '' }}>{{ $category->name }}</option>
                     @endif
                  @endforeach
               </select>
            </div>
            <div class="col-md-6">
               <label>Brand</label>
               <select class="form-select" id="brand" name="brands[]" onchange="handleSelectChange()">
                  <option value="0">Select Brand</option>
                  @foreach($brands as $_brand_id => $brand_name)
                  <option value="{{ $_brand_id }}" {{ isset($brand_id) && $brand_id==$_brand_id ? 'selected="selected"'
                     : '' }}>{{ $brand_name }}</option>
                  @endforeach
               </select>
            </div>
            <div class="col-md-6">
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
               <div id="stock" n style="padding-left: 488px  !important">
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
      @if(count($products) == 0)
         <div class="col-md-12 mt-3">
               <div class="alert alert-danger">No Product Found</div>
         </div>
      @endif
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
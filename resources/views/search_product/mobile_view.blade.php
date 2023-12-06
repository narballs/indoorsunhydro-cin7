<div class="container mobile-view">
   <div class="row mt-3">
      <p class="d-flex justify-content-start align-items-center mb-0">
         <button class="filler-and-sort btn  filler-and-sort p-0 filterMblbtn" type="button" data-bs-toggle="modal"
            data-bs-target="#filter_model" aria-expanded="false" aria-controls="" style="border: none !important;">
            {{-- <i class="fa fa-sliders filterIco_mbl"></i> --}}
            <img src="/theme/img/icons/filter_mobile_icon.png" alt="">
            <span class="search_filter_text">Search Filter </span>
            {{-- <img src="/theme/img/filler-icon.png" alt=""></span> --}}
         </button>
      </p>
   </div>
   <div class="collapse mt-5" id="collapseExample">
      <div class="card card-body p-0">
         <form id="form-filter">
            <div class="col-md-12">
               <div class="row pb-4 pt-3" style="border: 1px solid rgba(0,0,0,.125);">
                  <div class="col-md-12">
                     <label>Sort by</label>
                     <select class="form-select" id="search_price" onchange="handleSelectChange()">
                        <option value="0">Select Option</option>
                        <option class="form-group" value="best-selling" {{ $price_creteria }} {{ isset($price_creteria)
                           && $price_creteria=='best-selling' ? 'selected="selected"' : '' }}>Best Selling</option>
                        <option class="form-group" value="price-low-to-high" {{ $price_creteria }} {{
                           isset($price_creteria) && $price_creteria=='price-low-to-high' ? 'selected="selected"' : ''
                           }}>Price Low to High</option>
                        <option class="form-group" value="price-high-to-low" {{ $price_creteria }} {{
                           isset($price_creteria) && $price_creteria=='price-high-to-low' ? 'selected="selected"' : ''
                           }}>Price High to Low</option>
                        <option class="form-group" value="brand-a-to-z" {{ $price_creteria }} {{ isset($price_creteria)
                           && $price_creteria=='brand-a-to-z' ? 'selected="selected"' : '' }}>Brand A to Z</option>
                        <option class="form-group" value="brand-z-to-a" {{ $price_creteria }} {{ isset($price_creteria)
                           && $price_creteria=='brand-z-to-a' ? 'selected="selected"' : '' }}>Brand Z to A</option>
                     </select>
                  </div>
                  <div class="col-md-12">
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
                  <div class="col-md-12">
                     <label>Brand</label>
                     <select class="form-select" id="brand" name="brands[]" onchange="handleSelectChange()">
                        <option value="0">Select Brand</option>
                        @foreach($brands as $_brand_id => $brand_name)
                        <option value="{{ $_brand_id }}" {{ isset($brand_id) && $brand_id==$_brand_id
                           ? 'selected="selected"' : '' }}>{{ $brand_name }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-12">
                     <label>Result per page</label>
                     <select id="per_page" class="form-select" onchange="handleSelectChange()">
                        <option value="20" {{ $per_page }} {{ isset($per_page) && $per_page==20 ? 'selected="selected"'
                           : '' }}>20</option>
                        <option value="40" {{ $per_page }} {{ isset($per_page) && $per_page==40 ? 'selected="selected"'
                           : '' }}>40</option>
                        <option value="60" {{ $per_page }} {{ isset($per_page) && $per_page==60 ? 'selected="selected"'
                           : '' }}>60</option>
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
                        <button class="{{ $stock ? $stock : 'in-stock'  }} {{$danger}} in-stock" type="button"
                           id="in-stock" onclick="inStockOutstock('instock'), handleSelectChange()"
                           value="{{$stock}}">{{$text}}</button>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
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
      <div class=" w-100 justify-content-center p-2 mt-3">
         {{ $products->appends(Request::all())->onEachSide(1)->links('pagination.front_custom_pagination') }}
      </div>
   </div>
</div>

<div class="row mobile-view">
   @if (!empty($product_views_chunks_mobile) && count($product_views_chunks_mobile) > 0)
   @php
       $product_views_chunks = null;
       $product_views_chunks = $product_views_chunks_mobile;
   @endphp
       @include('partials.recent_products_slider')
   @endif
</div>


{{-- pop up filter mobile --}}

<div class="modal fade" id="filter_model" tabindex="-1" aria-labelledby="filter_content" aria-hidden="true" data-bs-backdrop="static" style="left:2rem;">
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
                        <select class="form-select form-select-mbl" id="selected_cat_mbl" name="selected_cat">
                           <option class="filter_drop_down_mbl" value="">Select Category</option>
                           @foreach ($categories as $category)
                              @if($category->name != 'Not visable')
                              <option class="filter_drop_down_mbl" value="{{$category->id}}" {{ isset($category_id) &&
                                 $category_id==$category->id ? 'selected="selected"' : '' }}>{{ $category->name }}
                                 
                              </option>
                              @endif
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


<div class="card-body" id="buy_again_container">
    {{-- @if(!empty($frequent_products))
        <div class="row">
            <div class="col-md-12">
                <p class="buy_again_heading">Buy Again</p>
            </div>
        </div>
        @foreach($frequent_products as $frequent_product)
            @if(!empty($frequent_product))
                <div class="row mt-4 mb-3">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4 image-div image-div-account d-flex justify-content-center">
                                @if(!empty($frequent_product->product->images))
                                    <img src="{{ $frequent_product->product->images }}" alt="" class="buy_again_product_image">
                                @else
                                    <img src="{{ asset('/theme/img/image_not_available.png') }}" alt="" class="buy_again_product_image">
                                @endif
                            </div>
                            <div class="col-md-8 data-div data-div-account">
                                <div class="row">
                                    <div class="col-md-10">
                                        
                                        <p class="product_name mb-1">
                                            @if(!empty($frequent_product->product->name))
                                                <a class="product_name" id="prd_name_{{$frequent_product->product->id }}" href="{{ url('product-detail/' . $frequent_product->product->id . '/' . $frequent_product->product->options[0]->option_id . '/' . $frequent_product->product->slug) }}">{{ $frequent_product->product->name }}</a>
                                            @endif
                                        </p>
                                        
                                    </div>
                                    <div class="col-md-10">
                                    <p class="product_price mb-1">
                                            @if(!empty($frequent_product->price))
                                                ${{ number_format($frequent_product->price, 2) }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-10">
                                        <p class="category_name mb-1">Category:
                                            @if(!empty($frequent_product->product->categories))
                                            <a class="category_name" href="{{ url('products/' . $frequent_product->product->categories->id . '/' .$frequent_product->product->categories->slug) }}"> 
                                                {{$frequent_product->product->categories->name}}
                                            </a> 
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-4">
                            <div class="col-md-10">
                                <button type="button" class="buy_frequent_again_btn border-0 w-100 p-2" onclick="add_to_cart('{{ $frequent_product->product->id }}', '{{ $frequent_product->product->options[0]->option_id }}')">Add to Cart</button>
                            </div>
                            <div class="col-md-10 mt-4 border-div d-flex align-items-center align-self-center"></div>
                        </div>
                    </div>
                    
                </div>
            @endif
        @endforeach
    @else
        <div class="row">
            <div class="col-md-12">
                <p class="buy_again_heading">No Frequently buyed products to show</p>
            </div>
        </div>
    @endif
    {!!$frequent_products->links()!!} --}}
</div>
<div id="pagination-container-buy-again">
    <ul class="pagination mt-1" id="pagination-list-buy-again">
        <!-- Pagination links will be loaded here dynamically -->
    </ul>
</div>
<style>
    .pagination-item-buy-again {
        margin: 0 1px;
    }

    .pagination-link-buy-again {
        text-decoration: none;
        padding: 5px 10px;
        border: 1px solid #ccc;
        border-radius: 3px;
        color: #333;
    }

    .pagination-link-buy-again:hover {
        background-color: #f5f5f5;
    }

    .pagination-link-buy-again.active {
        background-color: #7BC533;
        color: #fff;
    }
</style>

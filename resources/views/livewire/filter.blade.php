<div class="container">
    <div class="row">
        <div class="col-md-12">             
            <input type="text"  class="form-control" placeholder="Search" wire:model="searchTerm" />
                <table class="table table-bordered" style="margin: 10px 0 10px 0;">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                     @foreach($products as $product)
                        @foreach($product->options as $option)
                            <input type="hidden" name="product_buy_list_stock" id="product_buy_list_stock_{{ $product->product_id }}" value="{{ $option->stockAvailable }}">
                            @php
                                $retail_price = 0;
                                $user_price_column = App\Helpers\UserHelper::getUserPriceColumnForBuyList();
                                foreach ($option->price as $price) {
                                    $retail_price = $price->$user_price_column;
                                    if ($retail_price == 0) {
                                        $retail_price = $price->sacramentoUSD;
                                    }
                                    if ($retail_price == 0) {
                                        $retail_price = $price->retailUSD;
                                    }
                                }
                            @endphp
                            <tr>
                                <td class="text-center">
                                    <img src="{{$option->image}}" height="50px" width="50px">
                                </td>
                                <td>{{$product->name}}</td>
                                <td>{{$product->code}}</td>
                                <td>{{number_format($retail_price , 2)}}</td>
                                <td>
                          
                                    @if (!empty($option->stockAvailable) && $option->stockAvailable > 0 )
                                        <button id="btn_{{ $product->product_id }}" data-retail-price="{{ $retail_price }}" class="btn btn-primary btn-add-to-cart">Add to List</button>
                                    @else
                                        <span class="btn btn-danger">Out of Stock</span>
                                    @endif
                                   
                                </td>
                                
                            </tr>
                        @endforeach
                    @endforeach
                </table>
                <div class="pagination-container">
                    {{$products->links()}}
                </div>
        </div>
    </div>
</div>





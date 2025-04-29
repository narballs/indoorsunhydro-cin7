<style>
	nav svg{
		max-height: 20px !important;
	}
</style>
<h3>{{$list->title}}</h3>
		<div class="row w-100 pl-2 pr-0">
			<div class="card col-md-12">
				<div class="card-body w-100" id="list">
					<div id="list_title">
						<h4></h4>
					</div>
					<input type="hidden" id="list_id" value="{{$list->id}}">
					<input type="hidden" id="is_update" value="1">
						<table id="product_list" class="table">
							<tr>
								<td style="width:373px !important">Product Title</td>
								<td>Code</td>
								<td>Image</td>
								<td>Price</td>
								<td>Quantity</td>
								<td>Subtotal</td>
								<td>Remove</td>
							</tr>
							<?php //dd($list);?>
						@foreach($list->list_products as $list_product)
					
                        @foreach($list_product->product->options as $option)
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
                            <tr id="product_row_{{ $list_product->product_id }}" class="product-row-{{ $list_product->product_id }} admin-buy-list">
								<input type="hidden" name="product_buy_list_stock" id="product_buy_list_stock_{{ $product->product_id }}" value="{{ $option->stockAvailable }}">
                                <td>
                                    {{$list_product->product->name}}
                                </td>
                                <td>
                                    {{$list_product->product->code}}
                                </td>
                                <td>
                                	<input type="hidden" id="option_id_{{$list_product->product_id}}" value="{{ $option->option_id}}">
                                	<img src="{{$option->image}}" alt="Product 1" class="img-circle img-size-32 mr-2">
                                </td>
                                <td>	
                                	$<span id="retail_price_{{ $retail_price }}"> ${{number_format($retail_price , 2 )}} </span></td>
                                <td>
									<input type="number" min="1"   id="quantity_{{ $list_product->product_id }}" value="{{$list_product->quantity}}" onchange="handleQuantity({{$list_product->product_id}})">
								</td>
                                <td>
									$<span id="subtotal_{{$list_product->product_id}}"> {{ number_format($retail_price * $list_product->quantity, 2) }} </span>
								</td>
                                <td>
                                       <a class="cursor-pointer delete" title="" data-toggle="tooltip" data-original-title="Delete">
            <i class="fas fa-trash-alt cursor-pointer" onclick="deleteProduct({{$list_product->product_id }})"></i>
        </a>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach

						</table>
						<div class="row">
							<div class="col-md-10 border-top">Grand Total</div>
							<div class="col-md-2 border-top">amount : <span id="grand_total">${{number_format($list_product->grand_total , 2)}}</span></div>
						</div>
						<div class="row">
							<div class="col-md-10 border-top"><button type="button" class="ms-2 btn btn-primary" onclick="generatList()">Update List</button>
							</div>
						</div>
				</div>
			</div>
		</div>
	
 


 

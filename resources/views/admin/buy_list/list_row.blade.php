<tr id="product_row_{{ $product->product_id }}" class="product-row-{{ $product->product_id }} admin-buy-list">
	<td>
		{{ $product->name }}
	</td>
	<td>
		@foreach($product->options as $option)
			<img src="{{$option->image}}" class="img-circle img-size-32 mr-2">
			<input type="hidden" id="option_id_{{$product->product_id}}" value="{{ $option->option_id}}">
		@endforeach
	</td>
	<td>
		$<span id="retail_price_{{ $product->product_id }}"> {{ $product->retail_price}} </span>
	</td>
	<td>
		<input type="number" min="1" id="quantity_{{ $product->product_id }}" value="1" onclick="handleQuantity({{$product->product_id}})">
	</td>
	<td>
		$<span id="subtotal_{{ $product->product_id }}"> {{ $product->retail_price * 1 }} </span>
	</td>
	<td>
        <a class="cursor-pointer delete" title="" data-toggle="tooltip" data-original-title="Delete">
        	<i class="fas fa-trash-alt cursor-pointer" onclick="deleteProduct({{$product->product_id }})"></i>
        </a>
	</td>
</tr>

<tr id="product_row_{{ $product->product_id }}" class="product-row-{{ $product->product_id }} admin-buy-list">
	<td>
		{{ $product->name }}
	</td>
	<td>
		@foreach($product->options as $option)
			<img src="{{$option->image}}" style="width:100px !important">
			<input type="text" id="option_id_{{$product->product_id}}" value="{{ $option->option_id}}">
		@endforeach
	</td>
	<td>
		$<span id="retail_price_{{ $product->product_id }}"> {{ number_format($product->retail_price, 2) }} </span>
	</td>
	<td>
		<input type="number" min="1" id="quantity_{{ $product->product_id }}" value="1" onclick="handleQuantity({{$product->product_id}})">
	</td>
	<td>
		$<span id="subtotal_{{ $product->product_id }}"> {{ number_format($product->retail_price * 1, 2) }} </span>
	</td>
	<td>
        <a class="cursor-pointer delete" title="" data-toggle="tooltip" data-original-title="Delete">
        	<i class="fas fa-trash-alt cursor-pointer" onclick="deleteProduct({{$product->product_id }})"></i>
        </a>
	</td>
</tr>

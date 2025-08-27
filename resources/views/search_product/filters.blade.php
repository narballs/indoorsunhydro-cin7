@foreach ($products as $key => $product)
@foreach($product->options->where('status', '!=', 'Disabled') as $option)
<?php $count ++; ?>
@include('product_row')
@endforeach
@endforeach
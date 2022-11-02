@foreach ($products as $key => $product)
            @foreach($product->options as $option)
                <?php $count ++; ?>
                @include('product_row')
            @endforeach
        @endforeach

        <div class="card-body table-responsive p-0">
            <h2>{{$list->title}}</h2>
            <table class="table table-striped table-valign-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list->list_products as $list_product)
                        @foreach($list_product->product->options as $option)
                            <tr>
                                <td>
                                    <img src="{{$option->image}}" alt="Product 1" class="img-circle img-size-32 mr-2">
                                    {{$list_product->product->name}}
                                </td>
                                <td>${{$list_product->product->retail_price}}</td>
                                <td class="jsutify-content-middle">
                                  <!--   <small class="text-success mr-1">
                                        <i class="fas fa-arrow-up"></i>
                                    12%
                                    </small> -->
                                    {{$list_product->quantity}}
                                </td>
                                <td>
                                   ${{$list_product->sub_total}}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr colspan="4"> <th colspan="3">Grand Total</th><td class="text-center"><h4>${{$list_product->grand_total}}</h4></td></tr>
                </tbody>
            </table>
        </div>
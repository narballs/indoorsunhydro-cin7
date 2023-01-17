<div class="container">
    <div class="row">
        <div class="col-md-12">             
            <input type="text"  class="form-control" placeholder="Search" wire:model="searchTerm" />
                <table class="table table-bordered" style="margin: 10px 0 10px 0;">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Action</th>
                    </tr>
                     @foreach($products as $product)
                        @foreach($product->options as $option)
                            <tr>
                                <td class="text-center">
                                    <img src="{{$option->image}}" height="50px" width="50px">
                                </td>
                                <td>{{$product->name}}</td>
                                <td>{{$product->code}}</td>
                                <td>
                                    <button id="btn_{{ $product->product_id }}" data-retail-price="{{ $product->retail_price }}" class="btn btn-primary btn-add-to-cart">Add to quote</button>
                                   
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
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
   
@stop

@section('content')
<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">{{$list->title}}</h3>
        <div class="card-tools">
            <a href="#" class="btn btn-tool btn-sm">
                <i class="fas fa-download"></i>
            </a>
            <a href="#" class="btn btn-tool btn-sm">
                <i class="fas fa-bars"></i>
            </a>
        </div>
    </div>
    <?php //dd($list->list_products->product);?>
    <div class="card-body table-responsive p-0">
        <table class="table table-striped table-valign-middle">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>More</th>
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
                                <a href="#" class="text-muted">
                                    <i class="fas fa-search"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
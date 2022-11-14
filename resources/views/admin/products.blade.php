@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
   
@stop

@section('content')

<?php //dd($products);exit;?>

    <div class="table-wrapper">
        <div class="table-title">
            <span><h1>Products</h1></span>
            <div class="row justify-content-between mb-2">
                <div class="col-sm-2">
                    <div class="search-box">
                        <a href="{{'customer/create'}}"><input type="button" value="Create New Product" class="form-control btn btn-primary" placeholder="Create New">
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div id="custom-search-input">
                        <div class="input-group col-md-12">
                          <!--   <span class="input-group-btn">
                                <button class="btn btn-info" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span> -->
                            <!-- <input type="text" class="form-control input-lg" id="search" placeholder="Search" onkeydown="customer_search()" /> -->
                            <form method="get" action="/admin/products" class="w-100">
                                <input type="text" class="form-control" id="search" name="search" placeholder="Search" value="{{ isset($search) ? $search : '' }}" />
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-body">

            <table class="table table-striped table-hover table-bordered table-customer" id="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name <i class="fa fa-sort"></i></th>
                        <th>Status<i class="fa fa-sort"></i></th>
                        <th>Code<i class="fa fa-sort"></i></th>
                        <th>Retail Price</th>
                    </tr>
                </thead>
                <tbody id="searched">
                    <?php $count = 0; ?>
                    @foreach ($products as $key => $product)
                        <?php $count ++; ?>
                        <tr>
						    <td>{{ $count }}</td>
						    <td>{{$product->name}}</td>
						    <td>
						    	{{$product->status}}
						    </td>
						    <td>{{$product->code}}</td>
						    <td>{{$product->retail_price}}</td>
						    <td>
								<a href="{{ url('admin/products/'.$product->id) }}" class="view" title="" data-toggle="tooltip" data-original-title="View"><i class="fas fa-eye"></i></a>
        						<a href="#" class="edit" title="" data-toggle="tooltip" data-original-title="Edit"><i class="fas fa-pen"></i></a>
        						<a href="#" class="delete" title="" data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-trash-alt"></i></a>
						    </td>
						</tr>
                  
                    @endforeach
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-10">
                  {{$products->appends(Request::all())->links()}}
                </div>
<!--                 <div class="col-md-2">
 -->    <!--                 <select name="per_page" id="per_page" onchange="perPage()">
                        <option value="10" {{ isset($perPage) && $perPage == 10 ? 'selected="selected"' : '' }}>10</option>
                        <option value="20" {{ isset($perPage) && $perPage == 20 ? 'selected="selected"' : '' }}>20</option>
                        <option value="30" {{ isset($perPage) && $perPage == 30 ? 'selected="selected"' : '' }}>30</option>
                        <option value="30">30</option>
                    </select>
                    
                </div> -->
            </div>
        </div>
@stop

@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

@stop

@section('content')
<div class="table-wrapper">
    <div class="table-title">
        <div class="row">
            <div class="col-md-12">
                <h2>Orders</h2>
            </div>
            <div class="col-md-2">
                <div class="search-box">
                    <a href="{{'order/create'}}"><input type="button" value="Create New Order"
                            class="form-control btn btn-primary" placeholder="Create New">
                    </a>
                </div>
            </div>
            <div class="col-md-6"></div>
            <div class="col-md-4">
                <div id="custom-search-input">
                    <div class="input-group col-md-12">
                        <span class="input-group-btn">
                            <button class="btn btn-info btn-lg" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                        <form method="get" action="/admin/orders">
                            <input type="text" class="form-control input-lg" id="search" name="search"
                                placeholder="Search" value="{{ isset($search) ? $search : '' }}" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-body mt-4">
        <table class="table table-striped table-hover table-bordered table-customer">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date Created <i class="fa fa-sort"></i></th>
                    <th>Created by <i class="fa fa-sort"></i></th>
                    <th>Reference</th>
                    <th>Order Total <i class="fa fa-sort"></i></th>
                    <th>Stage</th>
                    <th>Status <i class="fa fa-sort"></i></th>
                    <th>Fullfilment</th>
                    <th>Payment Term</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{$order->id}}</td>
                    <td>{{$order->createdDate}}</td>
                    <td>
                        @if($order->createdby)
                        {{$order->createdby->firstName}} {{$order->createdby->lastName}}</td>
                    @endif
                    <td>{{$order->reference}}</td>
                    <td>{{$order->total}}</td>
                    <td>{{$order->stage}}</td>
                    <td>{{$order->status}}</td>
                    <td>{{$order->apiApproval}}</td>
                    <td>{{$order->paymentTerms}}</td>
                    <td>
                        <a href="{{ url('admin/order-detail/'.$order->id) }}" class="view" title=""
                            data-toggle="tooltip" data-original-title="View"><i class="fas fa-eye"></i></a>
                        <a href="#" class="edit" title="" data-toggle="tooltip" data-original-title="Edit"><i
                                class="fas fa-pen"></i></a>
                        <a href="#" class="delete" title="" data-toggle="tooltip" data-original-title="Delete"><i
                                class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="col-md-12 mt-3">
            {{$orders->appends(Request::all())->links()}}
        </div>

    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
<style type="text/css">
    #custom-search-input {
        padding: 3px;
        border: solid 1px #E4E4E4;
        border-radius: 6px;
        background-color: #fff;
    }

    #custom-search-input input {
        border: 0;
        box-shadow: none;
    }

    #custom-search-input button {
        margin: 2px 0 0 0;
        background: none;
        box-shadow: none;
        border: 0;
        color: #666666;
        padding: 0 8px 0 10px;
        border-right: solid 1px #ccc;
    }

    #custom-search-input button:hover {
        border: 0;
        box-shadow: none;
        border-left: solid 1px #ccc;
    }

    #custom-search-input .glyphicon-search {
        font-size: 23px;
    }
</style>
@stop

@section('js')
<script>
    function perPage() {
            var search = $('#search').val();
            var activeCustomer = $('#active_customer').val();

            if(perPage !=''){
                var basic_url = 'customers?perPage='+perPage+'&search='+search;
            }

           if (activeCustomer != '') {
               basic_url = basic_url+`&active-customer=${activeCustomer}`;
            }

            window.location.href = basic_url;
        } 
</script>
@stop
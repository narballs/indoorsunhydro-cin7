@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
<div class="table-wrapper">
    <div class="table-title">
        <div class="row">
            <div class="col-sm-8">
                <h2>Orders</h2>
            </div>
            <div class="col-sm-4">
                <div class="search-box">
                    <i class="material-icons"></i>
                    <input type="text" class="form-control" placeholder="Search…">
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2 mb-5">
        <div class="search-box">
            <a href="{{'order/create'}}"><input type="button" value="Create New Order"
                    class="form-control btn btn-primary" placeholder="Create New">
            </a>
        </div>
    </div>
    <table class="table table-striped table-hover table-bordered">
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
            <?php //dd($orders) ?>
            @foreach($orders as $key=>$order)
            <tr>
                <td>{{$key+1}}</td>
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
                    <a href="{{ url('admin/order-detail/'.$order->id) }}" class="view" title="" data-toggle="tooltip"
                        data-original-title="View"><i class="fas fa-eye"></i></a>
                    <a href="#" class="edit" title="" data-toggle="tooltip" data-original-title="Edit"><i
                            class="fas fa-pen"></i></a>
                    <a href="#" class="delete" title="" data-toggle="tooltip" data-original-title="Delete"><i
                            class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="clearfix">
        <div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
        <ul class="pagination">
            <li class="page-item disabled"><a href="#"><i class="fa fa-angle-double-left"></i></a></li>
            <li class="page-item"><a href="#" class="page-link">1</a></li>
            <li class="page-item"><a href="#" class="page-link">2</a></li>
            <li class="page-item active"><a href="#" class="page-link">3</a></li>
            <li class="page-item"><a href="#" class="page-link">4</a></li>
            <li class="page-item"><a href="#" class="page-link">5</a></li>
            <li class="page-item"><a href="#" class="page-link"><i class="fa fa-angle-double-right"></i></a></li>
        </ul>
    </div>
</div>

@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    console.log('Hi!'); 
</script>
@stop
@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
@stop
@section('content')
    <div class="table-wrapper">
        {{-- <div class="table-title">
            <span>
                <h1>Buy Lists</h1>
            </span>
            <div class="row justify-content-between mb-2">
                <div class="col-sm-2">
                    <div class="search-box">
                        <a href=""><input type="button" value="Create New Buy List"
                                class="form-control btn btn-primary" placeholder="Create New">
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div id="custom-search-input">
                        <div class="input-group col-md-12">

                            <form method="get" action="/admin/products" class="w-100">
                                <input type="text" class="form-control" id="search" name="search"
                                    placeholder="Search" value="{{ isset($search) ? $search : '' }}" />
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row mb-3">
            <div class="col-md-12 mt-3">
                <div class="row">
                    <div class="col-md-10">
                        <p class="order_heading">
                            Buy Lists
                        </p>
                        <p class="order_description">
                            In the buy list section, you can review and manage all items with their details. You can view
                            and edit information such as item <br> IDs, item name, description, price, and availability.
                            Access
                            to this area is restricted to administrators and team leaders. Any <br> changes you make will
                            require
                            approval after being verified for accuracy.
                        </p>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ 'buy-list/create ' }}" type="button" class="btn create-new-order-btn">
                            + Create new product
                        </a>
                    </div>
                </div>
                <div class="row p-3" style="background: #F3F3F3; border-radius: 8.8914px;">
                    <div class="col-md-8 order-search">
                        <div class="form-group has-search ">
                            <span class="fa fa-search form-control-feedback"></span>
                            <form method="get" action="/admin/products" class="mb-2">
                                <input type="text" class="form-control border-0" id="search" name="search"
                                    placeholder="Search for order ID, customer, order, status or something..."
                                    value="{{ isset($search) ? $search : '' }}" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-body">

            <table class="table  table-hover table-buylist" id="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title<i class="fa fa-sort"></i></th>
                        <th>Status<i class="fa fa-sort"></i></th>
                        <th>Description<i class="fa fa-sort"></i></th>
                        <th>Action<i class="fa fa-sort"></i></th>
                    </tr>
                </thead>
                <tbody id="searched">
                    @foreach ($buylists as $buylist)
                        <tr id="row-{{ $buylist->id }}" class="buylist-row">
                            <td>{{ $buylist->id }}</td>
                            <td class="buylist_title">
                                <span class="buy-list-title">{{ $buylist->title }}</span>
                            </td>
                            <td>{{ $buylist->status }}</td>
                            <td>{{ $buylist->description }}</td>
                            <td class="buylist_action">
                                <a href="buy-list/{{ $buylist->id }}"data-toggle="tooltip" data-original-title="View"
                                    class="a_class">
                                    <i class="icon-style fas fa-eye fa-border i_class"></i>
                                </a>
                                <a href="{{ route('buy-list.create', ['id' => $buylist->id]) }}"data-toggle="tooltip"
                                    class="buylist" data-original-title="Edit"><i
                                        class="icon-style fas fa-edit fa-border"></i>
                                </a>
                                <a href="#" class="delete" title="" data-toggle="tooltip"
                                    data-original-title="Delete" class="buylist">
                                    <i class="icon-style fas fa-trash-alt fa-border "></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12 mt-3 border-top">
                    {{ $buylists->appends(Request::all())->links() }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">

    <style>
        .text-successs {
            color: #7CC633 !important;
            font-family: 'Poppins', sans-serif !important;
        }

        .badge-success {
            color: #fff;
            /* background-color: #28a745; */
            background: rgba(124, 198, 51, 0.2);
            color: #7CC633;
            padding: 7px !important;
        }

        .badge-warning {
            color: #1f2d3d;
            background-color: #fce9a9;
            color: #ffc107 !important;
            padding: 5px;
        }

        .badge-danger {
            color: #fff;
            background-color: #f1abb2;
            color: #f14f4f;
            padding: 6px !important;
        }
    </style>
@stop

@section('js')
    <script>
        $('.buylist-row').hover(function() {
            let id = $(this).attr('id');
            children = $(this).children('.buylist_title').children('span').addClass('text-successs');
            let tet = $(this).children('.buylist_action').children('a');
            let get_class = tet.each(function(index, value) {
                let test = tet[index].children[0];
                test.classList.add('bg-icon');
            });
        });


        $('.buylist-row').mouseleave(function() {
            let id = $(this).attr('id');
            children = $(this).children('.buylist_title').children('span').removeClass('text-successs');
            let tet = $(this).children('.buylist_action').children('a');
            let get_class = tet.each(function(index, value) {
                let test = tet[index].children[0];
                test.classList.remove('bg-icon');
            });
        });
    </script>
@stop

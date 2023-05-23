@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
@stop

@section('content')
    <div class="table-wrapper">
        <div class="card-body mt-2">
            {{-- <div class="table-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h2>Suppliers</h2>
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
                    <a href="{{ 'order/create' }}"><input type="button" value="Create New Supplier"
                            class="form-control btn btn-primary" placeholder="Create New">
                    </a>
                </div>
            </div> --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-10">
                            <p class="order_heading">
                                Suppliers
                            </p>
                            <p class="order_description">
                                In the Suppliers section, you can review and manage all suppliers with their details. You
                                can view and edit information such as <br> supplier IDs, supplier name, address, contact
                                information, and products supplied. Access to this area is restricted to<br> administrators
                                and
                                team leaders. Any changes you make will require approval after being verified for accuracy.
                            </p>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn create-new-order-btn">
                                + Create New Order
                            </button>
                        </div>
                    </div>
                    <div class="row p-3 search_row_admin-interface">
                        <div class="col-md-12 order-search">
                            <div class="form-group has-search ">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="/admin/contact" class="mb-2">
                                    <input type="text" class="form-control border-0" id="search" name="search"
                                        placeholder="Search for order ID, customer, order, status or something..."
                                        value="{{ isset($search) ? $search : '' }}" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table  table-hover table-suppliers">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name <i class="fa fa-sort"></i></th>
                        <th>Status <i class="fa fa-sort"></i></th>
                        <th>Price Tier<i class="fa fa-sort"></i></th>
                        <th>Company <i class="fa fa-sort"></i></th>
                        <th>Notes<i class="fa fa-sort"></i></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php //dd($orders)
                    ?>
                    @foreach ($contacts as $key => $contact)
                        <tr id="row-{{ $contact->id }}" class="supplier-row">
                            <td>{{ $key + 1 }}</td>
                            <td class="supplier_name">
                                <span>
                                    {{ $contact->name }}
                                </span>
                            </td>
                            <td class="supplier-status">
                                @if ($contact->status == '1')
                                    <span class="badge badge-success w-100 supplier_status_1">Active</span>
                                @else
                                    <span class="badge badge-warning w-100 supplier_status_0">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $contact->price_tier }}</td>
                            <td>{{ $contact->company }}</td>
                            <td>{{ $contact->notes }}</td>
                            <td class="supplier_action">
                                <a href="{{ url('admin/order-detail/' . $contact->id) }}" class="view a_class"
                                    title="" data-toggle="tooltip" data-original-title="View">
                                    <i class="icon-style  fas fa-eye fa-border i_class"></i>
                                </a>
                                <a href="#" class="edit a_class" title="" data-toggle="tooltip"
                                    data-original-title="Edit"><i class="icon-style fas fa-edit fa-border "></i>
                                </a>
                                <a href="#" class="delete a_class" title="" data-toggle="tooltip"
                                    data-original-title="Delete"><i class="icon-style fas fa-trash-alt fa-border "></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
            background: rgba(124, 198, 51, 0.2);
            color: #7CC633 !important;
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
        $('.supplier-row').hover(function() {
            let id = $(this).attr('id');
            children = $(this).children('.supplier_name').children('span').addClass('text-successs');


            bg_success = $(this).children('.supplier-status').children('.supplier_status_1').addClass(
                'background-success');
            bg_success = $(this).children('.supplier-status').children('.supplier_status_0').addClass(
                'background-warning');
            bg_success = $(this).children('.supplier-status').children('.supplier_status_2').addClass(
                'background-danger');


            let tet = $(this).children('.supplier_action').children('a');
            let get_class = tet.each(function(index, value) {
                let test = tet[index].children[0];
                test.classList.add('bg-icon');
            });
        });


        $('.supplier-row').mouseleave(function() {
            let id = $(this).attr('id');
            children = $(this).children('.supplier_name').children('span').removeClass('text-successs');

            bg_success = $(this).children('.supplier-status').children('.supplier_status_1').removeClass(
                'background-success');
            bg_success = $(this).children('.supplier-status').children('.supplier_status_0').removeClass(
                'background-warning');
            bg_success = $(this).children('.supplier-status').children('.supplier_status_2').removeClass(
                'background-danger');

            let tet = $(this).children('.supplier_action').children('a');
            let get_class = tet.each(function(index, value) {
                let test = tet[index].children[0];
                test.classList.remove('bg-icon');
            });
        });
    </script>
@stop

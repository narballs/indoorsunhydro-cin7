@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content')
    <div class="table-wrapper">
        <div class="card-body mt-2">
            <div class="table-title">
                <div class="row mb-3 ms-5">
                    <div class="col-md-12 mt-3">
                        <div class="row">
                            <div class="col-md-10">
                                <p class="order_heading">
                                    Shipping Methods
                                </p>
                                <p class="order_description">
                                    In the Shipping methods section, you can review and manage all delivery options with
                                    their
                                    details. You can view and edit <br> information such as delivery method IDs, method
                                    names,
                                    estimated delivery time, and cost. Access to this area is restricted to <br>
                                    administrators
                                    and
                                    team leaders. Any changes you make will require approval after being verified for
                                    accuracy.
                                </p>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ 'shipping-methods/create' }}" class="btn create-new-order-btn">
                                    + Create new product
                                </a>
                            </div>
                        </div>
                        <div class="row p-3 search_row_admin-interface">
                            <div class="col-md-12 order-search">
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
            </div>
            <table class="table table-hover table-shipping-method">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name <i class="fa fa-sort"></i></th>
                        <th>Cost <i class="fa fa-sort"></i></th>
                        <th>Status <i class="fa fa-sort"></i></th>
                        <th>Action <i class="fa fa-sort"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php //dd($orders)
                    ?>
                    @foreach ($shippingmethods as $key => $shippingmethod)
                        <tr id="row-{{ $shippingmethod->id }}" class="shipping-method-row">
                            <td>{{ $key + 1 }}</td>
                            <td class="shipping_name">
                                <span>{{ $shippingmethod->title }}</span>
                            </td>
                            <td>{{ $shippingmethod->cost }}</td>
                            @if ($shippingmethod->status == 1)
                                <td>
                                    Enabled
                                </td>
                            @else
                                <td>
                                    Disabled
                                </td>
                            @endif
                            <td class="shipping_action">
                                <a href="{{ url('admin/shipping-details/') }}" class="view a_class" title=""
                                    data-toggle="tooltip" data-original-title="View"><i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ url('admin/shipping-method/' . $shippingmethod->id) }}" class="edit a_class"
                                    title="" data-toggle="tooltip" data-original-title="Edit"><i
                                        class="fas fa-pen"></i>
                                </a>
                                <a href="{{ url('admin/shipping-method/delete/' . $shippingmethod->id) }}"
                                    class="delete a_class" title="" data-toggle="tooltip"
                                    data-original-title="Delete"><i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12 mt-3 border-top">
                    {{ $shippingmethods->appends(Request::all())->links() }}
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
        $('.shipping-method-row').hover(function() {
            let id = $(this).attr('id');
            children = $(this).children('.shipping_name').children('span').addClass('text-successs');
            let tet = $(this).children('.shipping_action').children('a');
            let get_class = tet.each(function(index, value) {
                let test = tet[index].children[0];
                test.classList.add('bg-icon');
            });
        });


        $('.shipping-method-row').mouseleave(function() {
            let id = $(this).attr('id');
            children = $(this).children('.shipping_name').children('span').removeClass('text-successs');
            let tet = $(this).children('.shipping_action').children('a');
            let get_class = tet.each(function(index, value) {
                let test = tet[index].children[0];
                test.classList.remove('bg-icon');
            });
        });
    </script>
@stop

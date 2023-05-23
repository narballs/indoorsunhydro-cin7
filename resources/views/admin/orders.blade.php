@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content')
    <div class="table-wrapper">
        <div class="card-body mt-2">
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-10">
                            <p class="order_heading">
                                Orders
                            </p>
                            <p class="order_description">
                                In the order details section, you can review and manage all orders with their details. You
                                can view and edit many information <br> such as IDs of all orders, ordered product, order
                                date,
                                price and order status. Access to this area is limited. Only administrators <br>and team
                                leaders
                                can reach. The changes you make will be approved after they are checked.
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
                                <form method="get" action="/admin/order" class="mb-2">
                                    <input type="text" class="form-control border-0" id="search" name="search"
                                        placeholder="Search for order ID, customer, order, status or something..."
                                        value="{{ isset($search) ? $search : '' }}" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-hover table-customer">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date Created <i class="fa fa-sort"></i></th>
                        <th>Created by <i class="fa fa-sort"></i></th>
                        <th>Order Submitter Email <i class="fa fa-sort"></i></th>
                        <th>Primary Account Email <i class="fa fa-sort"></i></th>
                        <th>Reference <i class="fa fa-sort"></i></th>
                        <th>Order Total <i class="fa fa-sort"></i></th>
                        <th>Company Name <i class="fa fa-sort"></i> </th>
                        {{-- <th>Status <i class="fa fa-sort"></i></th> --}}
                        <th>Stage <i class="fa fa-sort"></i></th>
                        <th>Payment Term <i class="fa fa-sort"></i></th>
                        <th>Actions <i class="fa fa-sort"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        @if (empty($order))
                            <tr>
                                <td>
                                    <div class="alert alert-danger">No Orders Found</div>
                                </td>
                            </tr>
                        @else
                            <tr id="row-{{ $order->id }}" class="order-row">
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('F ' . 'd, Y, ' . 'g:i A') }}</td>
                                <td class="created_by toggleClass">
                                    @if (!empty($order->primaryId) && !empty($order->primary_contact))
                                        <span title="Secondary Contact"
                                            class="created_by_order">{{ $order->primary_contact->firstName }}
                                            {{ $order->primary_contact->lastName }}</span>
                                    @elseif (!empty($order->secondaryId) && !empty($order->secondary_contact))
                                        <span title="Secondary Contact"
                                            class="created_by_order">{{ $order->secondary_contact->firstName }}
                                            {{ $order->secondary_contact->lastName }}</span>
                                    @elseif (!empty($order->contact))
                                        {{ $order->contact->firstName }} {{ $order->contact->lastName }}
                                    @endif
                                </td>
                                <td>
                                    @if (!empty($order->primaryId) && !empty($order->primary_contact))
                                        <span title="Secondary Contact">{{ $order->primary_contact->email }}</span>
                                    @elseif (!empty($order->secondaryId) && !empty($order->secondary_contact))
                                        <span title="Secondary Contact">{{ $order->secondary_contact->email }}</span>
                                    @elseif (!empty($order->contact))
                                        {{ $order->contact->email }} {{ $order->contact->lastName }}
                                    @endif
                                </td>
                                <td>
                                    @if ($order->contact)
                                        {{ $order->contact->email }}
                                    @endif
                                </td>
                                <td>{{ $order->reference }}</td>
                                <td class="created_by_order_total">${{ number_format($order->total, 2) }}</td>
                                <td>
                                    @if ($order->contact)
                                        @if ($order->contact->company)
                                            {{ $order->contact->company }}
                                        @endif
                                    @endif
                                </td>
                                <td class="is-approved">
                                    @if ($order->isApproved == 0)
                                        <span class="badge badge-warning w-100 is_approded_0">New</span>
                                    @elseif ($order->isApproved == 1)
                                        <span class="badge badge-success w-100 is_approded_1">Fullfilled</span>
                                    @elseif ($order->isApproved == 2)
                                        <span class="badge badge-danger w-100 is_approded_2">Cancelled</span>
                                    @endif
                                </td>
                                <td>{{ $order->paymentTerms }}</td>
                                <td class="created_by toggleClass">
                                    <a href="{{ url('admin/order-detail/' . $order->id) }}" class="view a_class"
                                        title="" data-toggle="tooltip" data-original-title="View">
                                        <i class="icon-style  fas fa-eye fa-border i_class"></i>
                                    </a>
                                    <a href="#" class="edit a_class" title="" data-toggle="tooltip"
                                        data-original-title="Edit"><i class="icon-style fas fa-edit fa-border "></i></a>
                                    <a href="#" class="delete deleteIcon a_class" id="{{ $order->id }}"
                                        title="" data-toggle="tooltip" data-original-title="Delete"><i
                                            class="icon-style fas fa-trash-alt fa-border "></i></a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <div class="col-md-12 mt-3 border-top">
                {{ $orders->appends(Request::all())->links() }}
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
    <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet">

    <style type="text/css">
        .input-group-btn {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #custom-search-input {
            padding: 3px;
            border: solid 1px #E4E4E4;
            border-radius: 6px;
            background-color: #fff;
        }

        #custom-search-input input {
            border: 0;
            box-shadow: none;
            padding-top: 22px !important;
            width: 329px !important;
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
        // toggle hover on rows in loop 

        $('.order-row').hover(function() {
            let id = $(this).attr('id');
            children = $(this).children('.created_by').children('span').addClass('text-successs');
            bg_success = $(this).children('.is-approved').children('.is_approded_1').addClass('background-success');
            bg_success = $(this).children('.is-approved').children('.is_approded_0').addClass('background-warning');
            bg_success = $(this).children('.is-approved').children('.is_approded_2').addClass('background-danger');
            let tet = $(this).children('.created_by').children('a');
            let get_class = tet.each(function(index, value) {
                let test = tet[index].children[0];
                test.classList.add('bg-icon');
            });
        });


        $('.order-row').mouseleave(function() {
            let id = $(this).attr('id');
            children = $(this).children('.created_by').children('span').removeClass('text-successs');
            bg_success = $(this).children('.is-approved').children('.is_approded_1').removeClass(
                'background-success');
            bg_success = $(this).children('.is-approved').children('.is_approded_0').removeClass(
                'background-warning');
            bg_success = $(this).children('.is-approved').children('.is_approded_2').removeClass(
                'background-danger');
            let tet = $(this).children('.created_by').children('a');
            let get_class = tet.each(function(index, value) {
                let test = tet[index].children[0];
                test.classList.remove('bg-icon');
            });
        });


        function perPage() {
            var search = $('#search').val();
            var activeCustomer = $('#active_customer').val();

            if (perPage != '') {
                var basic_url = 'customers?perPage=' + perPage + '&search=' + search;
            }

            if (activeCustomer != '') {
                basic_url = basic_url + `&active-customer=${activeCustomer}`;
            }

            window.location.href = basic_url;
        }
    </script>
    <script>
        // delete employee ajax request
        $(document).on('click', '.deleteIcon', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            let csrf = '{{ csrf_token() }}';
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't delete this order!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '{{ route('admin.order.delete') }}',
                        method: 'delete',
                        data: {
                            id: id,
                            _token: csrf
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'Your order has been deleted.',
                                'success'
                            )
                            $('#row-' + id).remove();
                        }
                    });
                }
            })
        });
    </script>
@stop
@section('plugins.Sweetalert2', true);

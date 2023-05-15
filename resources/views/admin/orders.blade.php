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
                        <a href="{{ 'order/create' }}"><input type="button" value="Create New Order"
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
                            <tr id="row-{{ $order->id }}">
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('F ' . 'd, Y, ' . 'g:i A') }}</td>
                                <td>
                                    @if (!empty($order->primaryId) && !empty($order->primary_contact))
                                        <span title="Secondary Contact">{{ $order->primary_contact->firstName }}
                                            {{ $order->primary_contact->lastName }}</span>
                                    @elseif (!empty($order->secondaryId) && !empty($order->secondary_contact))
                                        <span title="Secondary Contact">{{ $order->secondary_contact->firstName }}
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
                                <td>${{ $order->total }}</td>
                                <td>
                                    @if ($order->contact)
                                        @if ($order->contact->company)
                                            {{ $order->contact->company }}
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if ($order->isApproved == 0)
                                        <span class="badge badge-warning">New</span>
                                    @elseif ($order->isApproved == 1)
                                        <span class="badge badge-success">Fullfilled</span>
                                    @elseif ($order->isApproved == 2)
                                        <span class="badge badge-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td>{{ $order->paymentTerms }}</td>
                                <td>
                                    <a href="{{ url('admin/order-detail/' . $order->id) }}" class="view" title=""
                                        data-toggle="tooltip" data-original-title="View"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="edit" title="" data-toggle="tooltip"
                                        data-original-title="Edit"><i class="fas fa-pen"></i></a>
                                    <a href="#" class="delete deleteIcon" id="{{ $order->id }}" title=""
                                        data-toggle="tooltip" data-original-title="Delete"><i
                                            class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <div class="col-md-12 mt-3">
                {{ $orders->appends(Request::all())->links() }}
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

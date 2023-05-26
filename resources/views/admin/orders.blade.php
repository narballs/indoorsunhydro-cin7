@extends('adminlte::page')
@section('title', 'Dashboard')

@section('content')
    <div class="table-wrapper">
        <div class="card-body p-0">
            <div class="row border-bottom">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-10">
                            <p class="order_heading">
                                Orders
                            </p>
                        </div>
                        <div class="col-md-2 pt-3">
                            <button type="button" class="btn create-new-order-btn">
                                Create New Order +
                            </button>
                        </div>
                    </div>
                    <div class="row search_row_admin-interface">
                        <div class="col-md-4 order-search">
                            <div class="has-search ">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="/admin/orders" class="mb-2">
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search for order ID, customer, order, status or something..."
                                        value="{{ isset($search) ? $search : '' }}" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table  table-customer mt-4 border rounded-2 mb-5">
                <thead>
                    <tr class="table-header-background">
                        <th> <input type="checkbox" name="select_all" class="selec_all" id="selectAll"> #</th>
                        <th>Created by </th>
                        <th>Reference </th>
                        <th>Date Created </th>
                        <th>Primary Account Email </th>
                        <th>Order Total </th>
                        <th>Company Name </th>
                        {{-- <th>Status </th> --}}
                        <th>Stage </th>
                        <th>Payment Term </th>
                        <th>Actions </th>
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
                            <tr id="row-{{ $order->id }}" class="order-row border-bottom">
                                <td class="created_by_order td_padding_row check">
                                    <input type="checkbox" name="test" class="checkbox-table">
                                    {{ $order->id }}
                                </td>
                                <td class="created_by toggleClass">
                                    @if (!empty($order->primaryId) && !empty($order->primary_contact))
                                        <span title="Secondary Contact"
                                            class="created_by_order">{{ $order->primary_contact->firstName }}
                                            {{ $order->primary_contact->lastName }}</span><br>
                                    @elseif (!empty($order->secondaryId) && !empty($order->secondary_contact))
                                        <span title="Secondary Contact"
                                            class="created_by_order">{{ $order->secondary_contact->firstName }}
                                            {{ $order->secondary_contact->lastName }}</span><br>
                                    @elseif (!empty($order->contact))
                                        {{ $order->contact->firstName }} {{ $order->contact->lastName }}
                                    @endif
                                    <span class="order_submited_email">
                                        @if (!empty($order->primaryId) && !empty($order->primary_contact))
                                            <span title="Secondary Contact">{{ $order->primary_contact->email }}</span>
                                        @elseif (!empty($order->secondaryId) && !empty($order->secondary_contact))
                                            <span title="Secondary Contact">{{ $order->secondary_contact->email }}</span>
                                        @elseif (!empty($order->contact))
                                            {{ $order->contact->email }} {{ $order->contact->lastName }}
                                        @endif
                                    </span>
                                </td>
                                <td class="td_padding_row">{{ $order->reference }}</td>
                                <td class="td_padding_row">
                                    {{ $order->created_at->format('d/m/Y') }}
                                </td>
                                <td class="td_padding_row">
                                    @if ($order->contact)
                                        {{ $order->contact->email }}
                                    @endif
                                </td>

                                <td class="created_by_order_total td_padding_row">
                                    ${{ number_format($order->total, 2) }}</td>
                                <td class="td_padding_row">
                                    @if ($order->contact)
                                        @if ($order->contact->company)
                                            {{ $order->contact->company }}
                                        @endif
                                    @endif
                                </td>
                                <td class="is-approved td_padding_row">
                                    @if ($order->isApproved == 0)
                                        <span class="badge badge-warning w-50 is_approded_0">New</span>
                                    @elseif ($order->isApproved == 1)
                                        <span class="badge badge-success is_approded_1">Fullfilled</span>
                                    @elseif ($order->isApproved == 2)
                                        <span class="badge badge-danger is_approded_2">Cancelled</span>
                                    @endif
                                </td>
                                <td class="td_padding_row">{{ $order->paymentTerms }}</td>
                                <td class="created_by toggleClass td_padding_row">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-h" style="color: #CBCBCB !important;"></i>
                                        </button>
                                        <div class="dropdown-menu dropdonwn_menu">
                                            <a class="dropdown-item" href="{{ url('admin/order-detail/' . $order->id) }}"
                                                class="view a_class" title="" data-toggle="tooltip"
                                                data-original-title="View">Previews
                                            </a>
                                            <a class="dropdown-item delete deleteIcon a_class" href="#" class=""
                                                id="{{ $order->id }}" title="" data-toggle="tooltip"
                                                data-original-title="Delete">Delete
                                            </a>
                                            <a class="dropdown-item"href="#" class="edit a_class" title=""
                                                data-toggle="tooltip" data-original-title="Edit">Edit
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="10">
                           
                                {{-- {{ $orders->appends(Request::all())->links() }} --}}
                                {{ $orders->links('pagination.custom_pagination') }}
                            
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
    <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet">

    <style type="text/css">

        
        .text-successs {
            color: #7CC633 !important;
            font-family: 'Poppins', sans-serif !important;
        }

        .badge-success {
            color: #fff;
            background: rgba(124, 198, 51, 0.2);
            color: #319701 !important;
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
            background-color: rgba(220, 78, 65, 0.12);
            color: #DC4E41;
            padding: 6px !important;
        }
    </style>
@stop

@section('js')
    <script>
        // toggle hover on rows in loop 
        $('.order-row-none').hover(function() {
            let id = $(this).attr('id');
            children = $(this).children('.created_by').children('span').addClass('text-successs');
            bg_success = $(this).children('.is-approved').children('.is_approded_1').addClass(
                'background-success');
            bg_success = $(this).children('.is-approved').children('.is_approded_0').addClass(
                'background-warning');
            bg_success = $(this).children('.is-approved').children('.is_approded_2').addClass(
                'background-danger');
            let tet = $(this).children('.created_by').children('a');
            let get_class = tet.each(function(index, value) {
                let test = tet[index].children[0];
                test.classList.add('bg-icon');
            });
        });


        $('.order-row-none').mouseleave(function() {
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

        // select all checkbox by click
        $(document).on('click','#selectAll' , function(e){
            var table = $(e.target).closest('table');
            $('td input:checkbox',table).prop('checked',this.checked);

            // if(this.checked) {
            //     // Iterate each checkbox
            //     $(':checkbox').each(function() {
            //         this.checked = true;
            //     });
            // } else {
            //     $(':checkbox').each(function() {
            //         this.checked = false;
            //     });
            // }
        });
        
    </script>
@stop
@section('plugins.Sweetalert2', true)

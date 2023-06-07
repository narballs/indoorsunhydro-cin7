@extends('adminlte::page')
@section('title', 'Dashboard')

@section('content')
    <div class="table-wrapper">
        <div class="card-body product_secion_main_body">
            <div class="row border-bottom product_section_header">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2">
                            <p class="order_heading">
                                Orders
                            </p>
                        </div>
                        <div class="col-md-8">
                            <div class="progress border d-none w-50 mx-auto" id="progress-bar">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                                    role="progressbar" aria-valuenow="100" aria-valuemin="" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex justify-content-end create_bnt">
                            <button type="button" class="btn create-new-order-btn">
                                Create New Order +
                            </button>
                        </div>
                    </div>
                    <div class="row search_row_admin-interface">
                        <div class="col-md-2 order-search">
                            <div class="has-search ">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="/admin/orders" class="mb-2">
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search" value="{{ isset($search) ? $search : '' }}" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body product_table_body">
                <div class="col-md-12 p-0">
                    <table class="table border table-customer mb-5">
                        <thead>
                            <tr class="table-header-background">
                                <td class="d-flex table-row-item">
                                    <span class="tabel-checkbox">
                                        <input type="checkbox" name="test" class="checkbox-table" id="selectAll">
                                    </span>
                                    <span class="table-row-heading">
                                        <i class="fas fa-arrow-up"></i>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Created By</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Reference</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Date Created</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Primary Account Email</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Order Total </span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Company Name </span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Stage</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Payment Term</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Actions</span>
                                </td>
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
                                        <td class="d-flex table-items">
                                            <span class="tabel-checkbox">
                                                <input type="checkbox" name="test" class="checkbox-table">
                                            </span>
                                            <span class="table-row-heading">
                                                {{ $order->id }}
                                            </span>
                                        </td>
                                        <td class="created_by toggleClass pb-0 pt-3">
                                            @if (!empty($order->primaryId) && !empty($order->primary_contact))
                                                <span title="Secondary Contact" class="created_by_order">
                                                    {{ $order->primary_contact->firstName }}
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
                                                    <span
                                                        title="Secondary Contact">{{ $order->primary_contact->email }}</span>
                                                @elseif (!empty($order->secondaryId) && !empty($order->secondary_contact))
                                                    <span
                                                        title="Secondary Contact">{{ $order->secondary_contact->email }}</span>
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
                                            @if ($order->isApproved == 1 && $order->isVoid == 1)
                                                <span class="badge badge-secondary  is_approded_0">Void</span>
                                            @elseif ($order->isApproved == 0 && $order->isVoid == 0)
                                                <span class="badge badge-warning  is_approded_0">New</span>
                                            @elseif ($order->isApproved == 1)
                                                <span class="badge badge-success is_approded_1">Fullfilled</span>
                                            @elseif ($order->isApproved == 2)
                                                <span class="badge badge-danger is_approded_2">Cancelled</span>
                                            @endif
                                        </td>
                                        <td class="td_padding_row">{{ $order->paymentTerms }}</td>
                                        <td class="created_by toggleClass td_padding_row">
                                            <div class="btn-group">
                                                <button type="button" class="btn p-0 btn-white dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-h" style="color: #CBCBCB !important;"></i>
                                                </button>
                                                <div class="dropdown-menu dropdonwn_menu">
                                                    <a class="dropdown-item"
                                                        href="{{ url('admin/order-detail/' . $order->id) }}"
                                                        class="view a_class" title="" data-toggle="tooltip"
                                                        data-original-title="View">Previews
                                                    </a>
                                                    <a class="dropdown-item delete deleteIcon a_class" href="#"
                                                        class="" id="{{ $order->id }}" title=""
                                                        data-toggle="tooltip" data-original-title="Delete">Delete
                                                    </a>
                                                    <a class="dropdown-item"href="#" class="edit a_class"
                                                        title="" data-toggle="tooltip"
                                                        data-original-title="Edit">Edit
                                                    </a>
                                                    <form>
                                                        @csrf
                                                        @if ($order->isApproved == 1 && $order->isVoid == 0)
                                                            <a class="dropdown-item disabled bg_success" type="button"
                                                                class="edit a_class" title="" data-toggle="tooltip"
                                                                data-original-title="Edit">Fulfill
                                                                Order
                                                            </a>
                                                        @elseif ($order->isApproved == 2 && $order->isVoid == 0)
                                                            <a class="dropdown-item disabled bg_danger" type="button"
                                                                class="edit a_class" title="" data-toggle="tooltip"
                                                                data-original-title="Edit">Fulfill
                                                                Order
                                                            </a>
                                                        @elseif ($order->isApproved == 1 && $order->isVoid == 1)
                                                            <a class="dropdown-item disabled bg_secondary" type="button"
                                                                class="edit a_class" title="" data-toggle="tooltip"
                                                                data-original-title="Edit">Void
                                                            </a>
                                                        @else
                                                            <a class="dropdown-item" type="button" class="edit a_class"
                                                                title="" data-toggle="tooltip"
                                                                data-original-title="Edit"
                                                                onclick="fullFillOrder()">Fulfill
                                                                Order
                                                            </a>
                                                            <input type="hidden" value="{{ $order->id }}"
                                                                id="order_id">
                                                        @endif
                                                        @if ($order->isApproved == 2 && $order->isVoid == 0)
                                                            <a class="dropdown-item disabled bg_danger" type="button"
                                                                class="edit a_class" title="" data-toggle="tooltip"
                                                                data-original-title="Edit">Cancel
                                                                Order
                                                            </a>
                                                        @elseif($order->isApproved == 1 && $order->isVoid == 0)
                                                            <a class="dropdown-item disabled bg_success" type="button"
                                                                class="edit a_class" title="" data-toggle="tooltip"
                                                                data-original-title="Edit">Cancel Order
                                                            </a>
                                                        @elseif($order->isApproved == 0 && $order->isVoid == 0)
                                                            <a class="dropdown-item" type="button" class="edit a_class"
                                                                title="" data-toggle="tooltip"
                                                                data-original-title="Edit" onclick="cancelOrder()">Cancel
                                                                Order
                                                            </a>
                                                        @endif
                                                    </form>
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
                                    {{ $orders->links('pagination.custom_pagination') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
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
            background: rgb(186 235 137 / 20%);
            color: #319701;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;

        }

        .badge-secondary {
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }

        .bg_success {
            /* background: rgb(186 235 137 / 20%) !important; */
            color: #319701 !important;
            padding: 6px !important;
            font-style: normal !important;
            font-weight: 500 !important;
            font-size: 11.3289px !important;
        }

        .bg_secondary {
            color: #383231 !important;
            padding: 6px !important;
            font-style: normal !important;
            font-weight: 500 !important;
            font-size: 11.3289px !important;
            padding-left: 16px !important;
        }

        .badge-warning {
            background-color: #f1e8cb;
            color: #b58903 !important;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }

        .badge-danger {
            color: #fff;
            background-color: rgba(220, 78, 65, 0.12);
            color: #DC4E41;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }

        .bg_danger {
            color: #DC4E41 !important;
            padding: 6px !important;
            font-style: normal !important;
            font-weight: 500 !important;
            font-size: 11.3289px !important;
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
        $(document).on('click', '#selectAll', function(e) {
            var table = $(e.target).closest('table');
            $('td input:checkbox', table).prop('checked', this.checked);
        });

        function cancelOrder() {
            var order_id = $("#order_id").val();
            $.ajax({
                url: "{{ url('admin/order-cancel') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "order_id": order_id
                },
                success: function(response) {
                    Swal.fire(
                        'Good job!',
                        'Order Cancel successfully',
                        'success'
                    )
                    setInterval('location.reload()', 3000);
                }
            })
        }

        function fullFillOrder() {
            var status = $("#status").val();
            var order_id = $("#order_id").val();
            // alert(order_id);
            var delay = 7000;
            $('#progress-bar').removeClass('d-none');
            jQuery(".progress-bar").each(function(i) {
                jQuery(this).delay(delay * i).animate({
                    width: $(this).attr('aria-valuenow') + '%'
                }, delay);

                jQuery(this).prop('Counter', 1).animate({
                    Counter: $(this).text()
                }, {
                    duration: delay,
                    step: function(now) {
                        jQuery(this).text(Math.ceil(100) + '%');

                    }
                });
            });

            jQuery.ajax({
                url: "{{ url('admin/order-full-fill') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "order_id": order_id
                },
                success: function(response) {
                    console.log(response);
                    jQuery.ajax({
                        url: "{{ url('admin/check-status') }}",
                        method: 'post',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "order_id": order_id
                        },
                        success: function(response) {
                            console.log(response.status);
                            if (response.status === 'Order fullfilled successfully') {
                                $('#fullfill_success').html(response.status);
                                Swal.fire(
                                    'Good job!',
                                    'Order fullfilled successfully',
                                    'success'
                                )
                            } else {
                                // $('#fullfill_failed').html(response.status);
                                Swal.fire('Order fullfilled failed')
                            }

                            // $('#progress-bar').addClass('d-none');
                            setInterval('location.reload()', 3000);
                        }
                    });
                }
            });
        }
    </script>
@stop
@section('plugins.Sweetalert2', true)

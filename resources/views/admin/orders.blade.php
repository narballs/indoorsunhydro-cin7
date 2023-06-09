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
                    <div class="col-md-12 btn-row my-3">
                        <div class="row">
                            <div class="col-md-3 d-flex justify-content-between align-content-center py-2">
                                <span class="border-right pe-5 select-row-items ms-2" id="items_selected">
                                    0 Selected
                                </span>
                                <span>
                                    <a class="order_ful_fill btn btn-sm fulfill-row-items-order-page "
                                        data-url="{{ url('admin/orders/multi-full-fill') }}">
                                        Fulfill Order
                                    </a>
                                </span>
                                <span>
                                    <a class="multiple_cancel_orders btn btn-danger btn-sm cancel-row-items-order-page"
                                        data-url="{{ url('admin/multiple/cancle/orders') }}">
                                        Cancel Order
                                    </a>
                                </span>
                            </div>

                            @if ($auto_fulfill == 1)
                                <div class="col-md-9 d-flex justify-content-end align-items-center">
                                    <span class="d-flex">

                                        <a class=" btn  btn-sm fulfill-row-items-order-page">
                                            Auto Fullfill
                                        </a>
                                        <label class="custom-control custom-checkbox ">
                                            <input type="checkbox" id="auto_full_fill" value="{{ $auto_fulfill }}"
                                                class="custom-control-input general_switch" onchange="autoFullfill()"
                                                {{ isset($auto_fulfill) && $auto_fulfill == 1 ? 'checked="checked"' : '' }}>
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                    </span>
                                </div>
                            @else
                                <div class="col-md-9 d-flex justify-content-end align-items-center">
                                    <span class="d-flex">

                                        <a class=" btn  btn-sm fulfill-row-items-order-page">
                                            Auto Fullfill
                                        </a>
                                        <label class="custom-control custom-checkbox ">
                                            <input type="checkbox" id="auto_full_fill" value=""
                                                class="custom-control-input general_switch" onchange="autoFullfill()">
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <table class="table border table-customer mb-5">
                        <thead>
                            <tr class="table-header-background">
                                <td class="d-flex table-row-item mt-0">
                                    <div class="custom-control custom-checkbox tabel-checkbox">
                                        <input class="custom-control-input custom-control-input-success checkbox-table"
                                            type="checkbox" id="selectAll" value="">
                                        <label for="selectAll" class="custom-control-label ml-4"></label>

                                        <span class="table-row-heading-order">
                                            <i class="fas fa-arrow-up mt-1" style="font-size:14.5px ;"></i>
                                        </span>
                                    </div>
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
                                    <tr id="tr_{{ $order->id }}" class="order-row border-bottom">
                                        <td class="d-flex table-items">
                                            <div class="custom-control custom-checkbox tabel-checkbox">
                                                <input class="custom-control-input custom-control-input-success sub_chk"
                                                    data-id="{{ $order->id }}" type="checkbox"
                                                    id="separate_check_{{ $order->id }}">
                                                <label for="separate_check_{{ $order->id }}"
                                                    class="custom-control-label ml-4"></label>
                                            </div>
                                            <span class="table-row-heading-order">
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
                                        <td class="td_padding_row">
                                            {{ $order->paymentTerms }}
                                        </td>
                                        <td class="created_by toggleClass td_padding_row ps-0">
                                            <div class="d-flex justify-content-between aling-items-center pe-5">
                                                <span>
                                                    <a href="{{ url('admin/order-detail/' . $order->id) }}"
                                                        class="view a_class" title="" data-toggle="tooltip"
                                                        data-original-title="View">
                                                        <i class="icon-style  fas fa-eye  i_class"></i>
                                                    </a>
                                                </span>
                                                <span>
                                                    <a href="#" class="edit a_class" title=""
                                                        data-toggle="tooltip" data-original-title="Edit"><i
                                                            class="icon-style fa fa-pen  "></i>
                                                    </a>
                                                </span>
                                                <span>
                                                    <a href="#" class="delete deleteIcon a_class"
                                                        id="{{ $order->id }}" title="" data-toggle="tooltip"
                                                        data-original-title="Delete"><i
                                                            class="icon-style fa fa-trash-alt"></i>
                                                    </a>
                                                </span>
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
    <link rel="stylesheet" href="/theme/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('/admin/admin_lte.css') }}">
    <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet">

    <style type="text/css">
        .custom-checkbox {
            min-height: 1rem;
            padding-left: 0;
            margin-right: 0;
            cursor: pointer;
        }

        .custom-checkbox .custom-control-indicator {
            content: "";
            display: inline-block;
            position: relative;
            width: 30px;
            height: 10px;
            background-color: #818181;
            border-radius: 15px;
            margin-right: 10px;
            -webkit-transition: background .3s ease;
            transition: background .3s ease;
            vertical-align: middle;
            margin: 0 16px;
            box-shadow: none;
        }

        .custom-checkbox .custom-control-indicator:after {
            content: "";
            position: absolute;
            display: inline-block;
            width: 18px;
            height: 18px;
            background-color: #f1f1f1;
            border-radius: 21px;
            box-shadow: 0 1px 3px 1px rgba(0, 0, 0, 0.4);
            left: -2px;
            top: -4px;
            -webkit-transition: left .3s ease, background .3s ease, box-shadow .1s ease;
            transition: left .3s ease, background .3s ease, box-shadow .1s ease;
        }

        .custom-checkbox .custom-control-input:checked~.custom-control-indicator {
            background-color: #28a745;
            background-image: none;
            box-shadow: none !important;
        }

        .custom-checkbox .custom-control-input:checked~.custom-control-indicator:after {
            background-color: #28a745;
            left: 15px;
        }

        .custom-checkbox .custom-control-input:focus~.custom-control-indicator {
            box-shadow: none !important;
        }

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
                            $('#tr_' + id).remove();
                        }
                    });
                }
            })
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
                                Swal.fire('Order fullfilled failed')
                            }
                            setInterval('location.reload()', 3000);
                        }
                    });
                }
            });
        }

        function autoFullfill() {
            var value = $('#auto_full_fill').val();
            if (value == 1) {
                var auto_fullfill = true;
            } else {
                auto_fullfill = false;
            }

            jQuery.ajax({
                url: "{{ url('admin/auto-full-fill') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "auto_fullfill": auto_fullfill
                },
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#selectAll').on('click', function(e) {
                if ($(this).is(':checked', true)) {
                    let count_checked = $(".sub_chk").prop('checked', true);
                    $('#items_selected').html('');
                    $('#items_selected').html(count_checked.length + ' Selected');
                } else {
                    let count_unchecked = $(".sub_chk").prop('checked', false);
                    $('#items_selected').html('');
                    $('#items_selected').html('0' + ' Selected');
                }
            });
            $('.sub_chk').on('click', function(e) {
                count_checked = $(".sub_chk:checked").length < 1 ? $('#selectAll').prop('checked', false) :
                    '';
                if ($(this).is(':checked', true)) {
                    let count_checked = $(".sub_chk:checked").length;
                    $('#items_selected').html('');
                    $('#items_selected').html(count_checked + ' Selected');
                } else {
                    let count_unchecked = $(".sub_chk:checked").length;
                    $('#items_selected').html('');
                    $('#items_selected').html(count_unchecked + ' Selected');
                }
            })
            $('.order_ful_fill').on('click', function(e) {
                var orderIds = [];
                $(".sub_chk:checked").each(function() {
                    orderIds.push($(this).attr('data-id'));
                });
                if (orderIds.length <= 0) {
                    Swal.fire(
                        'Please select at least one record to process.',
                    )
                } else {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "your want to full-fill order(s)?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7CC633 ',
                        cancelButtonColor: '#DC4E41',
                        confirmButtonText: 'Yes, Full Fill it!'
                    }).then((result) => {
                        if (result.value) {
                            var join_selected_values = orderIds.join(",");
                            $.ajax({
                                url: $(this).data('url'),
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                data: 'ids=' + join_selected_values,
                                success: function(response) {
                                    var delay = 8000;
                                    $('#progress-bar').removeClass('d-none');
                                    jQuery(".progress-bar").each(function(i) {
                                        jQuery(this).delay(delay * i).animate({
                                            width: $(this).attr(
                                                    'aria-valuenow') +
                                                '%'
                                        }, delay);

                                        jQuery(this).prop('Counter', 1)
                                            .animate({
                                                Counter: $(this).text()
                                            }, {
                                                duration: delay,
                                                step: function(now) {
                                                    jQuery(this).text(
                                                        Math.ceil(
                                                            100) +
                                                        '%');
                                                }
                                            });
                                    });
                                    if (response.status === 401) {
                                        $('#progress-bar').addClass('d-none');
                                        Swal.fire(
                                            'Warning!',
                                            'Order Already fullfilled',
                                            'warning'
                                        )
                                        setInterval(
                                            'location.reload()',
                                            8000);
                                    } else if (response.status === 402) {
                                        $('#progress-bar').addClass('d-none');
                                        Swal.fire(
                                            'Warning!',
                                            'Order request is null',
                                            'warning'
                                        )
                                        setInterval('location.reload()', 100);
                                    } else {
                                        jQuery.ajax({
                                            url: "{{ url('admin/multi/check-status') }}",
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': $(
                                                        'meta[name="csrf-token"]'
                                                    )
                                                    .attr('content')
                                            },
                                            data: 'ids=' + join_selected_values,
                                            success: function(response) {
                                                console.log(response
                                                    .status);
                                                if (response.status ===
                                                    'Order fullfilled successfully'
                                                ) {
                                                    Swal.fire(
                                                        'Good job!',
                                                        'Order fullfilled successfully',
                                                        'success'
                                                    )
                                                } else {
                                                    Swal.fire(
                                                        'Order fullfilled failed'
                                                    )
                                                }
                                                $('#progress-bar').addClass(
                                                    'd-none');
                                                setInterval(
                                                    'location.reload()',
                                                    8000);
                                            }
                                        });
                                    }
                                },
                            });
                        }
                    });
                }
            });
            $('.multiple_cancel_orders').on('click', function(e) {
                var orderIds = [];
                $(".sub_chk:checked").each(function() {
                    orderIds.push($(this).attr('data-id'));
                });
                if (orderIds.length <= 0) {
                    Swal.fire(
                        'Please select at least one record to process.',
                    )
                } else {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "your want to cancel order(s)?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DC4E41',
                        cancelButtonColor: '#7CC633',
                        confirmButtonText: 'Yes, Cancel it!'
                    }).then((result) => {
                        if (result.value) {
                            var join_selected_values = orderIds.join(",");
                            $.ajax({
                                url: $(this).data('url'),
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                data: 'ids=' + join_selected_values,
                                success: function(response) {
                                    console.log(response.data);
                                    if (response.status === 400) {
                                        Swal.fire(
                                            'Warning!',
                                            'Your order is already cancel!',
                                            'warning'
                                        )
                                    } else if (response.status === 401) {
                                        Swal.fire(
                                            'Warning!',
                                            'Your order is null !',
                                            'warning'
                                        )
                                    } else if (response.status === 402) {
                                        Swal.fire(
                                            'Warning!',
                                            'Your order request is null !',
                                            'warning'
                                        )
                                    } else if (response.status === 200) {
                                        Swal.fire(
                                            'Good job!',
                                            'Order cancel successfully',
                                            'success'
                                        )
                                    }

                                    setInterval('location.reload()', 3000);
                                },
                                error: function(response) {
                                    if (response.status.error) {
                                        console.log(response.status.error.message);
                                    }

                                },
                            });
                        }
                    });
                }
            });
        });
    </script>
@stop
@section('plugins.Sweetalert2', true)

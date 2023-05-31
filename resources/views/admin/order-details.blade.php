@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-fluid">
        <!-- Title -->
        <div class="d-flex justify-content-between align-items-center py-3">
            <h2 class="h5 mb-0"><a href="#" class="text-muted"></a> Order #{{ $order->id }}</h2>
        </div>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Details -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="mb-3 d-flex justify-content-between">
                            <div>
                                <span class="me-3">{{ $formatedDate }}</span>
                            </div>

                            <div class="d-flex">
                                <button class="btn btn-link p-0 me-3 d-none d-lg-block btn-icon-text"><i
                                        class="bi bi-download"></i> <span class="text">Invoice</span></button>
                                <div class="dropdown">
                                    <button class="btn btn-link p-0 text-muted" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-pencil"></i>
                                                Edit</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-printer"></i>
                                                Print</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <form method="POST" id="order_status" name="order_status">
                            {{-- <div>
                                    <span class="me-3">Order Status</span>
                                </div>
                                @if (!empty($order->order_id))
                                    <select onchange="updateStatus()" id="status">
                                        <option value='0'
                                            {{ isset($status) && $status == '0' ? 'selected="selected"' : '' }}>
                                            DRAFT</option>
                                        <option value='1'
                                            {{ isset($status) && $status == '1' ? 'selected="selected"' : '' }}>
                                            APPROVED</option>
                                        <option value='2'
                                            {{ isset($status) && $status == '2' ? 'selected="selected"' : '' }}>
                                            VOID</option>
                                    </select>
                                @else
                                    <select disabled="" onchange="updateStatus()" id="status">
                                        <option value='0'
                                            {{ isset($status) && $status == '0' ? 'selected="selected"' : '' }}>
                                            DRAFT</option>
                                        <option value='1'
                                            {{ isset($status) && $status == '1' ? 'selected="selected"' : '' }}>
                                            APPROVED</option>
                                        <option value='2'
                                            {{ isset($status) && $status == '2' ? 'selected="selected"' : '' }}>
                                            VOID</option>
                                    </select>
                                @endif --}}
                            <input type="hidden" value="{{ $order->id }}" id="order_id_status">
                            <div class="row mb-5">
                        </form>
                        <form>
                            @csrf
                            <input type="hidden" value="" id="timeSpanToCancel">
                            @if ($order->isApproved == 2)
                                <button type="button" class="btn btn-danger btn-sm" disabled>Cancel Order</button>
                        <div class="countdown">
                    </div>
                      @elseif($order->isApproved == 1 )
                    <div class="col-md-12" style=";
                            ">
                    @elseif($order->isApproved == 1)
                        <div class="col-md-12">
                            >>>>>>> ffc4485234883184405f159c6f516859f9c1301c
                            <button type="button" class="btn btn-secondary btn-sm" disabled>
                                Cancel Order
                            </button>
                        </div>
                    @else
                        <div class="col-md-12">
                            <input type="hidden" value="{{ $orderitems[0]['order_id'] }}" id="order_id">
                            <input class="btn btn-danger btn-sm" type="button" value="Cancel Order" id="cancel_order"
                                onclick=" cancelOrder(); addComment(0);">

                        </div>
                        <div class="countdown"></div>
                        <!-- <div class=" spinner-border d-none" role="status" id="spinner">
                                                                                                            <span class="sr-only" style="margin-left: 227px">Activating...</span>
                                                                                                        </div> -->

                    </div>

                    </form>
                    @endif
                    <form>
                        @csrf
                        @if ($order->isApproved == 1)
                            <div class="col-md-12" style="margin-top: -31px;
                             margin-left: 122px;">
                                <button type="button" class="btn btn-secondary btn-sm" disabled>
                                    Fullfilled
                                </button>
                            </div>
                        @elseif ($order->isApproved == 2)
                            <div class="col-md-12" style="margin-left: 122px;
                            margin-top: -29px;">
                                <button type="button" class="btn btn-danger btn-sm" disabled>
                                    Fullfilled
                                </button>
                            </div>
                        @else
                            <div class="col-md-12" style="margin-left: 50px;">
                                <input class="btn btn-primary btn-sm" type="button" value="Fullfill Order"
                                    onclick="fullFillOrder()">
                            </div>
                            <div class="spinner-border d-none" role="status" id="spinner">
                                <span class="sr-only" style="margin-left: 227px">Activating...</span>
                            </div>
                        @endif
                    </form>

                </div>
                <div class="progress border d-none w-50 mx-auto" id="progress-bar">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar"
                        aria-valuenow="100" aria-valuemin="" aria-valuemax="100"></div>
                </div>
                <div class="bg-success text-white text-center" id="fullfill_success"></div>
                <div class="bg-warning text-white text-center" id="fullfill_failed"></div>
                <table class="table mt-3">
                    <tr>
                        <th>Line Items</th>
                        <th>Quantity</th>
                        <th>Totals</th>
                    </tr>
                    <tbody>
                        @php
                            $tax = $order->total * ($tax_class->rate / 100);
                            $total_including_tax = $tax + $order->total;
                        @endphp
                        @foreach ($orderitems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex mb-2">
                                        <div class="flex-shrink-0 mx-4">
                                            <img src="{{ $item->product->images }}" alt="" width="35"
                                                class="img-fluid">
                                        </div>
                                        <div class="flex-lg-grow-1 ms-3">
                                            <h6 class="small mb-0"><a href="#"
                                                    class="text-reset">{{ $item->Product->name }}</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="ms-2">{{ $item->quantity }}</td>
                                <td class="text-end">${{ $item->price }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">Subtotal</td>
                            <td class="text-end">${{ $order->total }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Shipping</td>
                            <td class="text-end">$0.00</td>
                        </tr>
                        <tr>
                            <td colspan="2">Add Tax</td>
                            <td class="text-end">${{ number_format($tax, 2) }}</td>
                        </tr>
                        <tr class="fw-bold">
                            <td colspan="2"><strong>GRAND TOTAL</strong></td>
                            <td class="text-end">${{ number_format($total_including_tax, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <h3 class="h6">Payment Method</h3>
                        <span>{{ $order->paymentTerms }}</span></p>
                    </div>
                    <div class="col-lg-6">
                        <h3 class="h6">Billing address</h3>
                        <address>
                            @if (!empty($customer->firstName && $customer->lastName))
                                <strong>{{ $customer->firstName }}&nbsp;{{ $customer->lastName }}</strong><br>
                            @endif
                            {{ $customer->postalAddress1 }}<br>
                            {{ $customer->postalAddress2 }}
                            {{ $customer->postalCity }}, {{ $customer->state }}
                            <p title="Phone" class="m-0">P:({{ $customer->mobile }})</p>
                        </address>
                    </div>
                </div>
            </div>
        </div>

        </div>
     <div class="col-lg-4">
        <!-- Customer Notes -->
        <div class="card mb-4">
            <div class="card-body">
                <h3 class="h6" style="margin-bottom: 0px;"><strong>Order Notes</strong></h3>
                @foreach ($orderComment as $comment)
                    @php
                        $user = auth()->user();
                    @endphp
                    <p>{{ $user->first_name }} {{ $user->last_name }} {{ $comment->comment }}</p>
                    <p style="margin-bottom: 0px;">Date</p>
                    <p>
                        <i>
                            {{ $comment->created_at }}
                        </i>
                    </p>
                @endforeach

            </div>
        </div>
        <div class="col-lg-12">
            <form method="POST" id="order_notes" name="order_notes">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1" class="ms-2">Add Order Notes</label>
                    <textarea class="form-control" id="comment" rows="3">
                    </textarea>
                    <input class="btn btn-primary" type="button" value="Add Notes" onclick="addComment(1)">
                    <input type="hidden" value="{!! $order->id !!}" id="order_id">
                </div>

            </form>
        </div>
        <div class="col-lg-12">
            <form method="POST" id="order_notes" name="order_notes">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1" class="ms-2">Fullfil</label>
                    <textarea class="form-control" id="comment" rows="3">
                    </textarea>
                    <input class="btn btn-primary" type="button" value="Add Notes" onclick="fullFillOrder()">
                    <input type="hidden" value="{!! $order->id !!}" id="order_id">
                </div>

            </form>
        </div>
        <div class="card mb-4">
            <!-- Shipping information -->
            <div class="card-body">
                <h3 class="h6">Shipping Information</h3>

                <hr>
                <h3 class=" h6">Address</h3>
                <address>
                    <strong>{{ $customer->firstName }} {{ $customer->lastName }}</strong><br>
                    {{ $customer->address1 }}, {{ $customer->address2 }}<br>
                    {{ $customer->city }},
                    <p title="Phone" class="mb-0">P: ({{ $customer->mobile }})</p>
                    <p title="Phone">{{ $customer->email }}</p>
                </address>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">

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
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            var timeSpanToCancel = $("#timeSpanToCancel").val();
            var timeSpanToCancel = new Date(timeSpanToCancel);
            var currentTime = new Date();
            var currentTimeStamp = new Date();
            var day = currentTimeStamp.getDate();
            var month = currentTimeStamp.getMonth() + 1;
            var year = currentTimeStamp.getFullYear();
            var time = year + "-" + month + "-" + day + " " + currentTimeStamp.getMonth() + ":" + currentTimeStamp
                .getHours() + ":" + currentTimeStamp.getMinutes() + ":" + currentTimeStamp.getSeconds();
            //var canceltime = new Date(timeSpanToCancel);
            //var time_diff = new Date(canceltime) - new Date(currentTimeStamp);
            //var t = new Date(time_diff);
            // alert(t.getMinutes);
            //long diff = d2.getTime() - d1.getTime();



            var timer2 = "15:01";
            var interval = setInterval(function() {


                var timer = timer2.split(':');
                //by parsing integer, I avoid all extra string processing
                var minutes = parseInt(timer[0], 10);
                var seconds = parseInt(timer[1], 10);
                --seconds;
                minutes = (seconds < 0) ? --minutes : minutes;
                if (minutes < 0) clearInterval(interval);
                seconds = (seconds < 0) ? 59 : seconds;
                seconds = (seconds < 10) ? '0' + seconds : seconds;
                //minutes = (minutes < 10) ?  minutes : minutes;
                $('#cancel_order').val('Cancel Order in ' + minutes + ':' + seconds);
                timer2 = minutes + ':' + seconds;
                //console.log(minutes);
                //console.log(seconds);
            }, 1000);

        });

        function addComment(isUserAdded) {
            if (isUserAdded == 1) {
                var comment = $("#comment").val();
                var order_id = $("#order_id").val();
            } else {
                var comment = 'order cancel';
                var order_id = $("#order_id").val();
            }

            jQuery.ajax({
                url: "{{ url('admin/order-comments') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "comment": comment,
                    "order_id": order_id
                },
                success: function(response) {
                    //  window.location.reload();
                }
            });
        }

        function updateStatus() {

            var status = $("#status").val();
            var order_id_status = $("#order_id_status").val();
            jQuery.ajax({
                url: "{{ url('admin/order-status') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "status": status,
                    "order_id_status": order_id_status
                },
                success: function(response) {
                    window.location.reload();
                }
            });
        }

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
                    console.log(response);
                    window.location.reload();
                }
            })
        }

        function fullFillOrder() {
            var status = $("#status").val();
            var order_id = $("#order_id").val();
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
                    // easing: 'swing',
                    step: function(now) {
                        jQuery(this).text(Math.ceil(100) + '%');

                    }
                });
            })
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
                            } else {
                                $('#fullfill_failed').html(response.status);
                            }

                            $('#progress-bar').addClass('d-none');
                            setInterval('location.reload()', 7000);
                        }
                    });
                }
            });
        }
    </script>
@stop

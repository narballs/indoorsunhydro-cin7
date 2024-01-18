@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
    <h1>Dashboard</h1>
@stop
@section('content')
    <div class="container-fluid">
        <div class="">
            <!-- Title -->
            <div class="d-flex justify-content-between align-items-center py-3">
                <h2 class="h5 mb-0"><a href="#" class="text-muted"></a> Customer Details</h2>
            </div>
            <div class="bg-success d-none" id="contact_refresh">Contact refreshed successfully</div>
            <!-- Main content -->
            <div class="row">
                <div class="col-lg-9">
                    <!-- Details -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row mb-5">
                                <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer->id }}">
                                <input type="hidden" name="contact_id" id="contact_id" value="{{ $customer->contact_id }}">
                                <div class="row">
                                    <div class="text-muted col-md-3">
                                        <h5><span id="refreshed_firstname">{{ $customer->firstName }}</span><span
                                                id="refreshed_lastname" class="ms-2"> {{ $customer->lastName }}</span>
                                            @if ($customer->status == 1)
                                                <span class="fa fa-edit" onclick="updatePriceColumn(0)"></span>
                                            @endif
                                        </h5>
                                        <div id="first-last-name" class="d-none">
                                            <div><input type="text" name="first_name" value="{{ $customer->firstName }}">
                                            </div>
                                            <div class="mt-3"><input type="text" name="last_name"
                                                    value="{{ $customer->lastName }}"></div>
                                            <div class="mt-3">
                                                <button type="button" value="update"
                                                    onclick="updatePriceColumn(3)">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($customer)
                                        <?php
                                            $pricing = $customer->priceColumn;
                                        ?>
                                        <div class="col-md-4"><b>Pricing:</b>
                                            <select onchange="updatePriceColumn(4)" class="pricingColumn" id="pricingColumn">
                                                <option value="">Choose Pricing</option>
                                                @foreach ($contact_price_columns as $contact_price_column)
                                                    <option value="{{ lcfirst($contact_price_column) }}" {{ strtoupper($customer->priceColumn) == strtoupper($contact_price_column) ? 'selected="selected"' : ''}} >
                                                        {{ strtoupper($contact_price_column) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="spinner-border d-none" role="status"
                                                style="left: 50% !important;margin-left: -25em !important;" id="spinner2">
                                                <span class="sr-only">Activating...</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if( $customer->contact_id == null || $customer->contact_id == '')
                                        <div class="col-md-2">
                                            <button class="btn btn-primary" type="button"
                                                onclick="updateContact()">Activate</button>
                                        </div>
                                    @endif
                                    @if ($customer->user == '' && $customer->hashKey == '')
                                        @if ($customer->contact_id)
                                            <div class="col-md-2"><button class="btn btn-primary btn-sm" type="button"
                                                    onclick="refreshContact({{ $customer->contact_id }}, 'primary')">Refresh
                                                    Contact</button>
                                            </div>
                                        @elseif($customer->secondary_id)
                                            <div class="col-md-2"><button class="btn btn-primary btn-sm" type="button"
                                                    onclick="refreshContact({{ $customer->secondary_id }}, 'secondary')">Refresh
                                                    Contact</button>
                                            </div>
                                        @endif
                                    @elseif ($customer->hashKey != '' && $customer->hashUsed == 0)
                                        <div>
                                            <span class="badge bg-warning" style="margin-left: 12px!important;">Invitation
                                                Sent</span>
                                        </div>
                                    @else
                                        <div>
                                            <span class="badge bg-success"
                                                style="margin-left: 12px!important;">Merged</span>
                                        </div>
                                    @endif
                                    <div class="col-md-2 d-flex justify-content-end align-items-center">
                                        @if ($customer && $customer->status == 1)
                                            <span class="badge bg-success">Active</span>
                                            <label class="custom-control custom-checkbox ">
                                                <input type="checkbox" id="{{ $customer->id }}"
                                                    value="{{ $customer->status }}"
                                                    class="custom-control-input general_switch"
                                                    onchange="disableSecondary({{ $customer->id }})"
                                                    {{ isset($customer->status) && $customer->status == 1 ? 'checked="checked"' : '' }}>
                                                <span class="custom-control-indicator"></span>
                                            </label>
                                        @else
                                            <span class="badge bg-warning">Inactive</span>
                                            <label class="custom-control custom-checkbox ">
                                                <input type="checkbox" id="{{ $customer->id }}"
                                                    value="{{ $customer->status }}"
                                                    class="custom-control-input general_switch"
                                                    onchange="disableSecondary({{ $customer->id }})"
                                                    {{ isset($customer->status) && $customer->status == 1 ? 'checked="checked"' : '' }}>
                                                <span class="custom-control-indicator"></span>
                                            </label>
                                        @endif
                                    </div>
                                    <div class="spinner-border d-none" role="status"
                                        style="left: 50% !important;margin-left: -25em !important;" id="spinner">
                                        <span class="sr-only">Activating...</span>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <b>Company:</b> <span
                                                    id="refreshed_company">{{ $customer->company }}</span>
                                            </div>
                                            <div class="col-md-4">
                                                @if (!$customer->contact_id)
                                                    <b>Cin7 ID:</b> <span class="badge bg-info">empty</span>
                                                    <br>
                                                @else
                                                    @if ($customer->contact_id)
                                                        <b>Cin7 ID:Parent Account</b> {{ $customer->contact_id }}
                                                    @endif
                                                @endif
                                                <div class="col-md-12 p-0">
                                                    <b>Tax Status: {{!empty($customer->tax_class) ?  $customer->tax_class : 'Empty'}} </b>
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-flex justify-content-end align-items-center">
                                                @if (!empty($customer) && $customer->is_parent == 1)
                                                    @if ($customer && $customer->charge_shipping == 0)
                                                    <span class="">Do not charge Shipping</span>
                                                    <label class="custom-control custom-checkbox ">
                                                        <input type="checkbox" id="{{ $customer->contact_id }}"
                                                            value="{{ $customer->charge_shipping }}"
                                                            class="custom-control-input general_switch"
                                                            onchange="enable_shipping_price({{ $customer->contact_id }})"
                                                            {{ isset($customer->charge_shipping) && $customer->charge_shipping == 0 ? 'checked="checked"' : '' }}>
                                                        <span class="custom-control-indicator"></span>
                                                    </label>
                                                    @else
                                                    <span class="">Do not charge Shipping</span>
                                                    <label class="custom-control custom-checkbox ">
                                                        <input type="checkbox" id="{{ $customer->contact_id }}"
                                                            value="{{ $customer->charge_shipping }}"
                                                            class="custom-control-input general_switch"
                                                            onchange="disable_shipping_price({{ $customer->contact_id }})"
                                                            {{ isset($customer->charge_shipping) && $customer->charge_shipping == 1 ? '' : 'checked' }}>
                                                        <span class="custom-control-indicator"></span>
                                                    </label>
                                                    @endif
                                                @elseif (!empty($customer) && $customer->is_parent == 0)
                                                    @php
                                                       $parent_account = \App\Models\Contact::where('contact_id', $customer->parent_id)->first();   
                                                    @endphp
                                                    @if ($parent_account && $parent_account->charge_shipping == 0)
                                                        <span class="">Do not charge Shipping</span>
                                                        <label class="custom-control custom-checkbox ">
                                                            <input type="checkbox" id="{{ $customer->contact_id }}"
                                                                value="{{ $parent_account->charge_shipping }}"
                                                                class="custom-control-input general_switch"
                                                                onchange="enable_shipping_price({{ $parent_account->contact_id }})"
                                                                {{ isset($parent_account->charge_shipping) && $parent_account->charge_shipping == 0 ? 'checked="checked"' : '' }}>
                                                            <span class="custom-control-indicator"></span>
                                                        </label>
                                                    @else
                                                        <span class="">Do not charge Shipping</span>
                                                        <label class="custom-control custom-checkbox ">
                                                            <input type="checkbox" id="{{ $parent_account->contact_id }}"
                                                                value="{{ $parent_account->charge_shipping }}"
                                                                class="custom-control-input general_switch"
                                                                onchange="disable_shipping_price({{ $parent_account->contact_id }})"
                                                                {{ isset($parent_account->charge_shipping) && $parent_account->charge_shipping == 1 ? '' : 'checked' }}>
                                                            <span class="custom-control-indicator"></span>
                                                        </label>
                                                    @endif
                                                @endif
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <b>Website:</b> {{ $customer->website }}
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <div id="refreshed_email">{{ $customer->email }}</div>
                                        <input type="hidden" name="customer_email" id="customer_email"
                                            value="{{ $customer->email }}">
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        {{ $customer->phone }}{{ $customer->mobile }}
                                    </div>
                                    <div class="col-md-12 mt-5">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h3>Billing Address</h3>
                                                <div class="col-md-10 bg-light">
                                                    <div>
                                                        @if(empty($customer->contact_id))
                                                            {{$get_secondary_contact->postalAddress1 ? $get_secondary_contact->postalAddress1 . ","  : '' }}
                                                        @else
                                                            {{$customer->postalAddress1 ? $customer->postalAddress1 . ","  : '' }}
                                                        @endif
                                                    </div>
                                                    <div>
                                                        @if(empty($customer->contact_id))
                                                            {{$get_secondary_contact->postalAddress2 ? $get_secondary_contact->postalAddress2  . ","  : '' }}
                                                        @else
                                                            {{$customer->postalAddress2 ? $customer->postalAddress2  . ","  : '' }}
                                                        @endif
                                                    </div>
                                                    <div>
                                                        @if(empty($customer->contact_id))
                                                        {{ $get_secondary_contact->postalCity ? $get_secondary_contact->postalCity . ","  : '' }}
                                                        @else
                                                            {{ $customer->postalCity ? $customer->postalCity . ","  : '' }}
                                                        @endif

                                                        @if(empty($customer->contact_id))
                                                            {{ $get_secondary_contact->postalState ? $get_secondary_contact->postalState   . ","  : '' }}
                                                        @else
                                                            {{ $customer->postalState ? $customer->postalState . ","  : '' }}
                                                        @endif
                                                        @if(empty($customer->contact_id))
                                                            {{ $get_secondary_contact->postalPostCode ? $get_secondary_contact->postalPostCode   : '' }}

                                                        @else
                                                            {{ $customer->postalPostCode ? $customer->postalPostCode  : '' }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h3>Delivery Address</h3>
                                                <div class="col-md-10 bg-light">
                                                    <div>
                                                        @if(empty($customer->contact_id))
                                                            {{$get_secondary_contact->address1 ? $get_secondary_contact->address1  . ","  : '' }}
                                                        @else
                                                            {{$customer->address1 ? $customer->address1  . ","  : '' }}
                                                        @endif
                                                    </div>
                                                    <div>
                                                        @if(empty($customer->contact_id))
                                                            {{$get_secondary_contact->address2 ? $get_secondary_contact->address2  . ","  : ''}}
                                                        @else
                                                            {{$customer->address2 ? $customer->address2  . ","  : '' }}
                                                        @endif
                                                    </div>
                                                    <div>
                                                        
                                                        @if(empty($customer->contact_id))
                                                            {{ $get_secondary_contact->city ? $get_secondary_contact->city . ","  : '' }}
                                                        @else
                                                            {{ $customer->city ? $customer->city . ","  : '' }}
                                                        @endif
                                                        @if(empty($customer->contact_id))
                                                            {{ $get_secondary_contact->state ? $get_secondary_contact->state  . ","  : '' }}
                                                        @else
                                                            {{ $customer->state ? $customer->state . ","  : '' }}
                                                        @endif
                                                        @if(empty($customer->contact_id))
                                                            {{ $get_secondary_contact->postCode ? $get_secondary_contact->postCode : '' }}

                                                        @else
                                                            {{ $customer->postCode ? $customer->postCode: '' }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Payment -->
                    @if ($secondary_contacts)
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row align-items-center justify-content-between mb-2">
                                            <div class="col-md-9">
                                                <h2 class="h5 mb-0">
                                                    <a href="#" class="text-muted"></a>Secondary Users
                                                </h2>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="row align-items-center justify-content-end">
                                                    <a href="{{url('/admin/customer-detail/' . $customer->id)}}" class="btn btn-success text-white mr-2"> Active Users </a>
                                                    <form action="{{url('/admin/customer-detail/' . $customer->id)}}">
                                                        <input type="hidden" name="show_deleted_users" value="show_deleted_users">
                                                        <button class="btn btn-danger" type="submit">Deleted Users</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table">
                                            <tr>
                                                <th>Company</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Job Title</th>
                                                <th>Email</th>
                                                <th>Mobile#</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                            @foreach ($secondary_contacts as $contact)
                                                <tr class="{{!empty($contact->deleted_at) ? 'delete_grey' : ''}}" title="{{!empty($contact->deleted_at) ?  'deleted on ' . $contact->deleted_at : ''}}">
                                                    @if ($contact->company)
                                                        <td>
                                                            {{ $contact->company }}
                                                        </td>
                                                    @else
                                                        <td>
                                                            <span class="badge bg-info">empty</span>
                                                        </td>
                                                    @endif
                                                    @if ($contact->firstName)
                                                        <td>
                                                            {{ $contact->firstName }}
                                                        </td>
                                                    @else
                                                        <td>
                                                            <span class="badge bg-info">empty</span>
                                                        </td>
                                                    @endif
                                                    @if ($contact->lastName)
                                                        <td>
                                                            {{ $contact->lastName }}
                                                        </td>
                                                    @else
                                                        <td>
                                                            <span class="badge bg-info">empty</span>
                                                        </td>
                                                    @endif
                                                    @if ($contact->jobTitle)
                                                        <td>
                                                            {{ $contact->jobTitle }}
                                                        </td>
                                                    @else
                                                        <td>
                                                            <span class="badge bg-info">empty</span>
                                                        </td>
                                                    @endif
                                                    @if ($contact->email)
                                                        <td>
                                                            {{ $contact->email }}
                                                        </td>
                                                    @else
                                                        <td>
                                                            <span class="badge bg-info">empty</span>
                                                        </td>
                                                    @endif
                                                    @if ($contact->mobile)
                                                        <td>
                                                            {{ $contact->mobile }}
                                                        </td>
                                                    @else
                                                        <td>
                                                            <span class="badge bg-info">empty</span>
                                                        </td>
                                                    @endif
                                                    @if ($contact->phone)
                                                        <td>
                                                            {{ $contact->phone }}
                                                        </td>
                                                    @else
                                                        <td>
                                                            <span class="badge bg-info">empty</span>
                                                        </td>
                                                    @endif

                                                    @if ($contact && $contact->status == 1)
                                                        <td>
                                                            <span class="badge bg-success">Active</span>
                                                        </td>
                                                        <td>
                                                            <label class="custom-control custom-checkbox ">
                                                                <input type="checkbox" id="{{ $contact->id }}"
                                                                    value="{{ $contact->status }}"
                                                                    class="custom-control-input general_switch"
                                                                    onchange="disableSecondary({{ $contact->id }})"
                                                                    {{ isset($contact->status) && $contact->status == 1 ? 'checked="checked"' : '' }}>
                                                                <span class="custom-control-indicator"></span>
                                                            </label>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <span class="badge bg-warning">Inactive</span>
                                                        </td>
                                                        <td>
                                                            <label class="custom-control custom-checkbox ">
                                                                <input type="checkbox" id="{{ $contact->id }}"
                                                                    value="{{ $contact->status }}"
                                                                    class="custom-control-input general_switch"
                                                                    onchange="disableSecondary({{ $contact->id }})"
                                                                    {{ isset($contact->status) && $contact->status == 1 ? 'checked="checked"' : '' }}>
                                                                <span class="custom-control-indicator"></span>
                                                            </label>
                                                        </td>
                                                    @endif
                                                    @if (!empty($contact->deleted_at) && isset($show_deleted_users))
                                                    <td>
                                                        <form action="{{route('restore_contact' , $contact->id)}}" method="post">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-primary text-white" onclick="return confirm('Are you sure you want to restore this Contact?');">Restore</button>
                                                        </form>
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h2 class="h5 mb-0"><a href="#" class="text-muted"></a>Order History</h2>
                                    <table class="table">
                                        <tr>
                                            <th>Order #</th>
                                            <th>Date Created</th>
                                            <th>Total</th>
                                            <th>Ref#</th>
                                            <th>Order Status</th>
                                        </tr>
                                        @foreach ($customer_orders as $customer_order)
                                            <tr>
                                                <td>
                                                    <a href="{{url('admin/order-detail/' . $customer_order->id)}}" class="order-primary-id">{{$customer_order->id}}</a>
                                                </td>
                                                <?php $createdDate = $customer_order->created_at;
                                                $formatedDate = $createdDate->format('F j, Y');
                                                ?>
                                                <td>
                                                    {{ $formatedDate }}
                                                </td>
                                                <td>
                                                    {{ number_format($customer_order->total_including_tax , 2) }}
                                                </td>
                                                <td>
                                                    <a href="{{ url('admin/order-detail/' . $customer_order->id) }}">{{ $customer_order->reference }}
                                                </td>
                                                <td>
                                                    <select name="order_status_id" class="form-control" id="order_status_id" onchange="update_order_status('{{$customer_order->id}}')">
                                                        @foreach ($order_statuses as $order_status)
                                                            <option value="{{ $order_status->id }}"
                                                                {{ $customer_order->order_status_id == $order_status->id ? 'selected="selected"' : '' }}>
                                                                {{ $order_status->status }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <!-- Customer Notes -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h3 class="h6"><strong>Customer Notes</strong></h3>
                            <div>{{ $customer->notes }}</div>

                        </div>
                    </div>
                    <!-- Customer Notes -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h3 class="h6"><strong>User Logs</strong></h3>
                            @if (!empty($logs))
                                @foreach ($logs as $log)
                                    <div>{{ $log->user_notes }}</div>
                                @endforeach
                            @else
                                <div>No Logs Found</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/theme/css/admin_custom.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
    <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet">

    <style type="text/css">
        .delete_grey {
            background-color: #e7e7e7;
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
        .text-dark {
            color: #000 !important;
        }
        .order-primary-id:hover {
            color: #000 !important;
        }
    </style>

@stop

@section('js')
    <script>
        function update_order_status (order_id) {
            var order_status_id = $('#order_status_id').val();
            jQuery.ajax({
                url: "{{ url('admin/customer/update-order-status') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "order_id": order_id,
                    "order_status_id": order_status_id
                },
                success: function(response) {
                    console.log(response);
                    if (response.success == true) {
                        window.location.reload();
                    }
                }
            });
        } 
        function addComment() {
            var comment = $("#comment").val();
            var order_id = $("#order_id").val();
            jQuery.ajax({
                url: "{{ url('admin/order-comments') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "comment": comment,
                    "order_id": order_id
                },
                success: function(response) {
                    window.location.reload();
                }
            });
        }

        function updateStatus() {
            var status = $("#status").val();
            var order_id = $("#order_id").val();
            jQuery.ajax({
                url: "{{ url('admin/order-status') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "status": status,
                    "order_id": order_id
                },
                success: function(response) {
                    window.location.reload();
                }
            });
        }

        function updateContact() {
            var contact_id = $("#customer_id").val();
            $('#spinner').removeClass('d-none');
            jQuery.ajax({
                url: "{{ url('admin/customer-activate') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "contact_id": contact_id
                },
                success: function(response) {
                    console.log(response);
                    if (response.success == true) {
                        setInterval('location.reload()', response.time * 1000);
                    }
                    if (response.success == false) {
                        setInterval('location.reload()', response.time * 1000);
                    }

                }
            });
        }

        function updatePriceColumn(showSpinner) {
            if (showSpinner == 2) {
                $('#spinner').removeClass('d-none');

                var priceCol = $("#priceCol").val();
                var contact_id = $("#contact_id").val();

            }
            if (showSpinner == 1) {
                $('#priceCol').removeClass('d-none');

            } else {
                if (showSpinner == 0) {
                    $('#first-last-name').removeClass('d-none');
                }

                if (showSpinner == 3) {
                    var contact_id = $("#contact_id").val();

                    $('#spinner').removeClass('d-none');
                    var first_name = $("input[name=first_name]").val();
                    var last_name = $("input[name=last_name]").val();
                    var contact_id = $("#contact_id").val();
                }
                if (showSpinner == 4) {
                    var contact_id = $("#contact_id").val();

                    // console.log(contact_id);
                    var pricingCol = $('.pricingColumn').val();
                    // console.log(pricingCol);

                    jQuery.ajax({
                        url: "{{ url('admin/update-pricing-column') }}",
                        method: 'post',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "contact_id": contact_id,
                            "pricingCol": pricingCol,
                            "first_name": first_name,
                            "last_name": last_name
                        },

                        success: function(response) {
                            console.log(response);
                            if (response.success == true) {
                                console.log('yes');

                                $('#spinner').addClass('d-none');
                                setInterval('location.reload()', 1000);
                                //location.reload();
                            }
                            if (response.success == false) {
                                console.log(response.msg);
                                setInterval('location.reload()', 1000);
                            }

                        }

                    });
                }
            }
        }

        function mergeContact() {
            var contact_id = $("#contact_id").val();
            $('#spinner').removeClass('d-none');
            var customer_email = $("#customer_email").val();
            jQuery.ajax({
                url: "{{ url('admin/send-invitation-email') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "contact_id": contact_id,
                    "customer_email": customer_email

                },
                success: function(response) {
                    console.log(response);
                    if (response.msg == 'success') {
                        $('#spinner').addClass('d-none');
                        setInterval('location.reload()', 1000);
                    }
                }
            });

        }

        function withJquery() {
            console.time('time1');
            var temp = $("<input>");
            $("body").append(temp);
            temp.val($('#copyText1').text()).select();
            document.execCommand("copy");
            temp.remove();
            console.timeEnd('time1');
        }

        function refreshContact(contactId, type) {

            $('#spinner').removeClass('d-none');
            jQuery.ajax({
                url: "{{ url('admin/refresh-contact') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "contactId": contactId,
                    "type": type
                },
                success: function(response) {
                    console.log(response);
                    // $('#refreshed_email').html(response.updated_email);
                    // $('#refreshed_firstname').html(response.updated_firstName);
                    // $('#refreshed_lastname').html(' '+response.updated_lastName);
                    // $('#refreshed_company').html(response.updated_company);
                    // console.log(response.updated_priceColumn);
                    // $('#pricingColumn').find('option[value="'+response.updated_priceColumn+'"]').prop('selected', true);
                    // $('#spinner').addClass('d-none');

                }

            })
            setTimeout(function() {
                window.location.reload();
            }, 5000);
        }

        function disableSecondary(secondary_id) {
            jQuery.ajax({
                url: "{{ url('admin/disable-secondary') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "contactId": secondary_id
                },
                success: function(response) {
                    console.log(response);
                    if (response.msg == 'success') {
                        window.location.reload();
                    }
                }
            });
        }
        function enable_shipping_price(customer_id) {
            jQuery.ajax({
                url: "{{ url('admin/enable-shipping-price') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "contactId": customer_id
                },
                success: function(response) {
                    if (response.msg == 'success') {
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            title: 'Charge Shipping Enabled',
                            timer: 3000,
                            showConfirmButton: false,
                            position: 'top',
                            timerProgressBar: true
                        });
                        window.location.reload();
                    } else {
                        Swal.fire({
                            toast: true,
                            icon: 'error',
                            title: 'Something went wrong',
                            timer: 3000,
                            showConfirmButton: false,
                            position: 'top',
                            timerProgressBar: true
                        });
                        window.location.reload();
                    }
                }
            });
        }
        function disable_shipping_price(customer_id) {
            jQuery.ajax({
                url: "{{ url('admin/disable-shipping-price') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "contactId": customer_id
                },
                success: function(response) {
                    if (response.msg == 'success') {
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            title: 'Charge Shipping Disabled',
                            timer: 3000,
                            showConfirmButton: false,
                            position: 'top',
                            timerProgressBar: true
                        });
                        window.location.reload();
                    } else {
                        Swal.fire({
                            toast: true,
                            icon: 'error',
                            title: 'Something went wrong',
                            timer: 3000,
                            showConfirmButton: false,
                            position: 'top',
                            timerProgressBar: true
                        });
                        window.location.reload();
                    }
                }
            });
        }
    </script>
@stop

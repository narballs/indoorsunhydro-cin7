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
                                            <select onchange="updatePriceColumn(4)" class="pricingColumn"
                                                id="pricingColumn">
                                                <?php
                                                $pricing = $customer->priceColumn;
                                                ?>
                                                <option class="form-group" value="RetailUSD" {{ $pricing }}
                                                    {{ isset($pricing) && $pricing == 'RetailUSD' ? 'selected="selected"' : '' }}>
                                                    Retail</option>
                                                <option class="form-group" value="WholesaleUSD" {{ $pricing }}
                                                    {{ isset($pricing) && $pricing == 'WholesaleUSD' ? 'selected="selected"' : '' }}>
                                                    Wholesale</option>
                                                <option class="form-group" value="TerraInternUSD" {{ $pricing }}
                                                    {{ isset($pricing) && $pricing == 'TerraInternUSD' ? 'selected="selected"' : '' }}>
                                                    TerraIntern
                                                </option>
                                                <option class="form-group" value="SacramentoUSD" {{ $pricing }}
                                                    {{ isset($pricing) && $pricing == 'SacramentoUSD' ? 'selected="selected"' : '' }}>
                                                    Sacramento
                                                </option>
                                                <option class="form-group" value="OklahomaUSD" {{ $pricing }}
                                                    {{ isset($pricing) && $pricing == 'OklahomaUSD' ? 'selected="selected"' : '' }}>
                                                    Oklahoma</option>
                                                <option class="form-group" value="CalaverasUSD" {{ $pricing }}
                                                    {{ isset($pricing) && $pricing == 'CalaverasUSD' ? 'selected="selected"' : '' }}>
                                                    Calaveras</option>
                                                <option class="form-group" value="Tier1USD" {{ $pricing }}
                                                    {{ isset($pricing) && $pricing == 'Tier1USD' ? 'selected="selected"' : '' }}>
                                                    Tier1</option>
                                                <option class="form-group" value="Tier2USD" {{ $pricing }}
                                                    {{ isset($pricing) && $pricing == 'Tier2USD' ? 'selected="selected"' : '' }}>
                                                    Tier2</option>
                                                <option class="form-group" value="Tier3USD" {{ $pricing }}
                                                    {{ isset($pricing) && $pricing == 'Tier3USD' ? 'selected="selected"' : '' }}>
                                                    Tier3</option>
                                                <option class="form-group" value="CommercialOKUSD" {{ $pricing }}
                                                    {{ isset($pricing) && $pricing == 'CommercialOKUSD' ? 'selected="selected"' : '' }}>
                                                    CommercialOK
                                                </option>
                                                <option class="form-group" value="CostUSD" {{ $pricing }}
                                                    {{ isset($pricing) && $pricing == 'CostUSD' ? 'selected="selected"' : '' }}>
                                                    Cost
                                                </option>
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
                                    <div class="col-md-2 d-flex justify-content-center align-items-center">
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
                                            <div class="col-md-6">
                                                @if (!$customer->contact_id)
                                                    <b>Cin7 ID:</b> <span class="badge bg-info">empty</span>
                                                    <br>
                                                @else
                                                    @if ($customer->contact_id)
                                                        <b>Cin7 ID:Parent Account</b> {{ $customer->contact_id }}
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
                                                        {{$customer->address1 ? $customer->address1  : $customer->postalAddress1 }}
                                                    </div>
                                                    <div>
                                                        {{$customer->address2 ? $customer->address2  : $customer->postalAddress2 }}
                                                    </div>
                                                    <div>
                                                        {{ $customer->postCode ? $customer->postCode :$customer->postalPostCode }}, {{ $customer->state ? $customer->state : $customer->postalState }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h3>Delivery Address</h3>
                                                <div class="col-md-10 bg-light">
                                                    <div>
                                                        {{$customer->address1 ? $customer->address1  : $customer->postalAddress1 }}
                                                    </div>
                                                    <div>
                                                        {{$customer->address2 ? $customer->address2  : $customer->postalAddress2 }}
                                                    </div>
                                                    <div>
                                                        {{ $customer->postCode ? $customer->postCode :$customer->postalPostCode }}, {{ $customer->state ? $customer->state : $customer->postalState }}
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
                                        <h2 class="h5 mb-0"><a href="#" class="text-muted"></a>Secondary Users</h2>
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
                                                <tr>
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
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Ref#</th>
                                        </tr>
                                        @foreach ($customer_orders as $customer_order)
                                            <tr>
                                                @if ($customer_order->order_id)
                                                    <td>
                                                        {{ $customer_order->id }}

                                                    </td>
                                                @else
                                                    <td class="badge bg-danger">Pending Approval</td>
                                                @endif
                                                <?php $createdDate = $customer_order->created_at;
                                                $formatedDate = $createdDate->format('F j, Y');
                                                ?>
                                                <td>
                                                    {{ $formatedDate }}
                                                </td>
                                                <td>
                                                    {{ $customer_order->status }}
                                                </td>
                                                <td>
                                                    {{ $customer_order->total }}
                                                </td>
                                                <td>
                                                    <a href="{{ url('admin/order-detail/' . $customer_order->id) }}">{{ $customer_order->reference }}
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
                        $('#spinner').addClass('d-none');
                        setInterval('location.reload()', 7000);
                    }
                    if (response.success == false) {
                        console.log(response.msg);
                        $('#spinner').addClass('d-none');
                        setInterval('location.reload()', 7000);
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
    </script>
@stop

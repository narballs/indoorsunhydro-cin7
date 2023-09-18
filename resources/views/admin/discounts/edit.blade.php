@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
    @if (count($errors) > 0)
    <div class="alert alert-danger alert-dismissible">
        <a href="#" class="close text-white" data-dismiss="alert" aria-label="close">&times;</a>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div>
        <form method="Post" action="{{ route('discounts.update' , $discount->id) }}">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body d-flex justify-content-center">
                        <div class="col-md-6">
                            <div class="row justify-content-center d-flex" style="flex-direction:column;">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h3 class="text-bold">
                                                Update Discount
                                            </h3>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <a href="{{route('discounts.index')}}" class="btn btn-primary btn-sm text-white">Back</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 border p-4">
                                    <div class="row">
                                        <div class="card w-100">
                                            <div class="card-body">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="type">Discount Type</label>
                                                        <select name="type" id="discount_type" class="form-control">
                                                            <option value="cart" {{$discount->type == 'cart' ? 'selected' : ''}}>Cart</option>
                                                            <option value="products" {{$discount->type == 'cart' ? 'products' : ''}}>Products</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card w-100">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    <label for="">Discount Method</label>
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div>
                                                            <input type="radio" name="mode" id="manuall_mode" value="{{$discount->mode == 'Manuall' ? 'Manuall' : ''}}" {{$discount->mode == 'Manuall' ? 'checked' : ''}}>
                                                            <label>Discount Code</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="mode" id="automatic_mode" value="{{$discount->mode == 'Automatic' ? 'Automatic' : ''}}" {{$discount->mode == 'Automatic' ? 'checked' : ''}}>
                                                            <label>Automatic Discount</label>
                                                        </div>
                                                    </div>
                                                    <div class="row {{$discount->mode == 'Automatic' ? 'd-none' : ''}}" id="discount_code_div">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <input type="text" name="discount_code" id="discount_code" value="{{$discount->discount_code}}" class="form-control">
                                                            </div>
                                                        </div>
                                                        <p class="ml-1">
                                                            Customers must enter this code at checkout.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
    
                                        </div>
                                        <div class="card w-100">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    <label for="">Value</label>
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <button type="button" class="btn btn-secondary discount_variation_btn"  {{$discount->discount_variation == 'percentage' ? 'checked' : ''}} id="percentage_btn" data-value="percentage">
                                                                    Percentage
                                                                </button>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <button type="button" class="btn btn-light discount_variation_btn" id="fixed_btn" data-value="fixed" {{$discount->discount_variation == 'fixed' ? 'checked' : ''}}>
                                                                    Fixed Amount
                                                                </button>
                                                            </div>
                                                            <input type="hidden" value="{{$discount->discount_variation}}" name="discount_variation" id="discount_variation">
                                                            <div class="col-md-7">
                                                                <input type="number" min="1" class="form-control" id="discount_variation_value" value="{{$discount->discount_variation_value}}" name="discount_variation_value" step="any" onkeydown="removeAlpha($(this))" onkeyup="removeAlpha($(this))">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card w-100">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    <label for="">
                                                        Minimum Purchase Requirements
                                                    </label>
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <input type="radio" data-value="none" value="{{$discount->minimum_purchase_requirements == 'none' ? 'none' : ''}}" name="minimum_purchase_requirements" class="purchase_requirements" {{$discount->minimum_purchase_requirements == 'none' ? 'checked' : ''}}>
                                                                <label for="">No minimum requirements</label>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <input type="radio" data-value="amount" value="{{$discount->minimum_purchase_requirements == 'amount' ? 'amount' : ''}}" name="minimum_purchase_requirements" class="purchase_requirements" id="add_amount" {{$discount->minimum_purchase_requirements == 'amount' ? 'checked' : ''}}>
                                                                <label for="">Minimum purchase amount ($)</label>
                                                            </div>
                                                            <input type="number" min="0" name="minimum_purchase_amount" id="minimum_purchase_amount" value="{{$discount->minimum_purchase_amount}}" class="form-control {{$discount->minimum_purchase_requirements == 'amount' ? '' : 'd-none'}}" placeholder="Minimum Purchase Amount" step="any" onkeydown="removeAlpha($(this))" onkeyup="removeAlpha($(this))">
                                                            <div class="col-md-12">
                                                                <input type="radio" data-value="quantity" value="{{$discount->minimum_purchase_requirements == 'quantity' ? 'quantity' : ''}}" name="minimum_purchase_requirements" class="purchase_requirements" id="add_item_qty" {{$discount->minimum_purchase_requirements == 'quantity' ? 'checked' : ''}}>
                                                                <label for="">Minimum quantity of items</label>
                                                            </div>
                                                            <input type="number" min="1" name="minimum_quantity_items" id="minimum_quantity_items" value="{{$discount->minimum_quantity_items}}" class="form-control {{$discount->minimum_purchase_requirements == 'quantity' ? '' : 'd-none'}}" placeholder="Minimum Items Quantity" onkeydown="removeAlpha($(this))" onkeyup="removeAlpha($(this))">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card w-100">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    <label for="">
                                                        Customer Eligibility
                                                    </label>
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <input type="radio" data-value="All Customers" value="{{$discount->customer_eligibility == 'All Customers' ? 'All Customers' : ''}}" class="customer_eligibility" id="all_customers" name="customer_eligibility" {{$discount->customer_eligibility == 'All Customers' ? 'checked' : ''}}>
                                                                <label for="">All Customers</label>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <input type="radio" data-value="Specific Customers" class="customer_eligibility" value="{{$discount->customer_eligibility == 'Specific Customers' ? 'Specific Customers' : ''}}" name="customer_eligibility" id="specific_customers" {{$discount->customer_eligibility == 'Specific Customers' ? 'checked' : ''}}>
                                                                <label for="">Specific Customers</label>
                                                            </div>
                                                            <div class="col-md-12 {{$discount->customer_eligibility == 'Specific Customers' ? '' : 'd-none'}}" id="selected_customers_div">
                                                                <button type="button" class="btn btn-primary btn-sm selected_customers_btn" data-toggle="modal" data-target="#modal_content">Selected Customers ({{!empty($customerDiscounts) ? count($customerDiscounts) : '' }})</button>
                                                            </div>
                                                            <input type="hidden" name="contactids[]" id="contact_ids_array" value="<?php echo implode(',', $customerDiscountsArray); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card w-100">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    <label for="">
                                                        Maximum Discount Uses
                                                    </label>
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <input type="radio" data-value="none" name="max_discount_uses" class="discount_uses" value="{{$discount->max_discount_uses == 'none' ? 'none' : ''}}" {{$discount->max_discount_uses == 'none' ? 'checked' : ''}}>
                                                                <label for="">None</label>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <input type="radio" data-value="Limit Max Times" class="discount_uses" name="max_discount_uses" value="{{$discount->max_discount_uses == 'Limit Max Times' ? 'Limit Max Times' : ''}}" id="limit_max_times" {{$discount->max_discount_uses == 'Limit Max Times' ? 'checked' : ''}}>
                                                                <label for="">Limit number of times this discount can be used in total</label>
                                                            </div>
                                                            <input type="number" name="max_usage_count" value="{{$discount->max_usage_count}}" id="max_usage_count" class="form-control {{$discount->max_discount_uses == 'Limit For User' ? '' : 'd-none'}}">
                                                            <div class="col-md-12">
                                                                <input type="radio" data-value="Limit For User" class="discount_uses" name="max_discount_uses"  value="{{$discount->max_discount_uses == 'Limit Max Times' ? 'Limit Max Times' : ''}}" {{$discount->max_discount_uses == 'Limit For User' ? 'checked' : ''}}>
                                                                <label for="">Limit to one use per customer</label>
                                                            </div>
                                                            <input type="number" name="limit_per_user" value="{{$discount->limit_per_user}}" id="limit_per_user" class="form-control {{$discount->max_discount_uses == 'Limit For User' ? '' : 'd-none'}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card w-100">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    <label for="">Active Dates</label>
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="">Start Date</label>
                                                                <input type="date" name="start_date" id="start_date" min="{{date("Y-m-d")}}" value="{{$discount->start_date != '' ? \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d') : ''}}" class="form-control" onchange="check_start_date()">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="">End Date</label>
                                                                <input type="date" name="end_date" min="{{$discount->start_date != '' ? \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d') : ''}}" id="end_date" value="{{$discount->end_date != '' ? \Carbon\Carbon::parse($discount->end_date)->format('Y-m-d') : ''}}" class="form-control" onchange="check_end_date()">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card w-100">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    <label for="">Status</label>
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        <label for="status"></label>
                                                        <select name="status" id="status" class="form-control">
                                                            <option value="1" {{$discount->status == 1 ? 'selected' : ''}}>Active</option>
                                                            <option value="0" {{$discount->status == 0 ? 'selected' : ''}}>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Pop up Modal For Specific Customers --}}
        <!-- Modal -->
        <div class="modal fade" id="modal_content" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Search Customers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <input type="text" name="search_contact" class="form-control" placeholder="Search ..." id="search_customer" onkeydown="searchCustomer()">
                    </div>
                    <div class="col-md-12 customer_search_div {{count($customerDiscounts) > 0 ? '' : 'd-none' }}">
                        <div class="dropdown-menu customerList w-100 d-flex align-items-center row">
                            @if (count($customerDiscounts) > 0)
                                @foreach ($customerDiscounts as $key => $value)
                                <div class="col-md-1">
                                    <input type="checkbox" name="contact_id[]" id="contact_edit_id_{{$value->contact_id}}" class="contact_id"  value="{{$value->contact_id}}" checked>
                                </div>
                                <div class="col-md-11">
                                    <a class="dropdown-item text-dark" type="button">{{$value->contact->firstName  . $value->contact->lastName}}
                                        <small class="ml-3">{{$value->contact->email}}</small>    
                                    </a>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="getContactids()">Save changes</button>
            </div>
            </div>
        </div>
        </div>
    @stop

    @section('css')
        <link rel="stylesheet" href="/theme/css/admin_custom.css">
        <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
        <style>
            .customerList {
                display: block;
                position: relative !important;
                top: 0px;
                max-height: 250px;
                overflow-y: scroll;
                overflow-x: hidden;
            }
            .text-successs {
                color: #7CC633 !important;
                font-family: 'Poppins', sans-serif !important;
            }

            .badge-success {
                color: #fff;
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
                background-color: #f1eaea;
                color: #B42318;
                padding: 6px !important;
                font-style: normal;
                font-weight: 500;
                font-size: 11.3289px;

            }

            .badge-secondary {
                color: #8e8b8b !important;
                background-color: #d0dce6 !important;
                padding: 7px !important;
                border-radius: 6px;
            }

            .badge-primary {
                background-color: #339AC6;
                color: #339AC6 !important;
                padding: 5px;
            }

            /* css for switch */
            .switch {
                position: relative;
                display: inline-block;
                vertical-align: top;
                width: 70px;
                height: 30px;
                margin: 0 10px 10px 0;
                background: linear-gradient(to bottom, #eeeeee, #FFFFFF 25px);
                background-image: -webkit-linear-gradient(top, #eeeeee, #FFFFFF 25px);
                border-radius: 18px;
                box-shadow: inset 0 -1px white, inset 0 1px 1px rgba(0, 0, 0, 0.05);
                cursor: pointer;
                box-sizing: content-box;
            }
            label {
                font-weight: inherit;
            }
            input[type=checkbox], input[type=radio] {
                margin: 4px 0 0;

                line-height: normal;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                padding: 0;
            }


            .switch-input {
                position: absolute;
                top: 0;
                left: 0;
                opacity: 0;
                box-sizing: content-box;
            }
            .switch-left-right .switch-input:checked ~ .switch-label {
                background: inherit;
            }
            .switch-input:checked ~ .switch-label {
                background: #E1B42B;
                box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.15), inset 0 0 3px rgba(0, 0, 0, 0.2);
            }
            .switch-left-right .switch-label {
                overflow: hidden;
            }
            .switch-label, .switch-handle {
                transition: All 0.3s ease;
                -webkit-transition: All 0.3s ease;
                -moz-transition: All 0.3s ease;
                -o-transition: All 0.3s ease;
            }
            .switch-label {
                position: relative;
                display: block;
                height: inherit;
                font-size: 15px;
                text-transform: uppercase;
                background: #eceeef;
                border-radius: inherit;
                box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.12), inset 0 0 2px rgba(0, 0, 0, 0.15);
                box-sizing: content-box;
            }
            .switch-left-right .switch-input:checked ~ .switch-label:before {
                opacity: 1;
                left: 100px;
            }
            .switch-input:checked ~ .switch-label:before {
                opacity: 0;
            }
            .switch-left-right .switch-label:before {
                background: #eceeef;
                text-align: left;
                padding-left: 48px!important;
            }
            .switch-left-right .switch-label:before, .switch-left-right .switch-label:after {
                width: 23px;
                height: 25px;
                top: 1px;
                left: 0;
                right: 0;
                bottom: 0;
                padding: 11px 0 0 0;
                text-indent: -12px;
                border-radius: 20px;
                box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.2), inset 0 0 3px rgba(0, 0, 0, 0.1);
            }
            .switch-label:before {
                content: attr(data-off);
                right: 11px;
                color: #aaaaaa;
                text-shadow: 0 1px rgba(255, 255, 255, 0.5);
            }

            span.switch-label:after {
                content: attr(data-on);
                left: 11px;
                color: #FFFFFF;
                text-shadow: 0 1px rgba(0, 0, 0, 0.2);
                position: absolute;
            
            }

            .switch-label:before, .switch-label:after {
                position: absolute;
                top: 50%;
                margin-top: -5px;
                line-height: 1;
                -webkit-transition: inherit;
                -moz-transition: inherit;
                -o-transition: inherit;
                transition: inherit;
                box-sizing: content-box;
            }

            .switch-left-right .switch-input:checked ~ .switch-label:after {
                left: 0!important;
                opacity: 1;
                padding-left: 20px;
            }


            .switch-input:checked ~ .switch-label:after {
                opacity: 1;
            }


            .switch-left-right .switch-label:after {
                text-align: left;
                text-indent: 9px;
                background: #28a745!important;
                left: -100px!important;
                opacity: 1;
                width: 100%!important;
            
            }
            .switch-left-right .switch-label:before, .switch-left-right .switch-label:after {
                width: 23px;
                height: 25px;
                top: 1px;
                left: 0;
                right: 0;
                bottom: 0;
                padding: 11px 0 0 0;
                text-indent: -12px;
                border-radius: 20px;
                box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.2), inset 0 0 3px rgba(0, 0, 0, 0.1);
            }
            .switch-input:checked ~ .switch-handle {
                left: 40px;
                box-shadow: -1px 1px 5px rgba(0, 0, 0, 0.2);
            }
            .switch-label, .switch-handle {
                transition: All 0.3s ease;
                -webkit-transition: All 0.3s ease;
                -moz-transition: All 0.3s ease;
                -o-transition: All 0.3s ease;
            }

            .switch-handle {
                position: absolute;
                top: 1px;
                left: 4px;
                width: 28px;
                height: 28px;
                background: linear-gradient(to bottom, #FFFFFF 40%, #f0f0f0);
                background-image: -webkit-linear-gradient(top, #FFFFFF 40%, #f0f0f0);
                border-radius: 100%;
                box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
            }

            .switch-handle:before {
                content: "";
                position: absolute;
                top: 50%;
                left: 50%;
                margin: -6px 0 0 -6px;
                width: 12px;
                height: 12px;
                background: linear-gradient(to bottom, #eeeeee, #FFFFFF);
                background-image: -webkit-linear-gradient(top, #eeeeee, #FFFFFF);
                border-radius: 6px;
                box-shadow: inset 0 1px rgba(0, 0, 0, 0.02);
            }
            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
            -webkit-appearance: none;
                margin: 0;
            }

            /* Firefox */
            input[type=number] {
                -moz-appearance: textfield;
            }

            /* dropdown with search */
 
        </style>
    @stop

    @section('js')

    <script>

        function check_start_date() {
            var start = document.getElementById('start_date');
            var end = document.getElementById('end_date');

            if (start.value) {
                end.min = start.value;
            } else {
                false;
            }
        }

        function check_end_date() {
            var start = document.getElementById('start_date');
            var end = document.getElementById('end_date');

            if (end.value) {
                start.max = end.value;
            } else {
                false;
            }
        }


        function removeAlpha(element) {
            var regExp = new RegExp('[a-zA-Z]'),
            inputVal = '';
            var value = element.val();
            if (regExp.test(value)) {
                element.val(inputVal)
            }
            else{ 
                inputVal = value
            }
        }
        //discount mode functionality
        $('#automatic_mode').click(function(){
            if ($('#automatic_mode').is(':checked')) {
                $('#discount_code_div').addClass('d-none');
                $('#manuall_mode').val('');
                $('#automatic_mode').val('Automatic');
            } else {
                $('#manuall_mode').removeClass('d-none');
                $('#manuall_mode').val('Manuall');
                $('#discount_code_div').removeClass('d-none');
            }
        });
        $('#manuall_mode').click(function(){
            if ($('#manuall_mode').is(':checked')) {
                $('#manuall_mode').removeClass('d-none');
                $('#automatic_mode').val('');
                $('#manuall_mode').val('Manuall');
                $('#discount_code_div').removeClass('d-none');
            } else {
                $('#discount_code_div').addClass('d-none');
                $('#manuall_mode').val('');
                $('#automatic_mode').val('Automatic');
            }
        });

        // discount variation functionality
        $('.discount_variation_btn').click(function(){
            var value = $(this).attr('data-value');
            $('#discount_variation').val(value);
            if (value == 'percentage') {
                $('#percentage_btn').addClass('btn-secondary');
                $('#percentage_btn').removeClass('btn-light');
                $('#fixed_btn').removeClass('btn-secondary');
                $('#fixed_btn').addClass('btn-light');
                $('#discount_variation_value').attr('placeholder', 'Percentage %');
            } else {
                $('#fixed_btn').removeClass('btn-light');
                $('#fixed_btn').addClass('btn-secondary');
                $('#percentage_btn').removeClass('btn-secondary');
                $('#percentage_btn').addClass('btn-light');
                $('#discount_variation_value').attr('placeholder', 'Fixed Amount');
            }
        });

        // minimum purchase requirements functionality
        $('.purchase_requirements').click(function() {
            if ($(this).attr('data-value') == 'amount') {
                $('#minimum_purchase_amount').removeClass('d-none');
                $('#minimum_quantity_items').addClass('d-none');
                $('#minimum_quantity_items').val('');
            } 
            else if ($(this).attr('data-value') == 'quantity') {
                $('#minimum_purchase_amount').addClass('d-none');
                $('#minimum_quantity_items').removeClass('d-none');
                $('#minimum_purchase_amount').val('');
            } else {
                $('#minimum_purchase_amount').addClass('d-none');
                $('#minimum_quantity_items').addClass('d-none');
                $('#minimum_purchase_amount').val('');
                $('#minimum_quantity_items').val('');
            }
        });

        // customer eligibility functionality
        $('.customer_eligibility').click(function() {
            if ($(this).attr('data-value') == 'Specific Customers') {
                $('#all_customers').val('');
                $(this).val('Specific Customers');
                $(this).attr('checked', true);
                $('#selected_customers_div').removeClass('d-none');
                $('#modal_content').modal({
                    show: true
                });
            } else {
                $('#Specific Customers').val('');
                $('#contact_ids_array').val('');
                $(this).val('All Customers');
                $('#selected_customers_div').addClass('d-none');
                $('#specific_customers').attr('checked', false);
                $(this).attr('checked', true);
            }
        });

        // search customer functionality

        function searchCustomer() {
            var search = $('#search_customer').val();
            if (search.length >=1 && !null) {
                $.ajax({
                    url: '/admin/search/customer',
                    type: 'Post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content')
                    },
                    data: {
                        search: search
                    },
                    success: function(response) {
                        if ((response.contacts.length) > 0  && (response.success == true)) {
                            $('.customer_search_div').removeClass('d-none');
                            
                            $.each(response.contacts, function(key, value) {
                                $('.customerList').append(
                                    `<div class="col-md-1">
                                        <input type="checkbox" name="contact_id" id="contact_edit_id_`+value.id+`" class="contact_id" value="`+value.id+`">
                                    </div>
                                    <div class="col-md-11">
                                        <a class="dropdown-item text-dark" type="button">`+value.firstName+` `+value.lastName+`
                                            <small class="ml-3">`+value.email+`</small>    
                                        </a>
                                        
                                    </div>`
                                );

                                if ((value.customer_discount != null) && (value.customer_discount.contact_id == value.id)) {
                                    var matchId = $('.contact_id');
                                    $.each(matchId, function(key, val) {
                                        if (val.value == value.customer_discount.contact_id) {
                                            $('#contact_edit_id_'+val.value).attr('checked', true);
                                        } else {
                                            $('contact_edit_id_'+val.value).attr('checked', false);
                                        }
                                    });
                                    
                                }
                            });
                        } else {
                            $('.customerList').append(
                                `<div class="col-md-12">
                                    <a class="dropdown-item">No Record Found</a>
                                </div>`
                            );
                        }
                    }
                });
            } 
            else if (search.length == 0) {
                $('.customer_search_div').addClass('d-none');
                // $('.customerList').html('');
            }
            else {
                $('.customer_search_div').addClass('d-none');
                // $('.customerList').html('');
            }
        }

        function getContactids () {
            
            var contact_ids = [];
            var filterIds = [];
            var discounted_customer_ids = [];
            var customer_array = $('#contact_ids_array').val().split(',');
            if ($('.contact_id').is(':checked')) {
                $('.contact_id:checked').each(function() {
                    contact_ids.push($(this).val());
                });

                $('#modal_content').modal('hide');
            } else {
                $('#modal_content').modal('hide');
            }
            var array_result = $.merge(contact_ids,customer_array);
            for (let index = 0; index < array_result.length; index++) {
                var contactIds = array_result[index];
                var contact_element = $('#contact_edit_id_'+contactIds).attr('checked' , true);
                console.log(contact_element , 'element');
                for (let i = 0; i < contact_element.length; i++) {
                    var element = contact_element[i];
                    filterIds.push(element.value);
                    
                }
            }
            var unique = filterIds.filter(function(itm, i, a) {
                return i == a.indexOf(itm);
            });
            for (let index = 0; index < unique.length; index++) {
                var unique_contact_id = unique[index];
                if ($('#contact_edit_id_'+unique_contact_id).attr('checked' , true)) {
                    discounted_customer_ids.push($('#contact_edit_id_'+unique_contact_id).val())
                }
            }
            console.log(discounted_customer_ids);
            $('#contact_ids_array').val(discounted_customer_ids);
            $('#modal_content').modal('hide');
        }

        // max discount uses functionality
        $('.discount_uses').click(function() {
            if ($(this).attr('data-value') == 'Limit Max Times') {
                $('#max_usage_count').removeClass('d-none');
                $('#limit_per_user').addClass('d-none');
                $(this).val('Limit Max Times');
            } else if ($(this).attr('data-value') == 'Limit For User') {
                $('#max_usage_count').addClass('d-none');
                $('#limit_per_user').removeClass('d-none');
                $(this).val('Limit For User');
            } else {
                $('#max_usage_count').addClass('d-none');
                $('#limit_per_user').addClass('d-none');
                $(this).val('none');
            }
        });
        
    </script>
    

    @stop

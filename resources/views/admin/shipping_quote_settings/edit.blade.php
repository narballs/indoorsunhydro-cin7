@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
<div class="row">
    <div class="col-md-6">
        <h1>Edit Shipping Quote Settings</h1>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('shipping_quotes.settings.index') }}" class="btn btn-primary text-white">Back</a>
    </div>
</div>
@stop
@section('content')
    <div>
        <form method="POST" action="{{ route('shipping_quotes.settings.update' , $shipping_quote_setting->id) }}">
            @csrf
            <div class="row">
                @if ($errors->any())
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="col-md-12 card">
                    <div class="card-body">
                        <div class="row justify-conntent-between">
                                
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="service_name">Service Name</label>
                                    <input type="text" class="form-control" id="service_name" aria-describedby="titleHelp"
                                    name="service_name"  value="{{!empty($shipping_quote_setting->service_name) ? $shipping_quote_setting->service_name : ''}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="service_code">Service Code</label>
                                    <input type="text" class="form-control" id="service_code" aria-describedby="titleHelp"
                                    name="service_code" value="{{!empty($shipping_quote_setting->service_code) ? $shipping_quote_setting->service_code : ''}}">
                                </div>
                            </div>    
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="carrier_name">Carrier Name</label>
                                    <input type="text" class="form-control" id="carrier_name" aria-describedby="titleHelp"
                                    name="carrier_name" value="{{!empty($shipping_quote_setting->carrier_name) ? $shipping_quote_setting->carrier_name : ''}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="carrier_code">Carrier Code</label>
                                    <input type="text" class="form-control" id="carrier_code" aria-describedby="titleHelp"
                                    name="carrier_code" value="{{!empty($shipping_quote_setting->carrier_code) ? $shipping_quote_setting->carrier_code : ''}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="package_type">Package Type</label>
                                    <input type="text" class="form-control" id="package_type" aria-describedby="titleHelp"
                                    name="type" value="{{!empty($shipping_quote_setting->type) ? $shipping_quote_setting->type : ''}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="surcharge_type">Surcharge Type</label>
                                    <select class="form-control" id="surcharge_type" name="surcharge_type" aria-describedby="titleHelp" name="surcharge_type">
                                        <option value="percentage" {{!empty($shipping_quote_setting->surcharge_type) && $shipping_quote_setting->surcharge_type == 'percentage' ? 'selected' : ''}}>Percentage</option>
                                        <option value="fixed" {{!empty($shipping_quote_setting->surcharge_type) && $shipping_quote_setting->surcharge_type == 'fixed' ? 'selected' : ''}}>Fixed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="surcharge_value">Surcharge Value</label>
                                    <input type="text" class="form-control" name="surcharge_value" id="surcharge_value" aria-describedby="titleHelp"
                                    name="surcharge_value" value="{{!empty($shipping_quote_setting->surcharge_value) ? $shipping_quote_setting->surcharge_value : ''}}" step="any">
                                </div>
                            </div>    
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status" aria-describedby="titleHelp" name="status">
                                        <option value="1" {{!empty($shipping_quote_setting->status) && $shipping_quote_setting->status == 1 ? 'selected' : ''}}>Active</option>
                                        <option value="0" {{!empty($shipping_quote_setting->status) && $shipping_quote_setting->status == 0 ? 'selected' : ''}}>Inactive</option>
                                    </select>
                                </div>
                            </div>          
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @stop
    @section('css')
        <link rel="stylesheet" href="/theme/css/admin_custom.css">
        <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
        <style>
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
        </style>
    @stop
    @section('js')
    @stop
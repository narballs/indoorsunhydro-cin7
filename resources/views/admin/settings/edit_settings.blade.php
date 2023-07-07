@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Add New Settings</h1>
@stop

@section('content')
    <?php //dd($shippingmethod);
    ?>
    <div>
        <form method="POST" action="{{ url('admin/settings/update/' .$setting->id) }}">
            @csrf
            <div class="row">
                <div class="col-md-12 card">
                    <div class="card-body">
                        <div class="row justify-conntent-between">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="option_name">Option Name</label>
                                    <input type="text" class="form-control" id="option_name" aria-describedby="titleHelp"
                                    name="option_name" value="{{$setting->option_name}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="option_value">Option Value</label>
                                <div class="form-group">
                                    <input type="hidden" value="{{$setting->option_value}}" id="option_value_hidden" name="option_value">
                                    <label class="switch switch-left-right">
                                        <input class="switch-input" type="checkbox" name="" 
                                        id="option_value_edit" value="{{$setting->option_value}}" {{$setting->option_value == 'Yes' ? 'checked' : ''}}>
                                        <span class="switch-label" data-on="Yes" data-off="No"></span> 
                                        <span class="switch-handle"></span> 
                                    </label>
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

    <script>
        $('#option_value_edit').click(function(){
            if ($('#option_value_edit').is(':checked')){
                $('#option_value_edit').attr('checked' ,true);
                $('#option_value_edit').val('Yes');
                $('#option_value_hidden').val('Yes');
            }
            else{
                $('#option_value_edit').attr('checked' ,false);
                $('#option_value_edit').val('No');
                $('#option_value_hidden').val('No');

            }
        });
    </script>

    @stop

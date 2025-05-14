@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
@stop
@section('content')
<div class="row">
    <div class="col-md-8 mt-5">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Stock Report Settings</h3>
            </div>
            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin_update_stock_report_settings') }}" id="interval-form">
                    @csrf

                    <label>Email Recipients (comma-separated)</label>
                    <input id="email-tags" name="emails" class="form-control" value="{{ old('emails', $admin_stock_report_settings->emails ?? '') }}">

                    <div id="admin_stock_report_interval">
                        @if(old('admin_stock_report_interval', $admin_stock_report_settings->admin_stock_report_interval ?? false))
                            @foreach(old('admin_stock_report_interval', $admin_stock_report_settings->admin_stock_report_interval) as $index => $admin_stock_report_interval)
                                <div class="interval-set my-2" data-index="{{ $index }}">
                                    <div class="row align-items-end">
                                        <div class="col-md-5">
                                            <label>Date</label>
                                            <div class="form-group mb-0">
                                                <input type="date" name="admin_stock_report_interval[{{ $index }}][report_date]"
                                                    value="{{ is_array($admin_stock_report_interval) ? $admin_stock_report_interval['report_date'] : $admin_stock_report_interval->report_date }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <label>Time</label>
                                            <div class="form-group mb-0">
                                                <input type="time" name="admin_stock_report_interval[{{ $index }}][report_time]"
                                                    value="{{ is_array($admin_stock_report_interval) ? $admin_stock_report_interval['report_time'] : \Carbon\Carbon::parse($admin_stock_report_interval->report_time)->format('H:i') }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger remove-interval">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="interval-set my-2" data-index="0">
                                <div class="row align-items-center">
                                    <div class="col-md-5">
                                        <label>Date</label>
                                        <div class="form-group mb-0">
                                            <input type="date" name="admin_stock_report_interval[0][report_date]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label>Time</label>
                                        <div class="form-group mb-0">
                                            <input type="time" name="admin_stock_report_interval[0][report_time]" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <button type="button" id="add-interval" class="btn btn-secondary mt-3 mr-3">Add New Date & Time Interval</button>
                    <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
    <link rel="stylesheet" href="/theme/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
    <!-- Tagify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">

    <!-- Tagify JS -->
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
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

        /* Switch CSS */
        .switch {
            position: relative;
            display: inline-block;
            vertical-align: top;
            width: 70px;
            height: 30px;
            margin: 0 10px 10px 0;
            background: linear-gradient(to bottom, #eeeeee, #FFFFFF 25px);
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

        .switch-handle {
            position: absolute;
            top: 1px;
            left: 4px;
            width: 28px;
            height: 28px;
            background: linear-gradient(to bottom, #FFFFFF 40%, #f0f0f0);
            border-radius: 100%;
            box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
        }
        
        .tagify {
            min-height: 80px !important;
            max-height: auto !important;
            overflow-y: auto;
            display: flex;
            flex-wrap: wrap;
            padding: 0rem 0.4rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            background: #fff;
        }

        .tagify__tag {
            margin: 3px 8px;  /* Increased space between tags */
        }

        .tagify__input {
            min-width: 120px;
            flex: 1;
            margin: 3px 0;
            padding: 0;
            line-height: 1.5;
            font-size: 1rem;
            outline: none;
            border: none;
        }


        /* tagify  */
        
    </style>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const emailInput = document.querySelector('#email-tags');
            new Tagify(emailInput, {
                delimiters: ",",
                pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                dropdown: { enabled: 0 }
            });

            const addButton = document.getElementById('add-interval');

            if (addButton) {
                addButton.addEventListener('click', function () {
                    const intervalContainer = document.getElementById('admin_stock_report_interval');
                    const newIndex = intervalContainer.querySelectorAll('.interval-set').length;

                    const newIntervalSet = document.createElement('div');
                    newIntervalSet.classList.add('interval-set', 'my-2');
                    newIntervalSet.setAttribute('data-index', newIndex);

                    newIntervalSet.innerHTML = `
                        <div class="row align-items-end">
                            <div class="col-md-5">
                                <label>Date</label>
                                <div class="form-group mb-0">
                                    <input type="date" name="admin_stock_report_interval[${newIndex}][report_date]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label>Time</label>
                                <div class="form-group mb-0">
                                    <input type="time" name="admin_stock_report_interval[${newIndex}][report_time]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-interval">Remove</button>
                            </div>
                        </div>
                    `;

                    intervalContainer.appendChild(newIntervalSet);

                    newIntervalSet.querySelector('.remove-interval').addEventListener('click', function () {
                        newIntervalSet.remove();
                    });
                });
            }

            document.querySelectorAll('.remove-interval').forEach(function (button) {
                button.addEventListener('click', function () {
                    button.closest('.interval-set').remove();
                });
            });
        });
    </script>

@stop
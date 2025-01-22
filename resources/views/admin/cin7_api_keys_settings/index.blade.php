@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
@stop
@section('content')
    <div class="container mt-4 mx-0">
        <form>
            <div class="card p-4">
                <div class="mb-3">
                    
                    <div class="row align-items-center">
                        <label class="form-label ">
                            <strong>Select Date</strong>
                        </label>
                        
                    </div>
                    <div class="row">
                        <div class="col-12 p-0">
                            <div class="row align-items-center">
                                <div class="col-4">
                                    <div class="form-group mb-0">
                                        <input type="date" id="dateInputApi" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="col-1">
                                    <button id="todayBtn" class="btn btn-sm btn-primary mx-2 active">Today</button>
                                </div>
                                <div class="col-2">
                                    <button id="yesterdayBtn" class="btn btn-sm btn-secondary mx-2">Yesterday</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- First Key Block -->
                @if (count($cin7_api_keys) > 0)
                    @foreach ($cin7_api_keys as $cin7_api_key)
                        <div class="row">
                            <div class="col-md-12 p-0">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        
                
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <div class="row justify-content-center">
                                                    <h5 class="card-title text-center">
                                                        <strong>{{$cin7_api_key->name}}</strong>
                                                    </h5>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="row justify-content-center">
                                                    <span class="">
                                                        Threshold: <input type="number" class="form-control d-inline-block w-auto" value="{{$cin7_api_key->threshold}}">
                                                        <button class="btn btn-danger btn-sm">Stop</button>
                                                        <button class="btn btn-primary btn-sm">Refresh</button>
                                                        <span class="badge bg-success">Active</span>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                        </div>
                    
                                        <ul class="list-group mt-3">
                                            <li class="list-group-item d-flex justify-content-between">SYNC PRODUCTS <span>1 requests</span></li>
                                            <li class="list-group-item d-flex justify-content-between">SYNC CONTACTS <span>2 requests</span></li>
                                            <li class="list-group-item d-flex justify-content-between">PRODUCT DETAIL UPDATE STOCK <span>1 requests</span></li>
                                            <li class="list-group-item d-flex justify-content-between">STOCK NOTIFICATION TO USERS FROM INDOORSUN <span>3 requests</span></li>
                                            <li class="list-group-item d-flex justify-content-between">CREATE ORDER <span>3 requests</span></li>
                                            <li class="list-group-item d-flex justify-content-between"><strong>TOTAL REQUESTS</strong> <span>10 requests</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Event Log -->
                        <div class="row">
                            <div class="col-md-12 p-0">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5 class="card-title mb-2"><strong>{{$cin7_api_key->name}} Events Logs</strong></h5>
                                            </div>
                                            <div class="col-md-12">
                                                <ul class="list-group">
                                                    <li class="list-group-item">2025-01-14 00:15:23 - key1_ABC stopped: Threshold reached</li>
                                                    <li class="list-group-item">2025-01-14 10:30:45 - key2_XYZ stopped manually</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="row">
                        <button class="btn btn-primary">Update</button>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-12 p-0">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="card-title text-center">
                                                <strong>No API keys found</strong>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </form>
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
    </style>
@stop

@section('js')
    <script>
        // Set default date to today
        document.addEventListener("DOMContentLoaded", function () {
            let dateInputApi = document.getElementById('dateInputApi');
            let today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
            dateInputApi.value = today;

            // Button click events
            document.getElementById('todayBtn').addEventListener('click', function (e) {
                e.preventDefault();
                dateInputApi.value = today;
                toggleButtonClass(this, document.getElementById('yesterdayBtn'));
            });

            document.getElementById('yesterdayBtn').addEventListener('click', function (e) {
                e.preventDefault();
                let yesterday = new Date();
                yesterday.setDate(yesterday.getDate() - 1);
                dateInputApi.value = yesterday.toISOString().split('T')[0];
                toggleButtonClass(this, document.getElementById('todayBtn'));
            });

            function toggleButtonClass(activeBtn, inactiveBtn) {
                activeBtn.classList.remove('btn-secondary');
                activeBtn.classList.add('btn-primary');

                inactiveBtn.classList.remove('btn-primary');
                inactiveBtn.classList.add('btn-secondary');
            }
        });
    </script>
@stop
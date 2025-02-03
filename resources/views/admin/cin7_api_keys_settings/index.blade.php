@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
@stop
@section('content')
    <div class="container mt-4 mx-0">
        <div class="card p-4">
            <div class="mb-3">
                
                <div class="row align-items-center">
                    <label class="form-label ">
                        <strong>Select Date</strong>
                    </label>
                    
                </div>
                <div class="row">
                    <div class="col-12 p-0">
                        <form method="GET" action="{{ url('/admin/cin7-api-keys-settings') }}" id="dateForm">
                            <div class="row align-items-center">
                                <div class="col-4">
                                    <div class="form-group mb-0">
                                        <input 
                                            type="date" 
                                            id="dateInputApi" 
                                            name="current_date" 
                                            class="form-control" 
                                            value="{{ request('current_date', \Carbon\Carbon::today()->toDateString()) }}" 
                                            onchange="this.form.submit();"
                                        >
                                    </div>
                                </div>
                                <div class="col-1">
                                    <button type="submit" id="todayBtn" 
                                        class="btn btn-sm mx-2 text-white {{ request('current_date', \Carbon\Carbon::today()->toDateString()) == \Carbon\Carbon::today()->toDateString() ? 'btn-primary active' : 'btn-secondary' }}">
                                        Today
                                    </button>
                                </div>
                                <div class="col-2">
                                    <button type="submit" id="yesterdayBtn" 
                                        class="btn btn-sm mx-2 text-white {{ request('current_date', \Carbon\Carbon::today()->toDateString()) == \Carbon\Carbon::yesterday()->toDateString() ? 'btn-primary active' : 'btn-secondary' }}">
                                        Yesterday
                                    </button>
                                </div>
                            </div>
                        </form>  
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
                                                    Threshold: <input type="number" id="key_thrshold_{{$cin7_api_key->id}}"  max="5000" name="total_thresold" class="form-control d-inline-block w-auto" value="{{$cin7_api_key->threshold}}">
                                                    @if (request('current_date', \Carbon\Carbon::today()->toDateString()) >= \Carbon\Carbon::today()->toDateString())
                                                        <button 
                                                            type="button" 
                                                            onclick="stop_api('{{ $cin7_api_key->id }}')" 
                                                            class="btn {{ $cin7_api_key && $cin7_api_key->is_stop == 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                            {{ $cin7_api_key && $cin7_api_key->is_stop == 1 ? 'Resume' : 'Stop' }}
                                                        </button>
                                                        <button type="button" id="update_total_threshold" onclick="update_key_threshold('{{$cin7_api_key->id}}')" class="btn btn-primary btn-sm text-white">Refresh/Update</button>
                                                        <span id="key_badge_{{$cin7_api_key->id}}" class="badge {{ $cin7_api_key && $cin7_api_key->is_stop == 0 ? 'bg-success' : 'bg-danger' }}">{{ $cin7_api_key && $cin7_api_key->is_stop == 0 ? 'Active' : 'Inactive' }}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    @if (count($cin7_api_key->api_endpoint_requests) > 0)
                                    <ul class="list-group mt-3">
                                        @foreach ($cin7_api_key->api_endpoint_requests as $api_endpoint_request)
                                            <li class="list-group-item d-flex justify-content-between">{{$api_endpoint_request->title}}<span>{{$api_endpoint_request->request_count}} requests</span></li>
                                        @endforeach
                                    </ul>
                                    <ul class="list-group mt-3">
                                        <li class="list-group-item d-flex justify-content-between"><strong>TOTAL REQUESTS</strong> <span>{{$cin7_api_key->request_count}} requests</span></li>
                                    </ul>
                                    @else
                                        <ul class="list-group mt-3">
                                            <li class="list-group-item d-flex justify-content-between"><strong>TOTAL REQUESTS</strong> <span>{{$cin7_api_key->request_count}} requests</span></li>
                                        </ul>
                                        <ul class="list-group">
                                            <li class="list-group-item">No Endpoint requests found</li>
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Event Log -->
                    @if (count($cin7_api_key->api_event_logs) > 0)
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
                                                    @foreach ($cin7_api_key->api_event_logs as $api_event_logs)
                                                        <li class="list-group-item">{{$api_event_logs->created_at}} - {{$api_event_logs->description}}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let dateForm = document.getElementById('dateForm');
            let dateInputApi = document.getElementById('dateInputApi');
            let todayBtn = document.getElementById('todayBtn');
            let yesterdayBtn = document.getElementById('yesterdayBtn');

            if (!dateForm || !dateInputApi || !todayBtn || !yesterdayBtn) {
                console.error("One or more elements not found in the DOM!");
                return;
            }

            let today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
            let yesterday = new Date();
            yesterday.setDate(yesterday.getDate() - 1);
            let yesterdayFormatted = yesterday.toISOString().split('T')[0];

            // Set default date if empty
            dateInputApi.value = dateInputApi.value || today;

            // Button click events
            todayBtn.addEventListener('click', function (e) {
                e.preventDefault();
                dateInputApi.value = today;
                toggleButtonClass(todayBtn, yesterdayBtn);
                submitForm();
            });

            yesterdayBtn.addEventListener('click', function (e) {
                e.preventDefault();
                dateInputApi.value = yesterdayFormatted;
                toggleButtonClass(yesterdayBtn, todayBtn);
                submitForm();
            });

            function toggleButtonClass(activeBtn, inactiveBtn) {
                activeBtn.classList.remove('btn-secondary');
                activeBtn.classList.add('btn-primary', 'active');

                inactiveBtn.classList.remove('btn-primary', 'active');
                inactiveBtn.classList.add('btn-secondary');
            }

            function submitForm() {
                if (dateForm) {
                    dateForm.submit();
                } else {
                    console.error("Form with ID 'dateForm' not found!");
                }
            }
        });



        function stop_api(id) {
            var dateInputApi = document.getElementById('dateInputApi');
        
            $.ajax({
                url: "{{ url('/admin/cin7-api-keys-settings/stop-api') }}",
                type: 'POST',
                data: {
                    id: id,
                    current_date: dateInputApi.value,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.status === 'success') {
                        var is_stop  = response.is_stop;

                        if (is_stop == 1) {
                            $('#key_badge_' + id).removeClass('bg-success');
                            $('#key_badge_' + id).addClass('bg-danger');
                            $('#key_badge_' + id).text('Inactive');
                        } else {
                            $('#key_badge_' + id).removeClass('bg-danger');
                            $('#key_badge_' + id).addClass('bg-success');
                            $('#key_badge_' + id).text('Active');
                        }

                        Swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
            });
        }


        function update_key_threshold(id) {
            var dateInputApi = document.getElementById('dateInputApi');
            var threshold = $('#key_thrshold_' + id).val();
            if (threshold == '') {
                Swal.fire({
                    title: 'Error',
                    text: 'Please enter threshold value',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            if (threshold == 0 ) {
                Swal.fire({
                    title: 'Error',
                    text: 'Threshold value should be greater than 0',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            $.ajax({
                url: "{{ url('/admin/cin7-api-keys-settings/update-threshold') }}",
                type: 'POST',
                data: {
                    id: id,
                    threshold: threshold,
                    current_date: dateInputApi.value,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
            });
        }

    </script>
@stop
@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
@stop
@section('content')
<div class="row">
    <div class="col-md-8 mt-5">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Auto Label Settings</h3>
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

                <form method="post" action="{{ route('update_label_settings') }}">
                    @csrf
                    <!-- Days of the Week Section -->
                    <div class="form-group">
                        <label class="font-weight-bold">Days of Week:</label><br>
                        <div class="btn-group-toggle" data-toggle="buttons">
                            @foreach(['M', 'T', 'W', 'TH', 'F', 'ST', 'S'] as $day)
                                <label class="btn btn-outline-primary {{ in_array($day, $selectedDays) ? 'active' : '' }}">
                                    <input type="checkbox" name="days_of_week[]" class="day d-none" value="{{ $day }}" {{ in_array($day, $selectedDays) ? 'checked' : '' }}> {{ $day }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Time Range Section -->
                    <div class="form-group" id="time-range-container">
                        <label class="font-weight-bold">Time of Day:</label>
                        <div id="time-range-group">
                            @foreach($start_times as $index => $start_time)
                                <div class="row align-items-center">
                                    <div class="time-range mt-2 col-md-4">
                                        <input type="text" name="time_ranges[]" class="time-slider" data-start="{{ $start_time }}" data-end="{{ $end_times[$index] }}">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control start-time" value="{{ $start_time }}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control end-time" value="{{ $end_times[$index] }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-danger ml-2 remove-time-range btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-time-range" class="btn btn-info mt-2">Add Time Range <i class="fa fa-plus ml-2"></i></button>
                    </div>

                    <!-- Delay Processing Section -->
                    <div class="form-group">
                        <div>
                            <input type="checkbox" id="delay_checkbox" name="delay_processing" value="1" {{ !empty($auto_label_settings->delay_processing) ? 'checked' : '' }}>
                            <label for="delay_checkbox">Delay processing of next order by</label>
                        </div>
                        <div class="input-group mt-2">
                            <input type="number" name="delay_duration" id="delay_duration" class="form-control col-3" value="{{ $auto_label_settings->delay_duration ?? 5 }}" min="1">
                            <select name="delay_unit" id="delay_unit" class="form-control col-4 ml-2">
                                <option value="Minutes" {{ !empty($auto_label_settings) && $auto_label_settings->delay_unit == 'Minutes' ? 'selected' : '' }}>Minutes</option>
                                <option value="Hours" {{ !empty($auto_label_settings) && $auto_label_settings->delay_unit == 'Hours' ? 'selected' : '' }}>Hours</option>
                            </select>
                        </div>
                    </div>

                    <!-- Save Settings Button -->
                    <div class="">
                        <button id="saveBtnLabel" type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
    <link rel="stylesheet" href="/theme/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>

    <script>
    $(document).ready(function () {
        // Function to initialize time sliders
        function initTimeSliders() {
            $('.time-slider').each(function () {
                $(this).ionRangeSlider({
                    type: 'double',
                    grid: true,
                    min: 0,
                    max: 1440,
                    step: 15,
                    from: timeToMinutes($(this).data('start')),
                    to: timeToMinutes($(this).data('end')),
                    drag_interval: true,
                    prettify: function (num) {
                        return minutesToTime(num);
                    },
                    onFinish: function (data) {
                        var parent = $(data.input).closest('.row');
                        parent.find('.start-time').val(minutesToTime(data.from));
                        parent.find('.end-time').val(minutesToTime(data.to));
                    }
                });
            });
        }

        // Convert time to minutes
        function timeToMinutes(time) {
            var parts = time.split(':');
            return parseInt(parts[0]) * 60 + parseInt(parts[1]);
        }

        // Convert minutes back to time (HH:mm format)
        function minutesToTime(minutes) {
            var hours = Math.floor(minutes / 60);
            var mins = minutes % 60;
            return ('0' + hours).slice(-2) + ':' + ('0' + mins).slice(-2);
        }

        // Get current Los Angeles time and format it
        function getCurrentTimeInLA() {
            var date = new Date();
            var options = { timeZone: 'America/Los_Angeles', hour: '2-digit', minute: '2-digit' };
            return date.toLocaleTimeString('en-US', options);
        }

        // Initialize time sliders with California (Los Angeles) time
        initTimeSliders();

        // Add time range button click handler
        $('#add-time-range').click(function () {
            // Get current time in Los Angeles
            var currentLAStartTime = getCurrentTimeInLA(); // Example: "09:00"
            var currentLAEndTime = getCurrentTimeInLA(); // Example: "17:00"

            $('#time-range-group').append(`
                <div class="row align-items-center">
                    <div class="time-range mt-2 col-md-4">
                        <input type="text" name="time_ranges[]" class="time-slider" data-start="${currentLAStartTime}" data-end="${currentLAEndTime}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control start-time" value="${currentLAStartTime}" readonly>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control end-time" value="${currentLAEndTime}" readonly>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-danger ml-2 remove-time-range btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `);

            initTimeSliders(); // Reinitialize sliders
        });

        // Remove time range button click handler
        $(document).on('click', '.remove-time-range', function () {
            $(this).closest('.row').remove();
        });
    });

    </script>
@stop

@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

@stop
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="table-wrapper">
        <div class="card-body product_secion_main_body">
            <div class="row border-bottom product_section_header">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-10">
                            <p class="product_heading">
                                DAILY API LOGS
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body product_table_body">
                <div id="admin-users"></div>
                <div class="col-md-12 shadow border order-table-items-data">
                    <table class="table  bg-white table-users" id="user-table">
                        <tr>
                            <thead>
                                <tr class="table-header-background">
                                    <td width="250">
                                        <span class="d-flex table-row-item">Date</span>
                                    </td>
                                    <td width="250">
                                        <span class="d-flex table-row-item">Endpoint</span>
                                    </td>
                                    <td>
                                        <span class="d-flex table-row-item">Count</span>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total_count = 0; ?>
                                @foreach ($daily_api_logs as $key => $daily_api_log)
                                    <tr id="row-{{ $daily_api_log->id }}" class="user-row border-bottom">
                                        <td class="user_name">
                                            {{ date('F j, Y', strtotime($daily_api_log->date)) }}
                                        </td>
                                        <td class="user_table_items">
                                            {{ strtoupper(str_replace('_', ' ', $daily_api_log->api_endpoint)) }}
                                        </td>
                                        <td class="user_table_items">
                                            {{ $daily_api_log->count }}
                                            <?php $total_count += $daily_api_log->count; ?> 
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="user-row border-bottom">
                                    <td colspan="2" class="text-right text-bold">Total Count:</td>
                                    <td class="text-left text-bold">{{ number_format($total_count) }}</td>
                                </tr>
                            </tbody>
                    </table>
                </div>
            </div>
        @endsection
    </div>
</div>
@section('css')
    <link rel="stylesheet" href="/theme/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
@stop    
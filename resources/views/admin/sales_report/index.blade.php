@extends('adminlte::page')
@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')
<div class="">
    <div class="card-body mt-2 product_secion_main_body extra-sales-report-body">

        <h3 class="fw-bold text-dark mb-4 extra-sales-report-heading">Sales Reports</h3>

        {{-- Success Alert --}}
        @if (\Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                {!! \Session::get('success') !!}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @elseif (\Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                {!! \Session::get('error') !!}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('sales-report.index') }}" class="mb-4 gx-3 gy-2 align-items-end border p-3 rounded bg-light shadow-sm extra-sales-report-filter">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label class="fw-semibold extra-filter-label">From</label>
                        <input type="date" name="from" class="form-control " value="{{ request('from') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="fw-semibold extra-filter-label">To</label>
                        <input type="date" name="to" class="form-control " value="{{ request('to') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="fw-semibold extra-filter-label">Status</label>
                        <select name="status" class="form-control shadow-sm extra-filter-select">
                            <option value="">All</option>
                            @foreach(['succeeded', 'partial_paid', 'partially_refunded', 'dispute_lost'] as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end form-group">
                        <button type="submit" class="btn btn-primary fw-semibold filter_sales_report">Filter</button>
                        <a href="{{ route('sales-report.index') }}" class="btn btn-outline-secondary fw-semibold ml-2 reset_sales_report">Reset</a>
                    </div>
                </div>
            </div>
        </form>

        {{-- Import / Export Buttons --}}
        <form method="POST" action="{{ route('sales-report.import') }}" class="d-flex flex-wrap align-items-center justify-content-between mb-4 extra-import-export">
            @csrf
            <button class="btn btn-success fw-semibold import_sales_report">Import from Stripe</button>
            <div class="btn-group mt-2 mt-md-0 extra-export-buttons">
                <a class="export-indoor-btn btn btn-outline-secondary fw-semibold extra-export-csv" href="{{ route('sales-report.export', ['type' => 'csv', 'status' => request('status'), 'from' => request('from'), 'to' => request('to')]) }}">CSV</a>
                <a class="export-indoor-btn btn btn-outline-primary fw-semibold extra-export-excel" href="{{ route('sales-report.export', ['type' => 'xlsx', 'status' => request('status'), 'from' => request('from'), 'to' => request('to')]) }}">Excel</a>
                <a class="export-indoor-btn btn btn-outline-info fw-semibold extra-export-pdf" href="{{ route('sales-report.export', ['type' => 'pdf', 'status' => request('status'), 'from' => request('from'), 'to' => request('to')]) }}">PDF</a>
            </div>
        </form>

        {{-- Report Table --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="alert alert-info bg-light p-2 mb-2">
                    <strong>Total Amount:</strong>
                    ${{ number_format(request()->hasAny(['status', 'from', 'to']) ? $filteredTotalAmount : $overallTotalAmount, 2) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-info bg-light p-2 mb-2">
                    <strong>Partial Refund Amount:</strong>
                    ${{ number_format(request()->hasAny(['status', 'from', 'to']) ? $filteredPartialRefundAmount : $overallPartialRefundAmount, 2) }}
                </div>
            </div>
        </div>
        <div class="table-responsive extra-table-container">
            
            <table class="table  bg-white border  table-customer mb-0 mobile-view">
                <thead class="table-header-background">
                    <tr>
                        <th>S.No</th>
                        <th>Order ID</th>
                        <th>Stripe ID</th>
                        <th>Amount</th>
                        <th>Partially Refund Amount</th>
                        <th>Customer Email</th>
                        <th>Status</th>
                        <th>Payment Method</th>
                        <th>Refund Date</th>
                        <th>Transaction Date</th>
                    </tr>
                </thead>
                <tbody class="extra-table-body">
                    @php
                        $i = ($reports->currentPage() - 1) * $reports->perPage() + 1;
                    @endphp
                    @forelse($reports as $report)
                        @php

                            if ($report->status === 'succeeded') {
                                $badgeClass = 'badge-success';
                            } elseif (in_array($report->status, ['partial_paid', 'partially_refunded'])) {
                                $badgeClass = 'badge-warning';
                            } elseif ($report->status === 'dispute_lost') {
                                $badgeClass = 'badge-danger';
                            } else {
                                $badgeClass = 'badge-secondary';
                            }
                        @endphp
                        <tr class="extra-table-row">
                            <td>{{ $i++ }}</td>
                            <td>{{ $report->order_id ?? '-' }}</td>
                            <td>{{ $report->stripe_id }}</td>
                            <td>{{ $report->amount ? '$'.$report->amount : '' }}</td>
                            <td>{{ $report->partial_refund_amount ? '$'.$report->partial_refund_amount : '' }}</td>
                            <td>{{ $report->customer_email ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $badgeClass }} text-capitalize extra-status-badge">
                                    {{ str_replace('_', ' ', $report->status) }}
                                </span>
                            </td>
                            <td>{{ $report->payment_method ?? '-' }}</td>
                            <td>{{ $report->refund_date ? $report->refund_date->format('Y-m-d') : '-' }}</td>
                            <td>{{ $report->transaction_date ? $report->transaction_date->format('Y-m-d') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center extra-no-records">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $reports->withQueryString()->links('pagination.custom_pagination') }}
    </div>
</div>
<div id="page-loader-overlay-indoor" style="display: none;">
    <div class="loader-spinner-indoor"></div>
</div>
@stop


@section('css')
    <link rel="stylesheet" href="/theme/css/admin_custom.css?v2">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css?v2') }}">
    <style type="text/css">
        @media(min-width:280px) and (max-width: 425px) {
            .main-header {
                border-bottom: none;
                width: 25%;
                height: 0px !important;
                margin-top: 20px !important;
            }

            .mobile_heading {
                position: absolute;
                left: 10rem;
                top: -3rem;

            }

            .search_row_admin-interface {
                position: absolute;
                top: 1rem;
                left: 1rem;
                width: 95%;
            }

            .fullfill_btn_mbl {
                position: absolute;
                left: 3.3rem;
            }

            .fullfill_btn_mbl {
                position: absolute;
                left: 3.3rem;
            }

            .create_new_btn_mbl {
                margin-right: 0.5rem;
            }

            .product_section_header {
                border-bottom: none !important;
            }

            .sm-d-none {
                display: none !important;
            }

            .bx-mobile {
                display: flex !important;
                justify-content: space-around !important;
                align-items: center !important;
            }

            .mobile-screen-selected {
                width: 30%;
            }

            .mobile-screen-ordrs-btn {
                width: 70%;
            }

            .product_table_body {
                padding-left: 11px !important;
                padding-right: 7px !important;
                padding-top: 9px !important;
            }

            .select-row-items {
                padding-left: 12px !important;
                display: flex;
                justify-content: start;
                align-items: center !important;
                color: #222222 !important;
                font-style: normal !important;
                font-weight: 500 !important;
                font-size: 0.826rem !important;
                padding-top: 0px !important;
            }

            .order_heading {
                font-size: 18px !important;
                font-weight: 500 !important;
                line-height: 24px !important;
                letter-spacing: -0.252px !important;
                font-family: 'Poppins', sans-serif !important;
                margin-left: 37px !important;
                color: #242424 !important;
                margin-top: 20px !important;
            }

            .mobile_screen_Previous_btn {
                width: 25% !important;
            }

            .mobile_screen_pagination_number {
                width: 50% !important;
            }

            .mobile_screen_Previous_next {
                width: 25% !important;
                margin-top: 11px !important;
            }

            .main-sidebar {
                background-color: #fff !important;
                box-shadow: none !important;
                border-right: 1px solid #EAECF0 !important;
                top: -21px !important;
            }

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
    </style>


 <style>
        .extra-sales-report-wrapper {
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.05);
            background-color: #ffffff;
        }

        .extra-sales-report-heading {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .extra-sales-report-alert {
            font-size: 0.9rem;
            padding: 10px 16px;
        }

        .extra-sales-report-filter .form-control,
        .extra-sales-report-filter .form-select {
            font-size: 0.875rem;
            border-radius: 6px;
        }

        .extra-filter-label {
            font-weight: 500;
            font-size: 0.875rem;
        }

        .extra-filter-buttons .btn {
            font-size: 0.875rem;
        }

        .extra-import-button,
        .extra-export-buttons .btn {
            font-size: 0.875rem;
            padding: 8px 16px;
        }

        .extra-table-container {
            margin-top: 20px;
        }

        .extra-report-table th,
        .extra-report-table td {
            font-size: 0.85rem;
            vertical-align: middle;
        }

        .extra-table-row:hover {
            background-color: #f8f9fa;
        }

        .extra-status-badge {
            font-size: 0.75rem;
            padding: 6px 10px;
            border-radius: 4px;
        }

        .extra-no-records {
            font-style: italic;
            color: #999;
        }

        .badge-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .badge-warning {
            background-color: rgba(255, 193, 7, 0.15);
            color: #856404;
        }

        .badge-danger {
            background-color: rgba(220, 53, 69, 0.12);
            color: #dc3545;
        }

        .badge-secondary {
            background-color: rgba(108, 117, 125, 0.15);
            color: #6c757d;
        }

        @media(max-width: 768px) {
            .extra-import-export,
            .extra-sales-report-filter {
                flex-direction: column;
            }

            .extra-import-export .btn,
            .extra-export-buttons .btn,
            .extra-filter-buttons .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }

        #page-loader-overlay-indoor {
            position: fixed;
            z-index: 9999;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.85);
            display: flex;
            justify-content: center;
            align-items: center;
            pointer-events: all;
        }

        .loader-spinner-indoor {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const loader = document.getElementById('page-loader-overlay-indoor');

        // Show loader only on specific buttons
        document.querySelectorAll('.import_sales_report, .filter_sales_report, .reset_sales_report').forEach(button => {
            button.addEventListener('click', () => {
                loader.style.display = 'flex';
            });
        });
    });
</script>
@endsection

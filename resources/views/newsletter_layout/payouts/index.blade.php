@extends('newsletter_layout.dashboard')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mt-2">
                <div class="card-header">
                    <h3 class="card-title text-bold">
                        Payouts
                    </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{ session('error') }}
                        </div>
                    @endif
                    <div class="row mb-2">
                        <!-- Search Input -->
                        <div class="col-md-4">
                            <div class="has-search">
                                <form method="get" action="/payouts" class="mb-2">
                                    <div class="input-group">
                                        {{-- <span class="fa fa-search form-control-feedback"></span> --}}
                                        <input type="text" class="form-control" id="search" name="search" 
                                            placeholder="Search by Amount" value="{{ request('search') }}" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    
                        <!-- Filter Buttons -->
                        <div class="col-md-2">
                            <form method="get" action="/payouts" class="mb-2">
                                <input type="submit" class="btn {{ request()->has('last_14_days') ? 'btn-success' : 'btn-info' }} w-100"
                                    name="last_14_days" value="Last 14 Days">
                            </form>
                        </div>
                        <div class="col-md-2">
                            <form method="get" action="/payouts" class="mb-2">
                                <input type="submit" class="btn {{ request()->has('this_month') ? 'btn-success' : 'btn-info' }} w-100"
                                    name="this_month" value="This Month">
                            </form>
                        </div>
                        <div class="col-md-2">
                            <form method="get" action="/payouts" class="mb-2">
                                <input type="submit" class="btn {{ request()->has('last_month') ? 'btn-success' : 'btn-info' }} w-100"
                                    name="last_month" value="Last Month">
                            </form>
                        </div>
                        <div class="col-md-2">
                            <form method="get" action="/payouts" class="mb-2">
                                <input type="submit" class="btn {{ request()->has('all_time') ? 'btn-success' : 'btn-info' }} w-100"
                                    name="all_time" value="All Time">
                            </form>
                        </div>
                        
                    </div>
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Type</th>
                                <th>Method</th>
                                <th>Source Type</th>
                                <th>Payout Created</th>
                                <th>Arrive Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if (count($payouts) == 0)
                                <tr>
                                    <td colspan="10" class="text-center">No Payouts Found</td>
                                </tr>
                            @endif

                            @foreach ($payouts as $payout)
                                <tr>
                                    <td>
                                        <a href="{{ route('payouts.details', $payout->id) }}" class="text-dark" style="font-weight: 600;">{{'US$'. number_format($payout->amount , 2) }}</a>
                                    </td>
                                    <td>{{ $payout->status }}</td>
                                    <td>{{ $payout->type }}</td>
                                    <td>{{ $payout->method }}</td>
                                    <td>{{ $payout->source_type }}</td>
                                    <td>{{ $payout->payout_created }}</td>
                                    <td>{{ $payout->arrive_date }}</td>
        
                                    <td>
                                        <a href="{{ route('payouts.details', $payout->id) }}" class="btn btn-primary btn-sm text-white">Transactions</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
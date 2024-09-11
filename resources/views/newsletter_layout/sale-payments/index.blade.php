@extends('newsletter_layout.dashboard')
@section('content')
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
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sale Payments</h3>
            </div>
            <div class="card-body">
                <form action="{{url('/sale/payments')}}" method="GET">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5>
                                <strong>
                                    Filter By :
                                </strong>
                                @if (!empty($search) || !empty($payment_method) || !empty($date_from) || !empty($date_to))
                                <a href="{{url('/sale/payments')}}" class="btn btn-sm btn-primary mx-2 text-white">
                                    Reset Filter
                                </a>
                                @endif
                            </h5>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                            <div class="row align-items-center">
    
                                <div class="col-md-12">
                                    <label>
                                        Name / Email / Company
                                    </label>
                                    <input type="text" class="form-control" id="search" name="search_by_name_email"
                                        placeholder="Search" value="{{!empty($search) ? $search : ''}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-4 col-md-6 col-12">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <label>
                                        Payment Method
                                    </label>
                                    <select name="payment_method" id="" class="form-control">
                                        <option value="">Select Payment Method</option>
                                        <option value="cash" {{!empty($payment_method) && ($payment_method=='cash' )
                                            ? 'selected' : '' }}>Cash</option>
                                        <option value="card" {{!empty($payment_method) && ($payment_method=='card' )
                                            ? 'selected' : '' }}>Card</option>
                                        <option value="on account" {{!empty($payment_method) && ($payment_method=='on account' )
                                            ? 'selected' : '' }}>On Account</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-4 col-md-6 col-12">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <label for="" class="mb-2">Date From</label>
                                    <input type="date" class="form-control" name="date_from"
                                        value="{{!empty($date_from) ? $date_from : ''}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-4 col-md-6 col-12">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <label for="" class="mb-2">Date To</label>
                                    <input type="date" class="form-control" name="date_to"
                                        value="{{!empty($date_to)  ? $date_to : ''}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-4 col-md-6 col-12">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <label for="" class="mb-2"></label>
                                    <button type="submit" class="btn btn-primary w-100 mt-2">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="col-md-12 shadow border">
                    <div class="table-responsive">
                        <table class="table table-border">
                            <thead>
                                <tr>
                                    {{-- <th>S.No</th> --}}
                                    <th>Order Id</th>
                                    <th>Company</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>PO No</th>
                                    <th>Invoice No</th>
                                    <th>Order Reference</th>
                                    <th>Payment Method</th>
                                    <th>Order Type</th>
                                    <th>Payment Date</th>
                                    <th>Created Date</th>
                                    {{-- <th>Modifie Date</th> --}}
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $i =1;
                                @endphp
                                @if(count($sale_payments) > 0 )
                                @foreach ($sale_payments as $sale_payment)
                                <tr>
                                    {{-- <td>{{ $i++ }}</td> --}}
                                    <td>{{$sale_payment->orderId }}</td>
                                    <td>{{$sale_payment->company }}</td>
                                    <td>{{$sale_payment->customer_first_name .' '. $sale_payment->customer_last_name }}</td>
                                    <td>{{$sale_payment->email }}</td>
                                    <td>{{$sale_payment->po_number }}</td>
                                    <td>{{$sale_payment->invoice_number }}</td>
                                    <td>{{$sale_payment->orderRef}}</td>
                                    <td>{{strtoupper($sale_payment->method)}}</td>
                                    <td>{{$sale_payment->orderType}}</td>
                                    <td>{{ str_replace(['T', 'Z'], ' ', $sale_payment->paymentDate)}}</td>
                                    <td>{{ str_replace(['T', 'Z'], ' ', $sale_payment->createdDate)}}</td>
                                    {{-- <td>{{ str_replace(['T', 'Z'], ' ', $sale_payment->modifiedDate)}}</td> --}}
                                    <td>
                                        <a href="{{ route('sale-payments.show', $sale_payment->orderId) }}"
                                            class="btn btn-info btn-sm text-white">Order Detail</a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="3">No Sales Payments found</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="row mt-2 justify-content-center">
                            <div class="col-md-6">
                                {{ $sale_payments->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('newsletter_layout.dashboard')
@section('content')
    <div class="row">
        <div class="table-wrapper">
            <div class="card-body product_secion_main_body">
                <div class="row product_section_header">
                    
                    @if (Session::has('error'))
                        <div class="alert alert-danger alert-dismissible mt-2 ml-4">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ Session::get('error')}}
                        </div>
                    @endif
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible mt-2 ml-4">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ Session::get('success')}}
                        </div>
                    @endif
                </div>
                <div class="row product_section_header">
                    <div class="col-md-12">
                        <h3 class="order_heading">Payouts Transactions</h3>
                    </div>
                    <div class="col-md-12 my-2 d-flex align-items-center justify-content-between">
                        
                        <div class="d-flex align-items-center">
                            <div>
                                <input type="checkbox" name="hide_radar" id="hide_radar" onclick="hideRadar()">
                                <label for="hide_radar" class=" mb-0">Hide Radar</label>
                            </div>
                            <div class="ml-3">
                                <input type="checkbox" name="hide_Chargeback" id="hide_Chargeback" onclick="hideChargeback()">
                                <label for="hide_Chargeback" class=" mb-0">Hide Chargeback</label>
                            </div>
                        </div>
                        <a href="{{ route('transactions_export' , $id) }}" class="btn btn-primary text-white">Export</a>
                    </div>
                    
                </div>
                <div class="card-body product_table_body p-0">
                    <div class="col-md-12 p-0">
                        <div class="col-md-12 shadow-sm border order-table-items-data table-responsive p-0">
                            <table class="table  bg-white  table-customer mb-0 mobile-view">
                                <thead>
                                    <tr class="table-header-background">
                                        <td><span class="d-flex table-row-item"> Order ID</span></td>
                                        <td><span class="d-flex table-row-item"> Customer Name</span></td>
                                        <td><span class="d-flex table-row-item"> Customer Email</span></td>
                                        <td><span class="d-flex table-row-item"> Currency</span></td>
                                        <td><span class="d-flex table-row-item"> Type</span></td>
                                        <td><span class="d-flex table-row-item"> Description</span></td>
                                        <td><span class="d-flex table-row-item">Amount</span></td>
                                        <td><span class="d-flex table-row-item"> Converted Amount</span></td>
                                        <td><span class="d-flex table-row-item"> Fees</span></td>
                                        <td><span class="d-flex table-row-item"> Net</span></td>
                                        <td><span class="d-flex table-row-item"> Charge Created</span></td>
                                    </tr>
                                </thead>
                                <tbody id="payout_transactions">
    
                                    @if (count($payout_balances) == 0)
                                        <tr>
                                            <td colspan="10" class="text-center">No Transactions Found</td>
                                        </tr>
                                    @endif
    
                                    @foreach ($payout_balances as $payout_balance)
                                        <tr>
                                            <td>{{ $payout_balance->order_id != 0 ?  $payout_balance->order_id : '-'}}</td>
                                            <td>{{ $payout_balance->customer_name }}</td>
                                            <td>{{ $payout_balance->customer_email }}</td>
                                            <td>{{ $payout_balance->currency }}</td>
                                            <td>{{ $payout_balance->type }}</td>
                                            <td>{{ $payout_balance->description }}</td>
                                            <td>{{ $payout_balance->amount }}</td>
                                            <td>{{ $payout_balance->converted_amount }}</td>
                                            <td>{{ $payout_balance->fees }}</td>
                                            <td>{{ $payout_balance->net }}</td>
                                            <td>{{ $payout_balance->charge_created }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                {{-- <tfoot>
                                    <tr>
                                        <td colspan="10">
                                            {{ $payout_balances->links('pagination.custom_pagination') }}
                                        </td>
                                    </tr>
                                </tfoot> --}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function hideRadar() {
            var checkBox = document.getElementById("hide_radar");
            var radarRows = document.querySelectorAll("#payout_transactions tr"); // Select all rows inside tbody
    
            radarRows.forEach(row => {
                var descriptionCell = row.querySelector("td:nth-child(6)"); // Get the description column (6th column)
                if (descriptionCell && descriptionCell.textContent.includes("Radar")) {
                    row.style.display = checkBox.checked ? "none" : "table-row";
                }
            });
        }
    
        function hideChargeback() {
            var checkBox = document.getElementById("hide_Chargeback");
            var chargebackRows = document.querySelectorAll("#payout_transactions tr"); // Select all rows inside tbody
    
            chargebackRows.forEach(row => {
                var descriptionCell = row.querySelector("td:nth-child(6)"); // Get the description column (6th column)
                if (descriptionCell && descriptionCell.textContent.includes("Chargeback")) {
                    row.style.display = checkBox.checked ? "none" : "table-row";
                }
            });
        }
    
    
    
    </script>
@endsection 

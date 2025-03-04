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
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Customer Email</th>
                                <th>Currency</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th> Converted Amount</th>
                                <th> Fees</th>
                                <th> Net</th>
                                <th> Charge Created</th>
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
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
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

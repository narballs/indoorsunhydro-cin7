@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Add New Customer</h1>
@stop

@section('content')
    <?php //dd($shippingmethod);
    ?>
    <div>
        <form method="POST" action="{{ route('admin.customer.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-8 card">
                    <div class="row card-body" style="margin-left: 10px;">
                        <div class="form-group col-md-5">
                            <label for="mobile">Company</label>
                            <input type="text" class="form-control" id="company" aria-describedby="titleHelp"
                                name="company" placeholder="company">
                        </div>

                        <div class="form-group col-md-5" style="margin-left:10px">
                            <label for="mobile">Mobile</label>
                            <input type="text" class="form-control" id="title" aria-describedby="titleHelp"
                                name="mobile" placeholder="Mobile">

                        </div>


                    </div>
                    <div class="row card-body">
                        <div class="form-group col-md-5">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="title" aria-describedby="titleHelp"
                                name="first_name" placeholder="First Name">
                        </div>
                        <div class="form-group col-md-5" style="margin-left:10px">
                            <label for="first_name">Phone</label>
                            <input type="text" class="form-control" id="title" aria-describedby="titleHelp"
                                name="phone" placeholder="Phone">
                        </div>
                    </div>
                    <div class="row card-body">
                        <div class="form-group col-md-5">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="title" aria-describedby="titleHelp"
                                name="last_name" placeholder="Last Name">
                        </div>
                        <div class="form-group col-md-5" style="margin-left:10px">
                            <label for="last_name">Fax</label>
                            <input type="text" class="form-control" id="job_title" aria-describedby="titleHelp"
                                name="fax" placeholder="Fax">
                        </div>

                    </div>
                    <div class="row card-body">
                        <div class="form-group col-md-5">
                            <label for="last_name">Job Title</label>
                            <input type="text" class="form-control" id="job_title" aria-describedby="titleHelp"
                                name="job_title" placeholder="Job Title">
                        </div>
                        <div class="form-group col-md-5" style="margin-left:10px">
                            <label for="website">Website</label>
                            <input type="text" class="form-control" id="title" aria-describedby="titleHelp"
                                name="website" placeholder="Website">
                        </div>
                    </div>
                    <div class="row card-body">
                        <div class="form-group col-md-5">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="title" aria-describedby="titleHelp"
                                name="city" placeholder="City">
                        </div>
                        <div class="form-group col-md-5" style="margin-left:10px">
                            <label for="website">Email</label>
                            <input type="text" class="form-control" id="title" aria-describedby="titleHelp"
                                name="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="row card-body">
                        <div class="form-group col-md-3">
                            <label for="active_inactive">Active</label>
                            <select class="form-control" name="status">
                                <option value="1">On</option>
                                <option value="0">Off</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3" style="margin-left:21px">
                            <label for="type" name="type">Type</label>
                            <select class="form-control" name="type">
                                <option value="Customer">Customer</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3" style="margin-left:10px">
                            <label for="pricing_tier">Pricing Tier</label>
                            <select class="form-control" name="priceCol" id="priceCol">
                                <option value="Retail">Retail</option>
                                <option value="Wholesale">Wholesale</option>
                                <option value="TerraIntern">TerraIntern</option>
                                <option value="Sacramanto">Sacramanto</option>
                                <option value="Oklahoma">Oklahoma</option>
                                <option value="Calaveras">Calaveras</option>
                                <option value="Tier1">Tier1</option>
                                <option value="Tier2">Tier2</option>
                                <option value="Tier2">Tier3</option>
                                <option value="CommercialOK">CommercialOK</option>
                                <option value="Cost">Cost</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 card">
                    <div class="card-body">
                        <h4>Customer Notes</h4>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mobile"></label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="10" name="notes"></textarea>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h4>Billing Information</h4>
                        </div>
                        <div class="form-group col-md-12 card-body">
                            <label for="mobile">Address 1</label>
                            <input type="text" class="form-control" id="company" aria-describedby="titleHelp"
                                name="billing_address_1" placeholder="address_1">
                        </div>
                        <div class="form-group col-md-12 card-body">
                            <label for="billing_address_2">Address 2</label>
                            <input type="text" class="form-control" id="company" aria-describedby="titleHelp"
                                name="billing_address_2" placeholder="Address 2">
                        </div>
                        <div class="row card-body">
                            <div class="form-group col-md-6">
                                <label for="mobile">City</label>
                                <input type="text" class="form-control" id="company" aria-describedby="titleHelp"
                                    name="billing_city" placeholder="City">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="mobile">State</label>
                                <input type="text" class="form-control" id="company" aria-describedby="titleHelp"
                                    name="billing_state" placeholder="State">
                            </div>
                        </div>
                        <div class="form-group col-md-6 card-body">
                            <label for="mobile">Postal Code</label>
                            <input type="text" class="form-control" id="company" aria-describedby="titleHelp"
                                name="billing_postal_code" placeholder="Postal Code">
                        </div>
                    </div>

                </div>
                <div class="col-sm-6 card">
                    <div class="card-body">
                        <h4>Delivery Information</h4>
                    </div>
                    <div class="form-group col-md-12 card-body">
                        <label for="mobile">Address 1</label>
                        <input type="text" class="form-control" id="company" aria-describedby="titleHelp"
                            name="delivery_address_1" placeholder="address_1">
                    </div>
                    <div class="form-group col-md-12 card-body">
                        <label for="address_2">Address 2</label>
                        <input type="text" class="form-control" id="company" aria-describedby="titleHelp"
                            name="delivery_address_2" placeholder="Address 2">
                    </div>
                    <div class="row card-body">
                        <div class="form-group col-md-6">
                            <label for="mobile">City</label>
                            <input type="text" class="form-control" id="company" aria-describedby="titleHelp"
                                name="delivery_city" placeholder="City">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="mobile">State</label>
                            <input type="text" class="form-control" id="company" aria-describedby="titleHelp"
                                name="delivery_state" placeholder="State">
                        </div>
                    </div>
                    <div class="form-group col-md-6 card-body">
                        <label for="delivery_postal_code">Postal Code</label>
                        <input type="text" class="form-control" id="company" aria-describedby="titleHelp"
                            name="delivery_postal_code" placeholder="Postal Code">
                    </div>

                </div>

                <div class="form-group col-md-6 card-body">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
        </form>

    @stop

    @section('css')
        <link rel="stylesheet" href="/theme/css/admin_custom.css">
        <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
        <style>
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
        </style>
    @stop

    @section('js')


    @stop

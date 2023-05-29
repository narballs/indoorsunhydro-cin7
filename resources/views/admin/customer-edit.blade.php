@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <?php //dd($customer);
    ?>
    <div class="container-fluid">
        <div class="container">
            <!-- Title -->
            <div class="d-flex justify-content-between align-items-center py-3">
                <h2 class="h5 mb-0"><a href="#" class="text-muted"></a>Edit Customer</h2>
            </div>
            <!-- Main content -->
            <div class="row">
                <div class="col-lg-12">
                    <!-- Details -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('admin.customer.update') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <input type="hidden" name="id" value="{{ $contact->id }}">
                                    <div class="mb-3 col-md-6">
                                        <label for="exampleInputEmail1" class="form-label">First Name</label>
                                        <input type="text" value="{{ $contact->firstName }}" name="first_name"
                                            class="form-control" id="first_name" aria-describedby="emailHelp">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Last Name</label>
                                        <input type="text" value="{{ $contact->lastName }}" name="last_name"
                                            class="form-control" id="last_name">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="exampleInputEmail1" class="form-label">Company</label>
                                        <input type="text" name="company" value="{{ $contact->company }}"
                                            class="form-control" aria-describedby="emailHelp">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Website</label>
                                        <input type="text" name="website" value="{{ $contact->website }}"
                                            class="form-control" id="last_name">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="exampleInputEmail1" class="form-label">Email</label>
                                        <input type="text" name="email"
                                            value="{{ $contact->email }}"class="form-control" id="first_name"
                                            aria-describedby="emailHelp">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Phone #</label>
                                        <input type="text" value="{{ $contact->phone }}" name="phone"
                                            class="form-control" id="last_name">
                                    </div>
                                </div>
                                <div class="col-md-12 pl-0">
                                    <h3>Billing Address</h3>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="address_1" class="form-label">Address 1</label>
                                        <input type="text" name="address_1" value="{{ $contact->postalAddress1 }}"
                                            class="form-control" id="address_1" aria-describedby="emailHelp">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Address 2</label>
                                        <input type="text" name="address_2" class="form-control"
                                            value="{{ $contact->postalAddress2 }}" id="address_2">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="address_1" class="form-label">City</label>
                                        <input type="text" name="city" value="{{ $contact->postalCity }}"
                                            class="form-control" id="address_1" aria-describedby="emailHelp">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">State</label>
                                        <input type="text" name="state" value="{{ $contact->postalState }}"
                                            class="form-control" id="state">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Zip</label>
                                        <input type="text" name="zip" value="{{ $contact->postalPostCode }}"
                                            class="form-control" id="last_name">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
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

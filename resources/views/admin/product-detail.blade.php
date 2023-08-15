@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
    <h1>Dashboard</h1>
@stop
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-fluid">
        <!-- Title -->
        <div class="d-flex justify-content-between align-items-center py-3">
            <h2 class="h5 mb-0"><a href="#" class="text-muted"></a>Product Details</h2>
        </div>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12">
                <!-- Details -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div>
                            <h4><b>{{ $product->name }}</b></h4>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-1">
                                <h5><b>Status</b></h5>
                            </div>
                            <div class="col-md-1">
                                @if ($product->status == 'Active' || $product->status == 'Public')
                                    <span class="badge badge-success">{{ $product->status }}</span>
                                @elseif($product->status == 'Inactive')
                                    <span class="badge badge-danger">{{ $product->status }}</span>
                                @endif
                            </div>
                            @if ($product->categories)
                                <div class="col-md-4">
                                    <h5><b>Parent Category :</b>{{ $parent_category_name }}</h5>
                                </div>
                                <div class="col-md-3">
                                    <h5><b>Sub Category :</b>{{ $product->categories->name }}</h5>
                                </div>
                            @else
                                <div class="col-md-4">
                                    <h5><b>Parent Category :</b>Unassigned</h5>
                                </div>
                                <div class="col-md-3">
                                    <h5><b>Sub Category :</b>Unassigned</h5>
                                </div>
                            @endif
                            <div class="col-md-3">
                                <h5><b>Brand :</b> {{ $product->brand }}</h5>
                            </div>
                        </div>
                        <div class="mt-5">
                            <h5><b>Product Options</b></h5>
                        </div>
                        <table class="table">
                            <tr>
                                <th class="justify-content-between">Image</th>
                                <th class="text-center">Code</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Stock Available</th>
                                <th class="text-center">Option 1</th>
                                <th class="text-center">Option 2</th>
                                <th class="text-center">Option 3</th>
                                <th class="text-center">Supplier Code</th>
                            </tr>
                            <tbody>
                                <?php //dd($product->options)
                                ?>
                                @foreach ($product->options as $option)
                                    <tr>
                                        <td>
                                            <div class="d-flex mb-2">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ $option->image }}" alt="" width="35"
                                                        class="img-fluid">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="ms-2 text-center">
                                            {{ $option->code }}
                                        </td>
                                        @if ($option->status == 'Active' || $option->status == 'Primary')
                                            <td class="ms-2 text-center">
                                                <span class="badge badge-success">{{ $option->status }}</span>
                                            </td>
                                        @else
                                            <td class="ms-2 text-center">
                                                <span class="badge badge-danger">{{ $option->status }}</span>
                                            </td>
                                        @endif
                                        <td class="text-center">
                                            {{ $option->stockAvailable }}
                                        </td>
                                        <td class="text-center">
                                            {{ $option->option1 }}
                                        </td>
                                        <td class="text-center">
                                            {{ $option->option2 }}
                                        </td>
                                        <td class="text-center">
                                            {{ $option->option3 }}
                                        </td>
                                        <td class="text-center">
                                            {{ $option->supplierCode }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Payment -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="text-center col-md-12">
                                <h5><b>Pricing</b></h5>
                            </div>
                            <table class="table">
                                <tr>
                                    <th class="text-center">Retail</th>
                                    <th class="text-center">Wholesale</th>
                                    <th class="text-center">Vip</th>
                                    <th class="text-center">Special</th>
                                </tr>
                                <tr>
                                <tr>
                                    <td class="text-center">
                                        ${{ $option->retailPrice }}
                                    </td>
                                    <td class="text-center">
                                        ${{ $option->wholesalePrice }}
                                    </td>
                                    <td class="text-center">
                                        ${{ $option->vipPrice }}
                                    </td>
                                    <td class="text-center">
                                        ${{ $option->specialPrice }}
                                    </td>
                                </tr>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h5><b>Pricing Column</b></h5>
                            </div>
                            <table class="table">
                                @foreach ($product->options as $option)
                                    @if ($option->status != 'Disabled')
                                        @foreach ($option->price as $price)
                                            <tr>
                                                <td class="text-center"><b>retailUSD</b></td>

                                                <td class="text-center">
                                                    ${{ $price->retailUSD }}
                                                </td>
                                                <td class="text-center"><b>tier1USD</b></td>
                                                <td class="text-center">${{ $price->tier1USD }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center"><b>wholesaleUSD</b><b></td>
                                                </b>
                                                <td class="text-center">
                                                    ${{ $price->wholesaleUSD }}
                                                </td>
                                                <td class="text-center"><b>tier2USD</b></td>
                                                <td class="text-center">${{ $price->tier2USD }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center"><b>sacramentoUSD</b></td>
                                                <td class="text-center">
                                                    ${{ $price->sacramentoUSD }}
                                                </td>
                                                <td class="text-center"><b>tier3USD</b></td>
                                                <td class="text-center">${{ $price->tier3USD }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center"><b>terraInternUSD</b></td>
                                                <td class="text-center">
                                                    ${{ $price->terraInternUSD }}
                                                </td>
                                                <td class="text-center"><b>commercialOKUSD</b></td>
                                                <td class="text-center">${{ $price->commercialOKUSD }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center"><b>oklahomaUSD</b></td>
                                                <td class="text-center">
                                                    ${{ $price->oklahomaUSD }}
                                                </td>
                                                <td class="text-center"><b>costUSD</b></td>
                                                <td class="text-center">${{ $price->costUSD }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center"><b>calaverasUSD</b></td>
                                                <td class="text-center">
                                                    ${{ $price->calaverasUSD }}
                                                </td>
                                                <td class="text-center"><b>specialPrice</b></td>
                                                <td class="text-center">${{ $price->specialPrice }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center"><b>tier0USD</b></td>
                                                <td class="text-center">
                                                    ${{ $price->tier0USD }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin_custom.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
    <style>
        .text-successs {
            color: #7CC633 !important;
            font-family: 'Poppins', sans-serif !important;
        }

        .badge-success {
            background: rgba(124, 198, 51, 0.2);
            color: #7CC633;
            padding: 7px !important;
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

        .badge-warning {
            color: #1f2d3d;
            background-color: #fce9a9;
            color: #ffc107 !important;
            padding: 5px;
        }

        .badge-danger {
            color: #fff;
            background-color: #f1abb2;
            color: #f14f4f;
            padding: 6px !important;
        }
    </style>
@stop

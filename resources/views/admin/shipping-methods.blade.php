@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content')
    <div class="table-wrapper">
        <div class="table-title">
            <div class="row">
                <div class="col-sm-8">
                    <h2>Shipping Methods</h2>
                </div>

                <div class="col-sm-4 mt-2">
                    <div class="search-box">
                        <i class="material-icons"></i>
                        <input type="text" class="form-control" placeholder="Search…">
                    </div>
                </div>
                <div class="col-sm-2 mb-5">
                    <div class="search-box">
                        <a href="{{ 'shipping-methods/create' }}"><input type="button" value="Add New"
                                class="form-control btn btn-primary" placeholder="Add New">
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name <i class="fa fa-sort"></i></th>
                    <th>Cost</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php //dd($orders)
                ?>
                @foreach ($shippingmethods as $key => $shippingmethod)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $shippingmethod->title }}</td>
                        <td>{{ $shippingmethod->cost }}</td>
                        @if ($shippingmethod->status == 1)
                            <td>
                                Enabled
                            </td>
                        @else
                            <td>
                                Disabled
                            </td>
                        @endif
                        <td>
                            <a href="{{ url('admin/shipping-details/') }}" class="view" title=""
                                data-toggle="tooltip" data-original-title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ url('admin/shipping-method/' . $shippingmethod->id) }}" class="edit"
                                title="" data-toggle="tooltip" data-original-title="Edit"><i
                                    class="fas fa-pen"></i></a>
                            <a href="{{ url('admin/shipping-method/delete/' . $shippingmethod->id) }}" class="delete"
                                title="" data-toggle="tooltip" data-original-title="Delete"><i
                                    class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop

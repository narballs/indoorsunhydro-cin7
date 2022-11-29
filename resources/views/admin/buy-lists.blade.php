@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

@stop

@section('content')

<div class="table-wrapper">
    <div class="table-title">
        <span>
            <h1>Buy Lists</h1>
        </span>
        <div class="row justify-content-between mb-2">
            <div class="col-sm-2">
                <div class="search-box">
                    <a href="{{'buy-list/create '}}"><input type="button" value="Create New Buy List"
                            class="form-control btn btn-primary" placeholder="Create New">
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div id="custom-search-input">
                    <div class="input-group col-md-12">

                        <form method="get" action="/admin/products" class="w-100">
                            <input type="text" class="form-control" id="search" name="search" placeholder="Search"
                                value="{{ isset($search) ? $search : '' }}" />
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-body">

        <table class="table table-striped table-hover table-bordered table-customer" id="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title<i class="fa fa-sort"></i></th>
                    <th>Status<i class="fa fa-sort"></i></th>
                    <th>Description<i class="fa fa-sort"></i></th>
                    <th>Action<i class="fa fa-sort"></i></th>
                </tr>
            </thead>
            <tbody id="searched">
                @foreach($buylists as $buylist)
                    <tr>
                        <td>{{$buylist->id}}</td>
                        <td>{{$buylist->title}}</td>
                        <td>{{$buylist->status}}</td>
                        <td>{{$buylist->description}}</td>
                        <td>
                            <a href="" class="view" title="" data-toggle="tooltip"
                                data-original-title="View"><i class="fas fa-eye"></i></a>
                            <a href="#" class="edit" title="" data-toggle="tooltip" data-original-title="Edit"><i
                                    class="fas fa-pen"></i></a>
                            <a href="#" class="delete" title="" data-toggle="tooltip" data-original-title="Delete"><i
                                    class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-10">
            </div>
        </div>
    </div>
    @stop
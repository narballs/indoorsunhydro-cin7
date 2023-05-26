@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Edit Shipping Method</h1>
@stop

@section('content')
    <?php
    
    ?>

    <form method="POST" action="{{ route('admin.shipping-method.store') }}">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" aria-describedby="titleHelp"
                value="{{ $shippingmethod->title }}" name="title">
        </div>
        <div class="form-group">
            <label for="cost">Cost</label>
            <input type="text" class="form-control" id="cost" value="{{ $shippingmethod->cost }}" name="cost">
        </div>
        <input type="hidden" name="shippingmethodId" value="{{ $shippingmethod->id }}">
        <!--  	@if ($shippingmethod->status == 0)
    $selected = 'selected';
@else
    $selected = '';
    @endif -->
        <div class="form-group">
            <label for="Status">Status</label>
            <select id="status" name="status" class="form-control">
                <option value="0" @if ($shippingmethod->status == 0) selected @endif>Disabled</option>
                <option value="1" @if ($shippingmethod->status == 1) selected @endif>Enabled</option>
            </select>
        </div>
        <div class="form-group">
            <label for="states">Assign States to Method</label>
            <select class="form-select form-control" multiple aria-label="multiple select example" name="states[]">
                @foreach ($states as $key => $state)
                    <option value="{{ $state->id }}"
                        @foreach ($shipping_states as $shipping_state)
  					 		@if ($state->id == $shipping_state->state_id) selected @endif @endforeach>
                        {{ $state->name }}</option>
                @endforeach
            </select>
        </div>
        <!--  	<div class="form-group form-check">
     <input type="checkbox" class="form-check-input" id="exampleCheck1" >
     <label class="form-check-label" for="exampleCheck1">Check me out</label>
     </div> -->
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

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
            /* background-color: #28a745; */
            background: rgba(124, 198, 51, 0.2);
            color: #7CC633;
            padding: 7px !important;
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

@section('js')

@stop

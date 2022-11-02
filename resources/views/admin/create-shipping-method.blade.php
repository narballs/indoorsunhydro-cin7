@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Add New Shipping Method</h1>
@stop

@section('content')
<?php //dd($shippingmethod);?>
	<form method="POST" action="{{ route('admin.shipping-method.store') }}">
		@csrf
	  	<div class="form-group">
	    	<label for="title">Title</label>
	    	<input type="text" class="form-control" id="title" aria-describedby="titleHelp" name="title" placeholder="Title">
	  	</div>
	  	<div class="form-group">
	    	<label for="cost">Cost</label>
	    	<input type="text" class="form-control" id="cost" name="cost" placeholder="Cost">
	  	</div>
	  	<div class="form-group">
	  		<label for="Status">Status</label>
	  		<select id="status" name="status" class="form-control">
	  			<option value="0">Disabled</option>
	  			<option value="1">Enabled</option>
	  		</select>
	  	</div>
	  	<div class="form-group">
	  		<label for="states">Assign States to Method</label>
	  		<select class="form-select form-control" multiple aria-label="multiple select example" name="states[]">
	  			@foreach($states as $state)
  					<option value="{{$state->id}}">{{$state->name}}</option>
  				@endforeach
			</select>
		</div>
	  	<button type="submit" class="btn btn-primary">Submit</button>
	</form>

  @stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

@stop
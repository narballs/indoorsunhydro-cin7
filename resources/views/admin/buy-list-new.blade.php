@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Add New Buylist</h1>
@stop

@section('content')
<style>
	nav svg{
		max-height: 20px !important;
	}
</style>
	<div class="row">
		<div class="col-md-5 card">
			<div class="row">
		  		<div class="form-group col-md-6">
		    		<label for="list_name">Title</label>
		    		<input type="text" class="form-control" id="title" aria-describedby="titleHelp" name="list_name" placeholder="Buy List Title">
		  		</div>
		  		 <div class="form-group col-md-6 mb-0">
		    		<label for="type" name="type">Status</label>
		    		<select class="form-control" name="type" id="status">
		    			<option value="">Select Status</option>
		    			<option value="public">Public</option>
		    			<option value="private">Private</option>
		    			<option value="private">Shareable</option>
		    		</select>
		  		</div>
		  		<div class="col-md-12 card mt-5">
					<div class="card-body"><h4>Description</h4></div>
						<div class="form-group col-md-12">
		    				<label for="mobile"></label>
		    				<textarea class="form-control" rows="10" name="notes" id="description"></textarea>
		  				</div>
				</div>
				<div class="me-2">
					<button type="button" class="ms-2 btn btn-primary" onclick="createList()">
						Create List
					</button>
				</div>
		  	</div>
		  	<div class="row card-body">
		  	</div>
		</div>
		<div class="col-md-7 card">
			@livewire('filter')
		</div>
	</div>
	<div class="row w-100 pl-2 pr-0">
		<div class="card col-md-12">
			<div class="card-body w-100">
				<div id="list_title">
					<h4></h4>
				</div>
				<table id="product_list" class="table">
					<tr>
						<td style="width:373px !important">Product Title</td>
						<td>Image</td>
						<td>Price</td>
						<td>Quantity</td>
						<td>Subtotal</td>
						<td>Remove</td>
					</tr>
				</table>
				<div class="row">
					<div class="col-md-10 border-top">Grand Total</div>
					<div class="col-md-2 border-top">amount : <span id="grand_total">0</span></div>
				</div>
			</div>
		</div>
	</div>


@livewireScripts
  @stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
	$( document ).ready(function() {
		$('body').on('click', '.btn-add-to-cart', function() {
			var id = $(this).attr('id');
			var product_id = id.replace('btn_', '');
			var row = $('#product_row_' + product_id).length;

			if (row > 0) {
				var difference = 0;
				var subtotal_before_update = parseFloat($('#subtotal_' + product_id).html());
				console.log('difference => ' + difference);
				console.log('sub total before update  => ' + subtotal_before_update);

				var retail_price = parseFloat($('#retail_price_' + product_id).html());
				var quantity = parseFloat($('#quantity_' + product_id).val());
				var subtotal = parseFloat($('#subtotal_' + product_id).html());
				
				quantity++;
				subtotal = retail_price * quantity;

				difference = subtotal_before_update - subtotal;

				console.log('difference => ' + difference);

				var grand_total = $('#grand_total').html();
				grand_total = parseFloat(grand_total);

				console.log('Grand Total => ' + grand_total);


				grand_total = grand_total - difference;
				$('#grand_total').html(grand_total);

				console.log('Grand Total => ' + grand_total);

				$('#quantity_' + product_id).val(quantity);
				$('#subtotal_' + product_id).html(subtotal);
				return false;
			}


			jQuery.ajax({
				url: "{{ url('admin/add-to-list') }}",
				method: 'post',
				data: {
				"_token": "{{ csrf_token() }}",
					product_id: product_id,
					//option_id: option_id
				},
				success: function(response) {
					$('#product_list').append(response);

					var grand_total = $('#grand_total').html();
					grand_total = parseFloat(grand_total);

					var retail_price = $('#btn_' + product_id).attr('data-retail-price');
					console.log(retail_price);

					var subtotal = retail_price * 1;

					grand_total = grand_total + subtotal;

					$('#grand_total').html(grand_total);
				}
			});
		});

		function addToList(product_id, option_id) {
	    	
	    }
	});

		function deleteProduct(product_id) {
			var subtotal_to_remove = $('#subtotal_'+ product_id).html();
			var grand_total = $('#grand_total').html();
			var updated_total = grand_total - subtotal_to_remove;
			$('#subtotal_'+ product_id).val();
			$('#product_row_'+ product_id).remove();
			$('#grand_total').html(updated_total);
		}
		function handleQuantity(product_id) {
			var quantity = $('#quantity_'+product_id).val();
			var subtotal = $('#subtotal_'+ product_id).html();
			var new_sub_total = quantity * subtotal;
			$('#subtotal_'+ product_id).html(new_sub_total);
			$('#grand_total').html(new_sub_total);
			console.log('grand_total' + grand_total);
			console.log('new_sub_total' + new_sub_total)
			// var new_grand_total = grand_total + new_sub_total;
			// console.log(new_grand_total);
			// alert(grand_total);
			// alert(subtotal);
		}
    

        function createList() {
	    	var title = $('#title').val();
	    	var description = $('#description').val();
	    	var status = $('#status').val();
        	jQuery.ajax({
                  url: "{{ route('buy-list.store') }}",
                  method: 'post',
                  data: {
                    "_token": "{{ csrf_token() }}",
                     title : title,
                     description : description,
                     status : status 
                  },
                  success: function(result){
                    console.log(result);
                   	$( "#list_title" ).append("<h4>"+title+"</h4>");
            }});
    }
</script>

@stop
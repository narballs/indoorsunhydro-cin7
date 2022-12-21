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
<?php //dd($list);?>
	<div class="row">
		<div class="col-md-5 card">
			<div class="alert alert-success d-none" role="alert" id="success_msg"></div>
			<div class="row mt-5">
				@if($list)
			  		<div class="form-group col-md-6">
			    		<label for="list_name">Title</label>
			    		<input type="text" class="form-control" value={{$list->title}} id="title" aria-describedby="titleHelp" name="title" placeholder="Buy List Title">
			    		<div class="text-danger" id="title_errors"></div>
			  		</div>
		  		@else
			  		<div class="form-group col-md-6">
			    		<label for="list_name">Title</label>
			    		<input type="text" class="form-control"  id="title" aria-describedby="titleHelp" name="title" placeholder="Buy List Title">
			    		<div class="text-danger" id="title_errors"></div>
			  		</div>
		  		@endif
		  		@if(!empty($list->status))
			  		<div class="form-group col-md-6 mb-0">
			    		<label for="type" name="type">Status</label>
			    		<select class="form-control" name="type" id="status">
			    			 <option value="{{$list->status}}" >{{$list->status}}</option>
			    		</select>
			    		<div id="status_errors" class="text-danger"></div>
			  		</div>
		  		@else
			  		 <div class="form-group col-md-6 mb-0">
			    		<label for="type" name="type">Status</label>

			    		<select class="form-control" name="type" id="status">
			    			 <option value="Public" >Public</option>
			    			  <option value="Private">Private</option>
			    			   <option value="Shareable">Shareable</option>
			    		</select>
			    		<div id="status_errors" class="text-danger"></div>
			  		</div>
		  		@endif
		  		@if(!empty($list->description))
		  		<?php //dd($list);?>
			  		<div class="col-md-12 card mt-5">
						<div class="card-body"><h4>Description</h4></div>
						<div class="form-group col-md-12">
			    			<label for="mobile"></label>
			    			<textarea class="form-control" onfocus="this.select()" type="text" rows="10" name="notes" id="description">
			    				{{$list->description}}
			    			</textarea>
			    			<div id="description_errors" class="text-danger"></div>
			  			</div>
					</div>
				@else
					<div class="col-md-12 card mt-5">
						<div class="card-body"><h4>Description</h4></div>
							<div class="form-group col-md-12">
			    				<label for="mobile"></label>
			    				<textarea class="form-control" rows="10" name="notes" id="description"></textarea>
			    			<div id="description_errors" class="text-danger"></div>
			  				</div>
					</div>
				@endif
				@if(!empty($list->id))
				<div class="text-center ms-5" style="margin-bottom: 12px;margin-left: 150px !important;width: 331px;width: 358px !important;">
					<button type="button" class="ms-2 btn btn-primary w-100" onclick="createList()">
						Update List
					</button>
				</div>
				@else 
				<div class="text-center ms-5" style="margin-bottom: 12px;margin-left: 150px !important;width: 331px;width: 358px !important;">
					<button type="button" class="ms-2 btn btn-primary w-100" onclick="createList()">
						Create List
					</button>
				</div>
				@endif
		  	</div>
		</div>
		<div class="col-md-7 card">
			@livewire('filter')
		</div>
	</div>
<?php //dd($list->list_products);?>
	@if(!empty($list->list_products))
		<div class="row w-100 pl-2 pr-0">
			<div class="card col-md-12">
				<div class="card-body w-100" id="list">
					<div id="list_title">
						<h4></h4>
					</div>
					<input type="hidden" id="list_id" value="{{$list->id}}">
					<input type="hidden" id="is_update" value="1">
						<table id="product_list" class="table">
							<tr>
								<td style="width:373px !important">Product Title</td>
								<td>Image</td>
								<td>Price</td>
								<td>Quantity</td>
								<td>Subtotal</td>
								<td>Remove</td>
							</tr>
							<?php //dd($list);?>
						@foreach($list->list_products as $list_product)
						<?php //dd($list_product);?>
                        @foreach($list_product->product->options as $option)
                            <!-- <tr id="product_row_{{$list_product->product_id }}"> -->
                            <tr id="product_row_{{ $list_product->product_id }}" class="product-row-{{ $list_product->product_id }} admin-buy-list">
                                <td>
                                    {{$list_product->product->name}}
                                </td>
                                <td>
                                	<input type="hidden" id="option_id_{{$list_product->product_id}}" value="{{ $option->option_id}}">
                                	<img src="{{$option->image}}" alt="Product 1" class="img-circle img-size-32 mr-2">
                                </td>
                                <td>	
                                	$<span id="retail_price_{{ $list_product->product_id }}"> {{$list_product->product->retail_price}} </span></td>
                                <td>
									<input type="number" min="1"   id="quantity_{{ $list_product->product_id }}" value="{{$list_product->quantity}}" onclick="handleQuantity({{$list_product->product_id}})">
								</td>
                                <td>
									$<span id="subtotal_{{$list_product->product_id}}"> {{ number_format($list_product->product->retail_price * $list_product->quantity, 2) }} </span>
								</td>
                                <td>
                                       <a class="cursor-pointer delete" title="" data-toggle="tooltip" data-original-title="Delete">
            <i class="fas fa-trash-alt cursor-pointer" onclick="deleteProduct({{$list_product->product_id }})"></i>
        </a>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach

						</table>
						<div class="row">
							<div class="col-md-10 border-top">Grand Total</div>
							<div class="col-md-2 border-top">amount : <span id="grand_total">{{$list_product->grand_total}}</span></div>
						</div>
						<div class="row">
							<div class="col-md-10 border-top"><button type="button" class="ms-2 btn btn-primary" onclick="generatList()">Update List</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	@else 
		<div class="row w-100 pl-2 pr-0">
			<div class="card col-md-12">
				<div class="card-body w-100 d-none" id="list">
					<div id="list_title">
						<h4></h4>
					</div>
					<input type="hidden" id="list_id" value="">
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
						<div class="row">
							<div class="col-md-10 border-top"><button type="button" class="ms-2 btn btn-primary" onclick="generatList()">Create List</button>
						</div>
					</div>
				</div>
			</div>
		</div>

	@endif
@livewireScripts
  @stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
	$( document ).ready(function() {

		var list_id = $("#list_id").val();
		if (list_id == '') {
			$(".btn-add-to-cart").prop('disabled', true);
		}
		else {
			$(".btn-add-to-cart").prop('disabled', false);
		}
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

		
	});
		function generatList() {
			var is_update = $('#is_update').val();
			var listItems = [];
			var list_id = $('#list_id').val();
			var grand_total = $('#grand_total').html();
			console.log(grand_total);
			$('.admin-buy-list').each(function() {
				var product_id = this.id;
				product_id = product_id.replace('product_row_', '');
				var retail_price = $('#retail_price_' + product_id).html();
				var option_id = $('#option_id_' + product_id).val();
				var quantity = $('#quantity_' + product_id).val();
				var subtotal = $('#subtotal_' + product_id).html();
				console.log(subtotal);
				listItems.push({
					product_id: product_id,
					option_id : option_id,
					quantity :  quantity,
					subtotal: subtotal,
					grand_total: grand_total,
				});
			});
			console.log(listItems);
			jQuery.ajax({
				url: "{{ url('admin/generate-list') }}",
				method: 'post',
				data: {
				"_token": "{{ csrf_token() }}",
					listItems: listItems,
					listId : list_id,
					is_update: is_update
				},
				success: function(response) {
					 window.location.href = "{{ route('buy-list.index')}}";
				}
			});
		}

		function deleteProduct(product_id) {
			var row = $('#product_row_' + product_id).length;
			if (row < 1) {
				$('#grand_total').html(0.00);
			}
			var subtotal_to_remove = parseFloat($('#subtotal_'+ product_id).html());
			var grand_total = parseFloat($('#grand_total').html());
			var updated_total = 0;
			updated_total = parseFloat(grand_total) - parseFloat(subtotal_to_remove);
			$('#subtotal_'+ product_id).val();
			$('#product_row_'+ product_id).remove();
			alert(updated_total);
			$('#grand_total').html(updated_total);
		}
		function handleQuantity(product_id) {
			var difference = 0;
			var subtotal_before_update = parseFloat($('#subtotal_' + product_id).html());
			console.log('difference => ' + difference);
			console.log('sub total before update  => ' + subtotal_before_update);

			var retail_price = parseFloat($('#retail_price_' + product_id).html());
			var quantity = parseFloat($('#quantity_' + product_id).val());
			var subtotal = parseFloat($('#subtotal_' + product_id).html());
			
			
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
                  	success: function(response){
                   		$( "#list_title" ).append("<h4>"+title+"</h4>");
                   		$("#list_id").val(response.list_id);
                   		$("#title_errors").html('');
                   		$("#status_errors").html('');
                   		$("#description_errors").html('');
                   		console.log(response);
                   		$("#success_msg").html(response.success);
                   		$("#success_msg").removeClass('d-none');
                   		$(".btn-add-to-cart").prop('disabled', false);
                   		$("#list").removeClass('d-none');

            		}, 
            		error : function(response) {
            			console.log(response.responseJSON.errors);
            			if (response.responseJSON.errors.title) {

            				$("#title_errors").html(response.responseJSON.errors.title);
            			}
            			else {
            				$("#title_errors").html('');
            			}

            			if (response.responseJSON.errors.status) {
            				$("#status_errors").html(response.responseJSON.errors.status);
            			}
            			else {
            				$("#status_errors").html('');
            			}

            			if (response.responseJSON.errors.description) {
            				$("#description_errors").html(response.responseJSON.errors.description);
            			}
            			else {
            				$("#description_errors").html('');
            			}
            		}
        });
    }
</script>

@stop
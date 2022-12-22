@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
   
@stop

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header border-0">
            <div class="card-title"><h4>{{$list->title}}</h4></div>
            
            <div class="card-tools">
                <a href="#" class="btn btn-tool btn-sm">
                    <i class="fas fa-download"></i>
                </a>
                <a href="#" class="btn btn-tool btn-sm">
                    <i class="fas fa-bars"></i>
                </a>
                 <a href="{{url('/create-cart')}}/{{$list->id}}"><button type="button" class="btn btn-info">Share</button></a>
                 <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">Share</button>
            </div>

        </div>
<!-- Button trigger modal -->


        <?php //dd($list->list_products->product);?>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-valign-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                     <?php //dd($list->list_products);?>
                    @foreach($list->list_products as $list_product)
                        @foreach($list_product->product->options as $option)
                            <tr>
                                <td>
                                    <img src="{{$option->image}}" alt="Product 1" class="img-circle img-size-32 mr-2">
                                    {{$list_product->product->name}}
                                </td>
                                <td>${{$list_product->product->retail_price}}</td>
                                <td class="jsutify-content-middle">
                                  <!--   <small class="text-success mr-1">
                                        <i class="fas fa-arrow-up"></i>
                                    12%
                                    </small> -->
                                        {{$list_product->quantity}}
                                </td>
                                <td>
                                   ${{$list_product->sub_total}}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr colspan="3"> <th>Grand Total</th><td>${{$list_product->grand_total}}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Share List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <div class="modal-body">
            <form>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Please enter email.</label>
                    <input type="text" class="form-control" name="email" id="email">
                </div>
                <input type="hidden" id="list_id" name="list_id" value="{{$list->id}}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="sendEmail();">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function sendEmail() {
        var email = $('#email').val();
        var list_id = $('#list_id').val();
        //alert(list_id);
        jQuery.ajax({
                  url: "{{ url('/admin/share-list/') }}",
                  method: 'post',
                  data: {
                    "_token": "{{ csrf_token() }}",
                     email: email,
                     list_id: list_id
                  },
                  success: function(result){
                     console.log(result);
                      //jQuery('.alert').html(result.success);
                        // window.location.reload();
                  }});
    }
</script>
@stop
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
   
@stop

@section('content')

    <div class="table-wrapper">
        <div class="table-title">
            <span><h1>Customer</h1></span>
            <div class="row justify-content-between mb-2">
                <div class="col-sm-2">
                <div class="search-box">
                    <a href="{{'customer/create'}}"><input type="button" value="Create New Customer" class="form-control btn btn-primary" placeholder="Create New">
                    </a>
                </div>
            </div>
                <div class="col-md-4">
                    <div id="custom-search-input">
                        <div class="input-group col-md-8">
                            <span class="input-group-btn">
                                <button class="btn btn-info btn-lg" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            <input type="text" class="form-control input-lg" id="search" placeholder="Search" />
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="card card-body">

            <table class="table table-striped table-hover table-bordered" id="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name <i class="fa fa-sort"></i></th>
                        <th>Status <i class="fa fa-sort"></i></th>
                        <th>Price Tier<i class="fa fa-sort"></i></th>
                        <th>Company</th>
                        <th class="w-75">Notes<i class="fa fa-sort"></i></th>
                        <th class="w-25">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 0; ?>
                    @foreach($contacts as $key=>$contact)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$contact->firstName}}</td>
                            <td>
                            	@if($contact->status == '1')
                            		<span class="badge bg-success">Active</span>
                            	@else 
                            		<span class="badge bg-bg-danger">Inactive</span>
                            	@endif
                            </td>
                            <td>{{$contact->priceColumn}}</td>
                            <td>{{$contact->company}}</td>
                            <td>{{$contact->notes}}</td>
                            <td>
                                <a href="{{ url('admin/customer-detail/'.$contact->id) }}" class="view" title="" data-toggle="tooltip" data-original-title="View"><i class="fas fa-eye"></i></a>
                                <a href="#" class="edit" title="" data-toggle="tooltip" data-original-title="Edit"><i class="fas fa-pen"></i></a>
                                <a href="#" class="delete" title="" data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-10">
                    {{$contacts->links('pagination::bootstrap-4')}}
                </div>
                <div class="col-md-2 justify-content-">
                    <select id="pagination" onchange="paginate()">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="200">200</option>
                    </select>
                </div>
            </div>

          <!--   <div class="row" >
            {{ $contacts->links() }}
        </div> -->
         <!--    <div class="clearfix">
                <div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
                <ul class="pagination">
                    <li class="page-item disabled"><a href="#"><i class="fa fa-angle-double-left"></i></a></li>
                    <li class="page-item"><a href="#" class="page-link">1</a></li>
                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                    <li class="page-item active"><a href="#" class="page-link">3</a></li>
                    <li class="page-item"><a href="#" class="page-link">4</a></li>
                    <li class="page-item"><a href="#" class="page-link">5</a></li>
                    <li class="page-item"><a href="#" class="page-link"><i class="fa fa-angle-double-right"></i></a></li>
                </ul>
            </div> -->
        </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style type="text/css">
        #custom-search-input{
            padding: 3px;
            border: solid 1px #E4E4E4;
            border-radius: 6px;
            background-color: #fff;
        }

        #custom-search-input input{
            border: 0;
            box-shadow: none;
        }

        #custom-search-input button{
            margin: 2px 0 0 0;
            background: none;
            box-shadow: none;
            border: 0;
            color: #666666;
            padding: 0 8px 0 10px;
            border-right: solid 1px #ccc;
        }

        #custom-search-input button:hover{
            border: 0;
            box-shadow: none;
            border-left: solid 1px #ccc;
        }

        #custom-search-input .glyphicon-search{
            font-size: 23px;
        }
    </style>
@stop

@section('js')
    <script>
        var $rows = $('#table tr');
        $('#search').keyup(function() {
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
    
        $rows.show().filter(function() {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        return !~text.indexOf(val);
        }).hide();
}); 
    function paginate() {
        var num_of_rows = $( "#pagination" ).val();
        $.ajax({
           type: "GET",
           url: "{{ url('admin/customers') }}",
           data: {
                "num_of_rows" : num_of_rows,
            },
           cache: false,
           success: function(result){
            console.log(result);
            setInterval('location.reload()', 7000);
           // $(".flash").hide();
           // $("#pageData").html(result);
           }
      });

    }


</script>
@stop
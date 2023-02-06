@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
   
@stop

@section('content')
@if (\Session::has('success'))
    <div class="alert alert-success">
        <ul>
            <li>{!! \Session::get('success') !!}</li>
        </ul>
    </div>
@endif
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
                        <div class="input-group col-md-12">
                            <span class="input-group-btn">
                                <button class="btn btn-info btn-lg" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            <!-- <input type="text" class="form-control input-lg" id="search" placeholder="Search" onkeydown="customer_search()" /> -->
                            <form method="get" action="/admin/customers">
                                <input type="text" class="form-control input-lg" id="search" name="search" placeholder="Search" value="{{ isset($search) ? $search : '' }}" />
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
                        <th>Name <i class="fa fa-sort"></i></th>
                        <th>Status <i class="fa fa-sort"></i></th>
                        <th>Merged</th>
                        <th>Price Tier<i class="fa fa-sort"></i></th>
                        <th>Company</th>
                        <th>Email<i class="fa fa-sort"></i></th>
                        <th class="w-75">Notes<i class="fa fa-sort"></i></th>
                        <th class="w-25">Actions</th>
                    </tr>
                </thead>
                <tbody id="searched">
                    <?php $count = 0; ?>
                    @foreach ($contacts as $key => $contact)
                        <?php $count ++; ?>
                        @include('admin.customer_row')
                    @endforeach
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-10">
                  {{$contacts->appends(Request::all())->links()}}
                </div>
                <div class="col-md-2">
                    <select name="per_page" id="per_page" onchange="perPage()">
                        <option value="10" {{ isset($perPage) && $perPage == 10 ? 'selected="selected"' : '' }}>10</option>
                        <option value="20" {{ isset($perPage) && $perPage == 20 ? 'selected="selected"' : '' }}>20</option>
                        <option value="30" {{ isset($perPage) && $perPage == 30 ? 'selected="selected"' : '' }}>30</option>
                        <option value="30">30</option>
                    </select>
                    
                </div>
            </div>
        </div>
}
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
        function perPage() {
            var perPage = $('#per_page').val();
            var search = $('#search').val();
            // alert(perPage);
           var basic_url = 'customers?perPage='+perPage+'&search='+search;
            window.location.href = basic_url;
           //alert(basic_url);
        }
        function search() {
            var $rows = $('#table tr');
            $('#search').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
        
            $rows.show().filter(function() {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
            }).hide();
            }); 
        }

        function customer_search() {
            var typingTimer;                
            var doneTypingInterval = 1000;  
            var $input = $('#search');
            $input.on('keyup', function () {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(doneTyping, doneTypingInterval);
            });

            $input.on('keydown', function () {
                    clearTimeout(typingTimer);
            });

            function doneTyping () {
                var val = $('#search').val();
                jQuery.ajax({
                    url: "{{ url('admin/customersearch') }}",
                    method: 'GET', 
                    data: {
                        "value" : val,
                    },
                    cache:false,
                    success: function(response){
                        $('.table-customer tbody').html(response);
                    }
                });
            }
        }
</script>
@stop
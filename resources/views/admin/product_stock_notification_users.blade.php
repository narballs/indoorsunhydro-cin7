@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
@stop
@section('content')
    @if (\Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
            {!! \Session::get('success') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @elseif (\Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
            {!! \Session::get('error') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="table-wrapper">
        <div class="card-body product_secion_main_body">
            <div class="row border-bottom product_section_header">
                <div class="col-md-12">
                    <div class="row align-items-center">
                        <div class="col-md-8 mobile_heading">
                            <p class="product_heading">
                                Product Stock Notification Users
                            </p>
                        </div>
                        <div class="col-md-4">
                            <span class="notiy_btn_mbl">
                                @if ($auto_notify == true)
                                    <span class="d-flex">
                                        <a class="btn  btn-sm notify-row-items-order-page">
                                            Auto Notify
                                        </a>
                                        <label class="custom-control custom-checkbox ">
                                            <input type="checkbox" id="auto_notify" value="{{ $auto_notify }}"
                                                class="custom-control-input general_switch" onchange="autoNotify()"
                                                {{ isset($auto_notify) && $auto_notify == true ? 'checked="checked"' : '' }}>
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                    </span>
                                @else
                                    <span class="d-flex ">
                                        <a class=" btn  btn-sm notify-row-items-order-page">
                                            Auto Notify
                                        </a>
                                        <label class="custom-control custom-checkbox ">
                                            <input type="checkbox" id="auto_notify" value=""
                                                class="custom-control-input general_switch" onchange="autoNotify()">
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                    </span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-body  mt-5">
            <div class="col-md-12 shadow border">
                <table class="table table-border">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Email</th>
                            <th>Product Name</th>
                            <th>Product Code</th>
                            <th>Date Requested</th>
                            <th>Date Sent</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i =1; 
                        @endphp
                        @if(count($product_stock_notification_users) > 0 )
                        @foreach ($product_stock_notification_users as $product_stock_notification_user)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{$product_stock_notification_user->email}}</td>
                            <td>{{ \Illuminate\Support\Str::limit($product_stock_notification_user->product->name, 33) }}</td>
                            <td>{{$product_stock_notification_user->sku}}</td>
                            <td>{{$product_stock_notification_user->created_at}}</td>
                            <td>{{$product_stock_notification_user->updated_at}}</td>
                            <td>
                                @if ($product_stock_notification_user->status == 0) 
                                <span class="badge badge-danger">Pending</span> 
                                @elseif ($product_stock_notification_user->status == 1)
                                <span class="badge badge-success">Completed</span>
                                @endif
                            </td>
                            <td>
                                <div class="row">
                                    @if ($product_stock_notification_user->status == 0)
                                        <form action="{{route('product_stock_notification')}}" method="post">
                                            
                                            @csrf
                                            <input type="hidden" name="product_stock_notification_user" id="" class="" value="{{$product_stock_notification_user->id}}">
                                            <button type="submit" class="btn btn-primary text-white text-white" onclick="return confirm('Are you sure you want to Notify this Contact?');">Notify</button>
                                            <button type="button" class="btn btn-info" id="openModalBtn" onclick="open_alternative_modal('{{$product_stock_notification_user->id}}')">
                                                Offer Alternative
                                            </button>
                                            @if (count($product_stock_notification_user->productStockNotificationAlternatives) > 0)
                                            <button type="button" class="btn btn-warning" id="openModalBtn" onclick="openAlternativeHistoryModal('{{$product_stock_notification_user->id}}')">
                                                Offer Alternative History
                                            </button>
                                            @endif
                                        </form>
                                    @else
                                        <form action="">
                                            <button type="button" class="btn btn-success text-white text-white"> Notified</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="3">No Users Found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-10">
                    {{ $product_stock_notification_users->links('pagination.custom_pagination') }}
                </div>
            </div>
        </div>
    @stop


    @section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
    <style type="text/css">
        .productList {
            display: block;
            position: relative !important;
            top: 0px;
            max-height: 300px;
            overflow-y: scroll;
            overflow-x: hidden;
        }
        .title_style {
            font-size: 14px;
            font-weight: 500;
            color: #000;
            cursor: pointer;
            /* font-family: 'poppins' */
        }
        .code_style {
            font-size: 14px;
            font-weight: 500;
            color: #000;
            /* font-family: 'poppins' */
        }
        .img-style-div {
            border: 2px solid lightgray;
        }
        .p_image  {
            cursor: pointer;
        }

        @media(min-width:280px) and (max-width: 425px) {
            .main-header {
                border-bottom: none;
                width: 25%;
                height: 0px !important;
                margin-top: 20px !important;
            }

            .mobile_heading {
                position: absolute;
                left: 10rem;
                top: -3rem;
                width: 0px !important;

            }

            .search_row_admin-interface {
                position: absolute;
                top: 1rem;
                left: 1rem;
                width: 95%;
            }

            .mobile_fulfill_div {
                margin-top: 3.563rem
            }
            .mobile_notify_div {
                margin-top: 3.563rem
            }

            .fullfill_btn_mbl {
                position: absolute;
                left: 3.3rem;
            }
            .notiy_btn_mbl {
                position: absolute;
                left: 3.3rem;
            }

            .create_new_btn_mbl {
                margin-right: 0.5rem;
            }

            .product_section_header {
                border-bottom: none !important;
            }

            .sm-d-none {
                display: none !important;
            }

            .bx-mobile {
                display: flex !important;
                justify-content: space-around !important;
                align-items: center !important;
            }

            .mobile-screen-selected {
                width: 30%;
            }

            .mobile-screen-ordrs-btn {
                width: 70%;
            }

            .product_table_body {
                padding-right: 13px !important;
                margin-top: -17px;
                padding-left: 0px !important;
            }

            .select-row-items {
                padding-left: 12px !important;
                display: flex;
                justify-content: start;
                align-items: center !important;
                color: #222222 !important;
                font-style: normal !important;
                font-weight: 500 !important;
                font-size: 0.826rem !important;
                padding-top: 0px !important;
            }

            .product_heading {
                color: #242424 !important;
                font-size: 18px !important;
                font-weight: 500;
                line-height: 24px;
                letter-spacing: 0.252px;
                font-family: 'Poppins', sans-serif !important;
                margin-left: -5px !important;
                margin-top: 26px !important;
            }

            .create_bnt {
                padding: 9px 24px !important;
                margin-top: 114px !important;
            }

            .fillter-mobile-screen {
                width: 100% !important;
            }

            .filter-row-mobile-secreen {
                margin-top: 7px !important;
                padding-left: 41px !important;

            }

            .product_search {
                background: #FFFFFF !important;
                border-radius: 7.25943px !important;
                margin-top: -7px;
                margin-left: 32px !important;
                padding-right: 16px !important;
            }

            .mobile-screen {
                widows: 100% !important;
            }

            .mobile_screen_Previous_btn {
                width: 25% !important;
            }

            .mobile_screen_pagination_number {
                width: 50% !important;
            }

            .mobile_screen_Previous_next {
                width: 25% !important;
                margin-top: 11px !important;
            }

            .main-sidebar {
                background-color: #fff !important;
                box-shadow: none !important;
                border-right: 1px solid #EAECF0 !important;
                top: -21px !important;
            }
        }
        .text-successs {
            color: #7CC633 !important;
            font-family: 'Poppins', sans-serif !important;
        }

        .badge-success {
            background: rgb(186 235 137 / 20%);
            color: #319701;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;

        }

        .bg_success {
            /* background: rgb(186 235 137 / 20%) !important; */
            color: #319701 !important;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }

        .badge-warning {
            background-color: #f1e8cb;
            color: #b58903 !important;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }

        .badge-danger {
            color: #fff;
            background-color: rgba(220, 78, 65, 0.12);
            color: #DC4E41;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }

        .bg_danger {
            color: #DC4E41 !important;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }
        .custom-checkbox {
            min-height: 1rem;
            padding-left: 0;
            margin-right: 0;
            cursor: pointer;
        }

        .custom-checkbox .custom-control-indicator {
            content: "";
            display: inline-block;
            position: relative;
            width: 30px;
            height: 10px;
            background-color: #818181;
            border-radius: 15px;
            margin-right: 10px;
            -webkit-transition: background .3s ease;
            transition: background .3s ease;
            vertical-align: middle;
            margin: 0 16px;
            box-shadow: none;
        }

        .custom-checkbox .custom-control-indicator:after {
            content: "";
            position: absolute;
            display: inline-block;
            width: 18px;
            height: 18px;
            background-color: #f1f1f1;
            border-radius: 21px;
            box-shadow: 0 1px 3px 1px rgba(0, 0, 0, 0.4);
            left: -2px;
            top: -4px;
            -webkit-transition: left .3s ease, background .3s ease, box-shadow .1s ease;
            transition: left .3s ease, background .3s ease, box-shadow .1s ease;
        }

        .custom-checkbox .custom-control-input:checked~.custom-control-indicator {
            background-color: #28a745;
            background-image: none;
            box-shadow: none !important;
        }

        .custom-checkbox .custom-control-input:checked~.custom-control-indicator:after {
            background-color: #28a745;
            left: 15px;
        }

        .custom-checkbox .custom-control-input:focus~.custom-control-indicator {
            box-shadow: none !important;
        }

    </style>
@stop
    <div class="modal fade" id="alternative_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Offer Alternative</h5>
                    <button type="button" class="close" onclick="close_alternative_modal(this)" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info alert-dismissible fade show offer_alternative_div d-none" role="alert">
                                <p class="offer_alternative_text mb-0"></p>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <input type="hidden" name="product_stock_notification_id" id="product_stock_notification_id" value="">
                            <input type="text" name="search_products" class="form-control" placeholder="Search ..." id="search_products" onchange="searchProducts()"  onkeydown="searchProducts()">
                        </div>
                        <div class="col-md-12 products_search_div d-none">
                            <div class="dropdown-menu productList w-100 d-flex align-items-center row p-2"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="close_alternative_modal(this)">Close</button>
                    <button type="button" class="btn btn-info" onclick="send_alternate_product_notification()">Notify</button>
                    <div class="spinner-border offer_alternative_spinner d-none" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Products</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="notification_id" id="notificationId">
                    <ul id="productList" class="list-group">
                        <!-- Products will be appended here -->
                    </ul>
                </div>
            </div>
        </div>
    </div>

@section('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function open_alternative_modal($notification_id) {
        $('#alternative_modal').modal('show');
        $('#product_stock_notification_id').val($notification_id);
    }

    function close_alternative_modal() {
        $('#alternative_modal').modal('hide');
    }

    function open_alternative_history_modal($notification_id) {
        $('#alternative_history_modal').modal('show');
    }

    function close_alternative_history_modal() {
        $('#alternative_history_modal').modal('hide');
    }

    function searchProducts() {
        var search = $('#search_products').val();
        if (search.length >=2 && !null) {
            $.ajax({
                url: '/admin/search/aletrnative/products',
                type: 'Post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content')
                },
                data: {
                    search: search
                },
                success: function(response) {
                    $('.productList').html('');
                    if ((response.products.length) > 0  && (response.success == true)) {
                        $('.products_search_div').removeClass('d-none');
                        $.each(response.products, function(key, value) {
                            if (value.images != '') {
                                imageUrl = value.images;
                            } else {
                                imageUrl = '/theme/img/image_not_available.png';
                            }
                            $('.productList').append(
                                `
                                <div class="w-100 d-flex mb-1 border p-1 align-items-center">
                                <div class="col-md-1">
                                    <input type="checkbox" name="product_id[]" id="product_id_`+value.product_id+`" data-id="`+value.product_id+`" class="product_id" value="`+value.product_id+`">
                                </div>
                                <div class="col-md-11">
                                    <div class="row align-items-center">
                                        <div class="col-md-2 img-style-div p-1">
                                            <img src="`+ imageUrl +`" alt="Product Image" class="img-fluid p_image" onclick="checked_by_image(`+value.product_id+`)">
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-0 title_style" onclick="checked_by_name(`+value.product_id+`)">`+value.name+`</p>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <p class="mb-0 code_style">`+value.code+`</p>
                                        </div>
                                    </div
                                
                                    
                                </div>
                                </div>
                                `
                            );
                        });
                    } else {
                        $('.productList').append(
                            `<div class="col-md-12">
                                <a class="dropdown-item">No Record Found</a>
                            </div>`
                        );
                    }
                }
            });
        } 
        else if (search.length == 0) {
            $('.products_search_div').addClass('d-none');
            $('.productList').html('');
        }
        else {
            $('.products_search_div').addClass('d-none');
            $('.productList').html('');
        }
        
    }

    function checked_by_name (product_id) {
        if ($('#product_id_'+product_id).prop('checked') == true) {
            $('#product_id_'+product_id).prop('checked', false);
        } else {
            $('#product_id_'+product_id).prop('checked', true);
        }

    }

    function checked_by_image (product_id) {
        if ($('#product_id_'+product_id).prop('checked') == true) {
            $('#product_id_'+product_id).prop('checked', false);
        } else {
            $('#product_id_'+product_id).prop('checked', true);
        }
    }

    function send_alternate_product_notification () {
        $('.offer_alternative_spinner').removeClass('d-none');
        var product_ids = [];
        var product_stock_notification_id = $('#product_stock_notification_id').val();
        $('.product_id').each(function() {
            if ($(this).prop('checked') == true) {
                product_ids.push($(this).val());
            }
        });
        if (product_ids.length > 0) {
            $.ajax({
                url: '/admin/send/alternative/notification',
                type: 'Post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content')
                },
                data: {
                    product_ids: product_ids,
                    product_stock_notification_id: product_stock_notification_id
                },
                success: function(response) {
                    console.log(response)
                    if (response.status == true) {
                        $('.offer_alternative_spinner').addClass('d-none');
                        $('.offer_alternative_div').removeClass('d-none');
                        $('.offer_alternative_text').text(response.message);
                        setTimeout(() => {
                            $('#alternative_modal').modal('hide');
                            location.reload();
                        }, 1000);
                    } else {
                        $('.offer_alternative_spinner').addClass('d-none');
                        $('.offer_alternative_div').removeClass('d-none');
                        $('.offer_alternative_text').text(response.message);
                        setTimeout(() => {
                            $('#alternative_modal').modal('hide');
                            location.reload();
                        }, 1000);
                    }
                }
            });
        } else {
            $('.offer_alternative_spinner').addClass('d-none');
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'Please Select Product to Notify',
                timer: 3000,
                showConfirmButton: false,
                position: 'top',
                timerProgressBar: true
            });
        }
    }

</script>
<script>
    function openAlternativeHistoryModal(notificationId) {
        // Set the notification ID
        document.getElementById('notificationId').value = notificationId;

        // Fetch products from the server
        $.ajax({
            url: '/admin/alternative/products/history',  // Adjust the URL to your route
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',  // Laravel CSRF token
                product_stock_notification_id: notificationId
            },
            success: function(response) {
                if (response.status) {
                    const products = response.product_stock_notification_alternatives;
                    const productList = document.getElementById('productList');
                    productList.innerHTML = ''; // Clear previous content

                    // Append products to the list
                    products.forEach(value => {
                        let imageUrl = value.product.images !== '' ? value.product.images : '/theme/img/image_not_available.png';

                        const listItem = document.createElement('li');
                        listItem.className = 'list-group-item';

                        // Create the row div
                        const rowDiv = document.createElement('div');
                        rowDiv.className = 'row align-items-center';

                        // Create the image column
                        const imgColDiv = document.createElement('div');
                        imgColDiv.className = 'col-md-2';

                        // Create the image element
                        const img = document.createElement('img');
                        img.src = imageUrl;
                        img.alt = value.product.name;
                        img.style.width = '50px';
                        img.style.height = '50px';

                        // Append the image to the image column
                        imgColDiv.appendChild(img);

                        // Create the text column
                        const textColDiv = document.createElement('div');
                        textColDiv.className = 'col-md-6';

                        // Create the text node for the product name
                        const textNode = document.createTextNode(value.product.name);

                        // Append the text node to the text column
                        textColDiv.appendChild(textNode);

                        // Create the button column
                        const btnColDiv = document.createElement('div');
                        btnColDiv.className = 'col-md-4 text-right';

                        // Create the notify button
                        const notifyButton = document.createElement('button');
                        notifyButton.className = 'btn btn-info notify-user-btn';
                        notifyButton.innerText = 'Notify';
                        const loader = document.createElement('div');
                        loader.className = 'ml-2 alternate_history_loader spinner-border spinner-border-sm d-none';
                        notifyButton.appendChild(loader);

                        notifyButton.onclick = function() {
                            document.querySelectorAll('.notify-user-btn').forEach(btn => btn.disabled = true);
                            // Show the loader
                            loader.classList.remove('d-none');
                            // Notify the user
                            $.ajax({
                                url: '/admin/notify/user/product/history',
                                method: 'post',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    product_id: value.product.id,
                                    email: value.product_stock_notification.email,
                                    sku: value.product.code
                                },
                                success: function(response) {
                                    loader.classList.add('d-none');
                                    document.querySelectorAll('.notify-user-btn').forEach(btn => btn.disabled = false);
                                    if (response.status == true) {
                                        Swal.fire({
                                            toast: true,
                                            icon: 'success',
                                            title: response.message,
                                            timer: 3000,
                                            showConfirmButton: false,
                                            position: 'top',
                                            timerProgressBar: true
                                        });
                                    } else {
                                        
                                        Swal.fire({
                                            toast: true,
                                            icon: 'error',
                                            title: 'An error occurred while sending notification.',
                                            timer: 3000,
                                            showConfirmButton: false,
                                            position: 'top',
                                            timerProgressBar: true
                                        });
                                    }
                                },
                                error: function(error) {
                                   // Hide the loader
                                    loader.classList.add('d-none');
                                    document.querySelectorAll('.notify-btn').forEach(btn => btn.disabled = false);
                                    Swal.fire({
                                        toast: true,
                                        icon: 'error',
                                        title: 'An error occurred while sending notifications.',
                                        timer: 3000,
                                        showConfirmButton: false,
                                        position: 'top',
                                        timerProgressBar: true
                                    });
                                }
                            });
                        };

                        // Append the notify button to the button column
                        btnColDiv.appendChild(notifyButton);

                        // Append the columns to the row
                        rowDiv.appendChild(imgColDiv);
                        rowDiv.appendChild(textColDiv);
                        rowDiv.appendChild(btnColDiv);

                        // Append the row to the list item
                        listItem.appendChild(rowDiv);

                        // Append the list item to the product list
                        productList.appendChild(listItem);
                    });

                    // Show the modal
                    $('#productModal').modal('show');
                } else {
                    $('.alternate_history_loader').addClass('d-none');
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: 'An error occurred while fetching product alternatives.',
                        timer: 3000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
                    });
                }
            },
            error: function(error) {
                $('.alternate_history_loader').addClass('d-none');
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    title: 'An error occurred while fetching product alternatives.',
                    timer: 3000,
                    showConfirmButton: false,
                    position: 'top',
                    timerProgressBar: true
                });
            }
        });
    }
</script>



@stop
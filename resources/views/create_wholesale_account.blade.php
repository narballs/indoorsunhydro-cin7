@include('partials.header')

<body>
    <main>
        @include('partials.top-bar')
        @include('partials.search-bar')
        <div class="container-fluid px-0 overflow-hidden">
            <div class="row justify-content-center">
                <div class="col-md-8 " id="hide_tabs_div">
                    <div class="row mt-5">
                        <div class="col-md-3 bg-white p-3 pt-5 navigation-div">
                            <h5 class="tabs_heading mb-2">
                                Application Session
                            </h5>
                            <ul class="nav nav_wholesale flex-column nav-pills pl-2">
                                <li class="nav-item p-1">
                                    <a class="wholesale-nav-link wholesale_account_tabs step_1_nav_link active" data-toggle="pill" href="#step1">Customer Application</a>
                                </li>
                                <li class="nav-item p-1">
                                    <a class="wholesale-nav-link wholesale_account_tabs step_2_nav_link" data-toggle="pill" href="#step2">Regulation 1533.1</a>
                                </li>
                                <li class="nav-item p-1">
                                    <a class="wholesale-nav-link wholesale_account_tabs step_3_nav_link" data-toggle="pill" href="#step3">ACH Authorization Form</a>
                                </li>
                                <li class="nav-item p-1">
                                    <a class="wholesale-nav-link wholesale_account_tabs step_4_nav_link" data-toggle="pill" href="#step4">Credit Card Authorization</a>
                                </li>
                            </ul>
                        </div>
                        <!-- Content Area -->
                        <div class="col-md-9 bg-light p-4">
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
                            <div class="row justify-content-center">
                                <div class="col-md-10 p-4">
                                    <div class="important_notice mt-2  mb-3">
                                        <div class="row p-3">
                                            <div class="col-md-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" class="mt-1" height="16" viewBox="0 0 16 16" fill="none">
                                                    <g clip-path="url(#clip0_107_29812)">
                                                    <path d="M8.00016 14.6666C11.6821 14.6666 14.6668 11.6819 14.6668 7.99998C14.6668 4.31808 11.6821 1.33331 8.00016 1.33331C4.31826 1.33331 1.3335 4.31808 1.3335 7.99998C1.3335 11.6819 4.31826 14.6666 8.00016 14.6666Z" stroke="#242424" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M8 10.6667V8" stroke="#242424" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M8 5.33331H8.00667" stroke="#242424" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </g>
                                                    <defs>
                                                    <clipPath id="clip0_107_29812">
                                                        <rect width="16" height="16" fill="white"/>
                                                    </clipPath>
                                                    </defs>
                                                </svg>
                                            </div>
                                            <div class="col-md-11 pl-0">
                                                <p class="notice_para mb-0 font-weight-bold text-danger">
                                                    Please complete all the steps for Application Consideration!
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <form action="{{route('store_wholesale_account')}}" id="wholesaleForm" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="wholesale_application_id" id="whs_id" value="{{isset($id) ? $id : ''}}">
                                        <div class="" id="">
                                            <ul class="" id="wholesale_form_error"></ul>
                                        </div>
                                        <div class="tab-content">
                                            <!-- Step 1 Content -->
                                            <div class="tab-pane fade show active" id="step1">
                                                <div class="card">
                                                    <div class="card-body p-4">
                                                        <div class="row p-3">
                                                            <div class="main-head-step-form">
                                                                <h5 class="step_1_main_heading mb-0">
                                                                    New Wholesale Customer Application
                                                                </h5>
                                                            </div>
                                                            {{-- first row --}}
                                                            <div class="row mb-2">
                                                                <div class="sub-head-step-form mb-2">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <h6 class="step_1_sub_heading">
                                                                                Company Information 
                                                                            </h6>
                                                                        </div>
                                                                        @if(empty($id))
                                                                        <div class="col-md-6 text-right">
                                                                            <button class="step_next btn" data-toggle="" data-target="" type="button" id="show_previous_data_button" onclick="show_previous_data_pop_up()"> Show Previous Data </button>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="company_name">Company Name <span class="text-danger">*</span></label>
                                                                        <input type="text" name="company" class="form-control wholesale_inputs" id="company_name" value="{{!empty($id) ?  $wholesale_application->company : ''}}" placeholder="Enter your Company Name" onchange="remove_error(this)">
                                                                        <div class="text-danger wholesale_inputs" id="company_name_errors"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="">Name <span class="text-danger">*</span></label>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <input type="text" name="first_name" id="first_name" class="form-control wholesale_inputs" value="{{!empty($id) ?  $wholesale_application->first_name : ''}}" onchange="remove_error(this)" placeholder="First name">
                                                                                <div class="text-danger wholesale_inputs" id="first_name_errors"></div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <input type="text" name="last_name" id="last_name" class="form-control wholesale_inputs" onchange="remove_error(this)" placeholder="Last name" value="{{!empty($id) ?  $wholesale_application->last_name : ''}}">
                                                                                <div class="text-danger wholesale_inputs" id="last_name_errors"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="wholesale_form_labels numberError" for="phone">Phone <span class="text-danger">*</span></label>
                                                                                <input type="text" name="phone" value="{{!empty($id) ?  $wholesale_application->phone : ''}}" id="phone" class="allow_numeric form-control wholesale_inputs" onkeyup="isNumber(this)" onchange="remove_error(this)" placeholder="+1 (000) 000-0000">
                                                                                <div class="text-danger wholesale_inputs" id="phone_errors"></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="wholesale_form_labels" for="mobile">Mobile <span class="text-danger">*</span></label>
                                                                                <input type="text" name="mobile" id="mobile" class="allow_numeric form-control wholesale_inputs" value="{{!empty($id) ?  $wholesale_application->mobile : ''}}" onkeyup="isNumber(this)" onchange="remove_error(this)" placeholder="+1 (000) 000-0000">
                                                                                <div class="text-danger wholesale_inputs" id="mobile_errors"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="email">Email <span class="text-danger">*</span></label>
                                                                        <input type="email" class="form-control wholesale_inputs" id="email" onkeyup="isEmail(this)" value="{{!empty($id) ?  $wholesale_application->email : ''}}" name="email" onchange="remove_error(this)" onkeydown="validate_email()" placeholder="Enter your Email">
                                                                        <div class="text-danger wholesale_inputs" id="email_errors"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- second row --}}
                                                            <div class="row">
                                                                <div class="sub-head-step-form mb-2">
                                                                    <h6 class="step_1_sub_heading">
                                                                        Parent Company
                                                                    </h6>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="parent_company">Parent Company <span class="text-danger">*</span></label>
                                                                        <input type="text" name="parent_company" class="form-control wholesale_inputs" value="{{!empty($id) ?  $wholesale_application->parent_company : ''}}" id="parent_company" onchange="remove_error(this)" placeholder="Enter parent company name (if applicable)">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="account_payable_name">Accounts payable Name <span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control wholesale_inputs" id="account_payable_name" name="payable_name" value="{{!empty($id) ?  $wholesale_application->payable_name : ''}}" onchange="remove_error(this)" placeholder="Accounts payable Name">
                                                                        <div class="text-danger wholesale_inputs" id="account_payable_name_errors"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="account_payable_phone">Accounts payable Phone <span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control wholesale_inputs" id="account_payable_phone" onkeyup="isNumber(this)" name="payable_phone" value="{{!empty($id) ?  $wholesale_application->payable_phone : ''}}" onchange="remove_error(this)" placeholder="Accounts payable Phone">
                                                                        <div class="text-danger wholesale_inputs" id="account_payable_phone_errors"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="account_payable_email">Accounts payable Email <span class="text-danger">*</span></label>
                                                                        <input type="email" class="form-control wholesale_inputs" id="account_payable_email" onkeyup="isEmail(this)" value="{{!empty($id) ?  $wholesale_application->payable_email : ''}}" name="payable_email" onchange="remove_error(this)" placeholder="Accounts payable Email">
                                                                        <div class="text-danger wholesale_inputs" id="account_payable_email_errors"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- third row --}}
                                                            <div class="row mb-2">
                                                                <div class="sub-head-step-form mb-2">
                                                                    <h6 class="step_1_sub_heading">
                                                                        Billing Address
                                                                    </h6>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="">Name <span class="text-danger">*</span></label>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <input type="text" name="first_name_billing" value="{{!empty($id) ? $wholesale_application_address_billing->first_name : '' }}" id="first_name_billing" class="form-control wholesale_inputs" onchange="remove_error(this)" placeholder="First name">
                                                                                <div class="text-danger wholesale_inputs" id="first_name_billing_errors"></div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <input type="text" name="last_name_billing" id="last_name_billing" value="{{!empty($id) ? $wholesale_application_address_billing->last_name : '' }}" class="form-control wholesale_inputs" onchange="remove_error(this)" placeholder="Last name">
                                                                                <div class="text-danger wholesale_inputs" id="last_name_billing_errors"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="company_name">Company Name <span class="text-danger">*</span></label>
                                                                        <input type="text" name="company_name_billing" value="{{!empty($id) ? $wholesale_application_address_billing->company_name : '' }}" class="form-control wholesale_inputs" id="company_name_billing" onchange="remove_error(this)" placeholder="Enter your Company Name">
                                                                        <div class="text-danger wholesale_inputs" id="company_name_billing_errors"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="street_address">Street Address <span class="text-danger">*</span></label>
                                                                        <input type="text" name="street_address_billing" value="{{!empty($id) ? $wholesale_application_address_billing->street_address : '' }}" class="form-control wholesale_inputs" id="street_address_billing" onchange="remove_error(this)" placeholder="Enter your street address here">
                                                                        <div class="text-danger wholesale_inputs" id="street_address_billing_errors"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="address_2">Address2 <span class="text-danger">*</span></label>
                                                                        <input type="text" name="address_2_billing" value="{{!empty($id) ? $wholesale_application_address_billing->address_2 : '' }}" class="form-control wholesale_inputs" id="address_2_billing" onchange="remove_error(this)" placeholder="Enter your second address here">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="city">Town/City <span class="text-danger">*</span></label>
                                                                        <input type="text" name="city_billing" value="{{!empty($id) ? $wholesale_application_address_billing->city : '' }}" class="form-control wholesale_inputs" id="city_billing" onchange="remove_error(this)" placeholder="Enter your town/city name here">
                                                                        <div class="text-danger wholesale_inputs" id="city_billing_errors"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="wholesale_form_labels" for="state">State <span class="text-danger">*</span></label>
                                                                                <input type="text" name="state_billing" id="state_billing" value="{{!empty($id) ? $wholesale_application_address_billing->state : '' }}" class="form-control wholesale_inputs" onchange="remove_error(this)" placeholder="Enter your state name">
                                                                                <div class="text-danger wholesale_inputs" id="state_billing_errors"></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="wholesale_form_labels" for="postal_code">Zip/Postal <span class="text-danger">*</span></label>
                                                                                <input type="text" name="postal_code_billing" id="postal_code_billing" value="{{!empty($id) ? $wholesale_application_address_billing->postal_code : '' }}" class="form-control wholesale_inputs" onchange="remove_error(this)" placeholder="Enter zip/postal code here">
                                                                                <div class="text-danger wholesale_inputs" id="postal_code_billing_errors"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="wholesale_form_labels" for="phone">Phone <span class="text-danger">*</span></label>
                                                                                <input type="text" name="phone" id="phone_billing" onkeyup="isNumber(this)" value="{{!empty($id) ? $wholesale_application_address_billing->phone : ''}}" class="form-control wholesale_inputs" onchange="remove_error(this)" placeholder="+1 (000) 000-0000">
                                                                                <div class="text-danger wholesale_inputs" id="phone_billing_errors"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- fourth row --}}
                                                            <div class="row mb-2">
                                                                <div class="sub-head-step-form mb-2">
                                                                    <h6 class="step_1_sub_heading">
                                                                        Delivery Address
                                                                    </h6>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="">Name <span class="text-danger">*</span></label>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <input type="text" name="first_name_delivery" id="first_name_delivery" value="{{!empty($id) ? $wholesale_application_address_delivery->first_name : ''}}" class="form-control wholesale_inputs" onchange="remove_error(this)" placeholder="First name">
                                                                                <div class="text-danger wholesale_inputs" id="first_name_delivery_errors"></div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <input type="text" name="last_name_delivery" id="last_name_delivery" value="{{!empty($id) ? $wholesale_application_address_delivery->last_name : ''}}" class="form-control wholesale_inputs" onchange="remove_error(this)" placeholder="Last name">
                                                                                <div class="text-danger wholesale_inputs" id="last_name_delivery_errors"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="company_name">Company Name <span class="text-danger">*</span></label>
                                                                        <input type="text" name="company_name_delivery" value="{{!empty($id) ? $wholesale_application_address_delivery->company_name : ''}}" class="form-control wholesale_inputs" id="company_name_delivery" onchange="remove_error(this)" placeholder="Enter your Company Name">
                                                                        <div class="text-danger wholesale_inputs" id="company_name_delivery_errors"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="street_address">Street Address <span class="text-danger">*</span></label>
                                                                        <input type="text" name="street_address_delivery" value="{{!empty($id) ? $wholesale_application_address_delivery->street_address : ''}}" class="form-control wholesale_inputs" id="street_address_delivery" onchange="remove_error(this)" placeholder="Enter your street address here">
                                                                        <div class="text-danger wholesale_inputs" id="street_address_delivery_errors"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="address_2">Address2 <span class="text-danger">*</span></label>
                                                                        <input type="text" name="address_2_delivery" value="{{!empty($id) ? $wholesale_application_address_delivery->address_2 : ''}}" class="form-control wholesale_inputs" id="address_2_delivery" onchange="remove_error(this)" placeholder="Enter your second address here">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="city">Town/City <span class="text-danger">*</span></label>
                                                                        <input type="text" name="city_delivery" value="{{!empty($id) ? $wholesale_application_address_delivery->city : ''}}" class="form-control wholesale_inputs" id="city_delivery" onchange="remove_error(this)" placeholder="Enter your town/city name here">
                                                                        <div class="text-danger wholesale_inputs" id="city_delivery_errors"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="wholesale_form_labels" for="state">State <span class="text-danger">*</span></label>
                                                                                <input type="text" name="state_delivery" id="state_delivery" value="{{!empty($id) ? $wholesale_application_address_delivery->state : ''}}" class="form-control wholesale_inputs" onchange="remove_error(this)" placeholder="Enter your state name">
                                                                                <div class="text-danger wholesale_inputs" id="state_delivery_errors"></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="wholesale_form_labels" for="postal_code">Zip/Postal <span class="text-danger">*</span></label>
                                                                                <input type="text" name="postal_code_delivery" id="postal_code_delivery" value="{{!empty($id) ? $wholesale_application_address_delivery->postal_code : ''}}" class="form-control wholesale_inputs" onchange="remove_error(this)" placeholder="Enter zip/postal code here">
                                                                                <div class="text-danger wholesale_inputs" id="postal_code_delivery_errors"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="wholesale_form_labels" for="phone">Phone <span class="text-danger">*</span></label>
                                                                                <input type="text" name="phone_delivery" id="phone_delivery" onkeyup="isNumber(this)" value="{{!empty($id) ? $wholesale_application_address_delivery->phone : ''}}" class="form-control wholesale_inputs" onchange="remove_error(this)" placeholder="+1 (000) 000-0000">
                                                                                <div class="text-danger wholesale_inputs" id="phone_delivery_errors"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 mt-2">
                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <label for="" class="wholesale_form_labels_image">Please attach copy of California Cultivation Permit, CDFTA-230-D</label>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="row justify-content-center files-div">
                                                                                <div class="col-md-6">
                                                                                    <div class="row justify-content-center">
                                                                                        <div class="col-md-12">
                                                                                            <input id="file_upload" name="permit_image[]" style="display:none;" type="file" multiple max="">
                                                                                            <h6 class="drop_your_files_here text-center"> Drop your file here or  
                                                                                                <label for="files" class="browse">Browse</label>
                                                                                                <p class="size_info mb-0">Maximum size: 50MB</p>
                                                                                                <div class="text-danger wholesale_inputs text-center" id="file_upload_errors"></div>
                                                                                            </h6>
                                                                                        </div>
                                                                                        <input type="hidden" name="" id="edit_image_input" value="{{!empty($id) ? $wholesale_application->permit_image : ''}}">
                                                                                        @if(!empty($id))
                                                                                            <a href="{{asset('wholesale/images/' . $wholesale_application->permit_image)}}" id="permit_img_src" class="btn-sm btn btn-primary edit_view_image w-50">View Image</a>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="alert alert-info alert-dismissible fade show success_message mb-0 p-1 d-none" role="alert">
                                                                        <p id="successMessage" class="mb-0"></p>
                                                                        <button type="button" class="close btn-sm p-1" data-dismiss="alert" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                @if(empty($id))
                                                                <button id="save_for_now" type="button"  class="save_for_now_btn btn" data-toggle="" data-target="">Save for now</button>
                                                                @endif
                                                                <button type="button" id="step1_next" onclick="check_validation_step1()" class="step_next btn">
                                                                    Next
                                                                </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Step 2 Content -->
                                            <div class="tab-pane fade " id="step2">
                                                <div class="card">
                                                    <div class="card-body p-4">
                                                        <div class="row p-3">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p class="step_2_top_small_heading mb-2">
                                                                        COTFA-230-D REV.2 (8-17)
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <h5 class="step_2_top_heading">
                                                                        Partial  Exemption Certificate Qualified Sales And Purchases Of Farm Equipment And Machinery
                                                                    </h5>
                                                                </div>
                                                                <div class="col-md-4 text-right">
                                                                    <h5 class="step_2_top_small_heading mb-0">
                                                                        STATE OF CALIFORNIA
                                                                    </h5>
                                                                    <h5 class="step_2_top_subheading">
                                                                        CALIFORNIA DEPARTMENT OF TAX AND FEE ADMINISTRATION
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-3">
                                                                <div class="col-md-12">
                                                                    <div class="regulation_div p-3">
                                                                        <h4 class="tabs_heading">
                                                                            Regulation 1533.1
                                                                        </h4>
                                                                        <p class="regulation-para">
                                                                            <strong>NOTE:</strong> This is an exemption only from the state general fund portion of the sales and use tax rate. You are not relieved from your obligations for the local and district taxes on this transaction. This partial exemption also does not apply to any tax levied pursuant to Sections 6051.2 and 6201.2 of the Revenue and Taxation Code, or pursuant to Section 35 of article XIII of the California Constitution. This partial exemption also applies to lease payments made on or after September 1, 2001, for tangible personal property even if the lease agreement was entered into prior to September 1, 2001.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-3">
                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="seller_name">Seller’s / Lessor’s name<span class="text-danger">*</span> </label>
                                                                            <input type="text" name="seller_name" value="{{!empty($id) ? $wholesale_regulation->seller_name : ''}}" class="form-control wholesale_inputs" id="seller_name" onchange="remove_error(this)" placeholder="Enter seller’s or lessor’s full name">
                                                                            <div class="text-danger wholesale_inputs" id="seller_name_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="seller_address">Seller’s / Lessor’s address <span class="text-danger">*</span> </label>
                                                                            <input type="text" name="seller_address"  value="{{!empty($id) ? $wholesale_regulation->seller_address : ''}}" class="form-control wholesale_inputs" id="seller_address" onchange="remove_error(this)" placeholder="Enter seller’s or lessor’s address (street, city, state, zip code)">
                                                                            <div class="text-danger wholesale_inputs" id="seller_address_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col-md-12 mt-2">
                                                                        <h5 class="tabs_heading">Certificate Eligibility: <span class="text-danger">*</span></h5>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="undertaking_div p-2 d-flex">
                                                                            <div class="col-md-1">
                                                                                <input type="checkbox" name="certificate_eligibility_1"  onchange="remove_error(this)" value="{{!empty($id) ? $wholesale_regulation->certificate_eligibility_1 : ''}}"  {{!empty($wholesale_regulation->certificate_eligibility_1) ? 'checked=checked' : ''}} id="under_signed_checkbox" class="under_signed_checkbox mt-1">
                                                                                
                                                                            </div>
                                                                            <div class="col-md-11 pl-0">
                                                                                
                                                                                <p class="undersigned-para">
                                                                                    I, as the undersigned purchaser, hereby certify I am engaged in an agricultural business described in Codes 0111 to 0291 of the Standard Industrial Classification (SIC) Manual, or I perform an agricultural service described in Codes 0711 to 0783 of the SIC Manual for such classified persons. The property purchased or leased will be used primarily in producing and harvesting agricultural products in accordance with Revenue & Taxation Code Section 6356.5.
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-danger wholesale_inputs" id="under_signed_checkbox_errors"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col-md-12 mb-2">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="type_of_farm">Type of Farm Equipment and Machinery (or parts thereof) <span class="text-danger">*</span></label>
                                                                            <input type="text" name="equipment_type" class="form-control wholesale_inputs" value="{{!empty($id) ? $wholesale_regulation->equipment_type : ''}}" id="type_of_farm" onchange="remove_error(this)" placeholder="type of farm equipment or machinery being bought">
                                                                            <div class="text-danger wholesale_inputs" id="type_of_farm_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 mb-3">
                                                                        <div class="undertaking_div p-2 d-flex">
                                                                            <div class="col-md-1">
                                                                                <input type="checkbox" name="certificate_eligibility_2" onchange="remove_error(this)" value="{{!empty($id) ? $wholesale_regulation->certificate_eligibility_2 : ''}}"  {{!empty($wholesale_regulation->certificate_eligibility_2) ? 'checked=checked' : ''}} id="under_property_checkbox" class="under_signed_checkbox mt-1">
                                                                            
                                                                            </div>
                                                                            <div class="col-md-11 pl-0">
                                                                                <p class="undersigned-para">
                                                                                    I understand that if such property is not used in the manner qualifying for the partial exemption, or if I am not a qualified person, as applicable, that I am required by the sales and use tax law to report and pay the state tax measured by the sales price/rentals payable of the property to/by me. I also understand that this partial exemption certificate is in effect as of the date shown below and will remain in effect until revoked in writing.
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-danger wholesale_inputs" id="under_property_checkbox_errors"></div>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="purchaser_company_name">Purchaser’s name or company name (if applicable) <span class="text-danger">*</span></label>
                                                                            <input type="text" name="purchaser_company_name" value="{{!empty($id) ? $wholesale_regulation->purchaser_company_name : ''}}" class="form-control wholesale_inputs" id="company_name_seller" onchange="remove_error(this)" placeholder="Enter in the Purchaser’s name or company’s name">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="signature">Signature  <span class="text-danger">*</span></label>
                                                                            <input type="text" name="purchaser_signature" class="form-control wholesale_inputs"  value="{{!empty($id) ? $wholesale_regulation->purchaser_signature : ''}}" id="signature" onchange="remove_error(this)" placeholder="(signature of the purchaser, purchaser’s employee, or authorized representative or the the purchaser)">
                                                                            <div class="text-danger wholesale_inputs" id="signature_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="title">Title  <span class="text-danger">*</span></label>
                                                                            <input type="text" name="title" class="form-control wholesale_inputs" id="title" value="{{!empty($id) ? $wholesale_regulation->title : ''}}" onchange="remove_error(this)" placeholder="Title">
                                                                            <div class="text-danger wholesale_inputs" id="title_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="address"> Address <span class="text-danger">*</span></label>
                                                                            <input type="text" name="purchaser_address" class="form-control wholesale_inputs" id="address" value="{{!empty($id) ? $wholesale_regulation->purchaser_address : ''}}" onchange="remove_error(this)" placeholder="(street, city, state, zip code)">
                                                                            <div class="text-danger wholesale_inputs" id="address_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 mb-2">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="permit_number">Permit number</label>
                                                                            <input type="text" name="regulation_permit_number" id="permit_number" class="form-control wholesale_inputs" value="{{!empty($id) ? $wholesale_regulation->regulation_permit_number : ''}}" onchange="remove_error(this)" placeholder="Permit number (if applicable)">
                                                                            <div class="text-danger wholesale_inputs" id="permit_number_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 mb-2">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="phone">Phone <span class="text-danger">*</span></label>
                                                                            <input type="text" name="purchaser_phone" id="phone_number" class="form-control wholesale_inputs" onkeyup="isNumber(this)" value="{{!empty($id) ? $wholesale_regulation->purchaser_phone : ''}}" onchange="remove_error(this)" placeholder="+1 (000) 000-0000">
                                                                            <div class="text-danger wholesale_inputs" id="phone_number_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 mb-2">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="phone">Date <span class="text-danger">*</span></label>
                                                                            <input type="date" name="purchase_date" id="date" value="{{!empty($id) ? $wholesale_regulation->purchase_date : ''}}" onchange="remove_error(this)" class="form-control wholesale_inputs">
                                                                            <div class="text-danger wholesale_inputs" id="date_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="rules_regulation_div p-3">
                                                                            <div class="row">
                                                                                <div class="col-md-1">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="mt-1" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                                        <path d="M14.4866 12L9.15329 2.66665C9.037 2.46146 8.86836 2.29078 8.66457 2.17203C8.46078 2.05329 8.22915 1.99072 7.99329 1.99072C7.75743 1.99072 7.52579 2.05329 7.322 2.17203C7.11822 2.29078 6.94958 2.46146 6.83329 2.66665L1.49995 12C1.38241 12.2036 1.32077 12.4346 1.32129 12.6697C1.32181 12.9047 1.38447 13.1355 1.50292 13.3385C1.62136 13.5416 1.79138 13.7097 1.99575 13.8259C2.20011 13.942 2.43156 14.0021 2.66662 14H13.3333C13.5672 13.9997 13.797 13.938 13.9995 13.8208C14.202 13.7037 14.3701 13.5354 14.487 13.3327C14.6038 13.1301 14.6653 12.9002 14.6653 12.6663C14.6652 12.4324 14.6036 12.2026 14.4866 12Z" stroke="#A16207" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                        <path d="M8 6V8.66667" stroke="#A16207" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                        <path d="M8 11.3333H8.00667" stroke="#A16207" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                    </svg>
                                                                                </div>
                                                                                <div class="col-md-11 pl-0">
                                                                                    <p class="rules_regulation_para">
                                                                                        Vehicles that qualify as farm equipment and machinery, as defined in Regulation 1533.1)(1)(B), must be used exclusively in producing and harvesting agricultural products. A vehicle whose existing design is primarily for the transportation of persons or property on a highway, such as a pickup truck or trailer, does NOT quality for the partial exemption, unless such vehicle is otherwise specified as an implement of husbandry in some provision of the Vehicle Code, as provided in <strong>Regulation 1533.1(b)(1)(B)</strong>
                                                                                    </p>
                                                                                    <p class="rules_regulation_para">
                                                                                        If you are purchasing oil, grease, or lubricating or other qualifying fluids, indicate what percentage will be used in farm equipment and machinery performing qualified producing and harvesting activities. Please note: supply items not used in producing or harvesting agricultural products, such as shop towels, cleaning agents, hand cleansers, and chemicals, do NOT quality for the partial exemption as provided in <strong>Regulation 1533.1(b)(1)(A)</strong>
                                                                                    </p>
                                                                                    <p class="rules_regulation_para mb-0">
                                                                                        If you are not required to hold a seller's permit, please enter <strong>"not applicable".</strong>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-right">
                                                                    {{-- @if(empty($id))
                                                                        <button id="save_for_now" type="button"  class="step_next btn" data-toggle="" data-target="">Save for now</button>
                                                                    @endif --}}
                                                                    <button type="button" id="step2_next" class="step_next btn" onclick="check_validation_step2()">
                                                                        Next
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Step 3 Content -->
                                            <div class="tab-pane fade" id="step3">
                                                <div class="card">
                                                    <div class="card-body p-4">
                                                        <div class="row p-3">
                                                            <div class="col-md-12">
                                                                <div class="step3_heading_div">
                                                                    <h5 class="step_1_main_heading mb-2">
                                                                        ACH Authorization Form
                                                                    </h5>
                                                                    <h6 class="step_3_subheading">
                                                                        Credit/Debit Authorization Form
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <p class="credit_undertaking_para mb-0">
                                                                            I (we) hereby authorize <span class="text-danger">*</span>
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <div class="form-group mb-0">
                                                                            <input type="text" name="authorize_name" class="form-control wholesale_inputs" value="{{!empty($id) ? $wholesale_authorization->authorize_name : ''}}" id="authorization_name" onchange="remove_error(this)" placeholder="Enter text here">
                                                                            <div class="text-danger wholesale_inputs" id="authorization_name_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 mb-3">
                                                                        <p class="credit_undertaking_para">
                                                                            (THE COMPANY) to initiate entries to my (our) checking/savings accounts at the financial institution listed below (THE FINANCIAL INSTITUTION), and, if necessary, initiate adjustments for any transactions credited/debited in error. This authority will remain in effect until THE COMPANY is notified by me (us) in writing to cancel it in such time as to afford THE COMPANY and THE FINANCIAL INSTITUTION a reasonable opportunity to act on it.
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="financial_institution_name">Name of Financial Institution <span class="text-danger">*</span></label>
                                                                            <input type="text" name="financial_institute_name" class="form-control wholesale_inputs"  value="{{!empty($id) ? $wholesale_authorization->financial_institute_name : ''}}" id="financial_institution_name" onchange="remove_error(this)" placeholder="Enter the name of financial Institute">
                                                                            <div class="text-danger wholesale_inputs" id="financial_institution_name_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="financial_institution_address"> Address <span class="text-danger">*</span></label>
                                                                            <input type="text" name="financial_institute_address" value="{{!empty($id) ? $wholesale_authorization->financial_institute_address : ''}}" class="form-control wholesale_inputs" id="financial_institution_address" onchange="remove_error(this)" placeholder="Address of Financial Institute (Branch, city, state, zip code)">
                                                                            <div class="text-danger wholesale_inputs" id="financial_institution_address_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="financial_institution_signature">Signature  <span class="text-danger">*</span></label>
                                                                            <input type="text" name="financial_institute_signature" value="{{!empty($id) ? $wholesale_authorization->financial_institute_signature : ''}}" class="form-control wholesale_inputs" id="financial_institution_signature" onchange="remove_error(this)" placeholder="Signature">
                                                                            <div class="text-danger wholesale_inputs" id="financial_institution_signature_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="set_amount">Set amount</label>
                                                                            <input type="text" name="set_amount" value="{{!empty($id) ? $wholesale_authorization->set_amount : ''}}" class="form-control wholesale_inputs" id="set_amount" onchange="remove_error(this)" placeholder="Set Amount">
                                                                            <div class="text-danger wholesale_inputs" id="set_amount_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="maximum_amount">Maximum amount</label>
                                                                            <input type="text" name="maximum_amount" value="{{!empty($id) ? $wholesale_authorization->maximum_amount : ''}}" class="form-control wholesale_inputs" id="maximum_amount" onchange="remove_error(this)" placeholder="Maximum Amount">
                                                                            <div class="text-danger wholesale_inputs" id="maximum_amount_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="institute_routine_number">Financial institute routine number <span class="text-danger">*</span></label>
                                                                            <input type="text" name="financial_institute_routine_number" value="{{!empty($id) ? $wholesale_authorization->financial_institute_routine_number : ''}}" class="form-control wholesale_inputs" id="institute_routine_number" onchange="remove_error(this)" placeholder="Enter Financial institute routine number">
                                                                            <div class="text-danger wholesale_inputs" id="institute_routine_number_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="saving_account_number">Checking/Saving account number <span class="text-danger">*</span></label>
                                                                            <input type="text" name="financial_institute_account_number" value="{{!empty($id) ? $wholesale_authorization->financial_institute_account_number : ''}}" class="form-control wholesale_inputs" id="saving_account_number" onchange="remove_error(this)" placeholder="Enter Checking/Saving account number">
                                                                            <div class="text-danger wholesale_inputs" id="saving_account_number_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="autorization_permit_number">Permit number</label>
                                                                            <input type="text" name="financial_institute_permit_number" id="autorization_permit_number" value="{{!empty($id) ? $wholesale_authorization->financial_institute_permit_number : ''}}" class="form-control wholesale_inputs" onchange="remove_error(this)" placeholder="Permit number (if applicable)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="autorization_phone_number">Phone <span class="text-danger">*</span></label>
                                                                            <input type="text" name="financial_institute_phone_number" id="autorization_phone_number" onkeyup="isNumber(this)" value="{{!empty($id) ? $wholesale_authorization->financial_institute_phone_number : ''}}" class="form-control wholesale_inputs" onchange="remove_error(this)" placeholder="+1 (000) 000-0000">
                                                                            <div class="text-danger wholesale_inputs" id="autorization_phone_number_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <div class="row">
                                                                                <label class="wholesale_form_labels" for="numebers">These numbers are located on the bottom of your check as follows</label>
                                                                                <div class="col-md-6">
                                                                                    <input type="text" id="routing_number" class="form-control wholesale_number_inputs" placeholder="1234567890123" readonly>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <input type="text" id="account_number" class="form-control wholesale_number_inputs" placeholder="123456789" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-right">
                                                                    {{-- @if(empty($id))
                                                                        <button id="save_for_now" type="button"  class="step_next btn" data-toggle="" data-target="">Save for now</button>
                                                                    @endif --}}
                                                                    <button type="button" id="step3_next" class="step_next btn" onclick="check_validation_step3()">
                                                                        Next
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- step 4 goes here --}}
                                            <div class="tab-pane fade" id="step4">
                                                <div class="card">
                                                    <div class="card-body p-4">
                                                        <div class="row p-3">
                                                            <div class="col-md-12 mb-3">
                                                                <div class="step3_heading_div mb-4">
                                                                    <h5 class="step_1_main_heading">
                                                                        Credit Card Authorization Form
                                                                    </h5>
                                                                </div>
                                                                <div class="important_notice mt-2  mb-3">
                                                                    <div class="row p-3">
                                                                        <div class="col-md-1">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" class="mt-1" height="16" viewBox="0 0 16 16" fill="none">
                                                                                <g clip-path="url(#clip0_107_29812)">
                                                                                <path d="M8.00016 14.6666C11.6821 14.6666 14.6668 11.6819 14.6668 7.99998C14.6668 4.31808 11.6821 1.33331 8.00016 1.33331C4.31826 1.33331 1.3335 4.31808 1.3335 7.99998C1.3335 11.6819 4.31826 14.6666 8.00016 14.6666Z" stroke="#242424" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                <path d="M8 10.6667V8" stroke="#242424" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                <path d="M8 5.33331H8.00667" stroke="#242424" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                </g>
                                                                                <defs>
                                                                                <clipPath id="clip0_107_29812">
                                                                                    <rect width="16" height="16" fill="white"/>
                                                                                </clipPath>
                                                                                </defs>
                                                                            </svg>
                                                                        </div>
                                                                        <div class="col-md-11 pl-0">
                                                                            <p class="notice_para mb-0">
                                                                                Please complete all the fields. You may cancel this authorization at any time by contacting us. This authorization will remain in effect until cancelled.
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="sub-heading-div">
                                                                    <h5 class="step_1_sub_heading">
                                                                        Credit Card Information
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="wholesale_form_labels mb-0" for="card_type">Card Type <span class="text-danger">*</span></label>
                                                                    <div class="row justify-content-between pl-3 pt-2 pr-3">
                                                                        <div id="card_type_master" class="col-md-2 d-flex border justify-content-center align-items-center m-0 wholesale-custom-radio  {{!empty($id) && $wholesale_application_card->card_type == 'master_card' ? 'selected'  : ''}}" onclick="selectRadio(this)">
                                                                            <input type="radio" name="card_type" class="radio_check_wholesale hidden-radio border-0" onchange="remove_error_card_type(this)" value="{{!empty($id) && $wholesale_application_card->card_type == 'master_card' ? 'master_card'  : ''}}"  {{!empty($id) && $wholesale_application_card->card_type == 'master_card' ? 'checked'  : ''}}  id="master_card">
                                                                            <span class="master-radio-label"></span>
                                                                        </div>
                                                                        <div id="card_type_visa" class="col-md-2 d-flex border justify-content-center align-items-center m-0 wholesale-custom-radio {{!empty($id) && $wholesale_application_card->card_type == 'visa_card' ? 'selected'  : ''}}" onclick="selectRadio(this)">
                                                                            <input type="radio" name="card_type" class="radio_check_wholesale hidden-radio border-0" onchange="remove_error_card_type(this)" value="{{!empty($id) && $wholesale_application_card->card_type == 'visa_card' ? 'visa_card'  : ''}}"  {{!empty($id) && $wholesale_application_card->card_type == 'visa_card' ? 'checked'  : ''}} id="visa_card">
                                                                            <span class="visa-radio-label"></span>
                                                                        </div>
                                                                        <div id="card_type_discover" class="col-md-2 d-flex border justify-content-center align-items-center m-0 wholesale-custom-radio {{!empty($id) && $wholesale_application_card->card_type == 'discover_card' ? 'selected'  : ''}}" onclick="selectRadio(this)">
                                                                            <input type="radio" name="card_type" class="radio_check_wholesale hidden-radio border-0" onchange="remove_error_card_type(this)" value="{{!empty($id) && $wholesale_application_card->card_type == 'discover_card' ? 'discover_card'  : ''}}"  {{!empty($id) && $wholesale_application_card->card_type == 'discover_card' ? 'checked'  : ''}} id="discover_card">
                                                                            <span class="discover-radio-label"></span>
                                                                        </div>
                                                                        <div id="card_type_american" class="col-md-2 d-flex border justify-content-center align-items-center m-0 wholesale-custom-radio {{!empty($id) && $wholesale_application_card->card_type == 'american_express_card' ? 'selected'  : ''}}" onclick="selectRadio(this)">
                                                                            <input type="radio" name="card_type" class="radio_check_wholesale hidden-radio border-0" onchange="remove_error_card_type(this)" value="{{!empty($id) && $wholesale_application_card->card_type == 'american_express_card' ? 'american_express_card'  : ''}}"  {{!empty($id) && $wholesale_application_card->card_type == 'american_express_card' ? 'checked'  : ''}} id="american_express_card">
                                                                            <span class="american-radio-label"></span>
                                                                        </div>
                                                                        <div id="card_type_other" class="col-md-2 d-flex border justify-content-center align-items-center m-0 wholesale-custom-radio {{!empty($id) && $wholesale_application_card->card_type == 'other_card' ? 'selected'  : ''}}"  onclick="selectRadio(this)">
                                                                            <input type="radio" name="card_type" class="radio_check_wholesale hidden-radio border-0" onchange="remove_error_card_type(this)" value="{{!empty($id) && $wholesale_application_card->card_type == 'other_card' ? 'other_card'  : ''}}"  {{!empty($id) && $wholesale_application_card->card_type == 'other_card' ? 'checked'  : ''}} id="other_card">
                                                                            <span class="other-radio-label">OTHER</span>
                                                                        </div>
                                                                        <div class="text-danger wholesale_inputs" id="card_type_errors"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="wholesale_form_labels" for="cardholder_name">Cardholder name (as shown on card) <span class="text-danger">*</span></label>
                                                                    <input type="text" name="cardholder_name"  value="{{!empty($id) ? $wholesale_application_card->cardholder_name : ''}}" class="form-control wholesale_inputs" id="cardholder_name" onchange="remove_error(this)" placeholder="Enter card holder name">
                                                                    <div class="text-danger wholesale_inputs" id="cardholder_name_errors"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="wholesale_form_labels" for="card_number">Card Number <span class="text-danger">*</span></label>
                                                                    <input type="text" name="card_number" class="form-control wholesale_inputs ccFormatMonitor"  value="{{!empty($id) ? $wholesale_application_card->card_number : ''}}" id="card_number" onchange="remove_error(this)" placeholder="Enter card number">
                                                                    <div class="text-danger wholesale_inputs" id="card_number_errors"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="expiration_date">Expiration Date <span class="text-danger">*</span></label>
                                                                            <input type="text" name="expiration_date" class="form-control wholesale_inputs inputExpDate" id="expiration_date"  value="{{!empty($id) ? $wholesale_application_card->expiration_date : ''}}" maxlength='7' onchange="remove_error(this)" placeholder="MM / YY">
                                                                            <div class="text-danger wholesale_inputs" id="expiration_date_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="card_holder_zip_code">Cardholder ZIP Code <span class="text-danger">*</span></label>
                                                                            <input type="text" name="cardholder_zip_code" class="form-control wholesale_inputs" id="card_holder_zip_code" value="{{!empty($id) ? $wholesale_application_card->cardholder_zip_code : ''}}" onchange="remove_error(this)" placeholder="Zip code from credit card billing address">
                                                                            <div class="text-danger wholesale_inputs" id="cardholder_zip_code_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mt-3">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group mb-0 d-flex align-items-center">
                                                                            <label class="mb-0 text-center align-middle credit_card_undertaking">
                                                                                I<span class="text-danger">*</span>,
                                                                            </label>
                                                                            <input type="text" name="authorize_card_name" class="form-control wholesale_inputs w-50 ml-2" value="{{!empty($id) ? $wholesale_application_card->authorize_card_name : ''}}" id="undertaking_name" onchange="remove_error(this)" placeholder="Enter text here">
                                                                            <label class="mb-0 ml-2 text-center align-middle w-25 credit_card_undertaking">
                                                                                , authorize
                                                                             <span class="text-danger">*</span></label>
                                                                            <input type="text" name="authorize_card_text" class="form-control wholesale_inputs w-50" value="{{!empty($id) ? $wholesale_application_card->authorize_card_text : ''}}" id="authorize_text" onchange="remove_error(this)" placeholder="Enter text here">
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="text-danger wholesale_inputs" id="undertaking_name_errors"></div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="text-danger wholesale_inputs" id="authorize_text_errors"></div>
                                                                            </div>
                                                                        </div>
                                                                        <p class="credit_card_undertaking">
                                                                            to charge my credit card above for agreed upon purchases, I understand that my information will be saved to file for future transactions on my account.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="row mb-2">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="customer_signature">Customer Signature <span class="text-danger">*</span></label>
                                                                            <input type="text" name="customer_signature" value="{{!empty($id) ? $wholesale_application_card->customer_signature : ''}}" class="form-control wholesale_inputs" id="customer_signature" onchange="remove_error(this)" placeholder="Enter Signature">
                                                                            <div class="text-danger wholesale_inputs" id="customer_signature_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="date_wholesale"> Date <span class="text-danger">*</span></label>
                                                                            <input type="date" name="date" value="{{!empty($id) ? $wholesale_application_card->date : ''}}" class="form-control wholesale_inputs" onchange="remove_error(this)" id="date_wholesale">
                                                                            <div class="text-danger wholesale_inputs" id="date_wholesale_errors"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="spinner-grow d-none" id="wholesale_spinner" role="status">
                                                                <span class="sr-only">Loading...</span>
                                                            </div>
                                                            <div class="col-md-12 text-right">
                                                                <button type="button" id="step4_next" class="step_next btn" onclick="check_validation_step4()">
                                                                    Next
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="save_for_now_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Please Enter Your Email Address</h5>
                    <button type="button" class="close close_modal" data-dismiss="modal" aria-label="Close" onclick="close_modal()">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="email_address">Email Address</label>
                            <input type="email" name="email_address" id="email_address" class="form-control" onkeyup="isEmail(this)" placeholder="Enter your email address" required="required" onchange="remove_error(this)">
                            <div class="text-danger wholesale_inputs" id="email_address_errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close_modal" data-dismiss="modal" onclick="close_modal()">Close</button>
                        <button type="button" class="btn btn-primary" id="saveEmail_fornow" onclick="save_email_for_now()">Save changes</button>
                    </div>
                </div>
              </div>
        </div>
        <div class="modal fade" id="previous_data_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Please Enter Your Email Address</h5>
                    <button type="button" class="close close_previous_data_modal" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form action="{{route('wholesale_user_check_email')}}" method="post">
                  <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="email_address">Email Address</label>
                            <input type="email" name="email_address_previous" id="email_address_previous" class="form-control" placeholder="Enter your email address" required="required" onchange="remove_error(this)">
                            <div class="text-danger wholesale_inputs" id="email_address_previous_errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close_previous_data_modal" data-dismiss="modal" >Close</button>
                        {{-- <button type="button" class="btn btn-primary" id="show_previous_data_by_email" >Save changes</button> --}}
                        <button type="submit" class="btn btn-primary" id="" >Save changes</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </main>
    <style>
        .wholesale_account_tabs {
           color: #828282 !important;
            font-family: 'Poppins';
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            line-height: 16.902px;
            text-decoration: none !important;
        }
        #wholesale_spinner {
            width: 5rem;
            height: 5rem;
            position: absolute;
            top: 50%;
            left: 40%;
        
        }
        
        .nav  .active {
            background-color: none !important;
            color: #7CC633 !important;
            font-family: 'Poppins';
            font-size: 16px;
            font-style: normal;
            font-weight: 600;
            line-height: 16.902px;
            text-decoration: none !important;
        }
        .tabs_heading {
            color: #242424;
            font-family: 'Poppins';
            font-size: 18px;
            font-style: normal;
            font-weight: 600;
            line-height: 16.902px;
        }
        .step_1_main_heading {
            color: #242424;
            font-family: 'Poppins';
            font-size: 22px;
            font-style: normal;
            font-weight: 600;
            line-height: normal;
        }
        .step_1_sub_heading {
            color: #242424;
            font-family: 'Poppins';
            font-size: 20px;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
        }
        .wholesale_form_labels {
            color: #242424;
            font-family: 'Poppins';
            font-size: 14px !important;
            font-style: normal;
            font-weight: 400;
            line-height: 20.307px; /* 145.048% */
        }
        .wholesale_form_labels_image {
            color: #242424;
            font-family: 'Poppins';
            font-size: 14px !important;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
        }
        .wholesale_inputs {
            color: #828282;
            font-family: 'Poppins';
            font-size: 12px;
            font-style: normal;
            font-weight: 400;
            line-height: 20.307px; /* 169.222% */
        }
        .drop_your_files_here {
            color: #1F2937;
            font-family: 'Poppins';
            font-size: 16px;
            font-style: normal;
            font-weight: 500;
            line-height: 20.283px; /* 126.766% */
            letter-spacing: 0.08px;
        }
        .browse {
            color: #7CC633;
            text-align: center;
            font-family: 'Poppins';
            font-size: 16px;
            font-style: normal;
            font-weight: 600;
            line-height: 20.283px; /* 126.766% */
            letter-spacing: 0.08px;
            cursor: pointer;
        }
        .files-div {
            border: 1px dashed #E1E1E1;
            padding: 40px;
        }
        .size_info {
            color: #AFAFAF;
            text-align: center;
            font-family: 'Poppins';
            font-size: 14px;
            font-style: normal;
            font-weight: 400;
            line-height: 20.283px; /* 144.875% */
            letter-spacing: 0.07px;
        }
        .step_next {
            border-radius: 4px;
            border: 0.846px solid #7CC633;
            background: #7CC633;
            box-shadow: 0px 0.84611px 1.69222px 0px rgba(16, 24, 40, 0.05);
            color: #FFF;
            font-family: 'Poppins';
            font-size: 15.212px;
            font-style: normal;
            font-weight: 500;
            line-height: 20.307px; /* 133.492% */
        }

        .step_next:hover {
            border-radius: 4px;
            border: 0.846px solid #7CC633;
            background: #7CC633;
            box-shadow: 0px 0.84611px 1.69222px 0px rgba(16, 24, 40, 0.05);
            color: #FFF;
            font-family: 'Poppins';
            font-size: 15.212px;
            font-style: normal;
            font-weight: 500;
            line-height: 20.307px; /* 133.492% */
        }
        .save_for_now_btn {
            border-radius: 4px;
            border: 0.846px solid #7CC633;
            background: #7CC633;
            box-shadow: 0px 0.84611px 1.69222px 0px rgba(16, 24, 40, 0.05);
            color: #FFF;
            font-family: 'Poppins';
            font-size: 15.212px;
            font-style: normal;
            font-weight: 500;
            line-height: 20.307px; /* 133.492% */
        }

        .save_for_now_btn:hover {
            border-radius: 4px;
            border: 0.846px solid #7CC633;
            background: #7CC633;
            box-shadow: 0px 0.84611px 1.69222px 0px rgba(16, 24, 40, 0.05);
            color: #FFF;
            font-family: 'Poppins';
            font-size: 15.212px;
            font-style: normal;
            font-weight: 500;
            line-height: 20.307px; /* 133.492% */
        }
        .step_2_top_small_heading {
            color: #242424;
            font-family: 'Poppins';
            font-size: 10.169px;
            font-style: normal;
            font-weight: 500;
            line-height: 109.5%; /* 11.135px */
        }
        .step_2_top_heading {
            color: #242424;
            font-family: 'Poppins';
            font-size: 16px;
            font-style: normal;
            font-weight: 600;
            line-height: 130%; /* 20.8px */
            text-transform: uppercase;
        }
        .step_2_top_subheading {
            color: #242424;
            font-family: 'Poppins';
            font-size: 12.741px;
            font-style: normal;
            font-weight: 500;
            line-height: 109.5%;
        }
        .regulation_div {
            border-radius: 7px;
            border: 1px solid #E1E1E1;
            background: #FFF;
            box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.05);
        }
        .under_signed_checkbox {
            border: 1px solid #E1E1E1;
            background: #FFF;
        }
        .undertaking_div {
            border-radius: 7px;
            border: 1px solid #E1E1E1;
            background: #F8F8F8;
            box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.05);
        }
        .rules_regulation_div {
            border-radius: 7px;
            border: 1px solid #FEF08A;
            background: #FEF9C3;
        }
        .regulation-para {
            color: #242424;
            font-family: 'Poppins';
            font-size: 14px;
            font-style: normal;
            font-weight: 400;
            line-height: 24px;
            letter-spacing: 0.07px;
        }
        .undersigned-para {
            color: #242424;
            font-family: 'Poppins';
            font-size: 14px;
            font-style: normal;
            font-weight: 400;
            line-height: 24px;
            letter-spacing: 0.07px;
        }
        .regulation-para > strong {
            color: #242424;
            font-family: 'Poppins';
            font-size: 14px;
            font-style: normal;
            font-weight: 600;
            line-height: 24px;
            letter-spacing: 0.07px;
        }
        .rules_regulation_para {
            color:  #A16207;
            font-family: 'Poppins';
            font-size: 14px;
            font-style: normal;
            font-weight: 400;
            line-height: 24px; /* 171.429% */
            letter-spacing: 0.07px;
        }
        .rules_regulation_para > strong {
            color:  #A16207;
            font-family: 'Poppins';
            font-size: 14px;
            font-style: normal;
            font-weight: 600;
            line-height: 24px;
            letter-spacing: 0.07px;
        }
        .step_3_subheading {
            color: #242424;
            font-family: 'Poppins';
            font-size: 18px;
            font-style: normal;
            font-weight: 600;
            line-height: normal;
            letter-spacing: 0.09px;
        }
        .credit_undertaking_para {
            color: #242424;
            font-family: 'Poppins';
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            line-height: 41px; /* 256.25% */
            letter-spacing: 0.08px;
        }
        .wholesale_number_inputs {
            border-radius: 4px;
            background-color: #F0F0F0;
            color: #242424;
            font-family: 'Poppins';
            font-size: 14px;
            font-style: normal;
            font-weight: 500;
            line-height: 20.307px; /* 145.048% */
            letter-spacing: 1.47px;
        }
        .important_notice {
            border-radius: 7px;
            border: 1px solid #E1E1E1;
            background: #FBFBFB;
        }
        .notice_para {
            color: #242424;
            font-family: 'Poppins';
            font-size: 14px;
            font-style: normal;
            font-weight: 400;
            line-height: 24px; /* 171.429% */
            letter-spacing: 0.07px;
        }
        #cardholder_name {
            background-image: url("{{ asset('/theme/img/wholesale-card-icons/mastercard.svg') }}");
            background-repeat: no-repeat;
            background-position: right;
        }
        .master-radio-label {
            background-image: url("{{ asset('/theme/img/wholesale-card-icons/mastercard.svg') }}");
            background-repeat: no-repeat;
            background-position: center;
            width: 56px;
            height: 38px;
        }
        .visa-radio-label {
            background-image: url("{{ asset('/theme/img/wholesale-card-icons/visa.svg') }}");
            background-repeat: no-repeat;
            background-position: center;
            width: 56px;
            height: 38px;
        }
        .discover-radio-label {
            background-image: url("{{ asset('/theme/img/wholesale-card-icons/discover.svg') }}");
            background-repeat: no-repeat;
            background-position: center;
            width: 56px;
            height: 38px;
        }
        .american-radio-label {
            background-image: url("{{ asset('/theme/img/wholesale-card-icons/american_express.svg') }}");
            background-repeat: no-repeat;
            background-position: center;
            width: 56px;
            height: 38px;
        }
        .credit_card_undertaking {
            color: #242424;
            font-family: 'Poppins';
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            line-height: 41px; /* 256.25% */
            letter-spacing: 0.08px;
        }
        .wholesale-custom-radio {
            cursor: pointer;
        }

        .wholesale-custom-radio:hover {
            background-color: #ffffff;
        }

        .hidden-radio {
            display: none;
        }

        .radio-label {
            padding-left: 10px;
        }

        .wholesale-custom-radio.selected {
            outline: 2px solid #7CC633;
        }
        .navigation-div {
            border:1px solid #F1F1F1;
        }
        .nav_wholesale {
            border-left: 1.69px solid #F3F3F3;
        }

    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://www.jquery-az.com/jquery/js/intlTelInput/intlTelInput.js"></script>
    @include('partials.product-footer')
    @include('partials.footer')
    <script>
        $('.browse').click(function(){ $('#file_upload').trigger('click'); });
        var app;
        (function() {
            'use strict';
  
            app = {
                monthAndSlashRegex: /^\d\d \/ $/, // regex to match "MM / "
                monthRegex: /^\d\d$/, // regex to match "MM"
                
                el_cardNumber: '.ccFormatMonitor',
                el_expDate: '.inputExpDate',
                el_cvv: '.cvv',
                el_ccUnknown: 'cc_type_unknown',
                el_ccTypePrefix: 'cc_type_',
                el_monthSelect: '#monthSelect',
                el_yearSelect: '#yearSelect',
                
                cardTypes: {
                'American Express': {
                    name: 'American Express',
                    code: 'ax',
                    security: 4,
                    pattern: /^3[47]/,
                    valid_length: [15],
                    formats: {
                    length: 15,
                    format: 'xxxx xxxxxxx xxxx'
                    }
                },
                'Visa': {
                            name: 'Visa',
                            code: 'vs',
                    security: 3,
                            pattern: /^4/,
                            valid_length: [16],
                            formats: {
                                    length: 16,
                                    format: 'xxxx xxxx xxxx xxxx'
                                }
                        },
                'Maestro': {
                            name: 'Maestro',
                            code: 'ma',
                    security: 3,
                            pattern: /^(50(18|20|38)|5612|5893|63(04|90)|67(59|6[1-3])|0604)/,
                            valid_length: [16],
                            formats: {
                                    length: 16,
                                    format: 'xxxx xxxx xxxx xxxx'
                                }
                        },
                'Mastercard': {
                            name: 'Mastercard',
                            code: 'mc',
                    security: 3,
                            pattern: /^5[1-5]/,
                            valid_length: [16],
                            formats: {
                                    length: 16,
                                    format: 'xxxx xxxx xxxx xxxx'
                                }
                        } 
                }
            };
            
            app.addListeners = function() {
                $(app.el_expDate).on('keydown', function(e) {
                    app.removeSlash(e);
                });

                $(app.el_expDate).on('keyup', function(e) {
                    app.addSlash(e);
                });

                $(app.el_expDate).on('blur', function(e) {
                    app.populateDate(e);
                });

                $(app.el_cvv +', '+ app.el_expDate).on('keypress', function(e) {
                    return e.charCode >= 48 && e.charCode <= 57;
                });  
            };
            
            app.addSlash = function (e) {
                var isMonthEntered = app.monthRegex.exec(e.target.value);
                if (e.key >= 0 && e.key <= 9 && isMonthEntered) {
                e.target.value = e.target.value + " / ";
                }
            };
            
            app.removeSlash = function(e) {
                var isMonthAndSlashEntered = app.monthAndSlashRegex.exec(e.target.value);
                if (isMonthAndSlashEntered && e.key === 'Backspace') {
                e.target.value = e.target.value.slice(0, -3);
                }
            };
            
            app.populateDate = function(e) {
                var month, year;
                
                if (e.target.value.length == 7) {
                month = parseInt(e.target.value.slice(0, -5));
                year = "20" + e.target.value.slice(5);
                
                if (app.checkMonth(month)) {
                    $(app.el_monthSelect).val(month);
                } else {
                    $(app.el_monthSelect).val(0);
                }
                
                if (app.checkYear(year)) {
                    $(app.el_yearSelect).val(year);
                } else {
                    $(app.el_yearSelect).val(0);
                }
                
                }
            };
            
            app.checkMonth = function(month) {
                if (month <= 12) {
                var monthSelectOptions = app.getSelectOptions($(app.el_monthSelect));
                month = month.toString();
                if (monthSelectOptions.includes(month)) {
                    return true; 
                }
                }
            };
            
            app.checkYear = function(year) {
                var yearSelectOptions = app.getSelectOptions($(app.el_yearSelect));
                if (yearSelectOptions.includes(year)) {
                return true; 
                }
            };
                    
            app.getSelectOptions = function(select) {
                var options = select.find('option');
                var optionValues = [];
                for (var i = 0; i < options.length; i++) {
                optionValues[i] = options[i].value;
                }
                return optionValues;
            };
            
            app.setMaxLength = function ($elem, length) {
                if($elem.length && app.isInteger(length)) {
                $elem.attr('maxlength', length);
                }else if($elem.length){
                $elem.attr('maxlength', '');
                }
            };
            
            app.isInteger = function(x) {
                return (typeof x === 'number') && (x % 1 === 0);
            };

            app.createExpDateField = function() {
                $(app.el_monthSelect +', '+ app.el_yearSelect).hide();
                $(app.el_monthSelect).parent().prepend('<input type="text" class="ccFormatMonitor">');
            };
            
            
            app.isValidLength = function(cc_num, card_type) {
                for(var i in card_type.valid_length) {
                if (cc_num.length <= card_type.valid_length[i]) {
                    return true;
                }
                }
                return false;
            };

            app.getCardType = function(cc_num) {
                for(var i in app.cardTypes) {
                var card_type = app.cardTypes[i];
                if (cc_num.match(card_type.pattern) && app.isValidLength(cc_num, card_type)) {
                    return card_type;
                }
                }
            };

            app.getCardFormatString = function(cc_num, card_type) {
                for(var i in card_type.formats) {
                var format = card_type.formats[i];
                if (cc_num.length <= format.length) {
                    return format;
                }
                }
            };

            app.formatCardNumber = function(cc_num, card_type) {
                var numAppendedChars = 0;
                var formattedNumber = '';
                var cardFormatIndex = '';

                if (!card_type) {
                return cc_num;
                }

                var cardFormatString = app.getCardFormatString(cc_num, card_type);
                for(var i = 0; i < cc_num.length; i++) {
                cardFormatIndex = i + numAppendedChars;
                if (!cardFormatString || cardFormatIndex >= cardFormatString.length) {
                    return cc_num;
                }

                if (cardFormatString.charAt(cardFormatIndex) !== 'x') {
                    numAppendedChars++;
                    formattedNumber += cardFormatString.charAt(cardFormatIndex) + cc_num.charAt(i);
                } else {
                    formattedNumber += cc_num.charAt(i);
                }
                }

                return formattedNumber;
            };

            app.monitorCcFormat = function($elem) {
                var cc_num = $elem.val().replace(/\D/g,'');
                var card_type = app.getCardType(cc_num);
                $elem.val(app.formatCardNumber(cc_num, card_type));
                app.addCardClassIdentifier($elem, card_type);
            };

            app.addCardClassIdentifier = function($elem, card_type) {
                var classIdentifier = app.el_ccUnknown;
                if (card_type) {
                classIdentifier = app.el_ccTypePrefix + card_type.code;
                app.setMaxLength($(app.el_cvv), card_type.security);
                } else {
                app.setMaxLength($(app.el_cvv));
                }

                if (!$elem.hasClass(classIdentifier)) {
                var classes = '';
                for(var i in app.cardTypes) {
                    classes += app.el_ccTypePrefix + app.cardTypes[i].code + ' ';
                }
                $elem.removeClass(classes + app.el_ccUnknown);
                $elem.addClass(classIdentifier);
                }
            };

            
            app.init = function() {

                $(document).find(app.el_cardNumber).each(function() {
                var $elem = $(this);
                if ($elem.is('input')) {
                    $elem.on('input', function() {
                    app.monitorCcFormat($elem);
                    });
                }
                });
                
                app.addListeners();
                
            }();
    
        })();
    </script>

    <script>
        
        // save for now functionality
        $(document).ready(function() {
            $('.step_next').click(function() {
                $('html, body').animate({ scrollTop: 0 }, 800);
                return false; // Prevent the default anchor behavior
            });
            // $(document).on('keypress' , '#email_address' ,function(){
            //     if(event.keyCode == 13) {
            //         save_email_for_now(); 
            //     } else {
            //         return false;
            //     }
            // });
            // function save_progress_step1() {
            //     var validation = validation_message_step_1();
            //     if (validation == true) {
            //         $('#wholesale_spinner').removeClass('d-none');
            //         $.ajaxSetup({
            //             headers: {
            //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //             }
            //         });
            //         var data = new FormData();
            //         var files = $('input[name="permit_image"]')[0].files[0];
            //         data.append('permit_image',files);
            //         data.append('company_name', $('#company_name').val());
            //         data.append('first_name', $('#first_name').val());
            //         data.append('last_name', $('#last_name').val());
            //         data.append('phone', $('#phone').val());
            //         data.append('mobile', $('#mobile').val());
            //         data.append('email',$('#email').val());
            //         data.append('parent_company', $('#parent_company').val());
            //         data.append('account_payable_name', $('#account_payable_name').val());
            //         data.append('account_payable_phone', $('#account_payable_phone').val());
            //         data.append('account_payable_email', $('#account_payable_email').val());
            //         data.append('first_name_billing', $('#first_name_billing').val());
            //         data.append('last_name_billing', $('#last_name_billing').val());
            //         data.append('company_name_billing', $('#company_name_billing').val());
            //         data.append('street_address_billing', $('#street_address_billing').val());
            //         data.append('address2_billing', $('#address_2_billing').val());
            //         data.append('city_billing', $('#city_billing').val());
            //         data.append('state_billing', $('#state_billing').val());
            //         data.append('postal_code_billing', $('#postal_code_billing').val());
            //         data.append('phone_billing', $('#phone_billing').val());
            //         data.append('first_name_delivery', $('#first_name_delivery').val());
            //         data.append('last_name_delivery', $('#last_name_delivery').val());
            //         data.append('company_name_delivery', $('#company_name_delivery').val());
            //         data.append('street_address_delivery', $('#street_address_delivery').val());
            //         data.append('address2_delivery', $('#address_2_delivery').val());
            //         data.append('city_delivery', $('#city_delivery').val());
            //         data.append('state_delivery', $('#state_delivery').val());
            //         data.append('postal_code_delivery', $('#postal_code_delivery').val());
            //         data.append('phone_delivery', $('#phone_delivery').val());
            //         data.append('authorization_name', $('#authorization_name').val());
            //         data.append('financial_institution_name', $('#financial_institution_name').val());
            //         data.append('financial_institution_address', $('#financial_institution_address').val());
            //         data.append('financial_institution_signature', $('#financial_institution_signature').val());
            //         data.append('set_amount', $('#set_amount').val());
            //         data.append('maximum_amount', $('#maximum_amount').val());
            //         data.append('institute_routine_number', $('#institute_routine_number').val());
            //         data.append('saving_account_number', $('#saving_account_number').val());
            //         data.append('autorization_permit_number', $('#autorization_permit_number').val());
            //         data.append('autorization_phone_number', $('#autorization_phone_number').val());
            //         data.append('seller_name', $('#seller_name').val());
            //         data.append('seller_address', $('#seller_address').val());
            //         data.append('under_signed_checkbox', $('#under_signed_checkbox').val());
            //         data.append('under_property_checkbox', $('#under_property_checkbox').val());
            //         data.append('company_name_seller', $('#company_name_seller').val());
            //         data.append('signature', $('#signature').val());
            //         data.append('title', $('#title').val());
            //         data.append('address', $('#address').val());
            //         data.append('permit_number', $('#permit_number').val());
            //         data.append('phone_number', $('#phone_number').val());
            //         data.append('date', $('#date').val());
            //         data.append('type_of_farm', $('#type_of_farm').val());
                
            //         $.ajax({
            //             type:'POST',
            //             url:"{{ route('save_for_now') }}",
            //             data:data,
            //             contentType: false,
            //             processData: false,
            //             success:function(response){
            //                 if (response.status == true) {
            //                     $('#wholesale_spinner').addClass('d-none');
            //                     $('#save_for_now').attr('data-toggle', '')
            //                     $('#save_for_now').attr('data-target', '')
            //                     $('.success_message').removeClass('d-none');
            //                     $('#successMessage').html('Data saved For now');
            //                     setTimeout(() => {
            //                         window.location.href = '/wholesale/account/create'
            //                     }, 1000);
            //                 } else {
            //                     $('.success_message').removeClass('d-none');
            //                     $('#successMessage').html('Email Already Exist');
            //                     setTimeout(() => {
            //                         window.location.href = "/wholesale/account/edit/" + response.id;
            //                     }, 1000);
            //                 }
            //             },
            //             error: function (response) {
            //             }
            //         });
            //     } else {
            //         $('.success_message').removeClass('d-none');
            //         $('#successMessage').html('Please Enter the Corrrect Data');
            //     }
            // }
            // save email for now 
            
            // $(document).on('click', '#save_for_now' ,function() {
            //     if ($('#email').val() == '') {
            //         $('#save_for_now').attr('data-toggle', 'modal')
            //         $('#save_for_now').attr('data-target', '#save_for_now_modal')
            //         $('#save_for_now_modal').modal('show');
            //     } else {
            //         $('#save_for_now').attr('data-toggle', '')
            //         $('#save_for_now').attr('data-target', '')
            //         $('#save_for_now_modal').modal('hide');

            //         save_progress_step1();
            //     }
            // });
        });

        function save_progress_step1() {
            var validation = validation_message_step_1();
            if (validation == true) {
                $('#wholesale_spinner').removeClass('d-none');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var data = new FormData();
                var totalfiles = document.getElementById('file_upload').files.length;
                if (totalfiles > 0) {
                    for (var index = 0; index < totalfiles; index++) {
                        data.append("permit_image[]", document.getElementById('file_upload').files[index]);
                    }
                }
                // var files = $('input[name="permit_image"]')[0].files;
                // console.log(files);
                // data.append('permit_image',files);
                data.append('company_name', $('#company_name').val());
                data.append('first_name', $('#first_name').val());
                data.append('last_name', $('#last_name').val());
                data.append('phone', $('#phone').val());
                data.append('mobile', $('#mobile').val());
                data.append('email',$('#email').val());
                data.append('parent_company', $('#parent_company').val());
                data.append('account_payable_name', $('#account_payable_name').val());
                data.append('account_payable_phone', $('#account_payable_phone').val());
                data.append('account_payable_email', $('#account_payable_email').val());
                data.append('first_name_billing', $('#first_name_billing').val());
                data.append('last_name_billing', $('#last_name_billing').val());
                data.append('company_name_billing', $('#company_name_billing').val());
                data.append('street_address_billing', $('#street_address_billing').val());
                data.append('address2_billing', $('#address_2_billing').val());
                data.append('city_billing', $('#city_billing').val());
                data.append('state_billing', $('#state_billing').val());
                data.append('postal_code_billing', $('#postal_code_billing').val());
                data.append('phone_billing', $('#phone_billing').val());
                data.append('first_name_delivery', $('#first_name_delivery').val());
                data.append('last_name_delivery', $('#last_name_delivery').val());
                data.append('company_name_delivery', $('#company_name_delivery').val());
                data.append('street_address_delivery', $('#street_address_delivery').val());
                data.append('address2_delivery', $('#address_2_delivery').val());
                data.append('city_delivery', $('#city_delivery').val());
                data.append('state_delivery', $('#state_delivery').val());
                data.append('postal_code_delivery', $('#postal_code_delivery').val());
                data.append('phone_delivery', $('#phone_delivery').val());
                data.append('authorization_name', $('#authorization_name').val());
                data.append('financial_institution_name', $('#financial_institution_name').val());
                data.append('financial_institution_address', $('#financial_institution_address').val());
                data.append('financial_institution_signature', $('#financial_institution_signature').val());
                data.append('set_amount', $('#set_amount').val());
                data.append('maximum_amount', $('#maximum_amount').val());
                data.append('institute_routine_number', $('#institute_routine_number').val());
                data.append('saving_account_number', $('#saving_account_number').val());
                data.append('autorization_permit_number', $('#autorization_permit_number').val());
                data.append('autorization_phone_number', $('#autorization_phone_number').val());
                data.append('seller_name', $('#seller_name').val());
                data.append('seller_address', $('#seller_address').val());
                data.append('under_signed_checkbox', $('#under_signed_checkbox').val());
                data.append('under_property_checkbox', $('#under_property_checkbox').val());
                data.append('company_name_seller', $('#company_name_seller').val());
                data.append('signature', $('#signature').val());
                data.append('title', $('#title').val());
                data.append('address', $('#address').val());
                data.append('permit_number', $('#permit_number').val());
                data.append('phone_number', $('#phone_number').val());
                data.append('date', $('#date').val());
                data.append('type_of_farm', $('#type_of_farm').val());
            
                $.ajax({
                    type:'POST',
                    url:"{{ route('save_for_now') }}",
                    data:data,
                    contentType: false,
                    processData: false,
                    success:function(response){
                        if (response.status == true) {
                            $('#wholesale_spinner').addClass('d-none');
                            $('#save_for_now').attr('data-toggle', '')
                            $('#save_for_now').attr('data-target', '')
                            $('.success_message').removeClass('d-none');
                            $('#successMessage').html('Data saved For now');
                            setTimeout(() => {
                                window.location.href = '/wholesale/account/create'
                            }, 1000);
                        } else {
                            $('.success_message').removeClass('d-none');
                            $('#successMessage').html('Email Already Exist');
                            setTimeout(() => {
                                window.location.href = "/wholesale/account/edit/" + response.id;
                            }, 1000);
                        }
                    },
                    error: function (response) {
                    }
                });
            } else {
                $('.success_message').removeClass('d-none');
                $('#successMessage').html('Please Enter the Corrrect Data');
            }
        }

        $('#save_for_now').click(function() {
            if ($('#email').val() == '') {
                $('#save_for_now').attr('data-toggle', 'modal')
                $('#save_for_now').attr('data-target', '#save_for_now_modal')
                $('#save_for_now_modal').modal('show');
            } else {
                $('#save_for_now').attr('data-toggle', '')
                $('#save_for_now').attr('data-target', '')
                $('#save_for_now_modal').modal('hide');

                save_progress_step1();
            }
        })

        function save_email_for_now() {
            $('#wholesale_spinner').removeClass('d-none');
            var email = $('#email_address').val();
            if(email == '') {
                $('#wholesale_spinner').addClass('d-none');
                $('#email_address_errors').html('Email is required');
                return false;
            }
            else if (isEmail(document.getElementById('email_address')) == false) {
                $('#wholesale_spinner').addClass('d-none');
                $('#email_address_errors').html('Email is not valid');
                return false;
            }
            
            else {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'POST',
                    url:"{{ route('save_email_for_now') }}",
                    data:{email:email},
                    success:function(response){
                        if (response.status == true) {
                            $('#wholesale_spinner').addClass('d-none');
                            $('#save_for_now').attr('data-toggle', '');
                            $('#save_for_now').attr('data-target', '');
                            $('#save_for_now_modal').modal('hide');
                            $('.success_message').removeClass('d-none');
                            setTimeout(() => {
                                $('#successMessage').html(response.message);
                                window.location.href = "/wholesale/account/create/";
                            }, 1000);
                        } else if (response.status == false) {
                            $('#email_address_errors').html('Email Already Exists')
                            setTimeout(() => {
                                window.location.href = "/wholesale/account/edit/" + response.id;
                            }, 1000);

                        }
                    },
                    error: function (response) {
                    }
                });
            }
        }

        function validate_email() {
            var email = $('#email').val();
            var Email_input = $('#email');
            if (isEmail(Email_input == false)) {
                $('#email_errors').html('Email is not valid');
                return false;
            } else {
                $('#email_errors').html('');
                    $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var result = $.ajax({
                    type:'POST',
                    url:"{{ route('validate_email') }}",
                    data:{email:email},
                    success: function(response){
                        if (response.status == true) {
                            window.location.href = "/wholesale/account/edit/" + response.id;
                        } else {
                            return false;
                        }
                    }
                });
            }
            
        }

        // close modal
        function close_modal() {
            $(document).on('click', '.close_modal' ,function() {
                $('#save_for_now').attr('data-toggle', '')
                $('#save_for_now').attr('data-target', '')
                $('#save_for_now_modal').modal('hide');
            });
        }
        

        // remove error message when filled

        function remove_error_card_type(element) {
            var name = $(element).attr('name');
            $('#' + name + '_errors').html('');
        }
        function remove_error(element) {
            var id = $(element).attr('id');
            $('#' + id + '_errors').html('');
        }


        function selectRadio(element) {
            $('#card_type_errors').html('');
            $('.wholesale-custom-radio').removeClass('selected');
            let all_inputs = $('.hidden-radio');
            all_inputs.removeAttr('checked');
            all_inputs.each(function() {
                $(this).val('');
            });
            var radioInput = $(element).find('.hidden-radio');
            var getRadioId = radioInput.attr('id');
            var assign_value = $('#' + getRadioId).val(getRadioId)
            $('#' + getRadioId).attr('checked', 'checked');
            $(element).addClass('selected');
        }

        
        // validation for each  step 1

        function validation_message_step_1() {
            var company_name = $('#company_name').val();
            var first_name = $('#first_name').val();
            var last_name = $('#last_name').val();
            var phone = $('#phone').val();
            var mobile = $('#mobile').val();
            var email  = $('#email').val();
            var parent_company = $('#parent_company').val();
            var account_payable_name = $('#account_payable_name').val();
            var account_payable_phone = $('#account_payable_phone').val();
            var account_payable_email = $('#account_payable_email').val();
            var first_name_billing  = $('#first_name_billing').val();
            var last_name_billing = $('#last_name_billing').val();
            var company_name_billing = $('#company_name_billing').val();
            var street_address_billing = $('#street_address_billing').val();
            var city_billing = $('#city_billing').val();
            var state_billing = $('#state_billing').val();
            var postal_code_billing = $('#postal_code_billing').val();
            var phone_billing = $('#phone_billing').val();
            var first_name_delivery  = $('#first_name_delivery').val();
            var last_name_delivery = $('#last_name_delivery').val();
            var company_name_delivery = $('#company_name_delivery').val();
            var street_address_delivery = $('#street_address_delivery').val();
            var address2_delivery = $('#address_2_delivery').val();
            var city_delivery = $('#city_delivery').val();
            var state_delivery = $('#state_delivery').val();
            var postal_code_delivery = $('#postal_code_delivery').val();
            var phone_delivery = $('#phone_delivery').val();
            var file_upload = $('#file_upload').val();
            
            if (company_name == '') {
                $('#company_name_errors').html('Company name is required');
            } 
            if (first_name == '') {
                $('#first_name_errors').html('First name is required');
            }
            if (last_name == '') {
                $('#last_name_errors').html('Last name is required');
            }
            if (phone == '') {
                $('#phone_errors').html('Phone number is required');
            }
            if (mobile == '') {
                $('#mobile_errors').html('Mobile number is required');
            }
           
            if (email == '') {
                $('#email_errors').html('Email is required');
            }
            var email_validation = isEmail(document.getElementById('email'));
            if (email_validation == false) {
                $('#email_errors').html('Email is not valid');
            }
            if (account_payable_name == '') {
                $('#account_payable_name_errors').html('Account payable name is required');
            }
            if (account_payable_phone == '') {
                $('#account_payable_phone_errors').html('Account payable phone is required');
            }
            if (account_payable_email == '') {
                $('#account_payable_email_errors').html('Account payable email is required');
            }
            var account_pay_email_validation = isEmail(document.getElementById('account_payable_email'));
            if (account_pay_email_validation == false) {
                $('#account_payable_email_errors').html('Account payable email is not valid');
            }

            if (first_name_billing == '') {
                $('#first_name_billing_errors').html('First name is required');
            }
            if (last_name_billing == '') {
                $('#last_name_billing_errors').html('Last name is required');
            }
            if (company_name_billing == '') {
                $('#company_name_billing_errors').html('Company name is required');
            }
            if (street_address_billing == '') {
                $('#street_address_billing_errors').html('Street address is required');
            }
            if (city_billing == '') {
                $('#city_billing_errors').html('City is required');
            }
            if (state_billing == '') {
                $('#state_billing_errors').html('State is required');
            }
            if (postal_code_billing == '') {
                $('#postal_code_billing_errors').html('Postal code is required');
            }
            if (phone_billing == '') {
                $('#phone_billing_errors').html('Phone number is required');
            }

            if (first_name_delivery == '') {
                $('#first_name_delivery_errors').html('First name is required');
            }
            if (last_name_delivery == '') {
                $('#last_name_delivery_errors').html('Last name is required');
            }
            if (company_name_delivery == '') {
                $('#company_name_delivery_errors').html('Company name is required');
            }
            if (street_address_delivery == '') {
                $('#street_address_delivery_errors').html('Street address is required');
            }
            if (city_delivery == '') {
                $('#city_delivery_errors').html('City is required');
            }
            if (state_delivery == '') {
                $('#state_delivery_errors').html('State is required');
            }
            if (postal_code_delivery == '') {
                $('#postal_code_delivery_errors').html('Postal code is required');
            }
            if (phone_delivery == '') {
                $('#phone_delivery_errors').html('Phone number is required');
            }
            // if ((file_upload == '')  && $('#edit_image_input').val() == '') {
            //     $('#file_upload_errors').html('Permit image is required');
            // }
            var phoneValidation = isNumber(document.getElementById('phone'));
            var mobileValidation = isNumber(document.getElementById('mobile'));
            var phone_billing_validation = isNumber(document.getElementById('phone_billing'));
            var phone_delivery_validation  = isNumber(document.getElementById('phone_delivery'));
            var account_pay_phone = isNumber(document.getElementById('account_payable_phone'));
            if (phoneValidation == false) {
                $('#phone_errors').html('Phone number is invalid');
            }
            if (mobileValidation == false) {
                $('#mobile_errors').html('Mobile number is invalid');
            }
            if (phone_billing_validation == false) {
                $('#phone_billing_errors').html('Phone number is invalid');
            }
            if (phone_delivery_validation == false) {
                $('#phone_delivery_errors').html('Phone number is invalid');
            }
            if (account_pay_phone == false) {
                $('#account_payable_phone_errors').html('Phone number is invalid');
            }
            // if (company_name != '' && first_name != '' && last_name != '' && phone != ''  && mobile != ''  && email != '' && email_validation == true && account_payable_name != '' && account_payable_phone != '' && account_payable_email != '' && account_pay_email_validation == true && first_name_billing != '' && last_name_billing != '' && company_name_billing != '' && street_address_billing != '' && city_billing != '' && state_billing != '' && postal_code_billing != '' && phone_billing != '' && first_name_delivery != '' && last_name_delivery != '' && company_name_delivery != '' && street_address_delivery != '' && city_delivery != '' && state_delivery != '' && postal_code_delivery != '' && phone_delivery != '' && (file_upload != '' || $('#edit_image_input').val() != '') && account_pay_phone == true && phoneValidation == true && phone_billing_validation == true && phone_delivery_validation == true && mobileValidation == true) { 
            if (company_name != '' && first_name != '' && last_name != '' && phone != ''  && mobile != ''  && email != '' && email_validation == true && account_payable_name != '' && account_payable_phone != '' && account_payable_email != '' && account_pay_email_validation == true && first_name_billing != '' && last_name_billing != '' && company_name_billing != '' && street_address_billing != '' && city_billing != '' && state_billing != '' && postal_code_billing != '' && phone_billing != '' && first_name_delivery != '' && last_name_delivery != '' && company_name_delivery != '' && street_address_delivery != '' && city_delivery != '' && state_delivery != '' && postal_code_delivery != '' && phone_delivery != '' && account_pay_phone == true && phoneValidation == true && phone_billing_validation == true && phone_delivery_validation == true && mobileValidation == true) { 
                return true;
            } else {
                return false;
            }
        }

        function check_validation_step1 () {
            
            var validation =validation_message_step_1();
            if (validation == true) {
                $('#step1').removeClass('active');
                $('#step1').removeClass('show');
                $('.step_1_nav_link').removeClass('active');
                $('.step_2_nav_link').addClass('active');
                $('#step2').addClass('active');
                $('#step2').addClass('show');
            }
                      
        }

        $("#file_upload").change(function(e) {
            $('.edit_view_image').addClass('d-none');
            var all_files = e.target.files;
            if (all_files.length > 0) {
                for (let index = 0; index < all_files.length; index++) {
                    var file_element = all_files[index];
                    if (file_element.size > 419430400) {
                        $('#file_upload_errors').html('File size should be less than 50MB');
                        $('#file_upload').val('');
                        return false;
                    }
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#file_upload_errors').html('');
                        $('#file_upload').val(e.target.file_element);
                    }
                }
            }
        });

        //check vaidation for step 2

        $(document).on('click' ,  '#under_signed_checkbox' , function() {
            if ($(this).is(':checked')) {
                $(this).attr('checked', 'checked');
                $(this).val('1');
            } else {
                $(this).removeAttr('checked');
                $(this).val('');
            }
        });

        $(document).on('click' ,  '#under_property_checkbox' , function() {
            if ($(this).is(':checked')) {
                $(this).attr('checked', 'checked');
                $(this).val('1');
            } else {
                $(this).removeAttr('checked');
                $(this).val('');
            }
        });

        function validation_message_step2 () {
            var seller_name = $('#seller_name').val();
            var seller_address = $('#seller_address').val();
            var under_signed_checkbox = $('#under_signed_checkbox');
            var under_property_checkbox = $('#under_property_checkbox');
            var signature = $('#signature').val();
            var title = $('#title').val();
            var address  = $('#address').val();
            var permit_number = $('#permit_number').val();
            var phone_number = $('#phone_number').val();
            var date = $('#date').val();
            
            var type_of_farm = $('#type_of_farm').val();
            if (seller_name == '') {
                $('#seller_name_errors').html('Seller name is required');
            }
            if (seller_address == '') {
                $('#seller_address_errors').html('Seller address is required');
            }
            if (under_signed_checkbox.is(":checked")) {
                under_signed_checkbox.attr('checked', 'checked');
                under_signed_checkbox.val('1');
                $('#under_signed_checkbox_errors').html('');
            } else {
                $('#under_signed_checkbox_errors').html('Under signed checkbox is required');
            }
            if (under_property_checkbox.is(":checked")) {
                under_property_checkbox.attr('checked', 'checked');
                under_property_checkbox.val('1');
                $('#under_property_checkbox_errors').html('');
            } else {
                $('#under_property_checkbox_errors').html('Under property checkbox is required');
            }
            if (signature == '') {
                $('#signature_errors').html('Signature is required');
            }
            if (title == '') {
                $('#title_errors').html('Title is required');
            }
            if (address == '') {
                $('#address_errors').html('Address is required');
            }
            if (phone_number == '') {
                $('#phone_number_errors').html('Phone number is required');
            }
            if (date == '') {
                $('#date_errors').html('Date is required');
            }
            if (type_of_farm == '') {
                $('#type_of_farm_errors').html('Type of farm or Equipments is required');
            }
            var phone_number_validation = isNumber(document.getElementById('phone_number'));
            if (phone_number_validation == false) {
                $('#phone_number_errors').html('Phone number is invalid');
            }

            if (seller_name != '' && seller_address != '' && under_signed_checkbox.is(":checked") && under_property_checkbox.is(":checked") && signature != '' && title != '' && address != ''  && phone_number != '' && phone_number_validation == true && date != '' && type_of_farm != '') {
                return true;
            } else {
                return false;
            }
        }

        function check_validation_step2 () {
            var validation =  validation_message_step2();

            if (validation == true) {
                $('#step2').removeClass('active');
                $('#step2').removeClass('show');
                $('.step_2_nav_link').removeClass('active');
                $('.step_3_nav_link').addClass('active');
                $('#step3').addClass('active');
                $('#step3').addClass('show');
            }
        }
        // check validation for step 3

        function validation_message_step3() {
            var authorization_name = $('#authorization_name').val();
            var financial_institution_name = $('#financial_institution_name').val();
            var financial_institution_address = $('#financial_institution_address').val();
            var financial_institution_signature = $('#financial_institution_signature').val();
            var set_amount = $('#set_amount').val();
            var maximum_amount = $('#maximum_amount').val();
            var institute_routine_number = $('#institute_routine_number').val();
            var saving_account_number = $('#saving_account_number').val();
            var autorization_phone_number = $('#autorization_phone_number').val();
           

            if (authorization_name == '') {
                $('#authorization_name_errors').html('Authorization name is required');
            }
            if (financial_institution_name == '') {
                $('#financial_institution_name_errors').html('Financial institution name is required');
            }
            if (financial_institution_address == '') {
                $('#financial_institution_address_errors').html('Address is required');
            }
            if (financial_institution_signature == '') {
                $('#financial_institution_signature_errors').html('Signature is required');
            }
            if (institute_routine_number == '') {
                $('#institute_routine_number_errors').html('Institute routine number is required');
            }
            if (saving_account_number == '') {
                $('#saving_account_number_errors').html('Saving account number is required');
            }
            if (autorization_phone_number == '') {
                $('#autorization_phone_number_errors').html('Phone number is required');
            }
            var autorization_phone_number_validation = isNumber(document.getElementById('autorization_phone_number'));
            if (autorization_phone_number_validation == false) {
                $('#autorization_phone_number_errors').html('Phone number is invalid');
            }

            if (authorization_name != '' && financial_institution_name != '' && financial_institution_address != '' && financial_institution_signature != ''  && institute_routine_number != '' && saving_account_number != '' && autorization_phone_number != '' && autorization_phone_number_validation == true) {
                return true;
            } else {
                return false;
            }
        }
        
        function check_validation_step3 () {
            
            var validation  = validation_message_step3();

            if (validation == true) {
                $('#step3').removeClass('active');
                $('#step3').removeClass('show');
                $('.step_3_nav_link').removeClass('active');
                $('.step_4_nav_link').addClass('active');
                $('#step4').addClass('active');
                $('#step4').addClass('show');
            }
            
        }

        function validation_message_step_4 () {
            var master_card = $('#master_card');
            var visa_card = $('#visa_card');
            var discover_card = $('#discover_card');
            var american_express_card = $('#american_express_card');
            var other_card = $('#other_card');
            var cardholder_name = $('#cardholder_name').val();
            var card_number = $('#card_number').val();
            var expiration_date = $('#expiration_date').val();
            var card_holder_zip_code = $('#card_holder_zip_code').val();
            var undertaking_name = $('#undertaking_name').val();
            var authorize_text  = $('#authorize_text').val();
            var customer_signature = $('#customer_signature').val();
            var date_wholesale  = $('#date_wholesale').val();
            
            
            
            if (master_card.is(':checked') || visa_card.is(':checked') || discover_card.is(':checked') || american_express_card.is(':checked') || other_card.is(':checked')) {
                $('#card_type_errors').html('');
            } else {
                $('#card_type_errors').html('Please select card type');
            }

            if (cardholder_name == '') {
                $('#cardholder_name_errors').html('Cardholder name is required');
            }
            if (card_number == '') {
                $('#card_number_errors').html('Card number is required');
            }
            if (expiration_date == '') {
                $('#expiration_date_errors').html('Expiration date is required');
            }
            if (card_holder_zip_code == '') {
                $('#card_holder_zip_code_errors').html('Card holder zip code is required');
            }
            if (undertaking_name == '') {
                $('#undertaking_name_errors').html('Undertaking name is required');
            }
            if (authorize_text == '') {
                $('#authorize_text_errors').html('Authorize text is required');
            }
            if (customer_signature == '') {
                $('#customer_signature_errors').html('Customer signature is required');
            }
            if (date_wholesale == '') {
                $('#date_wholesale_errors').html('Date is required');
            }

            if ((master_card.val() != '' || visa_card.val() != '' || discover_card.val() != '' || american_express_card.val() != '' || other_card.val() != '') && cardholder_name != '' && card_number != '' && expiration_date != '' && card_holder_zip_code != '' && undertaking_name != '' && authorize_text != '' && customer_signature != '' && date_wholesale != '') {
                return true;
            } else {
                return false;
            }

        }

        function check_validation_step4 () {
            var is_update = false;
            var validation  = validation_message_step_4();
            var check_all_validation = validation_message_step_1() && validation_message_step2() && validation_message_step3() && validation_message_step_4();
            if (validation_message_step_1() == false) {
                $('#step1').addClass('active');
                $('#step1').addClass('show');
                $('.step_1_nav_link').addClass('active');
                $('#step4').removeClass('active');
                $('#step4').removeClass('show');
                $('.step_4_nav_link').removeClass('active');
                return false;
            } else if(validation_message_step2()  == false) {
                $('#step2').addClass('active');
                $('#step2').addClass('show');
                $('.step_2_nav_link').addClass('active');
                $('#step4').removeClass('active');
                $('#step4').removeClass('show');
                $('.step_4_nav_link').removeClass('active');
                return false;
            } else if(validation_message_step3()  == false) {
                $('#step3').addClass('active');
                $('#step3').addClass('show');
                $('.step_3_nav_link').addClass('active');
                $('#step4').removeClass('active');
                $('#step4').removeClass('show');
                $('.step_4_nav_link').removeClass('active');
                return false;
            } else if(validation_message_step_4()  == false) {
                $('#step4').addClass('active');
                $('#step4').addClass('show');
                $('.step_4_nav_link').addClass('active');
                return false;
            }
            else {

                $('#wholesale_spinner').removeClass('d-none');
                var data = new FormData();
                // var files = $('input[name="permit_image"]')[0].files[0];
                // data.append('permit_image',files);
                var totalfiles = document.getElementById('file_upload').files.length;
                if (totalfiles > 0) {
                    for (var index = 0; index < totalfiles; index++) {
                        data.append("permit_image[]", document.getElementById('file_upload').files[index]);
                    }
                }
                data.append('card_type',$('input[name="card_type"]:checked').val());
                data.append('cardholder_name', $('#cardholder_name').val());
                data.append('card_number', $('#card_number').val());
                data.append('expiration_date', $('#expiration_date').val());
                data.append('card_holder_zip_code', $('#card_holder_zip_code').val());
                data.append('undertaking_name', $('#undertaking_name').val());
                data.append('authorize_text', $('#authorize_text').val());
                data.append('customer_signature', $('#customer_signature').val());
                data.append('date_wholesale', $('#date_wholesale').val());
                data.append('authorization_name', $('#authorization_name').val());
                data.append('financial_institution_name', $('#financial_institution_name').val());
                data.append('financial_institution_address', $('#financial_institution_address').val());
                data.append('financial_institution_signature', $('#financial_institution_signature').val());
                data.append('set_amount', $('#set_amount').val());
                data.append('maximum_amount', $('#maximum_amount').val());
                data.append('institute_routine_number', $('#institute_routine_number').val());
                data.append('saving_account_number', $('#saving_account_number').val());
                data.append('autorization_permit_number', $('#autorization_permit_number').val());
                data.append('autorization_phone_number', $('#autorization_phone_number').val());
                data.append('seller_name', $('#seller_name').val());
                data.append('seller_address', $('#seller_address').val());
                data.append('under_signed_checkbox', $('#under_signed_checkbox').val());
                data.append('under_property_checkbox', $('#under_property_checkbox').val());
                data.append('company_name_seller', $('#company_name_seller').val());
                data.append('signature', $('#signature').val());
                data.append('title', $('#title').val());
                data.append('address', $('#address').val());
                data.append('permit_number', $('#permit_number').val());
                data.append('phone_number', $('#phone_number').val());
                data.append('date', $('#date').val());
                data.append('type_of_farm', $('#type_of_farm').val());
                data.append('company_name', $('#company_name').val());
                data.append('first_name', $('#first_name').val());
                data.append('last_name', $('#last_name').val());
                data.append('phone', $('#phone').val());
                data.append('mobile', $('#mobile').val());
                data.append('email',$('#email').val());
                data.append('parent_company', $('#parent_company').val());
                data.append('account_payable_name', $('#account_payable_name').val());
                data.append('account_payable_phone', $('#account_payable_phone').val());
                data.append('account_payable_email', $('#account_payable_email').val());
                data.append('first_name_billing', $('#first_name_billing').val());
                data.append('last_name_billing', $('#last_name_billing').val());
                data.append('company_name_billing', $('#company_name_billing').val());
                data.append('street_address_billing', $('#street_address_billing').val());
                data.append('address2_billing', $('#address_2_billing').val());
                data.append('city_billing', $('#city_billing').val());
                data.append('state_billing', $('#state_billing').val());
                data.append('postal_code_billing', $('#postal_code_billing').val());
                data.append('phone_billing', $('#phone_billing').val());
                data.append('first_name_delivery', $('#first_name_delivery').val());
                data.append('last_name_delivery', $('#last_name_delivery').val());
                data.append('company_name_delivery', $('#company_name_delivery').val());
                data.append('street_address_delivery', $('#street_address_delivery').val());
                data.append('address2_delivery', $('#address_2_delivery').val());
                data.append('city_delivery', $('#city_delivery').val());
                data.append('state_delivery', $('#state_delivery').val());
                data.append('postal_code_delivery', $('#postal_code_delivery').val());
                data.append('phone_delivery', $('#phone_delivery').val());
                data.append('wholesale_application_id', $('#whs_id').val());
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            
                $.ajax({
                    type:'POST',
                    url:"{{ route('store_wholesale_account') }}",
                    data:data,
                    contentType: false,
                    processData: false,
                    success:function(response){
                        console.log(response);
                        if (response.status == true) {
                            $('#wholesale_spinner').addClass('d-none');
                            window.location.href = '/wholesale/account/thankyou/' + response.wholesale_appication_id;
                        } else {
                            $('#wholesale_spinner').addClass('d-none');
                            $('#wholesale_form_error').html(response.message);
                        }
                     },
                    error: function (response) {
                        $('#wholesale_spinner').addClass('d-none')
                        var errors = response.responseJSON;
                        var errorsHtml = '';
                        $.each(errors.errors, function( key, value ) {
                            errorsHtml += '<li>' + value + '</li>'; //showing only the first error.
                        });
                        $('#wholesale_form_error').html(errorsHtml);
                    }
                });
            }
        }

       
        // show preview data
        $(document).ready(function() {
            $(document).on('click', '.close_previous_data_modal' ,function() {
                $('.close_previous_data_modal').attr('data-toggle', '')
                $('.close_previous_data_modal').attr('data-target', '')
                $('#previous_data_modal').modal('hide');
            });

            $(document).on('click', '#show_previous_data_button' ,function() {
                $('#show_previous_data_button').attr('data-toggle', 'modal')
                $('#show_previous_data_button').attr('data-target', '#previous_data_modal')
                $('#previous_data_modal').modal('show');
            });
        });

        function show_previous_data_pop_up() {
            $('#show_previous_data_button').attr('data-toggle', 'modal')
            $('#show_previous_data_button').attr('data-target', '#previous_data_modal')
            $('#previous_data_modal').modal('show');
        }

        function isNumber(element) {
            var getID = element.id;
            var phoneNumber = element.value;
            var regex = /^\+\d{1,3} \(\d{3}\) \d{3}-\d{4}$/;
            if (regex.test(phoneNumber)) {
                return true;
            } else {
                $('#' + getID + '_errors').html(`Please Enter a valid data`);
                return false;
            }
        }
        
        function isEmail(element) {
            var getID = element.id;
            var phoneNumber = element.value;
            var customRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            if (customRegex.test(phoneNumber)) {
                return true;
            } else {
                $('#' + getID + '_errors').html(`Please Enter a valid Email`);
                return false;
            }
        }

     </script>

</body>
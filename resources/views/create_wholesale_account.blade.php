@include('partials.header')

<body>
    <main>
        @include('partials.top-bar')
        @include('partials.search-bar')
        <div class="container-fluid px-0 overflow-hidden">
            <div class="row justify-content-center">
                <div class="col-md-8 border">
                    <div class="row">
                        <div class="col-md-3 bg-white p-3 pt-5">
                            <h5 class="tabs_heading mb-2">
                                Application Session
                            </h5>
                            <ul class="nav nav_wholesale flex-column nav-pills pl-2">
                                <li class="nav-item p-1">
                                    <a class="wholesale-nav-link wholesale_account_tabs" data-toggle="pill" href="#step1">Customer Application</a>
                                </li>
                                <li class="nav-item p-1">
                                    <a class="wholesale-nav-link wholesale_account_tabs " data-toggle="pill" href="#step2">Regulation 1533.1</a>
                                </li>
                                <li class="nav-item p-1">
                                    <a class="wholesale-nav-link wholesale_account_tabs active" data-toggle="pill" href="#step3">ACH Authorization Form</a>
                                </li>
                                <li class="nav-item p-1">
                                    <a class="wholesale-nav-link wholesale_account_tabs" data-toggle="pill" href="#step4">Credit Card Authorization</a>
                                </li>
                            </ul>
                        </div>
                        <!-- Content Area -->
                        <div class="col-md-9 bg-light p-4">
                            <div class="row justify-content-center">
                                <div class="col-md-10 p-4">
                                    <div class="tab-content">
                                        <!-- Step 1 Content -->
                                        <div class="tab-pane fade" id="step1">
                                            <div class="card">
                                                <div class="card-body p-4">
                                                    <div class="row p-3">
                                                        <div class="main-head-step-form">
                                                            <h5 class="step_1_main_heading mb-0">
                                                                New Wholesale Customer Application
                                                            </h5>
                                                        </div>
                                                        <form action="" class="mt-3">
                                                            {{-- first row --}}
                                                            <div class="row mb-2">
                                                                <div class="sub-head-step-form mb-2">
                                                                    <h6 class="step_1_sub_heading">
                                                                        Company Information
                                                                    </h6>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="company_name">Company Name</label>
                                                                        <input type="text" name="company_name" class="form-control wholesale_inputs" id="company_name" placeholder="Enter your Company Name">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="">Name</label>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <input type="text" name="first_name" id="first_name" class="form-control wholesale_inputs" placeholder="First name">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <input type="text" name="last_name" id="last_name" class="form-control wholesale_inputs" placeholder="Last name">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="wholesale_form_labels" for="phone">Phone</label>
                                                                                <input type="text" name="phone" id="phone" class="form-control wholesale_inputs" placeholder="+1 (000) 000-0000">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="wholesale_form_labels" for="mobile">Mobile</label>
                                                                                <input type="text" name="mobile" id="mobile" class="form-control wholesale_inputs" placeholder="+1 (000) 000-0000">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="email">Email</label>
                                                                        <input type="email" class="form-control wholesale_inputs" id="email" name="email" placeholder="Enter your Email">
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
                                                                        <label class="wholesale_form_labels" for="parent_company">Parent Company</label>
                                                                        <input type="text" name="parent_company" class="form-control wholesale_inputs" id="parent_company" placeholder="Enter parent company name (if applicable)">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="account_payable_name">Accounts payable Name</label>
                                                                        <input type="text" class="form-control wholesale_inputs" id="account_payable_name" name="account_payable_name" placeholder="Accounts payable Name">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="account_payable_phone">Accounts payable Phone</label>
                                                                        <input type="number" class="form-control wholesale_inputs" id="account_payable_phone" name="account_payable_phone" placeholder="Accounts payable Phone">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="account_payable_email">Accounts payable Email</label>
                                                                        <input type="email" class="form-control wholesale_inputs" id="account_payable_email" name="account_payable_email" placeholder="Accounts payable Email">
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
                                                                        <label class="wholesale_form_labels" for="">Name</label>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <input type="text" name="first_name" id="first_name" class="form-control wholesale_inputs" placeholder="First name">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <input type="text" name="last_name" id="last_name" class="form-control wholesale_inputs" placeholder="Last name">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="company_name">Company Name</label>
                                                                        <input type="text" name="company_name" class="form-control wholesale_inputs" id="company_name" placeholder="Enter your Company Name">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="street_address">Street Address</label>
                                                                        <input type="text" name="street_address" class="form-control wholesale_inputs" id="street_address" placeholder="Enter your street address here">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="address_2">Address2</label>
                                                                        <input type="text" name="address_2" class="form-control wholesale_inputs" id="address_2" placeholder="Enter your second address here">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="city">Town/City</label>
                                                                        <input type="text" name="city" class="form-control wholesale_inputs" id="city" placeholder="Enter your town/city name here">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="wholesale_form_labels" for="state">State</label>
                                                                                <input type="text" name="state" id="state" class="form-control wholesale_inputs" placeholder="Enter your state name">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="wholesale_form_labels" for="postal_code">Zip/Postal</label>
                                                                                <input type="text" name="postal_code" id="postal_code" class="form-control wholesale_inputs" placeholder="Enter zip/postal code here">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="wholesale_form_labels" for="phone">Phone</label>
                                                                                <input type="text" name="phone" id="first_name" class="form-control wholesale_inputs" placeholder="+1 (000) 000-0000">
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
                                                                        <label class="wholesale_form_labels" for="">Name</label>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <input type="text" name="first_name" id="first_name" class="form-control wholesale_inputs" placeholder="First name">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <input type="text" name="last_name" id="last_name" class="form-control wholesale_inputs" placeholder="Last name">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="company_name">Company Name</label>
                                                                        <input type="text" name="company_name" class="form-control wholesale_inputs" id="company_name" placeholder="Enter your Company Name">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="street_address">Street Address</label>
                                                                        <input type="text" name="street_address" class="form-control wholesale_inputs" id="street_address" placeholder="Enter your street address here">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="address_2">Address2</label>
                                                                        <input type="text" name="address_2" class="form-control wholesale_inputs" id="address_2" placeholder="Enter your second address here">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="wholesale_form_labels" for="city">Town/City</label>
                                                                        <input type="text" name="city" class="form-control wholesale_inputs" id="city" placeholder="Enter your town/city name here">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="wholesale_form_labels" for="state">State</label>
                                                                                <input type="text" name="state" id="state" class="form-control wholesale_inputs" placeholder="Enter your state name">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="wholesale_form_labels" for="postal_code">Zip/Postal</label>
                                                                                <input type="text" name="postal_code" id="postal_code" class="form-control wholesale_inputs" placeholder="Enter zip/postal code here">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="wholesale_form_labels" for="phone">Phone</label>
                                                                                <input type="text" name="phone" id="first_name" class="form-control wholesale_inputs" placeholder="+1 (000) 000-0000">
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
                                                                                        <input type="file" id="file_upload" style="display:none"/> 
                                                                                        <div class="col-md-12">
                                                                                            <h6 class="drop_your_files_here text-center"> Drop your file here or  
                                                                                                <span type="button" class="browse">browse</span>
                                                                                            </h6>
                                                                                        </div>
                                                                                        <div class="col-md-10">
                                                                                            <p class="size_info">Maximum size: 50MB</p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="text-right">
                                                                <button type="button" id="step1_next" class="step_next btn">
                                                                    Next
                                                                </button>
                                                            </div>
                                                        </form>
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
                                                            <form action="">
                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="seller_name">Seller’s / Lessor’s name</label>
                                                                            <input type="text" name="seller_name" class="form-control wholesale_inputs" id="seller_name" placeholder="Enter seller’s or lessor’s full name">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="seller_address">Seller’s / Lessor’s address </label>
                                                                            <input type="text" name="seller_address" class="form-control wholesale_inputs" id="seller_address" placeholder="Enter seller’s or lessor’s address (street, city, state, zip code)">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col-md-12 mt-2">
                                                                        <h5 class="tabs_heading">Certificate Eligibility:</h5>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="undertaking_div p-2 d-flex">
                                                                            <div class="col-md-1">
                                                                                <input type="checkbox" name="under_signed_checkbox" id="under_signed_checkbox" class="under_signed_checkbox mt-1">
                                                                            </div>
                                                                            <div class="col-md-11 pl-0">
                                                                                
                                                                                <p class="undersigned-para">
                                                                                    I, as the undersigned purchaser, hereby certify I am engaged in an agricultural business described in Codes 0111 to 0291 of the Standard Industrial Classification (SIC) Manual, or I perform an agricultural service described in Codes 0711 to 0783 of the SIC Manual for such classified persons. The property purchased or leased will be used primarily in producing and harvesting agricultural products in accordance with Revenue & Taxation Code Section 6356.5.
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col-md-12 mb-2">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="type_of_farm">Type of Farm Equipment and Machinery (or parts thereof) </label>
                                                                            <input type="text" name="type_of_farm" class="form-control wholesale_inputs" id="type_of_farm" placeholder="type of farm equipment or machinery being bought">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 mb-3">
                                                                        <div class="undertaking_div p-2 d-flex">
                                                                            <div class="col-md-1">
                                                                                <input type="checkbox" name="under_property_checkbox" id="under_property_checkbox" class="under_signed_checkbox mt-1">
                                                                            </div>
                                                                            <div class="col-md-11 pl-0">
                                                                                <p class="undersigned-para">
                                                                                    I understand that if such property is not used in the manner qualifying for the partial exemption, or if I am not a qualified person, as applicable, that I am required by the sales and use tax law to report and pay the state tax measured by the sales price/rentals payable of the property to/by me. I also understand that this partial exemption certificate is in effect as of the date shown below and will remain in effect until revoked in writing.
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="seller_name">Purchaser’s name or company name (if applicable)</label>
                                                                            <input type="text" name="company_name" class="form-control wholesale_inputs" id="company_name" placeholder="Enter in the Purchaser’s name or company’s name">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="signature">Signature </label>
                                                                            <input type="text" name="signature" class="form-control wholesale_inputs" id="signature" placeholder="(signature of the purchaser, purchaser’s employee, or authorized representative or the the purchaser)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="title">Title </label>
                                                                            <input type="text" name="title" class="form-control wholesale_inputs" id="title" placeholder="Title">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 mb-2">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="address"> Address</label>
                                                                            <input type="text" name="address" class="form-control wholesale_inputs" id="address" placeholder="(street, city, state, zip code)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 mb-2">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="permit_number">Permit number (if applicable)</label>
                                                                            <input type="text" name="permit_number" id="permit_number" class="form-control wholesale_inputs" placeholder="Permit number (if applicable)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 mb-2">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="phone">Phone</label>
                                                                            <input type="text" name="phone" id="phone_number" class="form-control wholesale_inputs" placeholder="+1 (000) 000-0000">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 mb-2">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="phone">Date</label>
                                                                            <input type="date" name="phone" id="date" class="form-control wholesale_inputs">
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
                                                                    <button type="button" id="step2_next" class="step_next btn">
                                                                        Next
                                                                    </button>
                                                                </div>
                                                            </form>
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
                                                        <form action="" class="p-0">
                                                            <div class="col-md-12 mb-2">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <p class="credit_undertaking_para mb-0">
                                                                            I (we) hereby authorize
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <div class="form-group mb-0">
                                                                            <input type="text" name="undertaking_input" class="form-control wholesale_inputs" id="undertaking_input" placeholder="Enter text here">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 mb-3">
                                                                        <p class="credit_undertaking_para">
                                                                            (THE COMPANY) to initiate entries to my (our) checking/savings accounts at the financial institution listed below (THE FINANCIAL INSTITUTION), and, if necessary, initiate adjustments for any transactions credited/debited in error. This authority will remain in effect until THE COMPANY is notified by me (us) in writing to cancel it in such time as to afford THE COMPANY and THE FINANCIAL INSTITUTION a reasonable opportunity to act on it.
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="financial_institution_name">Name of Financial Institution</label>
                                                                            <input type="text" name="financial_institution_name" class="form-control wholesale_inputs" id="financial_institution_name" placeholder="Enter the name of financial Institute">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="address"> Address</label>
                                                                            <input type="text" name="financial_institution_address" class="form-control wholesale_inputs" id="financial_institution_address" placeholder="Address of Financial Institute (Branch, city, state, zip code)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="signature">Signature </label>
                                                                            <input type="text" name="signature" class="form-control wholesale_inputs" id="signature" placeholder="Signature">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="set_amount">Set amount</label>
                                                                            <input type="text" name="set_amount" class="form-control wholesale_inputs" id="name" placeholder="Set Amount">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="maximum_amount">Maximum amount</label>
                                                                            <input type="text" name="maximum_amount" class="form-control wholesale_inputs" id="name" placeholder="Maximum Amount">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="institute_routine_number">Financial institute routine number</label>
                                                                            <input type="text" name="institute_routine_number" class="form-control wholesale_inputs" id="institute_routine_number" placeholder="Enter Financial institute routine number">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="saving_account_number">Checking/Saving account number</label>
                                                                            <input type="text" name="saving_account_number" class="form-control wholesale_inputs" id="saving_account_number" placeholder="Enter Checking/Saving account number">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="permit_number">Permit number (if applicable)</label>
                                                                            <input type="text" name="permit_number" id="permit_number" class="form-control wholesale_inputs" placeholder="Permit number (if applicable)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="phone_number">Phone</label>
                                                                            <input type="text" name="phone_number" id="phone_number" class="form-control wholesale_inputs" placeholder="+1 (000) 000-0000">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <div class="row">
                                                                                <label class="wholesale_form_labels" for="numebers">These numbers are located on the bottom of your check as follows</label>
                                                                                <div class="col-md-6">
                                                                                    <input type="text" name="routing_number" id="routing_number" class="form-control wholesale_number_inputs" placeholder="Routing Number (1234567890123)">
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <input type="text" name="account_number" id="account_number" class="form-control wholesale_number_inputs" placeholder="Account Number (123456789)">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-right">
                                                                    <button type="button" id="step3_next" class="step_next btn">
                                                                        Next
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- step 4 goes here --}}
                                        <div class="tab-pane fade show active" id="step4">
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
                                                        <form action="" class="p-0">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="wholesale_form_labels mb-0" for="card_type">Card Type</label>
                                                                    <div class="row justify-content-between pl-3 pt-2 pr-3">
                                                                        <div class="col-md-2 d-flex border justify-content-center m-0  master-div-focus">
                                                                            <input type="radio" name="card_type" class="wholesale_master_card form-control border-0" value="master_card">
                                                                        </div>
                                                                        <div class="col-md-2 d-flex border justify-content-center m-0 ">
                                                                            <input type="radio" name="card_type" class="wholesale_visa_card form-control border-0" value="visa_card">
                                                                        </div>
                                                                        <div class="col-md-2 d-flex border justify-content-center m-0 ">
                                                                            <input type="radio" name="card_type" class="wholesale_discover_card form-control border-0" value="discover_card">
                                                                        </div>
                                                                        <div class="col-md-2 d-flex border justify-content-center m-0 ">
                                                                            <input type="radio" name="card_type" class="wholesale_american_card form-control border-0" value="american_express_card">
                                                                        </div>
                                                                        <div class="col-md-2 d-flex border justify-content-center m-0 ">
                                                                            <input type="radio" name="card_type" class="form-control border-0 wholesale_other_card" value="other_card">
                                                                            <span class="wholesale_other_card_label">OTHER</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="wholesale_form_labels" for="cardholder_name">Cardholder name (as shown on card)</label>
                                                                    <input type="text" name="cardholder_name" class="form-control wholesale_inputs" id="cardholder_name" placeholder="Enter card holder name">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="wholesale_form_labels" for="card_number">Card Number</label>
                                                                    <input type="text" name="card_number" class="form-control wholesale_inputs ccFormatMonitor" id="card_number" placeholder="Enter card number">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="expiration_date">Expiration Date</label>
                                                                            <input type="text" name="expiration_date" class="form-control wholesale_inputs inputExpDate" id="expiration_date" maxlength='7' placeholder="MM / YY">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="card_holder_zip_code">Cardholder ZIP Code</label>
                                                                            <input type="text" name="card_holder_zip_code" class="form-control wholesale_inputs" id="card_holder_zip_code" placeholder="Zip code from credit card billing address">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mt-3">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group mb-0 d-flex align-items-center">
                                                                            <label class="mb-0 text-center align-middle credit_card_undertaking">
                                                                                I,
                                                                            </label>
                                                                            <input type="text" name="undertaking_name" class="form-control wholesale_inputs w-50 ml-2" id="undertaking_name" placeholder="Enter text here">
                                                                            <label class="mb-0 ml-2 text-center align-middle w-25 credit_card_undertaking">
                                                                                , authorize
                                                                            </label>
                                                                            <input type="text" name="authorize_text" class="form-control wholesale_inputs w-50" id="authorize_text" placeholder="Enter text here">
                                                                        </div>
                                                                        <p class="credit_card_undertaking">
                                                                            to change my credit card above for agreed upon purchases, I understand that my information will be saved to file for future transactions on my account.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="row mb-2">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="customer_signature">Customer Signature</label>
                                                                            <input type="text" name="customer_signature" class="form-control wholesale_inputs" id="customer_signature" placeholder="Enter Signature">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="wholesale_form_labels" for="date_wholesale"> Date</label>
                                                                            <input type="date" name="date_wholesale" class="form-control wholesale_inputs" id="date_wholesale">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 text-right">
                                                                <button type="button" id="step4_next" class="step_next btn">
                                                                    Next
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
        .wholesale_master_card {
            background-image: url("{{ asset('/theme/img/wholesale-card-icons/mastercard.svg') }}");
            background-repeat: no-repeat;
            background-position: center;
            width: 56px;
            height: 38px;
            padding: 10.153px 13.538px !important;
        }
        .wholesale_master_card:focus {
            box-shadow: 0 0 0 0rem rgb(239, 240, 238) !important;
        }
        .wholesale_visa_card {
            background-image: url("{{ asset('/theme/img/wholesale-card-icons/visa.svg') }}");
            background-repeat: no-repeat;
            background-position: center;
            width: 56px;
            height: 38px;
            padding: 10.153px 13.538px !important;
        }
        .wholesale_american_card {
            background-image: url("{{ asset('/theme/img/wholesale-card-icons/american_express.svg') }}");
            background-repeat: no-repeat;
            background-position: center;
            width: 56px;
            height: 38px;
            padding: 10.153px 13.538px !important;
        }
        .wholesale_discover_card {
            background-image: url("{{ asset('/theme/img/wholesale-card-icons/discover.svg') }}");
            background-repeat: no-repeat;
            background-position: center;
            width: 56px;
            height: 38px;
            padding: 10.153px 13.538px !important;
        }
        .wholesale_other_card {
            width: 56px;
            height: 38px;
            padding: 10.153px 13.538px !important;
        }
        .wholesale_other_card_label {
            color: #242424;
            font-family: 'Poppins';
            font-size: 14px;
            font-style: normal;
            font-weight: 500;
            line-height: 20.307px; /* 145.048% */
            text-transform: uppercase;
            position: absolute;
            top: 10px;
            left: 35px;
        }
        
        .master-div-focus:active {
            outline: 2px solid #7CC633;
        } 
        .master-div-focus:focus-visible {
            outline: 2px solid #7CC633;
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
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
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
</body>
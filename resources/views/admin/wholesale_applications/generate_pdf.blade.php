
    <div class="table-wrapper">
        <div class="card-body product_secion_main_body">
            <div class="row border-bottom product_section_header">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6 mobile_heading">
                            <h2>
                                Wholesale Application Detail
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(!empty($wholesale_application))
        <div class="card mt-3">
            <div class="row1 border-bottom">
                <div class="col-md-12">
                    <div class="card-header p-3 ml-2 border-0">
                        <h4 class="text-bold mb-0">
                            Company Information
                        </h4>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <div class="col-md-12 shadow border p-0">
                                <table class="table table-border">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>
                                                Company Name
                                            </th>
                                            <th>
                                                First Name
                                            </th>
                                            <th>
                                                Last Name
                                            </th>
                                            <th>
                                                Email
                                            </th>
                                            <th>
                                                Mobile
                                            </th>
                                            <th>
                                                Phone
                                            </th>
                                            <th>
                                                Permit Image
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                {{ !empty($wholesale_application['company']) ? $wholesale_application['company'] : '-'}}
                                            </td>
                                            <td>
                                                {{ !empty($wholesale_application['first_name'] ) ? $wholesale_application['first_name'] : '-'}}
                                            </td>
                                            <td>
                                                {{ !empty($wholesale_application['last_name'] ) ? $wholesale_application['last_name'] : '-'}}
                                            </td>
                                            <td>
                                                {{ !empty($wholesale_application['email'] ) ? $wholesale_application['email'] : '-'}}
                                            </td>
                                            <td>
                                                {{ !empty($wholesale_application['mobile'] ) ? $wholesale_application['mobile'] : '-'}}
                                            </td>
                                            <td>
                                                {{ !empty($wholesale_application['phone'] ) ? $wholesale_application['phone'] : '-'}}
                                            </td>
                                            <td>
                                                @if(!empty($wholesale_application['permit_image']))
                                                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/wholesale/images/'.$wholesale_application['permit_image']))) }}" alt="" width="100px" class="img-fluid">
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @if(!empty($wholesale_application['permit_images']))
                <div class="col-md-12">
                    <div class="card-header border-bottom-0">
                        <h4 class="card-title text-bold">
                            Attachments
                        </h4>
                    </div>
                    <div class="card-body pt-0">
                        <div class="col-md-12 shadow border p-2">
                            <div class="row justify-content-between align-items-center" style="display: flex !important;
                            align-items: center;
                            justify-content: space-between;">
                            <table class="table table-border">
                                <thead class="bg-light">
                                    @for ($i = 0; $i < count($wholesale_application['permit_images']); $i++)
                                        th
                                        <th>
                                            Attachment {{$i+1}}
                                        </th>
                                    @endfor
                                </thead>
                                <tbody>
                                    <tr>
                                        @foreach ($wholesale_application['permit_images'] as $image)    
                                        <td>
                                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/wholesale/images/'.$image['permit_image']))) }}" alt="" width="100px" class="img-thumbnail image-zoom" style="max-height: 8rem !important;">
                                        </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-md-12">
                    <div class="card-header border-bottom-0">
                        <h4 class="card-title text-bold">
                            Parent Company
                        </h4>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <div class="col-md-12 shadow border p-0">
                                <table class="table table-border">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>
                                                Parent Company 
                                            </th>
                                            <th>
                                                Accounts Payable Name
                                            </th>
                                            <th>
                                                Accounts Payable Phone
                                            </th>
                                            <th>
                                                Accounts Payable Email
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                {{ !empty($wholesale_application['parent_company']) ? $wholesale_application['parent_company'] : '-'}}
                                            </td>
                                            <td>
                                                {{ !empty($wholesale_application['payable_name']) ? $wholesale_application['payable_name'] : '-' }}
                                            </td>
                                            <td>
                                                {{ !empty($wholesale_application['payable_phone']) ? $wholesale_application['payable_phone'] : '-' }}
                                            </td>
                                            <td>
                                                {{ !empty($wholesale_application['payable_email']) ? $wholesale_application['payable_email'] : '-' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @if(!empty($wholesale_application['wholesale_application_address']))
    
                    @foreach ($wholesale_application['wholesale_application_address'] as $address)
                    <div class="col-md-12">        
                        <div class="card-header border-0">
                            <h4 class="card-title text-bold">
                                {{$address['type']}}
                            </h4>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <div class="col-md-12 shadow border p-0">
                                    <table class="table table-border">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>
                                                    First Name
                                                </th>
                                                <th>
                                                    Last Name
                                                </th>
                                                <th>
                                                    Company Name
                                                </th>
                                                <th>
                                                    Street Address
                                                </th>
                                                <th>
                                                    Address 2 
                                                </th>
                                                <th>
                                                    City 
                                                </th>
                                                <th>
                                                    State 
                                                </th>
                                                <th>
                                                    Postal Code 
                                                </th>
                                                <th>
                                                    Phone
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    {{!empty($address['first_name']) ? $address['first_name'] : '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($address['last_name']) ? $address['last_name'] : '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($address['company_name']) ? $address['company_name'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($address['street_address']) ? $address['street_address'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($address['address2']) ? $address['address2'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($address['city']) ? $address['city'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($address['state']) ? $address['state'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($address['postal_code']) ? $address['postal_code'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($address['phone']) ? $address['phone'] :  '-'}}
                                                </td>
                                                
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
            <div class="row2 border-bottom">
                <div class="card-header border-0">
                    <div class="col-md-12 p-0">
                        <table class="divs_table">
                            <tr>
                                <td style="width: 60%;">
                                    <div class="col-md-6" >
                                        <p class="mb-0">COTFA-230-D REV.2 (8-17)</p>
                                        <h2 class="card-title text-bold">
                                            PARTIAL EXEMPTION CERTIFICATE QUALIFIED SALES AND PURCHASES OF FARM EQUIPMENT AND MACHINERY
                                        </h2>
                                    </div>
                                </td>
                                <td style="width: 40%;">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <h4 class="card-title text-bold">
                                                    STATE OF CALIFORNIA
                                                </h4>
                                            </div>
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <h4 class="card-title text-bold">
                                                    CALIFORNIA DEPARTMENT OF TAX AND FEE ADMINISTRATION
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        {{-- <div class="row p-2" style="display: flex;">
                            <div class="col-md-6" style="width: 50%;">
                                <p class="mb-0">COTFA-230-D REV.2 (8-17)</p>
                                <h2 class="card-title text-bold">
                                    PARTIAL EXEMPTION CERTIFICATE QUALIFIED SALES AND PURCHASES OF FARM EQUIPMENT AND MACHINERY
                                </h2>
                            </div>
                            <div class="col-md-6" style="width: 50%;">
                                <div class="row">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <h4 class="card-title text-bold">
                                            STATE OF CALIFORNIA
                                        </h4>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <h4 class="card-title text-bold">
                                            CALIFORNIA DEPARTMENT OF TAX AND FEE ADMINISTRATION
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                @if(!empty($wholesale_application['wholesale_application_regulation_detail']))
                @php
                    $regulation_detail = $wholesale_application['wholesale_application_regulation_detail'];
                @endphp
                    <div class="card-body pt-0">
                        <div class="col-md-12 p-0">
                            <div class="row p-3">
                                <h4 class="card-title text-bold">
                                    Regulation 1533.1
                                </h4>
                                <p class="regulation-para">
                                    <strong>NOTE:</strong> This is an exemption only from the state general fund portion of the sales and use tax rate. You are not relieved from your obligations for the local and district taxes on this transaction. This partial exemption also does not apply to any tax levied pursuant to Sections 6051.2 and 6201.2 of the Revenue and Taxation Code, or pursuant to Section 35 of article XIII of the California Constitution. This partial exemption also applies to lease payments made on or after September 1, 2001, for tangible personal property even if the lease agreement was entered into prior to September 1, 2001.
                                </p>
                            </div>
                            <div class="table-responsive p-2">
                                <table class="table table-border border">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>
                                                Seller Name
                                            </th>
                                            <th>
                                                Seller Address
                                            </th>
                                            <th>
                                                Purchaser Name
                                            </th>
                                            <th>
                                                Equipment and Machinery
                                            </th>
                                            <th>
                                                Signature
                                            </th>
                                            <th>
                                                Title
                                            </th>
                                            <th>
                                                Permit No
                                            </th>
                                            <th>
                                                Address
                                            </th>
                                            
                                            <th>
                                                Phone
                                            </th>
                                            <th>
                                                Date
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                {{!empty($regulation_detail['seller_name']) ? $regulation_detail['seller_name'] :  '-'}}
                                            </td>
                                            <td>
                                                {{!empty($regulation_detail['seller_address']) ? $regulation_detail['seller_address'] :  '-'}}
                                            </td>
                                            <td>
                                                {{!empty($regulation_detail['purchaser_company_name']) ? $regulation_detail['purchaser_company_name'] :  '-'}}
                                            </td>
                                            <td>
                                                {{!empty($regulation_detail['equipment_type']) ? $regulation_detail['equipment_type'] :  '-'}}
                                            </td>
                                            <td>
                                                {{!empty($regulation_detail['purchaser_signature']) ? $regulation_detail['purchaser_signature'] :  '-'}}
                                            </td>
                                            <td>
                                                {{!empty($regulation_detail['title']) ? $regulation_detail['title'] :  '-'}}
                                            </td>
                                            <td>
                                                {{!empty($regulation_detail['regulation_permit_number']) ? $regulation_detail['regulation_permit_number'] :  '-'}}
                                            </td>
                                            <td>
                                                {{!empty($regulation_detail['purchaser_address']) ? $regulation_detail['purchaser_address'] :  '-'}}
                                            </td>
                                            <td>
                                                {{!empty($regulation_detail['purchaser_phone']) ? $regulation_detail['purchaser_phone'] :  '-'}}
                                            </td>
                                            <td>
                                                {{!empty($regulation_detail['purchase_date']) ? $regulation_detail['purchase_date'] :  '-'}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-header border-0 p-0">
                            <div class="col-md-12">
                                <div class="row p-2">
                                    <h4 class="card-title text-bold">
                                        Certificate Eligibility:
                                    </h4>
                                    <p>
                                        <input type="checkbox" value="{{$regulation_detail['certificate_eligibility_1']}}" checked disabled>
                                        I, as the undersigned purchaser, hereby certify I am engaged in an agricultural business described in Codes 0111 to 0291 of the Standard Industrial Classification (SIC) Manual, or I perform an agricultural service described in Codes 0711 to 0783 of the SIC Manual for such classified persons. The property purchased or leased will be used primarily in producing and harvesting agricultural products in accordance with Revenue & Taxation Code Section 6356.5.
                                    </p>
            
                                    <p>
                                        <input type="checkbox" value="{{$regulation_detail['certificate_eligibility_2']}}" checked disabled>
                                        I understand that if such property is not used in the manner qualifying for the partial exemption, or if I am not a qualified person, as applicable, that I am required by the sales and use tax law to report and pay the state tax measured by the sales price/rentals payable of the property to/by me. I also understand that this partial exemption certificate is in effect as of the date shown below and will remain in effect until revoked in writing.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body  p-2">
                            <div class="col-md-12 p-0">
                                <div class="d-flex p-1 rules_regulation_div">
                                    <div class="">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mt-1" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M14.4866 12L9.15329 2.66665C9.037 2.46146 8.86836 2.29078 8.66457 2.17203C8.46078 2.05329 8.22915 1.99072 7.99329 1.99072C7.75743 1.99072 7.52579 2.05329 7.322 2.17203C7.11822 2.29078 6.94958 2.46146 6.83329 2.66665L1.49995 12C1.38241 12.2036 1.32077 12.4346 1.32129 12.6697C1.32181 12.9047 1.38447 13.1355 1.50292 13.3385C1.62136 13.5416 1.79138 13.7097 1.99575 13.8259C2.20011 13.942 2.43156 14.0021 2.66662 14H13.3333C13.5672 13.9997 13.797 13.938 13.9995 13.8208C14.202 13.7037 14.3701 13.5354 14.487 13.3327C14.6038 13.1301 14.6653 12.9002 14.6653 12.6663C14.6652 12.4324 14.6036 12.2026 14.4866 12Z" stroke="#A16207" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M8 6V8.66667" stroke="#A16207" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M8 11.3333H8.00667" stroke="#A16207" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <div class="ml-2 mt-1">
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
                @endif
            </div>
            <div class="row3 border-bottom">
                <div class="col-md-12 p-3">
                   <div class="card-header border-0">
                        <div class="row mb-1">
                            <h2 class="card-title text-bold">
                                ACH Authorization Form
                            </h2>
                        </div>
                        <div class="row">
                            <h4 class="card-title text-bold">
                                Credit/Debit Authorization Form
                            </h4>
                        </div>
                    </div>
                    @if(!empty($wholesale_application['wholesale_application_authorization_detail']))
                    @php
                        $authorization_detail = $wholesale_application['wholesale_application_authorization_detail']; 
                    @endphp
                        <div class="card-body p-2">
                            <div class="col-md-12">
                                <p class="">
                                    I (we) hereby authorize <strong>{{$authorization_detail['authorize_name']}}</strong> (THE COMPANY) to initiate entries to my (our) checking/savings accounts at the financial institution listed below (THE FINANCIAL INSTITUTION), and, if necessary, initiate adjustments for any transactions credited/debited in error. This authority will remain in effect until THE COMPANY is notified by me (us) in writing to cancel it in such time as to afford THE COMPANY and THE FINANCIAL INSTITUTION a reasonable opportunity to act on it.
                                </p>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-border border">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>
                                                    Name of Financial Institution
                                                </th>
                                                <th>
                                                    Address
                                                </th>
                                                <th>
                                                    Signature
                                                </th>
                                                <th>
                                                    Set amount
                                                </th>
                                                <th>
                                                    Maximum amount
                                                </th>
                                                <th>
                                                    Institute Routine No
                                                </th>
                                                <th>
                                                    Account No
                                                </th>
                                                <th>
                                                    Permit No
                                                </th>
                                                
                                                <th>
                                                    Phone
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    {{!empty($authorization_detail['financial_institute_name']) ? $authorization_detail['financial_institute_name'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($authorization_detail['financial_institute_address']) ? $authorization_detail['financial_institute_address'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($authorization_detail['financial_institute_signature']) ? $authorization_detail['financial_institute_signature'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($authorization_detail['set_amount']) ? $authorization_detail['set_amount'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($authorization_detail['maximum_amount']) ? $authorization_detail['maximum_amount'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($authorization_detail['financial_institute_routine_number']) ? $authorization_detail['financial_institute_routine_number'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($authorization_detail['financial_institute_account_number']) ? $authorization_detail['financial_institute_account_number'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($authorization_detail['financial_institute_permit_number']) ? $authorization_detail['financial_institute_permit_number'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($authorization_detail['financial_institute_phone_number']) ? $authorization_detail['financial_institute_phone_number'] :  '-'}}
                                                </td>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row4">
                <div class="col-md-12 p-3">
                    <div class="card-header pb-0 border-0">
                        <div class="row ">
                            <h2 class="card-title text-bold">
                                Credit Card Authorization Form
                            </h2>
                        </div>
                    </div>
                    @if(!empty($wholesale_application['wholesale_application_card']))
                    @php
                        $card_detail = $wholesale_application['wholesale_application_card']; 
                    @endphp
                        <div class="card-body p-2">
                            <div class="col-md-12 d-flex p-0">
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
                                <p class="ml-2">
                                    Please complete all the fields. You may cancel this authorization at any time by contacting us. This authorization will remain in effect until cancelled.
                                </p>
                            </div>
                            <div class="col-md-12 p-0">
                                <div class="table-responsive">
                                    <table class="table table-border border">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>
                                                    Card Type
                                                </th>
                                                <th>
                                                    Cardholder name
                                                </th>
                                                <th>
                                                    Card Number
                                                </th>
                                                <th>
                                                    Expiration Date
                                                </th>
                                                <th>
                                                    Cardholder ZIP Code
                                                </th>
                                                <th>
                                                    Customer Signature
                                                </th>
                                                
                                                <th>
                                                    Date
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    {{!empty($card_detail['card_type']) ? $card_detail['card_type'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($card_detail['cardholder_name']) ? $card_detail['cardholder_name'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($card_detail['card_number']) ? $card_detail['card_number'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($card_detail['expiration_date']) ? $card_detail['expiration_date'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($card_detail['cardholder_zip_code']) ? $card_detail['cardholder_zip_code'] :  '-'}}
                                                </td>
                                                
                                                <td>
                                                    {{!empty($card_detail['customer_signature']) ? $card_detail['customer_signature'] :  '-'}}
                                                </td>
                                                <td>
                                                    {{!empty($card_detail['date']) ? $card_detail['date'] :  '-'}}
                                                </td>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <p>
                                    I <strong> {{$card_detail['authorize_card_name']}}</strong>  , authorize  <strong>{{$card_detail['authorize_card_text']}}</strong>
                                    to charge my credit card above for agreed upon purchases, I understand that my information will be saved to file for future transactions on my account.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @else
        <div class="card mt-3">
            <h4 class="text-center">No Record Found</h4>
        </div>
        @endif
    </div>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        /* Define the font size for the entire document */
        body {
            font-size: 13px;
            font-family: 'Times New Roman', Times, serif;
        }
        .divs_table {
            width: 100%;
            border: none !important;
        }
        
    </style>
    
   
        
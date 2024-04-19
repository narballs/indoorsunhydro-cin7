@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')


@if ($message = Session::get('message'))
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif
<style>
    
    .customplaceholderclass {
        background: none !important;
        font-size: 18px;
        font-weight: 500 !important;
    }
    .dd_options {
        border: 1px solid #EBEBEB;
        background: #F5F5F5;
        width: 100%;
        /* color: #9A9A9A; */
        font-size: 18px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }
    .add_steric::after {
        content: "*";
        color: red;
        font-family: 'Poppins';
        position: absolute;
        top: 30%;
        left: 35%;
    }
    .add_steric_city::after {
        content: "*";
        color: red;
        font-family: 'Poppins';
        position: absolute;
        top: 30%;
        left: 60%;
    }
    
    @media screen and (min-width: 768) and (max-width : 895px)  {
        .continue_account_wholesale {
            margin-top: 0rem !important;
        }
        .dont-have-an-account-pra {
            margin-bottom: 0rem !important;
            padding-bottom: 0rem !important;
        }
    }
    select.form-control:not([size]):not([multiple]) {
        height: calc(2.25rem + 2px);
        width: 100%;
        background-color: lightgray !important;
        padding: 12px !important;
        border-radius: 2px !important;
        height: 49px;
    }
    .sign-up-fields:focus-visible {
        outline: none;
    }
    .sign-up-fields {
        border: 1px solid #EBEBEB;
        background: #F5F5F5;
        width: 100%;
        color: #9A9A9A;
        font-size: 18px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }
    .sign-up-fields::placeholder {
        color: #9A9A9A;
        font-size: 18px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }
    .step-btn-signup:focus-visible {
        outline: none;
    }
    .continue_sign_up_shopping:hover {
        text-decoration: none;
    }
    @media only screen and (min-width:280px) and (max-width:550px) {
        .sing-up-label {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 600 !important;
            font-size: 17px !important;
            line-height: 36px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #131313;
        }
        .sign-up-fields {
            padding:0.5rem !important;
            font-size: 13px !important;
        }
        .register_first_step {
            margin-top: 10px !important;
        }
        .margin-adjust-mbl {
            margin-top: 0.25rem !important;
        }
        .user_last_name {
            /* margin-top: 1rem !important; */
        }
        .step-btn-signup-div {
            margin-top:0.75rem !important;
        }
        #business-row {
            left:8.5%;
            height: 410px !important;
        }
        #address_info_sidebar {
            top:75% !important;
        }
        .margin-top-adjust-address {
            margin-top: 0.75rem !important;
        }
        .continue-shoping-mbl {
            height: 50% !important;
            width: 100% !important;
        }
        .continue_sign_up_shopping:hover {
            text-decoration: none !important;
        }
    }
</style>

<div class="container-fluid pl-0 pr-0">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 pl-0 pr-0">
        <div class="row">
            <div class="col-md-12 bg-light text-center d-none">
                <h2 style="color:#25529F; font-weight:700" class="text-center pt-5 ">Have an account</h2>
                <div class="col-md-4">
                    <p class="text-center pb-5" style="font-size: 16px">Your personal data will be used to support your
                        experience throughout this website, to manage access to your account, and for other purposes
                        described
                        in our privacy policy.</p>
                </div>
            </div>
            <div class="col-md-12 col-xs-6 mt-5 d-none" id="icons">
                <div class="icon-container d-flex">
                    <figure>
                        <img class="img-fluid" src="/theme/img/round-solid.png" id="sign-up">
                        <img class="img-fluid" src="/theme/img/white-arrow.png" style="margin-left: -41px;"
                            id="arrow">
                        <img class="img-fluid" src="/theme/img/line2.png" style="margin-left: 11px">
                        <figcaption id="sigup-bold" class="mt-3">Signup</figcaption>
                    </figure>
                    <figure>
                        <img class="img-fluid" src="/theme/img/round-border.png" id="company-round">
                        <img class="img-fluid" src="/theme/img/company.png" style="margin-left: -39px" id="building">
                        <img class="img-fluid" src="/theme/img/line2.png" style="margin-left: 11px">
                        <figcaption id="company-bold" class="mt-3">Company</figcaption>
                    </figure>
                    <figure>
                        <img class="img-fluid" src="/theme/img/round-border.png" id="timer">
                        <img class="img-fluid" src="/theme/img/location.png" style="margin-left: -37px" id="timer-main">
                        <img class="img-fluid" src="/theme/img/line2.png" style="margin-left: 11px">
                        <figcaption id="address-bold" class="mt-3">Location</figcaption>
                    </figure>
                    <figure>
                        <img src="/theme/img/round-border.png" id="finish-round">
                        <img class="img-fluid" src="/theme/img/finish.png" style="margin-left: -36px" id="tick">
                        <figcaption id=thankyou-bold class="mt-3">Finish</figcaption>
                    </figure>
                </div>
            </div>
        </div>
        <div class="row ml-0 pr-0 w-100 main_image_mbl" style="background-image: url('/theme/img/img_12.png');">
            <div class="login-reg-panel col-xs-6">
                @if(!empty($setting) && $setting->option_value == 'Yes')
                <div class="register-info-box text-center">
                    <h2 class=" dont-have-an-account">Don't have an account?</h2>
                    <p class=" dont-have-an-account-pra">Personal data will be used to support your experience
                        throughout
                        this website, to
                        manage access to your account, and for other purposes described in our privacy policy.</p>
                       <div class="d-flex justify-content-center">
                        <label id="label-login" for="log-login-show" class="sing-up-label d-flex justify-content-center align-items-center w-75 mb-0 p-0">
                            <span class="sign-up">SIGN UP</span>
                        </label>
                       </div>
                    <input type="radio" name="active-log-panel" id="log-login-show">
                </div>
                @else
                <div class="register-info-box text-center">
                    <h2 class=" dont-have-an-account">Don't have an account?</h2>
                    <p class=" dont-have-an-account-pra">We are not open for public at the moment. Please come back later. If you have any questions please contact us here..</p>
                    <div class="d-flex justify-content-center">
                        <label id="label-login" for="log-login-show" class="sing-up-label d-flex justify-content-center align-items-center w-75 mb-0 p-0">
                            <a href="{{url('contact-us')}}"><span class="sign-up">Contact us</span></a>
                        </label>
                    </div>
                </div>
                @endif
                <div class="white-panel" style="box-shadow: 0 0 0 0 !important;" id="main-white-panel">
                    <div class="login-show">
                        <h2 class="text-center login-title">LOGIN</h2>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-login">
                                <div class="input-placeholder">
                                    <input type="text" name="email" required class="login-inputMbl">
                                    <div class="placeholder pl-3 fontAwesome top_placeholder_style " style="background: none !important;left:0px !important;">
                                        &#xf0e0; Email
                                    </div>
                                </div>
                                <div class="input-placeholder">
                                    <input type="password" name="password" required class="login-inputMbl">
                                    <div class="placeholder pl-3 fontAwesome top_placeholder_style " style="background: none !important;left:0px !important;">
                                        &#xf023; Password
                                    </div>
                                </div>

                            </div>
                            <button type="submit" class="btn-login info login-button">LOGIN</button>

                            <div class="row mt-3 mb-1 align-items-center justify-content-between">
                                <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12 remember-me">
                                    <div class="form-check form-login  pl-4 d-flex align-items-center">
                                        <input class="mb-0 mt-0 form-check-input desktop-login align-items-center mbl-reme-check justify-conent-center d-flex"
                                            type="checkbox" value="" id="checkbox-2"
                                            style="background-color:none !important; width:20px; height:20px;" />
                                        <label class="ml-2 formemail-registration-check-label mb-0" for="checkbox-2" style="color:#9A9A9A;">Remember
                                            me</label>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12 lost_pas_div">
                                    <a href="{{ route('lost.password') }}" class="btn-lost-password p-0">Lost your
                                        password?</a>
                                </div>
                            </div>
                            <div class="row mx-auto mt-3 mbl-border-bottom" style="border-bottom:1px solid #EBEBEB;">

                            </div>
                        </form>
                        
                        <div class="row mt-2">
                            <div class=" col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <p class="required-field" style="color:#9A9A9A !important ;font-weight:400;"><span class="req">*</span> Required field</p>
                            </div>
                        </div>
                    </div>
                    <div class="register-show" style="margin-top: 56px;" id="register_first_step">
                        <form id="email-registration" class="form-signup">
                            @csrf
                            <h2 class="d-flex justify-content-center align-items-center sing-up-label">SIGN UP</h2>
                            <div class="input-placeholder">
                                <input type="text" id="email" name="email" required>
                                <div class="placeholder pl-3 fontAwesome top_placeholder_style " style="background: none !important;left:0px !important;">
                                    &#xf0e0; Email
                                </div>
                            </div>
                            <button type="button" name="save" id="save" onclick="signUp2()"
                                class="btn-login">SIGN UP</button>
                        </form>
                        <div class="row">
                            <div class="col-md-6 mt-5">
                                <p class="required-field" style="color:#9A9A9A !important ;font-weight:400;"><span class="req">*</span> Required field</p>
                            </div>
                        </div>
                        <div id="signup_error" class="text-danger"></div>
                    </div>
                </div>
                <div class="login-info-box text-white" id="login_sidebar">
                    <h2 class=" dont-have-an-account text-center">Have an account?</h2>
                    <p class=" dont-have-an-account-pra" id="account">Your
                        personal data will be used to support
                        your experience
                        throughout
                        this website, to manage access to your account, and for other purposes described in our privacy
                        policy.
                    </p>
                    <div class="d-flex justify-content-center">
                        <label onclick="hidesignup()" id="label-register" for="log-reg-show" class="d-flex justify-content-center align-items-center w-75 mb-0 p-0 sing-up-label login_for_web">LOGIN</label>
                    </div>
                    <input type="radio" name="active-log-panel" id="log-reg-show" checked="checked">
                </div>

                <div class="login-info-box text-white d-none" id="company_info_sidebar">
                    <h2 class=" dont-have-an-account text-center">Company Info</h2>
                    <p class=" dont-have-an-account-pra" id="account">
                        Please tell us some information about your company and website
                    </p>
                </div>

                <div class="login-info-box text-white d-none" id="address_info_sidebar">
                    <h2 class=" dont-have-an-account text-center">Address Info</h2>
                    <p class=" dont-have-an-account-pra" id="account">
                        Knowing where you are located is also helpful to approve your
                            account
                            faster.
                    </p>
                </div>

                <div class="row company-row bg-light">
                    <div class="white-panel d-none company-detail" id="company-detail"
                    style="box-shadow: 0 0 0px 0px;">
                        <div class="mt-5 margin-adjust-mbl">
                            <h2 class="text-center login-title" style="color: #393939;">
                                Company Detail
                            </h2>
                            <div class="">
                                <form action="">
                                    <input type="hidden" value="Pay in Advanced" id="paymentTerms">
                                    <div class="row justify-content-center">
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="input-placeholder col-md-12 mt-3">
                                                    <input type="text" name="company_name" id="company_name" required="" class="sign-up-fields p-3 pl-0 w-100">
                                                    <div class="placeholder pl-3 fontAwesome top_placeholder_style text-dark border-0 bg-none customplaceholderclass">
                                                        <i class="fa fa-building  ml-2 mr-2"></i>Company Name
                                                    </div>
                                                </div>
                                                <div class="text-danger" id="company_name_errors"></div>
                                                <div class="input-placeholder col-md-12 mt-3">
                                                    <input type="text" name="company_website" id="company_website" required="" class="sign-up-fields p-3 pl-0 w-100">
                                                    <div class="placeholder pl-3 fontAwesome_new top_placeholder_style text-dark border-0 bg-none customplaceholderclass">
                                                        <i class="fa fa-globe  ml-2 mr-2"></i>Company Website
                                                    </div>
                                                </div>
                                                <div class="text-danger" id="company_website_errors"></div>
                                                <div class="input-placeholder col-md-12 mt-3">
                                                    <input type="text" name="phone" id="phone" required="" class="sign-up-fields p-3 pl-0 w-100">
                                                    <div class="placeholder pl-3 fontAwesome top_placeholder_style text-dark border-0 bg-none customplaceholderclass">
                                                        <i class="fa fa-phone  ml-2 mr-2"></i>Phone
                                                    </div>
                                                </div>
                                                <div class="text-danger" id="phone_errors"></div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-10 mb-5 mt-5">
                                <input type="submit" value="SAVE AND CONTINUE"
                                    class="btn-login mb-5 w-100 step-btn-signup" onclick="loadAddress()">
                            </div>
                        </div>
                    </div>

                    <div class="white-panel d-none" id="login-form-section" style="box-shadow: 0 0 0px 0px;">
                       <div class="mt-5 margin-adjust-mbl">
                            <h2 class="text-center login-title" style="color: #393939;">SIGN UP</h2>
                            <form action="">
                                <div class="row justify-content-center">
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="input-placeholder col-md-6 mb-3">
                                                <input type="text" name="first_name" id="user_first_name" required="" class="sign-up-fields p-3 pl-0 w-100">
                                                <div class="placeholder pl-3 fontAwesome top_placeholder_style text-dark border-0 bg-none customplaceholderclass">
                                                    <i class="fa fa-user fa fa-user ml-2 mr-2"></i>First Name
                                                </div>
                                            </div>
                                            <div class="input-placeholder col-md-6 mb-3">
                                                <input type="text" name="last_name" id="user_last_name" required="" class="user_last_name sign-up-fields p-3 pl-0 w-100">
                                                <div class="placeholder pl-3 fontAwesome_new top_placeholder_style text-dark border-0 bg-none customplaceholderclass">
                                                    <i class="fa fa-user fa fa-user ml-2 mr-2"></i>Last Name
                                                </div>
                                            </div>
                                            <div class="text-danger" id="first_name_errors"></div>
                                        </div>
                                        <div class="row">
                                            <div class="input-placeholder col-md-12 mb-3">
                                                <input type="password" name="password" id="password" required="" class="user_password_signup sign-up-fields p-3 pl-0 w-100">
                                                <div class="placeholder pl-3 fontAwesome top_placeholder_style text-dark border-0 bg-none customplaceholderclass">
                                                    <i class="fa fa-lock fa fa-lock ml-2 mr-2"></i>Password
                                                </div>
                                            </div>
                                            <div class="text-danger" id="password_errors"></div>
                                            <div class="input-placeholder col-md-12">
                                                <input type="password" name="confirm_password" id="confirm_password" required="" class="sign-up-fields p-3 pl-0 w-100">
                                                <div class="placeholder pl-3 fontAwesome top_placeholder_style text-dark border-0 bg-none customplaceholderclass">
                                                    <i class="fa fa-lock fa fa-lock ml-2 mr-2"></i>Confirm Password
                                                </div>
                                            </div>
                                            <div class="text-danger" id="confirm_password_errors"></div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div id="user-info-error" class="text-danger"></div>
                            <div id="signup_error" class="text-danger"></div>
                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-12 mt-3 text-center step-btn-signup-div">
                                            <button type="submit" value="" class="step-btn-signup w-100 btn-login"
                                                onclick="signup()">
                                                SIGN UP & CONTINUE</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row white-panel bg-light d-none" id="business-row">
                    <div class="mt-4 margin-adjust-mbl">
                        <h2 class="text-center login-title" style="color: #393939;">Address info</h2>
                        
                        <div class="row_error text-danger"></div>
                        <div class="row company-address justify-content-center" id="address-form-section">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="input-placeholder col-md-12 mt-3">
                                        <input type="text" name="street_address" id="street_address" required="" class="sign-up-fields p-3 pl-0 w-100">
                                        <div class="placeholder pl-3 fontAwesome top_placeholder_style text-dark border-0 bg-none customplaceholderclass">
                                            <i class="fa fa-road  ml-2 mr-2"></i>Street Address, House no, Street Name
                                        </div>
                                        <div class="text-danger" id="street_address_errors"></div>
                                    </div>
                                    <div class="input-placeholder col-md-12 mt-2">
                                        <input type="text" name="suit_apartment" id="suit_apartment" required="" class="sign-up-fields p-3 pl-0 w-100">
                                        <div class="placeholder pl-3 fontAwesome_new  top_placeholder_style text-dark border-0 bg-none customplaceholderclass">
                                            <i class="fa fa-road  ml-2 mr-2"></i>Apartment, Suit, unit 
                                        </div>
                                    </div>
                                    <div class="input-placeholder col-md-12 mt-2 ">
                                        <div class="add_steric">
                                            <select id="state-dd" name="state" class="sign-up-fields p-3 pl-0 w-100" required style="">
                                                <option value="" class="">
                                                    Select State
                                                </option>
                                                @if (!empty($states))
                                                    @foreach ($states as $data)
                                                        <option value="{{ $data->id }}">
                                                            {{ $data->state_name }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <option value="">No State Found</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="text-danger" id="state_errors"></div>
                                    </div>
                                    <div class="input-placeholder col-md-6 mt-2">
                                        <div class="add_steric_city">
                                            <select id="city-dd"  name="city" class="sign-up-fields p-3 pl-0 w-100" style="" required>
                                                <option value="" name="town_city" class="">
                                                    Town/City 
                                                </option>
                                            </select>
                                        </div>
                                        <div class="text-danger" id="town_city_errors"></div>
                                    </div>
                                    <div class="input-placeholder col-md-6 mt-2">
                                        <input type="text" name="zip" id="zip" required="" class="sign-up-fields p-3 pl-0 w-100">
                                        <div class="placeholder pl-3 fontAwesome top_placeholder_style text-dark border-0 bg-none customplaceholderclass">
                                            <i class="fa fa-zip  ml-2 mr-2"></i>Zip
                                        </div>
                                        <div class="text-danger" id="zip_errors"></div>
                                    </div>
                                    <div id="address-info-error" class="text-danger"></div>
                                    <div class="col-md-12 margin-top-adjust-address mt-3">
                                        <div class="spinner-border d-none" role="status" id="sign_up_loader" style="position: absolute;top:18%;left:87% !important;color:#131313;">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <input type="button" value="SAVE AND CONTINUE"
                                            class="step-btn-signup btn-login  w-100" onclick="thankYou()">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-none mt-5 pt-5" id="thanks">
                    <div class="col-md-12 text-center">
                        <h2 class=" dont-have-an-account text-center">Thank You!</h2>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <p class="text-center col-md-12 pb-2  dont-have-an-account-pra">Please allow us
                                sometime to review your submission. You are currently eligible for retail prices. To buy at wholesale prices, continue
                                filling out your application at the link below
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-12 text-center mt-5 continue_account_wholesale">
                        <a href="{{ route('create_wholesale_account') }}" class="continue_sign_up_shopping d-flex justify-content-center">
                            <input style="color: #7BC533;" type="button" value="Apply For Wholesale account" class="continue-shoping-mbl border-0 sing-up-label d-flex justify-content-center align-items-center w-50 mb-0 p-0"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('partials.footer')
@include('partials.product-footer')
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script type="text/javascript">
    var input = document.getElementById("email");
    input.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            document.getElementById("save").click();
        }
    });

    function hidesignup() {
        // alert('hi');
        $('#login-form-section').addClass('d-none');
        $('#main-white-panel').removeClass('d-none');
    }

    function signUp2() {

        // $("#icons").removeClass('d-none');
        $('#sigup-bold').css('font-weight', '700');
        var email = $('#email').val();
        jQuery.ajax({
            method: 'post',
            url: "{{ url('/check/email') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "email": email,
            },
            success: function(response) {
                console.log(response.success);
                if (response.success == true) {
                    // $(".login-info-box").addClass('d-none');
                    // $(".white-panel").remove();
                    
                    // $('#user_first_name').addClass('d-none');
                    // $('.user_last_name').addClass('d-none');
                    $('#signup_error').html(response.msg);

                }else{
                    
                    
                    $(".company").show();
                    $(".white-panel").add();
                    $("#login-form-section").removeClass('d-none');
                    $("#company").removeClass('d-none');
                }
            },
            error: function(response) {
                console.log(response)

                var error_message = response.responseJSON;

                var error_text = '';
                error_text += error_message.message;

                error_text += '<br />' + error_message.errors.email[0];

                $('#signup_error').html(error_text);
            }
        });

    }

    function Error(error) {
        console.log(error)
        let errorMessages = [];
        let detailedMessages = [];
        let errorMessage = error.responseJSON;
        errorMessages.push(errorMessage);
        if (error.errors) {
            detailedMessages = [].concat.apply(
                [],
                Object.values(error.errors)
            );
            errorMessages = errorMessages.concat(detailedMessages);
        }

        let _messages = ''

        errorMessages.forEach(message => {
            _messages += message.message + "<br />"
        });

        return _messages.replace('The given data was invalid.', '')
    }

   

    function signup() {
        $('#sigup-bold').css('font-weight', 'normal');
        $('#company-bold').css('font-weight', '700');
        var first_name = $('input[name=first_name]').val();
        var last_name = $('input[name=last_name]').val();
        var password = $('.user_password_signup').val();
        var email = $('#email').val();
        var confirm_password = $('input[name=confirm_password]').val();
        jQuery.ajax({
            method: 'post',
            url: "{{ url('/register/basic/create') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                // "email": email,
                "first_name": first_name,
                "last_name": last_name,
                "password": password,
                "confirm_password": confirm_password

            },
            success: function(response) {
                console.log(response);
                // if (response.msg == 'Existing contact updated') {
                //     console.log(response.code);
                //     $("#thanks").removeClass('d-none');
                //     $(".address").hide();
                //     $(".company-row").addClass('d-none');
                //     $(".company-row").hide();
                // }
                if (response.success == true) {
                    $('#sign-up').attr('src', '/theme/img/round-border.png');
                    $('#arrow').attr('src', '/theme/img/arrow.png');
                    $('#company-round').attr('src', '/theme/img/round-solid.png');
                    $('#building').attr('src', '/theme/img/building-white.png');
                    $('#login-form-section').addClass('d-none');
                    $('#register_first_step').addClass('d-none');
                    $('#main-white-panel').addClass('d-none');
                    $(".company-loc").hide();
                    $("#company-detail").removeClass('d-none');
                    $('#login_sidebar').addClass('d-none');
                    $('#company_info_sidebar').removeClass('d-none');

                    // $("#thanks").removeClass('d-none');
                    // $(".address").hide();
                    // $(".company-row").addClass('d-none');
                    // $(".company-row").hide();
                    
                }else{
                    console.log(response.msg);
                    var error_message = response.responseJSON;

                    var error_text = '';
                    //error_text += error_message.message;
                    if (typeof error_message.errors.first_name != 'undefined') {
                        error_text = error_message.errors.first_name;
                        $('#first_name_errors').html(error_text);
                    } else {
                        error_text = '';
                        $('#first_name_errors').html(error_text);
                    }
                    // if (typeof error_message.errors.last_name != 'undefined') {
                    //     var error_text2 = error_message.errors.last_name;
                    //     $('#last_name_errors').html(error_text2);
                    // } else {
                    //     error_text2 = '';
                    //     $('#last_name_errors').html(error_text2);
                    // }
                    if (typeof error_message.errors.password != 'undefined') {
                        var error_text3 = error_message.errors.password;
                        $('#password_errors').html(error_text3);
                    } else {
                        error_text3 = ''
                        $('#password_errors').html(error_text3);
                    }
                    if (typeof error_message.errors.confirm_password != 'undefined') {
                        var error_text4 = error_message.errors.confirm_password;
                        $('#confirm_password_errors').html(error_text4);
                    } else {
                        $('#confirm_password_errors').html(error_text4);
                    }
                }
            },
            error: function(response) {
                // var error = Error(response)

                console.log(response)

                var error_message = response.responseJSON;

                var error_text = '';
                //error_text += error_message.message;
                if (typeof error_message.errors.first_name != 'undefined') {
                    error_text = error_message.errors.first_name;
                    $('#first_name_errors').html(error_text);
                } else {
                    error_text = '';
                    $('#first_name_errors').html(error_text);
                }
                if (typeof error_message.errors.last_name != 'undefined') {
                    var error_text2 = error_message.errors.last_name;
                    $('#last_name_errors').html(error_text2);
                } else {
                    error_text2 = '';
                    $('#last_name_errors').html(error_text2);
                }
                if (typeof error_message.errors.password != 'undefined') {
                    var error_text3 = error_message.errors.password;
                    $('#password_errors').html(error_text3);
                } else {
                    error_text3 = ''
                    $('#password_errors').html(error_text3);
                }
                if (typeof error_message.errors.confirm_password != 'undefined') {
                    var error_text4 = error_message.errors.confirm_password;
                    $('#confirm_password_errors').html(error_text4);
                } else {
                    $('#confirm_password_errors').html(error_text4);
                }

            },

        });


    }

    function loadAddress() {
        $('#company-bold').css('font-weight', 'normal');
        $('#address-bold').css('font-weight', '700');
        var company_website = $('input[name=company_website]').val();
        var company_name = $('input[name=company_name]').val();
        var phone = $('input[name=phone]').val();

        jQuery.ajax({
            method: 'post',
            url: "{{ url('/check/address/') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "company_website": company_website,
                "company_name": company_name,
                "phone": phone
            },
            success: function(response) {
                // if (response.code == 201) {
                //     $("#thanks").removeClass('d-none');
                //     $(".address").hide();
                //     $("#company-detail").addClass('d-none');

                // } else 
                if (response.success == true) {
                    $('#company-round').attr('src', '/theme/img/round-border.png');
                    $('#building').attr('src', '/theme/img/building.png');
                    $('#timer').attr('src', '/theme/img/round-solid.png');
                    $('#timer-main').attr('src', '/theme/img/timer-white.png');
                    $(".company-detail").hide();
                    $("#business-row").removeClass('d-none');
                    $('#login-form-section').addClass('d-none');
                    $('#company_info_sidebar').addClass('d-none');
                    $('#address_info_sidebar').removeClass('d-none');

                    // $("#thanks").removeClass('d-none');
                    $(".address").hide();
                    $("#company-detail").addClass('d-none');
                }else{
                    var error_message = response.responseJSON;
                    var error_text = '';
                    if (typeof error_message.errors.company_name != 'undefined') {
                        error_text = error_message.errors.company_name;
                        $('#company_name_errors').html(error_text);
                    } else {
                        error_text = '';
                        $('#company_name_errors').html(error_text);
                    }
                    if (typeof error_message.errors.phone != 'undefined') {
                        var error_text3 = error_message.errors.phone;
                        $('#phone_errors').html(error_text3)
                    } else {
                        error_text3 = '';
                    }
                    $('#company-info-error').html(error_text);
                }
            },
            error: function(response) {
                var error_message = response.responseJSON;
                var error_text = '';
                if (typeof error_message.errors.company_name != 'undefined') {
                    error_text = error_message.errors.company_name;
                    $('#company_name_errors').html(error_text);
                } else {
                    error_text = '';
                    $('#company_name_errors').html(error_text);
                }
                if (typeof error_message.errors.phone != 'undefined') {
                    var error_text3 = error_message.errors.phone;
                    $('#phone_errors').html(error_text3)
                } else {
                    error_text3 = '';
                }
                $('#company-info-error').html(error_text);
            },
        });
    }

    function thankYou() {
        $('#address-bold').css('font-weight', 'normal');
        $('#thankyou-bold').css('font-weight', '700');
        $('#user_first_name').removeClass('d-none');
        $('.user_last_name').removeClass('d-none');
        $('#sign_up_loader').removeClass('d-none');
        var street_address = $('input[name=street_address]').val();
        var suit_apartment = $('input[name=suit_apartment]').val();
        var state = $('#state-dd').val();
        var town_city_address = $('#city-dd').val();
        var zip = $('input[name=zip]').val();
        var company_website = $('input[name=company_website]').val();
        var company_name = $('input[name=company_name]').val();
        var phone = $('input[name=phone]').val();
        var first_name = $('input[name=first_name]').val();
        var last_name = $('input[name=last_name]').val();
        var password = $('.user_password_signup').val();
        var email = $('#email').val();
        var confirm_password = $('input[name=confirm_password]').val();
        var paymentTerms = $('#paymentTerms').val();
        jQuery.ajax({
            method: 'post',
            url: "{{ url('/user-contact/') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "email": email,
                "first_name": first_name,
                "last_name": last_name,
                "password": password,
                "confirm_password": confirm_password,
                "company_website": company_website,
                "company_name": company_name,
                "phone": phone,
                "street_address": street_address,
                "suit_apartment": suit_apartment,
                "city_id": town_city_address,
                "state_id": state,
                "zip": zip,
                'paymentTerms':paymentTerms
            },
            success: function(response) {
                if (response.success == true) {
                    $('#sign_up_loader').addClass('d-none');
                    $('#timer').attr('src', '/theme/img/round-border.png');
                    $('#timer-main').attr('src', '/theme/img/location.png');
                    $('#finish-round').attr('src', '/theme/img/round-solid.png');
                    $('#tick').attr('src', '/theme/img/white-tick.png');
                    $(".business-row").hide();
                    $("#thanks").removeClass('d-none');
                    $(".address").hide();
                    $(".company-address").hide();
                    $(".white-panel").addClass('d-none');
                    $("#thanks").removeClass('d-none');
                    $('#user_first_name').removeClass('d-none');
                    $('.user_last_name').removeClass('d-none');
                    $('login-form-section').addClass('d-none');
                    $('#company_info_sidebar').addClass('d-none');
                    $('#address_info_sidebar').addClass('d-none');
                    
                }
                else{
                    if (response.address_validator == false) {
                        $('#sign_up_loader').addClass('d-none');
                        $('.row_error').html(response.address_validation_message);
                    }
                    var error_message = response.responseJSON;
                    var error_text = '';
                    if (typeof error_message.errors.street_address != 'undefined') {
                        error_text = error_message.errors.street_address;
                        $('#street_address_errors').html(error_text);
                    } else {
                        error_text = '';
                        $('#street_address_errors').html(error_text);
                    }
                    if (typeof error_message.errors.suit_apartment != 'undefined') {
                        var error_text2 = error_message.errors.suit_apartment;
                        $('#suit_apartment_errors').html(error_text2);
                    } else {
                        error_text2 = '';
                        $('#suit_apartment_errors').html(error_text2);
                    }

                    if (typeof error_message.errors.city_id != 'undefined') {
                        var error_text3 = error_message.errors.city_id;
                        $('#town_city_errors').html(error_text3);
                    } else {
                        error_text3 = '';
                        $('#town_city_errors').html(error_text3);
                    }
                    if (typeof error_message.errors.state_id != 'undefined') {
                        var error_text4 = error_message.errors.state_id;
                        $('#state_errors').html(error_text4);
                    } else {
                        error_text4 = '';
                        $('#state_errors').html(error_text4);
                    }
                    if (typeof error_message.errors.zip != 'undefined') {
                        var error_text5 = error_message.errors.zip;
                        $('#zip_errors').html(error_text5);
                    } else {
                        error_text5 = '';
                        $('#zip_errors').html(error_text5);
                    }

                }

            },
            error: function(response) {
                if (response.address_validation_flag == false) {
                    $('#sign_up_loader').addClass('d-none');
                    $('.row_error').html(response.validatedAddress_message);
                }
                var error_message = response.responseJSON;
                var error_text = '';
                if (typeof error_message.errors.street_address != 'undefined') {
                    error_text = error_message.errors.street_address;
                    $('#street_address_errors').html(error_text);
                } else {
                    error_text = '';
                    $('#street_address_errors').html(error_text);
                }
                if (typeof error_message.errors.suit_apartment != 'undefined') {
                    var error_text2 = error_message.errors.suit_apartment;
                    $('#suit_apartment_errors').html(error_text2);
                } else {
                    error_text2 = '';
                    $('#suit_apartment_errors').html(error_text2);
                }

                if (typeof error_message.errors.city_id != 'undefined') {
                    var error_text3 = error_message.errors.city_id;
                    $('#town_city_errors').html(error_text3);
                } else {
                    error_text3 = '';
                    $('#town_city_errors').html(error_text3);
                }
                if (typeof error_message.errors.state_id != 'undefined') {
                    var error_text4 = error_message.errors.state_id;
                    $('#state_errors').html(error_text4);
                } else {
                    error_text4 = '';
                    $('#state_errors').html(error_text4);
                }
                if (typeof error_message.errors.zip != 'undefined') {
                    var error_text5 = error_message.errors.zip;
                    $('#zip_errors').html(error_text5);
                } else {
                    error_text5 = '';
                    $('#zip_errors').html(error_text5);
                }

            },
        });
    }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#state-dd').on('change', function() {
            $('#city-dd').on('change', function() {
                var cityId = this.value;
                // alert(cityId);
            });
            var idState = this.value;
            $("#city-dd").html('');
            $.ajax({
                url: "{{ url('api/fetch-cities') }}",
                type: "POST",
                data: {
                    state_id: idState,
                    // city_id: cityId,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#city-dd').html(
                        '<option value="" placeholder="&#xf5a0;  Town/City" name="town_city"class="form-control mt-2 company-info fontAwesome"> &#xf5a0; Town/City</option>'
                    );
                    $.each(result.cities, function(key, value) {
                        $("#city-dd").append('<option value="' + value
                            .id + '">' + value.city + '</option>');
                    });
                    // $('#zip-dd').html('<option value="">Select Zip</option>');
                }
            });
        });
        // $('#city-dd').on('change', function () {
        //     var idZip = this.value;
        //     $("#zip-dd").html('');
        //     $.ajax({
        //         url: "{{ url('api/fetch-zip') }}",
        //         type: "POST",
        //         data: {
        //             city_id: ididZip,
        //             _token: '{{ csrf_token() }}'
        //         },
        //         dataType: 'json',
        //         success: function (res) {
        //             $('#zip-dd').html('<option value="">Select City</option>');
        //             $.each(res.zip, function (key, value) {
        //                 $("#zip-dd").append('<option value="' + value
        //                     .id + '">' + value.name + '</option>');
        //             });
        //         }
        //     });
        // });
    });
</script>

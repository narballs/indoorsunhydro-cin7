@include('partials.header')
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

    @media screen and (min-width: 768) and (max-width : 895px) {
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
            padding: 0.5rem !important;
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
            margin-top: 0.75rem !important;
        }

        #business-row {
            left: 8.5%;
            height: 410px !important;
        }

        #address_info_sidebar {
            top: 75% !important;
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

@include('partials.top-bar')
@include('partials.search-bar')
<div class="container-fluid pl-0 pr-0">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 pl-0 pr-0">
        <div class="row ml-0 pr-0 w-100 main_image_mbl" style="background-image: url('/theme/img/img_12.png');">
            <div class="login-reg-panel col-xs-6">
                <div class="mt-5 pt-5" id="">
                    <div class="col-md-12 text-center">
                        <h2 class=" dont-have-an-account text-center">Thank You!</h2>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <p class="text-center col-md-12 pb-2  dont-have-an-account-pra">Please allow us
                                sometime to review your submission. You are currently eligible for retail prices. To buy
                                at wholesale prices, continue
                                filling out your application at the link below
                            </p>
                        </div>
                    </div>

                    <div class="col-md-12 text-center mt-5 continue_account_wholesale">
                        <a href="{{ url('contact-us') }}"
                            class="continue_sign_up_shopping d-flex justify-content-center">
                            <input style="color: #7BC533;" type="button" value="Apply For Wholesale account"
                                class="continue-shoping-mbl border-0 sing-up-label d-flex justify-content-center align-items-center w-50 mb-0 p-0"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.footer')
@include('partials.product-footer')
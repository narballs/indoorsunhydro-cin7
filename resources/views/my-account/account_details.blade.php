@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<style>
    .nav .active {
        background: #F5F5F5;
        /* border-left: none !important; */
        /* color: green !important; */
        color: #008AD0 !important;
    }

    nav svg {
        max-height: 20px !important;
    }

    #spinner-global {
        display: none !important;
    }

    input[type=number]::-webkit-outer-spin-button {

        opacity: 20;

    }
</style>
<div class="mb-5">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
        MY ACCOUNT
    </p>
</div>
<div class="container-fluid" style="width:1621px  !important;">
    <div class="row bg-light">
        <div class="container-fluid" id="main-row">
            @include('my-account.my-account-top-bar')
            <div class="row flex-xl-nowrap p-0 m-0 mr-3">
                @include('my-account.my-account-side-bar')

                <div class="col-xl-10 col-sm-12 col-xs-12 py-3 bg-white ms-3" style="border-radius: 10px !important;">
                    <div class="customer-details pr-2" id="customer-address">
                        <div class="row mt-3 detail-heading ms-2 mr-0 ml-0 p-0" id="detail-heading">
                            <div class="col-md-12 border-bottom border-4 p-0 mr-3">
                                <div class="row mb-4 mt-3">
                                    <div class="col-md-4 ">
                                        <img src="/theme/img/account_details.png" style="margin: -1px 2px 1px 1px;">
                                        <span class="pt-1 my-account-content-heading">Account Details</span>
                                    </div>
                                    <div class="col-md-8">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 pl-0 pr-0 mr-0 ml-0 w-100">
                            <div class="row mt-4  mr-0 ml-0 align-items-center">
                                <div class="col-auto">
                                    <!-- <label for="inputPassword6" class="col-form-label">Password</label> -->
                                </div>
                                <div class="bg-success text-white" id="updated-success"
                                    style="background-color: #7BC743 !important;">
                                </div>
                                @csrf
                                <div class="col-md-6 pl-0 mt-2 ">
                                    <label for="first_name" class="col-form-label dashboard-content">First
                                        Name</label>
                                    <input type="text" id="first_name" name="first_name"
                                        value="{{ $user_address->firstName }}" class="bg-light form-control">
                                </div>
                                <div class="col-md-6 mt-2 pr-0">
                                    <label for="last_name" class="col-form-label dashboard-content">Last
                                        Name</label>
                                    <input type="text" id="last_name" name="last_name"
                                        value="{{ $user_address->lastName }}" class="form-control bg-light">
                                </div>
                                <div class="col-md-12 mt-2 pl-0 pr-0">
                                    <label for="last_name" class="col-form-label dashboard-content">Email</label>
                                    <input type="text" id="email_address" value="{{ $user->email }}"
                                        name="email_address" class="form-control bg-light">
                                </div>
                            </div>
                        </div>
                        <div class="border-bottom border-4 mt-3 pt-4"></div>
                        <div class="row align-items-center mt-4">
                            <div class="col-auto">

                            </div>
                            <div class="billing-address-heading-subtitle ms-3 pl-0">Password Change</div>
                            <div class="col-md-12">
                                <label for="first_name" class="col-form-label dashboard-content">Current password
                                    <span class="text-uppercase">(<i class="unchanged-blank">leave blank to leave
                                            unchanged</i>)</span></label>
                                <div class="password-container">


                                    <input type="password" id="current_password" name="current_password"
                                        class="fontAwesome form-control bg-light" placeholder="">
                                    <i class="text-dark eye fa-solid fa-eye"
                                        onclick="showHidePassword('current_password')" id="eye"></i>
                                    <div class='text-danger' id="current_password_error"></div>
                                </div>
                            </div>

                            <div class="text-danger" id="password-match-fail"></div>
                            <div class="col-md-6">
                                <label for="first_name" class="col-form-label dashboard-content">New Password (<i
                                        class="unchanged-blank">LEAVE BLANK TO LEAVE UNCHANGED</i>)</label>
                                <div class="password-container">
                                    <input type="password" id="new_password" name="new_password"
                                        class="bg-light form-control ms-1">
                                    <i class="text-dark eye fa-solid fa-eye"
                                        onclick="showHidePassword('new_password')" id="eye2"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="first_name" class="col-form-label dashboard-content">Confirm New
                                    Password</label>
                                <div class="password-container">
                                    <input type="password" id="new_confirm_password" name="new_confirm_password"
                                        class="bg-light form-control ms-1">
                                    <i class="text-dark eye fa-solid fa-eye" id="eye2"
                                        onclick="showHidePassword('new_confirm_password')"></i>
                                </div>
                            </div>
                            <div class="text-danger" id="errors_password_comfimation"></div>
                            <div class="mt-5 ms-2">
                                <button type="button" class="btn-save btn col-md-2 text-align-middle p-0"
                                    value="Save" onclick="change_password()">SAVE CHANGES</button>
                            </div>
                        </div>

                        <div class="row ms-2 mb-5 d-none" id="address_row">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-9 pl-1">
                                <div class="row mt-3 " style="margin:auto;">
                                    <div class="col bg-white mr-3" style="border-radius: 10px !important;">
                                        <div class="mt-4 mb-4"><img src="/theme/img/user_address.png"><span
                                                class="billing-address-heading-subtitle pt-2 ms-2 align-middle address-weight">Order
                                                Details</span>
                                        </div>
                                        <div class="border-bottom"></div>
                                        <div id="address_table" class="mt-3 mb-4"></div>
                                    </div>
                                    <div class="col pl-1 bg-white"
                                        style="border-radius: 10px; border: 1px solid #008AD0!important;">
                                        <div class="mt-4 mb-4 ms-3"><img src="/theme/img/shipping_address2.png">
                                            <span
                                                class="billing-address-heading-subtitle pt-2 ms-2 align-middle address-weight">
                                                Order
                                                Details</span>
                                        </div>
                                        <div class="border-bottom ms-3"></div>
                                        <div class="ms-3">
                                            <div id="shipping_table" class="mt-3 mb-4"></div>
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
@include('my-account.my-account-scripts')
@include('partials.product-footer')
<!-- End of .container -->
@include('partials.footer')

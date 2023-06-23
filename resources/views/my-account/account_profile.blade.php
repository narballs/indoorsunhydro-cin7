@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
@section('account-active', 'active')

<div class="col-md-12 p-0">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
        MY ACCOUNT
    </p>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 m-auto">
            <div class="row">
                <div class="col-md-6 pt-3 ps-0 rounded-5">
                    @include('my-account.my-account-side-bar')
                </div>
                <div class="row">
                    <div class="col-md-12 p-0">
                        <div class="card my-3">
                            @if (session('success'))
                                <div class="col-md-6 m-auto pt-5 ">

                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                </div>
                            @endif
                            <div class="card-header bg-white">
                                <p class="account_detais_profile ps-2 mb-0">Account Details</p>
                            </div>
                            <div class="card-boday">
                                <form action="{{ route('account_profile_update') }}" method="POST"
                                    id="edit_profile_form">
                                    @csrf
                                    <div class="row p-4">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1"
                                                    class="my_profile_form_label form-label">First
                                                    Name</label>
                                                <input type="text" class="my_profile_form form-control"
                                                    id="exampleInputEmail1" aria-describedby="firstNAmeHelp"
                                                    placeholder="Enter your first name"
                                                    value="{{ $user->contact[0]['firstName'] }}" name="firstName">
                                                <span>
                                                    @error('firstName')
                                                        <div class="text-danger">{{ $errors->first('firstName') }}</div>
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1"
                                                    class="my_profile_form_label form-label">Last Name</label>
                                                <input type="text" class="form-control my_profile_form"
                                                    id="exampleInputEmail1" aria-describedby="lastNameHelp"
                                                    placeholder="Enter your last name"
                                                    value="{{ $user->contact[0]['lastName'] }}" name="lastName">
                                                <span>
                                                    @error('lastName')
                                                        <div class="text-danger">{{ $errors->first('lastName') }}</div>
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1"
                                                    class="my_profile_form_label form-label">Email</label>
                                                <input type="email" class="form-control my_profile_form"
                                                    id="exampleInputEmail1" aria-describedby="emailHelp"
                                                    placeholder="you@company.com" value="{{ $user->email }}"
                                                    name="email">
                                                <span>
                                                    @error('email')
                                                        <div class="text-danger">{{ $errors->first('email') }}</div>
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1"
                                                    class="my_profile_form_label form-label">Phone number</label>
                                                <input type="number" class="form-control my_profile_form"
                                                    id="exampleInputEmail1" aria-describedby="phoneNumberHelp"
                                                    placeholder="+1 (555) 000-0000"
                                                    value="{{ $user->contact[0]['phone'] }}" name="phone">
                                                <span>
                                                    @error('phone')
                                                        <div class="text-danger">{{ $errors->first('phone') }}</div>
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1"
                                                    class="my_profile_form_label form-label">Old
                                                    Password</label>
                                                <input type="password" class="form-control my_profile_form disabled"
                                                    id="exampleInputEmail1" aria-describedby="emailHelp"
                                                    value="{{ $user->password }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1"
                                                    class="my_profile_form_label form-label">New
                                                    Password</label>
                                                <input type="password" class="form-control my_profile_form"
                                                    id="exampleInputEmail1" aria-describedby="emailHelp"
                                                    placeholder="Enter your new password" value=""
                                                    name="password">
                                                <span>
                                                    @error('password')
                                                        <div class="text-danger">Enter Your New password</div>
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1"
                                                    class="my_profile_form_label form-label">Confirm New
                                                    Password</label>
                                                <input type="password" class="form-control my_profile_form"
                                                    id="exampleInputEmail1" aria-describedby="emailHelp"
                                                    placeholder="Confirm your new password" value=""
                                                    name="password_confirmation">
                                                <span>
                                                    @error('password_confirmation')
                                                        <div class="text-danger">Enter Your Confirm password</div>
                                                    @enderror
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" id="edit_savechange_btn"
                                                        class="btn profile_save_changes w-100 mb-5"> Save
                                                        Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id" value="{{ $user->id }}">
                                </form>
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
@include('partials.footer')

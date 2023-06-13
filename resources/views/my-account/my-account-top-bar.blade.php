<div class="row my-2" style="border-radius: 0.5rem !important;margin:auto">
    <div class="col-md-6 col-xl-6 col-xs-12 col-sm-12">
        <div class="row bg-white">
            <div class="col-md-2">
            </div>
            <div class="col-md-4 text-left mt-2">
                <span class="d-block my-acount-profile text-capitalize">{{ $user->first_name }}
                    {{ $user->last_name }}</span>
                <span class="d-block" style="font-family: Roboto">{{ $user->email }}</span>
                <div class="col-md-12 ps-0">
                    @php
                        $session_contact_id = Session::get('contact_id');
                    @endphp
                    <form action="">
                        <select class="form-select" name="company_switch" id="company_switch"
                            onchange="switch_company()" aria-label="Default select example"
                            style="background: #F4FFEC !important;">
                            <option class="form-select">Select one company</option>
                            @foreach ($companies as $company)
                                @php
                                    if ($company->contact_id) {
                                        $contact_id = $company->contact_id;
                                        $primary = '(primary)';
                                    } else {
                                        $contact_id = $company->secondary_id;
                                        $primary = '(secondary)';
                                    }
                                @endphp
                                <option class="form-control" value="{{ $contact_id }}"
                                    {{ $session_contact_id == $contact_id ? 'selected' : '' }}>
                                    {{ $company->company }}{{ $primary }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                </div>
            </div>
            <div class="col-md-6 col-xl-6 col-xs-12 col-12 col-sm-12">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-6 col-xs-12 col-sm-12 p-0 align-middle d-flex justify-content-center align-items-center"
        style="background: #F4FFEC; color: #7BC743; border-top-right-radius: 0.5rem !important;border-bottom-right-radius: 0.5rem !important">
        <span style="font-family: 'Roboto';font-style: normal;font-weight: 500;font-size: 40px;">
            My Account
        </span>
    </div>
</div>
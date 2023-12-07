@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
@section('users-active', 'active')

<div class="col-md-12 p-0">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle my-account-main-heading">
        MY ACCOUNT
    </p>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 m-auto">
            <div class="row">
                <div class="col-md-6 pt-4">
                    @include('my-account.my-account-side-bar')
                </div>
            </div>
            @if(count($all_companies) > 0)
                <div class="accordion mt-3" id="accordionExample">
                    @foreach ($all_companies as $company)
                       
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{$company->id}}">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$company->id}}" aria-expanded="true" aria-controls="collapse{{$company->id}}">
                                {{ucfirst($company->company)}}
                                </button>
                            </h2>
                            <div id="collapse{{$company->id}}" class="accordion-collapse collapse show" aria-labelledby="heading{{$company->id}}" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-12 my-3">
                                            <div class="card">
                                                <div class="card-header bg-white">
                                                    <p class="my_account_users_heading mb-0 mx-2">Users</p>
                                                </div>
                                                <div class="card-body">
                                                    @php
                                                       $secondary_contacts = App\Models\Contact::withTrashed()->with('allow_user')->where('company', $company->company)->get();
                                                    @endphp
                                                    <div class="table-responsive">
                                                        <table class="table address-table-items-data m-0 ">
                                                            <thead class="border-bottom-0 border-bottom-0">
                                                                <tr class="table-header-background">
                                                                    <td class="table-row-item">
                                                                        <div class="custom-control custom-checkbox tabel-checkbox d-flex align-items-center">
                                                                            {{-- <input
                                                                                class="custom-control-input custom-control-input-success checkbox-table"
                                                                                type="checkbox" id="selectAll" value="">
                                                                            <label for="selectAll" class="custom-control-label"></label> --}}
                                                                            <span class="table-row-heading-order">
                                                                                <i class="fas fa-arrow-up" style="font-size:14.5px ;"></i>
                                                                            </span>
                                                                        </div>
                                                                    </td>
                                                                    <td class="my_account_addresses">Name</td>
                                                                    <td class="my_account_addresses">Email</td>
                                                                    <td class="my_account_addresses">Job Title</td>
                                                                    <td class="my_account_addresses">Type</td>
                                                                    <td class="my_account_addresses">Company</td>
                                                                    <td class="my_account_addresses">Phone Number</td>
                                                                    <td class="my_account_addresses">Balance Owing</td>
                                                                    <td class="my_account_addresses">Credit Limit</td>
                                                                    <td class="my_account_addresses">Allow Access</td>
                                                                    <td></td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($secondary_contacts as $key => $contact)
                                                                    <tr class="{{!empty($contact->is_deleted) ? 'bg-deleted' : ''}}" title="{{!empty($contact->is_deleted) ?  'deleted on ' . $contact->is_deleted : ''}}">
                                                                        <td class="table-items justify-content-start align-items-lg-center user_table_items pt-0 align-middle">
                                                                            <div class="custom-control custom-checkbox tabel-checkbox d-flex align-items-center">
                                                                                {{-- <input
                                                                                    class="custom-control-input custom-control-input-success sub_chk"
                                                                                    data-id="{{ $contact->id }}" type="checkbox"
                                                                                    id="separate_check_{{ $contact->id }}"> --}}
                                                                                {{-- <label for="separate_check_{{ $contact->id }}"
                                                                                    class="custom-control-label"></label> --}}
                                                                                <span> {{ $key + 1 }}</span>
                                                                            </div>
                                                                        </td>
                                                                        <td class="my_account_address_items" style="vertical-align: middle;">
                                                                            @if (!empty($contact['firstName'] && $contact['lastName']))
                                                                                {{ $contact['firstName'] . ' ' . $contact['lastName'] }}
                                                                            @else
                                                                            @endif
                                                                        </td>
                                                                        <td class="my_account_all_items" style="vertical-align: middle;">
                                                                            @if (!empty($contact['email']))
                                                                                {{ $contact['email'] }}
                                                                            @else
                                                                            @endif
                                                                        </td>
                                                                        <td class="my_account_all_items" style="vertical-align: middle;">
                                                                            @if (!empty($contact['jobTitle']))
                                                                                {{ $contact['jobTitle'] }}
                                                                            @else
                                                                            @endif
                                                                        </td>
                                                                        {{-- <td class="my_account_all_items" style="vertical-align: middle;">
                                                                            <a href="{{ url('send-password/fornt-end/' . $contact->user_id) }}"
                                                                                class="btn bg-white ps-0">Send Password</a>
                                                                        </td> --}}
                                                                        <td class="my_account_all_items" style="vertical-align: middle;">
                                                                            @if (!empty($contact['contact_id']))
                                                                                Primary contact
                                                                            @else
                                                                                Secondary contact
                                                                            @endif
                                                                        </td>
                                                                        <td class="my_account_all_items" style="vertical-align: middle;">
                                                                            @if (!empty($contact['company']))
                                                                                {{$contact['company']}}
                                                                            @else
                                                                                
                                                                            @endif
                                                                        </td>
                                                                        <td class="my_account_all_items" style="vertical-align: middle;">
                                                                            @if (!empty($contact['phone']))
                                                                                {{ $contact['phone'] }}
                                                                            @elseif(!empty($contact['mobile']))
                                                                                {{ $contact['mobile'] }}
                                                                            @else
                                                                            @endif
                                                                        </td>
                                                                        <td class="my_account_all_items" style="vertical-align: middle;">
                                                                            {{ !empty($contact['contact_id']) ? '$' .  number_format($contact['balance_owing'], 2) : "" }}
                                                                        </td>
                                                                         <td class="my_account_all_items" style="vertical-align: middle;">
                                                                            ${{ number_format($contact['credit_limit'], 2) }}
                                                                        </td>
                                                                        <td class="my_account_all_items align-middle text-center">
                                                                            @if (!empty($user['contact'] && $user['contact'][0]['contact_id'] != null))
                                                                                <div class="form-check form-switch">
                                                                                    <input class="form-check-input allow_access" type="checkbox" role="switch" id="allow_access{{$contact['id']}}" {{!empty($contact['allow_user'] && $contact['allow_user']['allow_access'] == 1) ? 'checked' : ''}} value="{{!empty($contact['allow_user']) ? $contact['allow_user']['allow_access'] : 0}}" onclick="allow_access('{{$contact['id']}}')">
                                                                                </div>
                                                                            @else
                                                                                <div class="form-check form-switch">
                                                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDisabled" disabled>
                                                                                </div>
                                                                            @endif
                                                                        </td>
                                                                        {{-- <td style="vertical-align: middle;">
                                                                            <img src="/theme/img/dots_icons.png" alt="">
                                                                        </td> --}}
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    {{-- <tfoot>
                                                        <tr>
                                                            <td colspan="12">
                                                                {{ $secondary_contacts->links('pagination.custom_pagination') }}
                                                            </td>
                                                        </tr>
                                                    </tfoot> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    @endforeach
                </div>
            @else
                <h3>
                    No company found
                </h3>
            @endif
            
        </div>
    </div>
</div>
<style>
    .bg-deleted {
        background-color: #d3d3d3 !important;
    }
</style>
<script>
     function allow_access(id) {
        var allow_access = document.querySelector('#allow_access'+id);
        if (allow_access.checked == true) {
            allow_access.value = 1;
            allow_access.attributes.checked = 'checked';
        } else {
            allow_access.value = 0;
            allow_access.attributes.checked = '';
        }
        var access_value = allow_access.value;
        var contact_primary_id = id;
        jQuery.ajax({
            url: "{{ url('/my-account/allow-access/') }}",
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "contact_primary_id": contact_primary_id,
                "access_value": access_value
            },
            success: function(response) {
                if (response.status == 'success') {
                    if (response.data.allow_access == 1) {
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            title: 'Access allowed successfully',
                            timer: 2000,
                            showConfirmButton: false,
                            position: 'top',
                            timerProgressBar: true
                        });
                    } else {
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            title: 'Access denied successfully',
                            timer: 2000,
                            showConfirmButton: false,
                            position: 'top',
                            timerProgressBar: true
                        });
                    }
                }  else {
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: response.message,
                        timer: 2000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
                    });
                }
                
            }
        });
    }
</script>
@include('my-account.my-account-scripts')
@include('partials.product-footer')
@include('partials.footer')

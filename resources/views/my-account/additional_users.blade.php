@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
@section('users-active', 'active')

<div class="col-md-12 p-0">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
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
                {{-- <div class="col-md-6 py-4">
                    <div class="row search_row_my_account_page">
                        <div class="col-md-10 d-flex ">
                            <div class="has-search my_account_search w-100 ">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="#" class="mb-2">
                                    <input type="text" class="form-control border-0" id="search" name="search"
                                        placeholder="Search all orders" value="{{ isset($search) ? $search : '' }}" />
                            </div>
                            <div class="ps-3">
                                <button type="button" class="btn my_account_search_btn">Search</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div> --}}
            </div>
            <div class="row">
                <div class="col-md-12 my-3">
                    <div class="card">
                        <div class="card-header bg-white">
                            <p class="my_account_users_heading mb-0">Users</p>
                        </div>
                        <div class="card-boday">
                            <table class="table address-table-items-data m-0 ">
                                <thead class="border-bottom-0 border-bottom-0">
                                    <tr class="table-header-background">
                                        <td class="table-row-item">
                                            <div class="custom-control custom-checkbox tabel-checkbox">
                                                <input
                                                    class="custom-control-input custom-control-input-success checkbox-table"
                                                    type="checkbox" id="selectAll" value="">
                                                <label for="selectAll" class="custom-control-label"></label>
                                                <span class="table-row-heading-order">
                                                    <i class="fas fa-arrow-up mt-1" style="font-size:14.5px ;"></i>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="my_account_addresses">Name</td>
                                        <td class="my_account_addresses">Email</td>
                                        <td class="my_account_addresses">Job Title</td>
                                        <td class="my_account_addresses">Status</td>
                                        <td class="my_account_addresses">Type</td>
                                        <td class="my_account_addresses">Phone Number</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($secondary_contacts as $key => $contact)
                                        <tr>
                                            <td class="table-items justify-content-start align-items-lg-center">
                                                <div class="custom-control custom-checkbox tabel-checkbox">
                                                    <input
                                                        class="custom-control-input custom-control-input-success sub_chk"
                                                        data-id="{{ $contact->id }}" type="checkbox"
                                                        id="separate_check_{{ $contact->id }}">
                                                    <label for="separate_check_{{ $contact->id }}"
                                                        class="custom-control-label"></label>
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
                                            <td class="my_account_all_items" style="vertical-align: middle;">
                                                <a href="{{ url('send-password/fornt-end/' . $contact->user_id) }}"
                                                    class="btn bg-white">send password</a>
                                            </td>
                                            <td class="my_account_all_items" style="vertical-align: middle;">
                                                @if (!empty($contact['contact_id']))
                                                    Primary contact
                                                @else
                                                    Secondary contact
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
                                            <td style="vertical-align: middle;">
                                                <img src="/theme/img/dots_icons.png" alt="">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <tfoot>
                                <tr>
                                    <td colspan="12">
                                        {{ $secondary_contacts->links('pagination.custom_pagination') }}
                                    </td>
                                </tr>
                            </tfoot>
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

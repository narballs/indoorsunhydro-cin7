@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
@stop
@section('content')
    <div class="table-wrapper">
        <div class="card-body mt-2 product_secion_main_body">
            <div class="row border-bottom product_section_header">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="product_heading">
                                Buy Lists
                            </p>
                        </div>
                        <div class="col-md-6 text-right pt-1">
                            <a href="{{ 'buy-list/create ' }}" type="button" class="btn create_new_buylist_btn">
                                Create new buy list +
                            </a>
                        </div>
                    </div>
                    <div class="row search_row_admin-interface">
                        <div class="col-md-4 product_search">
                            <div class="has-search ">
                                <span class="fa fa-search form-control-feedback"></span>
                                <form method="get" action="/admin/products" class="mb-2">
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search for order ID, customer, order, status or something..."
                                        value="{{ isset($search) ? $search : '' }}" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body product_table_body">
                <div class="col-md-12 shadow-sm border order-table-items-data table-responsive">
                    <table class="table bg-white mb-0 table-buylist" id="table">
                        <thead>
                            <tr class="table-header-background">
                                <td class="d-flex table-row-item mt-0">
                                    <div class="custom-control custom-checkbox tabel-checkbox">
                                        <input class="custom-control-input custom-control-input-success checkbox-table"
                                            type="checkbox" id="selectAll" value="">
                                        <label for="selectAll" class="custom-control-label"></label>

                                        <span class="table-row-heading-order">
                                            <i class="fas fa-arrow-up mt-1" style="font-size:14.5px ;"></i>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Title</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Status</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"> Description</span>
                                </td>
                                <td>
                                    <span class="d-flex table-row-item"></span>
                                </td>
                            </tr>
                        </thead>
                        <tbody id="searched">
                            @foreach ($buylists as $key => $buylist)
                                <tr id="row-{{ $buylist->id }}" class="buylist_row border-bottom">
                                    <td class="d-flex table-items">
                                        <div class="custom-control custom-checkbox tabel-checkbox">
                                            <input class="custom-control-input custom-control-input-success sub_chk"
                                                data-id="{{ $buylist->id }}" type="checkbox"
                                                id="separate_check_{{ $buylist->id }}">
                                            <label for="separate_check_{{ $buylist->id }}"
                                                class="custom-control-label "></label>
                                        </div>
                                        <span class="table-row-heading-order">
                                            {{ $key + 1 }}
                                        </span>
                                    </td>
                                    <td class="buylist_title pb-0 pt-4">
                                        <span class="buy_list_title">{{ $buylist->title }}</span>
                                    </td>
                                    <td class="pb-0 pt-4">
                                        @if ($buylist->status == 'Public')
                                            <span class="badge badge-success">{{ $buylist->status }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ $buylist->status }}</span>
                                        @endif
                                    </td>
                                    <td class="pb-0 pt-4">
                                        {{ $buylist->description }}
                                    </td>
                                    <td class="created_by toggleClass td_padding_row">
                                        <div class="d-flex aling-items-center order-table-actions">
                                            <span>
                                                <a href="buy-list/{{ $buylist->id }}" class="view a_class" title=""
                                                    data-toggle="tooltip" data-original-title="View">
                                                    <img src="/theme/img/view.png" alt="" class="img-fluid">
                                                </a>
                                            </span>
                                            <span>

                                                <a href="{{ route('buy-list.create', ['id' => $buylist->id]) }}"
                                                    class="edit a_class" title="" data-toggle="tooltip"
                                                    data-original-title="Edit"><img src="/theme/img/edit.png" alt=""
                                                        class="img-fluid">
                                                </a>
                                            </span>
                                            <span>
                                                <a href="#" class="delete deleteIcon a_class"
                                                    id="{{ $buylist->id }}" title="" data-toggle="tooltip"
                                                    data-original-title="Delete">
                                                    <img src="/theme/img/delete.png" alt="" class="img-fluid">
                                                </a>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="10">
                                    {{ $buylists->links('pagination.custom_pagination') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">

    <style>
        .text-successs {
            color: #7CC633 !important;
            font-family: 'Poppins', sans-serif !important;
        }

        .badge-success {
            background: rgb(186 235 137 / 20%);
            color: #319701;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;

        }

        .badge-warning {
            background-color: #f1e8cb;
            color: #b58903 !important;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }

        .badge-danger {
            color: #fff;
            background-color: rgba(220, 78, 65, 0.12);
            color: #DC4E41;
            padding: 6px !important;
            font-style: normal;
            font-weight: 500;
            font-size: 11.3289px;
        }
    </style>
@stop

@section('js')
    <script>
        $('.buylist-row-none').hover(function() {
            let id = $(this).attr('id');
            children = $(this).children('.buylist_title').children('span').addClass('text-successs');
            let tet = $(this).children('.buylist_action').children('a');
            let get_class = tet.each(function(index, value) {
                let test = tet[index].children[0];
                test.classList.add('bg-icon');
            });
        });


        $('.buylist-row-none').mouseleave(function() {
            let id = $(this).attr('id');
            children = $(this).children('.buylist_title').children('span').removeClass('text-successs');
            let tet = $(this).children('.buylist_action').children('a');
            let get_class = tet.each(function(index, value) {
                let test = tet[index].children[0];
                test.classList.remove('bg-icon');
            });
        });

        $(document).on('click', '#selectAll', function(e) {
            var table = $(e.target).closest('table');
            $('td input:checkbox', table).prop('checked', this.checked);
        });
    </script>
@stop

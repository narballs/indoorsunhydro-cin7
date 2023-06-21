@if ($paginator->hasPages())
    <ul class="pager m-0 p-0">
        <div class="d-flex">
            <div style="width: 15%">
                @if ($paginator->onFirstPage())
                    <li class="disabled btn btn-flat prev_btn_css_front_end_mbl"><span
                            class="prev_next_btn"><i class="fa fa-angle-left" style="color: #ffffff;"></i></span>
                    </li>
                @else
                    <li class="">
                        <a class="btn btn-flat prev_btn_css_front_end_mbl" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                            <span class="prev_next_btn"><i class="fa fa-angle-left" style="color: #ffffff;"></i></span></span>
                        </a>
                    </li>
                @endif
            </div>
            <div class="pagination mt-0" style="width:70%;">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="disabled"><span>{{ $element }}</span></li>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="active my-active-front pd_nmber"><span class="">{{ $page }}</span>
                                </li>
                            @else
                                <li class="pd_nmber"><a href="{{ $url }}"><span
                                            class="non_active_page_number">{{ $page }}</span></a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>
            <div class="pagination mt-0 pagination-sm justify-content-end" style="width: 15%" >
                @if ($paginator->hasMorePages())
                    <li>
                        <a class="btn btn-flat next_btn_css_front_end_mbl" href="{{ $paginator->nextPageUrl() }}" rel="next">
                            <span class="prev_next_btn mr-1"><i class="fa fa-angle-right" style="color: #ffffff;"></i></span>
                            </i>
                        </a>
                    </li>
                @else
                    <li class="disabled btn btn-flat next_btn_css_front_end_mbl">
                        <span class="mr-1 prev_next_btn"><i class="fa fa-angle-right" style="color: #ffffff;"></i></span>
                        
                        </i>
                    </li>
                @endif
            </div>
        </div>
    </ul>
@endif

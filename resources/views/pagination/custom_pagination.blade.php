@if ($paginator->hasPages())
<ul class="pager m-0 p-0">
    <div class="row main-row">
        <div class="col-md-3 pagination previous_btn">
            @if ($paginator->onFirstPage())
                <li class="disabled btn btn-flat prev_btn_css">
                    
                    <i class="fa fa-arrow-left  mr-1" style="color:#4B4B4B; font-size:13px;"></i><span class="prev_next_btn"> Previous</span>
                </li>
                @else
                <li>
                    <a class="btn btn-flat prev_btn_css" href="{{ $paginator->previousPageUrl() }}" rel="prev"> <i class="fa fa-arrow-left mr-1" style="color:#4B4B4B; font-size:13px;"></i> 
                        <span class="prev_next_btn">Previous</span>
                    </a>
                </li>
            @endif
        </div>
        <div class="col-md-6 pagination order_page_number">
                @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="disabled"><span>{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active my-active pd_nmber"><span class="">{{ $page }}</span></li>
                        @else
                            <li class="pd_nmber"><a href="{{ $url }}"><span class="non_active_page_number">{{ $page }}</span></a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>
        <div class="col-md-3 pagination next_btn">
            @if ($paginator->hasMorePages())
                <li>
                    <a class="btn btn-flat next_btn_css" href="{{ $paginator->nextPageUrl() }}" rel="next"><span class="prev_next_btn mr-1">Next</span>
                        <i class="fa fa-arrow-right" style="color:#4B4B4B; font-size:13px;"></i>
                    </a>
                </li>
            @else
                <li class="disabled btn btn-flat next_btn_css">
                    <span class="mr-1 prev_next_btn">Next</span>
                        <i class="fa fa-arrow-right" style="color:#4B4B4B; font-size:13px;">
                    </i>
                </li>
            @endif
        </div>
    </div>   
</ul>
@endif
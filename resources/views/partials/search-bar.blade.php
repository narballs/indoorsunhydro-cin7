<div class="container-fluid mt-3 header-top">
    <div class="row header-top">
        <div class="col-xl-1 col-lg-0 col-md-0 col-sm-0 col-xs-0 "></div>
        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6 col-xs-6">
            <a class="navbar-brand" href="/">
                <img class="top-img img-fluid" src="{{ url('/theme/img/indoor_sun.png') }}" ;>
            </a>
        </div>
        <div class="col-xl-5 col-lg-6 col-md-12 col-sm-6 col-xs-6 mt-2 top-header-navigation p-0">
            @include('partials.nav')
        </div>
        <div class=" col-xl-3 col-lg-3 col-md-12 col-sm-12 col-xs-12 top-reach-bar">
            <form class="d-flex mt-3" method="get" action="{{route('product_search')}}">
                <input type="hidden" id="is_search" name="is_search" value="1">
                <div class="input-group top-search-group">
                    <input type="text" class="form-control" placeholder="What are you searching for" aria-label="Search"
                        aria-describedby="basic-addon2" id="search" name="value"
                        value="{{ isset($searched_value) ? $searched_value : '' }}">
                    <span class="input-group-text" id="search-addon">
                        <button class="btn-info" type="submit" id="search" style="background: transparent;border:none">
                            <i class="text-white" data-feather="search"></i>
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container-fluid mobile-view ">
    <div class="w-100">
        {{-- @include('partials.top-bar') --}}
        @include('partials.nav')
    </div>
</div>
<div class="container-fluid ipad-view ">
    <div class="w-100">
        @include('partials.nav')
    </div>
</div>
</div>
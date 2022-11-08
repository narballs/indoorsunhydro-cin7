<div class="container-fluid">
    <div class="row">
        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <a class="navbar-brand" href="/">
                <img class="top-img" src="/theme/img/indoor_sun.png" ;>
            </a>
        </div>
        <div class="col-lg-8 col-xl-8 col-sm-12 col-xs-12 mt-2 top-header-navigation">
            @include('partials.nav')
        </div>
        <div class=" col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <form class="d-flex mt-3" method="get" action="{{route('product_search')}}">
                <div class="input-group top-search-group">
                    <input type="text" class="form-control" placeholder="What are you searching for" aria-label="Search"
                        aria-describedby="basic-addon2" id="search" name="value">
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
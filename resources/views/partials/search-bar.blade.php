<div class="container-fluid">
    <div class="row">
        <div class="col-md-1">

        </div>
        <div class="col-md-2">
            <a class="navbar-brand" href="/">
                <img class="img-fluid" src="/theme/img/indoor_sun.png" width="325px" height="65px" ;>
            </a>
        </div>
        <div class="col-md-5 mt-2 top-header-navigation">
            @include('partials.nav')
        </div>
        <div class="col-md-4">
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
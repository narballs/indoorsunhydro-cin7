<div class="container">
    <div class="row">
        <div class="col-md-12">
            <nav class="navbar navbar-light bg-transparent">
                <div class="container-fluid">
                    <a class="navbar-brand" href="/">
                        <img class="img-fluid" src="/theme/img/indoor_sun.png" width="325px" height="65px" ;>
                    </a>
                    <form class="d-flex mt-3" method="get" action="{{route('product_search')}}">
                        <div class="input-group top-search-group">
                            <input type="text" class="form-control" placeholder="What are you searching for"
                                aria-label="Search" aria-describedby="basic-addon2" id="search" name="value">
                            <span class="input-group-text" id="search-addon">
                                <button class="btn-info" type="submit" id="search"
                                    style="background: transparent;border:none">
                                    <i class="text-white" data-feather="search"></i>
                                </button>
                            </span>
                            <!-- 	<span class="input-group-text" id="search-addon">
                                <button class="btn-info" type="button" id="search">
                                    <i class="text-white" data-feather="search-addon"></i>
                                </button>
                            </span> -->
                        </div>
                    </form>
                </div>
            </nav>
        </div>
        {{-- <nav class="navbar navbar-light bg-transparent">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">
                    <img class="img-fluid" src="/theme/img/indoor_sun.png" width="325px" height="65px" ;>
                </a>
                <form class="d-flex mt-3" method="get" action="{{route('product_search')}}">
                    <div class="input-group top-search-group">
                        <input type="text" class="form-control" placeholder="What are you searching for"
                            aria-label="Search" aria-describedby="basic-addon2" id="search" name="value">
                        <span class="input-group-text" id="search-addon">
                            <button class="btn-info" type="submit" id="search"
                                style="background: transparent;border:none">
                                <i class="text-white" data-feather="search"></i>
                            </button>
                        </span>
                        <!-- 	<span class="input-group-text" id="search-addon">
                                <button class="btn-info" type="button" id="search">
                                    <i class="text-white" data-feather="search-addon"></i>
                                </button>
                            </span> -->
                    </div>
                </form>
            </div>
        </nav> --}}
    </div>
</div>

@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')


  <div class="alert alert-warning alert-dismissible fade show" role="alert">
   @if(isset($msg))
   {{$msg}}
   @endif
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

@include('partials.footer')
@include('partials.product-footer')
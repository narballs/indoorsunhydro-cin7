// <?php $images[0] = '';
// $images[1] = '';
// $images[2] = '';
// $images[3] = '';
// $images[4] = '';
// $images[5] = '';
?>
<div class="wrapper2">
    @if(!empty($images[0]))
        <img class="img2" src="{{$images[0]}}"  style="border-radius: 23px 0px 0px 0px;max-height:193px !important; min-height:193px !important ;"/>
    @else
        <img class="img2" src="theme/img/greenback.png"  style="border-radius: 23px 0px 0px 0px;max-height:193px !important; min-height:193px !important ;"/>
    @endif
    @if(!empty($images[1]))
        <img class="img2" src="{{$images[1]}}" style="border-radius: 0px 23px 0px 0px;max-height:193px !important ; min-height:193px !important ;position: relative ;"/>
    @else
        <img class="img2" src="theme/img/greenback.png" style="border-radius: 0px 23px 0px 0px;max-height:193px !important ; min-height:193px !important ;"/>
    @endif
    @if(!empty($images[2]))
        <img class="img2 box-height" src="{{$images[2]}}" style="border-radius: 0px 0px 0px 23px"/>
    @else
        <img class="img2 box-height" src="theme/img/greenback.png" style="border-radius: 0px 0px 0px 23px"/>
    @endif
    @if(!empty($images[3]))
        <img class="img2 box-height" src="{{$images[3]}}" />
    @else
        <img class="img2 box-height" src="theme/img/greenback.png" />
    @endif
    @if(!empty($images[4]))
        <img class="img2 box-height" src="{{$images[4]}}" />
    @else
        <img class="img2 box-height" src="theme/img/greenback.png" />
    @endif
    @if(!empty($images[5]))
        <img class="img2 box-height" src="{{$images[5]}}" style="border-radius: 0px 0px 23px 0px"/>
    @else
        <img class="img2 box-height" src="theme/img/greenback.png" style="border-radius: 0px 0px 23px 0px"/>
    @endif
</div>
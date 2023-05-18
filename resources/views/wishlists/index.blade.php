<?php //dd($lists);?>
@foreach($lists as $list)
    <div class="my-account-content-heading text-center mb-4">{{$list->title}}</div>
       <div class="wrapper2">
        <?php 
        $images = [];

            foreach ($list->list_products as $list) {
                foreach ($list->product->options as $image) {

                     array_push($images, $image->image);
            }
            // shuffle($images);
        }?>
            @if(!empty($images[0]))
                <img class="img2" src="{{$images[0]}}"  style="border-radius: 15px 0px 0px 0px;max-height:193px !important; min-height:193px !important ;"/>
            @else
                <img class="img2" src="theme/img/greenback.png"  style="border-radius: 15px 0px 0px 0px;max-height:193px !important; min-height:193px !important ;"/>
            @endif
            @if(!empty($images[1]))
                <img class="img2" src="{{$images[1]}}" style="border-radius: 0px 15px 0px 0px;max-height:193px !important ; min-height:193px !important ;position: relative ;"/>
            @else
                <img class="img2" src="theme/img/greenback.png" style="border-radius: 0px 15px 0px 0px;max-height:193px !important ; min-height:193px !important ;"/>
            @endif
            @if(!empty($images[2]))
                <img class="img2 box-height" src="{{$images[2]}}" style="border-radius: 0px 0px 0px 0px"/>
            @else
                <img class="img2 box-height" src="theme/img/greenback.png" style="border-radius: 0px 0px 0px 0px"/>
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
                <img class="img2 box-height" src="{{$images[5]}}" style="border-radius: 0px 0px 15px 0px"/>
            @else
                <img class="img2 box-height" src="theme/img/greenback.png" style="border-radius: 0px 0px 0px 0px"/>
            @endif
        </div>
@endforeach
@foreach($lists as $list)
    @foreach ($list->list_products as $product)

        @foreach($product->product->options as $option)
            <div>
                @foreach($option->price as $price)
                
                    <table class="table">
                        <tbody>
                            <tr style="border-bottom :1px solid lightgray; " >
                                <td style="width:400px; border:none;vertical-align: middle;"> 
                                    {{$product->product->name}}
                                </td>
                                <td style="border:none;"> 
                                    @if($product->product->images)
                                        <img src="{{$option->image}}" class="" width="50px" height="50px">
                                    @else 
                                        <img src="/theme/image_not_available.png" class="" width="50px" height="50px">
                                    @endif
                                </td>
                                <td style="border:none; vertical-align: middle;">
                                    ${{$product->sub_total}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
            </div>  
            @endforeach
        @endforeach
    @endforeach
@endforeach
                 
        







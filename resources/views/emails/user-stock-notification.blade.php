<!DOCTYPE html>
<html>
<head>
    <title>{{ \App\Helpers\SettingHelper::getSetting('website_name') }}</title>
</head>
<body>
    <div style="padding: 20px;background:#f7f7f7;">
        <div style="background-color: #fff; border:1px solid #f7f7f7;padding: 20px;">
            <div style="text-align: center;margin-bottom:5px;">
                <img src="{{ url('/theme/img/' . \App\Helpers\SettingHelper::getSetting('logo_name')) }}" width="200">
            </div>
            <h5 style="text-align: center; margin-bottom:5px;font-weight:600;font-size:20px;">Product Back in Stock</h5>
            <p style="text-align: center; margin-bottom:25px;font-weight:500;font-size:16px;">The Item you've been waiting for is back in stock</p>
            <table width="60%" align="center" border="0" style="margin-bottom: 10px;">
                <tr>
                    <td width="40%" align="center">
                        @if (!empty($product->images))
                            <img src="{{$product->images}}" alt="" style="width: 60%;">
                        @endif
                    </td>
                    <td width="80" style="vertical-align: top;">
                        <strong style="text-align: center;font-weight:600;font-size:14px;margin-top:3px;">{{$product->name}}</strong>
                    </td>
                </tr>
            </table>   
           
            <div style="width: 100%;text-align:center">
                <button  style="color:#FFF;background:#008BD3;border:1px solid #008BD3;padding:4px;text-align-center;px;font-weight:500;font-size:16px;">
                   <a href="{{url('product-detail/'.$product->id . '/'. $product_options[0]->option_id . '/'. $product->slug)}}">Buy Now</a>
                </button>
            </div>
        
            <p style="text-align:center;margin-bottom:5px;font-weight:500;font-size:14px;">Act quickly, as this product may be available in limited quantities</p>
        </div>
    </div>
</body>
</html>
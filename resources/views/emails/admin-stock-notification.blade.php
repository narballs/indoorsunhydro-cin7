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
            <h5 style="text-align: center; margin-bottom:5px;font-weight:600;font-size:20px;">Product Stock Request</h5>
            <p style="text-align: center; margin-bottom:25px;font-weight:500;font-size:16px;">{{$email}}</p>
            <table width="60%" align="center" border="1" style="margin-bottom: 10px;">
                <tr>
                    <th>
                        <strong style="text-align: center;font-weight:600;font-size:14px;margin-top:3px;">SKU</strong>
                        
                    </th>
                    <th>
                        <strong style="text-align: center;font-weight:600;font-size:14px;margin-top:3px;">Product Name</strong>
                        
                    </th>
                </tr>
                <tr>
                    <td style="text-align: center;" align="center">
                        <strong style="text-align: center;font-weight:600;font-size:14px;margin-top:3px;">{{$product->code}}</strong>
                    </td>
                    <td style="text-align: center;" align="center">
                        <a href="{{url('product-detail/'.$product->id . '/'. $product_options[0]->option_id . '/'. $product->slug)}}"><strong style="text-align: center;font-weight:600;font-size:14px;margin-top:3px;">{{$product->name}}</strong></a>
                    </td>
                </tr>
            </table>   
        </div>
    </div>
</body>
</html>
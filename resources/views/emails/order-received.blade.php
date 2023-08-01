<!DOCTYPE html>
<html>
<head>
    <title>{{ \App\Helpers\SettingHelper::getSetting('website_name') }}</title>
</head>
<body>
    Welcome<h1>{{$name}}</h1>
    {{$subject}}
    <p>{{$name}}</p>
    {{$reference}}
    <p>Your order has been received. Order # <h4>{{$reference}}</h4> Once approved we will notify through an email.</p>
      
    <p>Thank you</p>
</body>
</html>
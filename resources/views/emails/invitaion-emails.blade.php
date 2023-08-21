<!DOCTYPE html>
<html>
<head>
    <title>{{ \App\Helpers\SettingHelper::getSetting('website_name') }}</title>
</head>
<body>
    Welcome<h1></h1>
    {{$subject}}
    <p>
      
             {{$url}}
        
   </p>
    <p>{{$content}}</p>
    <p>Thank you</p>
</body>
</html>

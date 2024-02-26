<!DOCTYPE html>
<html>
<head>
    <title>{{ \App\Helpers\SettingHelper::getSetting('website_name') }}</title>
</head>
<body>
    <p>Hi {{$name}},</p>
    <p>
        {{$content}} <a href="{{url('admin/users')}}">{{ \App\Helpers\SettingHelper::getSetting('website_name') }}</a>.
    </p>
    <p>
        <strong>Name:</strong> {{ $name}}<br>
        <strong>Email:</strong> {{ $email }}<br>
    </p>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>{{ \App\Helpers\SettingHelper::getSetting('website_name') }}</title>
</head>
<body>
    <p>Hi Admin,</p>
    <p>
        A new user has been registered on <a href="{{url('admin/users')}}">{{ \App\Helpers\SettingHelper::getSetting('website_name') }}</a>.
    </p>
    <p>
        <strong>Name:</strong> {{ $user->first_name .' '. $user->last_name }}<br>
        <strong>Email:</strong> {{ $user->email }}<br>
    </p>
</body>
</html>
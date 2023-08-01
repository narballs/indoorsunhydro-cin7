<!DOCTYPE html>
<html>
<head>
    <title>{{ \App\Helpers\SettingHelper::getSetting('website_name') }}</title>
</head>
<body>
    <p>&nbsp;</p>

    <h1>Password Reset Instructions</h1>

    <p>Click below to reset your password</p>

    <p><a href="{{$url}}">Reset Password</a></p>

    <p>or copy paste the URL below in your browser</p>

    <p><a href="{{$url}}">{{$url}}</a></p>

    <p>Thank you.</p>

    <p><strong>{{ \App\Helpers\SettingHelper::getSetting('website_name') }} IT Department</strong></p>
</body>
</html>
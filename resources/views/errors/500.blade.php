<!DOCTYPE html>
<html>
<head>
    <title>Server Error</title>
    <link rel="stylesheet" href="/theme/bootstrap5/css/bootstrap.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'poppins';
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }
        .full-height {
            height: 100vh;
        }
        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }
        .position-ref {
            position: relative;
        }
        .code {
            font-size: 26px;
            font-family: 'poppins';
        }
        .message {
            font-size: 16px;
            font-family: 'poppins';
        }
    </style>
</head>
<body>
    <div class="container full-height">
        <div class="row justify-content-center">
            <?php $email_logo_name = \App\Helpers\SettingHelper::getSetting('logo_name'); ?>
            <?php $error_message = \App\Helpers\SettingHelper::getSetting('500_error_message'); ?>
            <div class="col-md-4">
                <img src="{{ url('theme/img/' . $email_logo_name) }}" alt="" class="img-fluid">
            </div>
        </div>
        <div class="row mt-5">
            <div class="alert alert-danger" role="alert">
                <strong class="code text-dark">500 Internal Server Error</strong>
                <p class="message text-dark">
                    {{$error_message}}
                </p>
                <ul>
                    <li><strong>Logout:</strong> If you'd like to logout, click the button below.</li>
                    {{-- <li><strong>Return Home:</strong> Alternatively, you can return to the home page.</li> --}}
                </ul>
            </div>
            <div class="text-center">
                <a  class="btn btn-danger" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">Logout</a>
                {{-- <a href="{{url('/user')}}" class="btn btn-primary">Return Home</a> --}}
            </div>
        </div>

        <form style="display:none;" id="frm-logout" action="{{ route('logout') }}"
            method="POST">
            {{ csrf_field() }}
            <input class="btn btn-link text-white" type="submit" value="logout">
        </form>
    </div>
    
</body>
</html>

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
        <div class="">
            <div class="alert alert-danger" role="alert">
                <strong class="code text-dark">500 Internal Server Error</strong>
                <p class="message text-dark">Oops! Something went wrong on our end. Our team has been notified and we're working to fix the issue as soon as possible. In the meantime, you can try the following options:</p>
                <ul>
                    <li><strong>Logout:</strong> If you'd like to logout, click the button below.</li>
                    <li><strong>Return Home:</strong> Alternatively, you can return to the home page.</li>
                </ul>
            </div>
            <div class="text-center">
                <a  class="btn btn-danger" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">Logout</a>
                <a href="/" class="btn btn-primary">Return Home</a>
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

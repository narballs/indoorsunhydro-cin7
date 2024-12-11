<!DOCTYPE html>
<html>
<head>
    <title>Server Error</title>
    <link rel="stylesheet" href="/theme/bootstrap5/css/bootstrap.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
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
        .internal_error_msg {
            font-size: 18px;
            font-family: 'poppins';
            font-weight: 500;
            color: #5F5B5B;
        }
        .appologies {
            font-size: 32px;
            font-family: 'poppins';
            font-weight: 700;
            color: #373737;
        }
        .back_to_home {
            background-color: #7CC633;
            color: #ffffff;
            font-size: 15px;
            border-radius: 6px;
            border-color: #7CC633;
            font-weight: 500;
            font-family: 'poppins';
        }

        .back_to_home:hover {
            background-color: #ffffff;
            color: #7CC633;
        }

        .contact_us_500 {
            color: #7CC633;
            font-size: 15px;
            border-radius: 6px;
            background-color: #ffffff;
            border-color: #7CC633;
            font-weight: 500;
            font-family: 'poppins';
        }
        .contact_us_500:hover {
            background-color: #7CC633;
            color: #ffffff;
        }

        .logout_500 {
            background-color: #7CC633;
            font-size: 15px;
            border-radius: 6px;
            color: #ffffff;
            border-color: #7CC633;
            font-weight: 500;
            font-family: 'poppins';
        }
        .logout_500:hover {
            background-color: #ffffff;
            color: #7CC633;
        }
    </style>
</head>
<body>
    <div class="container full-height">
        <div class="row justify-content-center">
            <?php $email_logo_name = \App\Helpers\SettingHelper::getSetting('logo_name'); ?>
            <?php $error_message = \App\Helpers\SettingHelper::getSetting('500_error_message'); ?>
            {{-- <div class="col-md-4">
                <img src="{{ url('theme/img/' . $email_logo_name) }}" alt="" class="img-fluid">
            </div> --}}
        </div>
        <div class="row mt-5 justify-content-center">
            {{-- <div class="alert alert-white" role="alert">
                <strong class="code text-dark">500 Internal Server Error</strong>
                <p class="message text-dark">
                    {{$error_message}}
                </p>
                <ul>
                    <li><strong>Logout:</strong> If you'd like to logout, click the button below.</li>
                    <li><strong>Contact Us:</strong> <a href="{{url('contact-us')}}">Please Click here</a> </li>
                    <li><strong>Return Home:</strong> Alternatively, you can return to the home page.</li>
                </ul>
            </div>
            <div class="text-center">
                <a  class="btn btn-danger" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">Logout</a>
                <a href="{{url('/user')}}" class="btn btn-primary">Return Home</a>
            </div> --}}

            <div class="col-md-8 mt-5">
                <div class="row mb-5">
                    <span class="text-center">
                        <img src="{{ asset('theme/bootstrap5/images/500_error_blade.png') }}" alt="" class="img-fluid">
                    </span>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5 class="appologies text-center mb-4">
                            Looks like we encountered an error
                        </h5>
                        <p class="text-center internal_error_msg mb-4">
                            {{$error_message}}
                        </p>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="d-flex justify-content-center gap-2">
                                <a class="btn back_to_home" href="{{'/'}}">Back To Home</a>
                                <a class="btn contact_us_500" href="{{url('contact-us')}}">Contact Us</a>
                                <a class="btn logout_500"  href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
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

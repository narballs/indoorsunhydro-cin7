<!DOCTYPE html>
<html>
<head>
    <title>IndoorSun</title>
    <style type="text/css">
        .main-div {
            width: 600px;
            min-width: 600px;
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 10px;
            padding-bottom: 100px;
            margin-bottom: 100px;
        }

        table.bordered {
            border-top: 1px solid gray;
            border-right: 1px solid gray;
            border: none;
        }

        table.bordered td.has-bordered {
            /*border-left: 1px solid gray;*/
             border-bottom: 1px solid gray;
            padding: 10px;
        }

        .bottom-row td.border-bottom {
            border-bottom: 1px solid gray;
        }
    </style>
</head>
<body style="background: #f9f9f9;">
    <div class="main-div">
        <p>Hi {{ $full_name }},</p>
        <p>You have recently requested for a password change.</p>
        <p>{{ $any_other_variable }}</p>

        <p>
            Thanks,
            Indoor
        </p>


    </div>
</body>
</html>
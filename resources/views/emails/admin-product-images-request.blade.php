<!DOCTYPE html>
<html>
<head>
    <title>New Product Images Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px #ccc;
            max-width: 600px;
            margin: auto;
        }
        .header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .content {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        .footer {
            font-size: 14px;
            color: #888;
            margin-top: 20px;
            text-align: center;
        }
        .button {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">New Product Images Request Received</div>
        <div class="content">
            Hello Admin,<br><br>
            A new request has been made to add product images. Please review the request as soon as possible.
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Your Company. All rights reserved.
        </div>
    </div>
</body>
</html>

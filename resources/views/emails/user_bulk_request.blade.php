<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Products Request Confirmation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Additional custom styles can be added here */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #007bff;
        }
        .email-content {
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Bulk Products Request Confirmation</h2>
        
        <div class="email-content">
            <p>Dear {{ $name }},</p>
            <p>Thank you for submitting your bulk products request. We will review your request shortly.</p>
            <hr>
            <p><strong>Items List:</strong> {{ $items_list }}</p>
            <p><strong>Quantity:</strong> {{ $quantity }}</p>
            <p><strong>Delivery Location:</strong> {{ $delievery }}</p>
        </div>

        <p class="text-muted">This is an automated message, please do not reply.</p>
        
        <div class="footer">
            <?php $email_logo_name = \App\Helpers\SettingHelper::getSetting('email_logo_name'); ?>
            <img src="{{ url('/theme/bootstrap5/images/' . $email_logo_name) }}" alt="" />
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="stylesheet" href="/theme/bootstrap5/css/bootstrap.css">
    <link rel="stylesheet" href="/theme/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.33/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #000;
        padding: 5px;
    }

    table {
        background-color: #FFFFFF;
        border-radius: 5px;
    }
</style>

<body style="background-color: #000000;">
    <table bgcolor="#FFFFFF" style="background-color:#FFFFFF;border-radius:5px;" width="50%" border="0" align="center" cellpadding="0">
        <tbody>
            <tr>
                <td>
                    <table width="80%" border="0" align="center">
                        <!-- Header Icon -->
                        <tr>
                            <td align="center">
                                <img class="img-fluid" src="{{ url('/theme/img/email/approve_email_icon.png') }}" alt="Approval Icon">
                            </td>
                        </tr>

                        <!-- Subject -->
                        <tr>
                            <td align="center" style="color:#000000;font-size: 18px;font-weight: 600;padding-top: 10px;">
                                <b>{{ !empty($subject) ? $subject : '' }}</b>
                            </td>
                        </tr>

                        <!-- Message Body -->
                        <tr>
                            <td align="center" style="padding-top: 10px;padding-bottom: 20px;">
                                <p style="color:#000000;font-size: 14px;font-weight: 400;">
                                    Your shipment label for order #{{ $content['order_id'] }} is attached.
                                </p>
                            </td>
                        </tr>

                        <!-- Shipping Address -->
                        <tr>
                            <td style="padding: 10px 20px; color: #000;">
                                <h4 style="font-size: 16px; font-weight: 600;">Shipping to:</h4>
                                <p style="font-size: 14px; margin: 0;">
                                    <b>{{ !empty($content['company']) ? $content['company'] : '' }}</b><br>
                                    {{!empty( $content['name']) ?  $content['name'] : '' }}<br>
                                    {{ !empty($content['street1']) ? $content['street1'] : '' }}<br>
                                    {{ !empty($content['street2']) ? $content['street2'] : '' }}<br>
                                    {{ !empty($content['street3']) ? $content['street3'] : '' }}<br>
                                    {{ !empty($content['city']) ? $content['city'] : '' }}, {{ !empty($content['state']) ? $content['state'] : '' }} {{ !empty($content['postalCode']) ? $content['postalCode'] : '' }}<br>
                                    {{ !empty($content['country']) ? $content['country'] : '' }}
                                </p>
                            </td>
                        </tr>

                        <!-- Order Items -->
                        <tr>
                            <td style="padding: 10px 20px; color: #000;">
                                @if (!empty($order_items))
                                    <h4 style="font-size: 16px; font-weight: 600;">Order Items:</h4>
                                    <ul style="font-size: 14px; padding-left: 20px;">
                                        @foreach ($order_items as $item)
                                            @if (!empty($item['quantity']) && !empty($item['name']) && !empty($item['sku']))
                                                <li>{{ $item['quantity'] }} x {{ $item['name'] }} (SKU: {{ $item['sku'] }})</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>

                        <!-- Footer with Logo -->
                        <tr>
                            <td align="center">
                                <?php $email_logo_name = \App\Helpers\SettingHelper::getSetting('email_logo_name'); ?>
                                <img src="{{ url('/theme/bootstrap5/images/' . $email_logo_name) }}" alt="Email Logo" />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>

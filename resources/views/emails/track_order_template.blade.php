<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Track Your Order</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
  
  <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center">
        <table width="800" cellpadding="20" cellspacing="0" style="background-color: #ffffff; margin-top: 20px; border-radius: 10px;">
          <tr>
            <td align="center" style="padding-bottom: 0;">
                
            </td>
          </tr>

          <tr>
            <td align="center" style="padding-bottom: 0;">
                <a href="{{ route('index') }}" style="text-align: center;">
                    @php
                        $email_logo_name = \App\Helpers\SettingHelper::getSetting('email_logo_name');
                    @endphp
                    <img src="{{ url('/theme/bootstrap5/images/' . $email_logo_name) }}" alt="Logo" />
                </a>
                <h2 style="color: #333;">Your Order Has Shipped!</h2>
                <p style="color: #555;">Thank you for shopping with us. Your items are on the way.</p>
            </td>
          </tr>

          <tr>
            <td>
              <h3 style="color: #333;">📦 Items Included:</h3>
              <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse; font-size: 14px;">
                <thead>
                  <tr style="background-color: #f0f0f0;">
                    <th align="left" style="border-bottom: 1px solid #ddd;">Product Name</th>
                    <th align="left" style="border-bottom: 1px solid #ddd;">SKU</th>
                    <th align="left" style="border-bottom: 1px solid #ddd;">Quantity</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($order_items as $item)
                    <tr>
                      <td style="border-bottom: 1px solid #eee;">
                        {{ $item->product->name ?? '' }}
                      </td>
                      <td style="border-bottom: 1px solid #eee;">
                        {{ $item->product->code ?? 'N/A' }}
                      </td>
                      <td style="border-bottom: 1px solid #eee;">
                        {{ $item->quantity ?? 1 }}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </td>
          </tr>

          @if (!empty($tracking_url))
          <tr>
            <td align="center" style="padding-top: 20px;">
              <a href="{{ $tracking_url }}" target="_blank" style="background-color: #007bff; color: #fff; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Track Your Order
              </a>
            </td>
          </tr>
          @endif

          <tr>
            <td style="font-size: 12px; color: #999; text-align: center;">
                <p class="mb-0" style="text-align: center;">
                    Copyright @ {{ \App\Helpers\SettingHelper::getSetting('website_name') }}. All right reserved
                </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>

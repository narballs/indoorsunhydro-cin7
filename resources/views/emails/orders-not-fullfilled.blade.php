<!DOCTYPE html>
<html>
<head>
    <title>{{ \App\Helpers\SettingHelper::getSetting('website_name') }}</title>
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
      <table width="100%" align="left" cellpadding="10" cellspacing="10">
            <tr>
                <td style="text-align: center;">
                    <div >
                        <img src="{{ url('/theme/img/' . \App\Helpers\SettingHelper::getSetting('logo_name')) }}" width="200">
                    </div>
                    <div style="margin-top:100px; border-bottom: 1px solid gray">
                        <h2>Pending Orders</h2>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="border-bottom: 1px solid gray;margin-bottom:50px">
                        <p style="font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Arial,'Roboto';font-size: 15px;
                        line-height: 32px;
                        font-weight: 400;
                        color: #333333;
                        margin: 11px 0 26px;">
                            {{$content}}<br>
                            <table>
                                
                                <th>
                                    <td>Reference</td>
                                    <td>Order Date</td>
                                    <td>Order Status</td>
                                    <td>Order Total</td>
                                </th>
                                @foreach($orders as $order)
                                <tr>
                                    <td>{{$order->reference}}</td>
                                    <td>{{ date('m/d/Y', strtotime($order->created_at)) }}</td>
                                    <td>{{$order->isApproved == 0 ? 'Not FullFilled' : ''}}</td>
                                    <td>${{ number_format($order->total_including_tax, 2) }}</td>
                                </tr>
                                @endforeach
                            </table>
                            Thanks,<br>
                            Team {{ \App\Helpers\SettingHelper::getSetting('website_name') }}
                        </p>
                    </div >
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

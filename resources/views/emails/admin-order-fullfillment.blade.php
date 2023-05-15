<html>

<head>
    <title>IndoorSun</title>
    <style type="text/css">
        .main-div {
            width: 600px;
            min-width: 600px;
            max-width: 600px;
            margin: auto;
            background: #f9f9f9;
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
                    <div>
                        <img src="{{ url('/theme/img/indoor_sun.png') }}" width="200">
                    </div>
                    <div style="margin-top:100px; border-bottom: 1px solid gray">
                        <h2>Order Fullfilled</h2>
                    </div>
                </td>
            </tr>
            <br>
            <tr>
                <td>
                    <div style="border-bottom: 1px solid gray;margin-bottom:50px !important">
                        <p
                            style="font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Arial,'Roboto' !important; font-size: 15px !important;
                            line-height: 32px !important;
                            font-weight: 400 !important;
                            color: #333333 !important;
                            margin: 11px 0 26px; !important">
                            Hey {{ $reference }},<br>
                            {{ $content }}<br><br>
                        <table>

                            <tr>
                                <td>
                                    <h2>Order Details</h2>
                                </td>
                            </tr>
                            <tr>
                                <td><b>QCOM Order ID:</b> {{ $order_id }}</td>
                            </tr>
                            <tr>
                                <td><b>Cin7 Order ID: {{ $reference }}</b></td>
                            </tr>

                        </table>
                        Thanks,<br>
                        Team IndoorSunHydro
                        </p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
<br><br>

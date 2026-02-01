<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trizent Report</title>
    <style>
        h2 {
            text-align: center;
            margin: none;
        }
        h4 {
            text-align: center;
            margin: none;
        }
        #com_logo{
            position: absolute;
            width: 150px;
            height: auto;
            margin-left: 10px;
            border-radius: 5px;
        }
        #tbl_main{
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        td, th {
            border: 1px solid black;
            padding: 4px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div id="div_top">
        <div style="width: 150px;">
            <img src="{{ public_path('images/company/com_logo_1.png') }}" alt="company logo" id="com_logo">
        </div>
        <div style="text-align: center;">
            <h2>Trizent Infratech Reports</h2>
            <h4>All Camps Sales Summary Report</h4>
        </div>
    </div>
    <div>
        <p>Camp Sale Summary from <strong>{{ $start_date }} to {{ $end_date }}</strong></p>
    </div>

    <table id="tbl_main">
        <thead>
            <<tr>
                <th rowspan="2" class="text-center">No</th>
                <th rowspan="2" class="text-center">Camp Name</th>
                <th colspan="2" class="text-center">Subscription</th>
                <th colspan="2" class="text-center">Voucher</th>
                <th rowspan="2" class="text-center">Total</th>
            </tr>
            <tr>
                <th>Count</th>
                <th>Sale</th>
                <th>Count</th>
                <th>Sale</th>
            </tr>
        </thead>
        <tbody>
             @foreach ($sales as $sale)
                <tr class="text-center">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sale['camp'] }}</td>
                    <td>{{ $sale['subscription_count']}}</td>
                    <td>{{ $sale['subscription_sale']}}</td>
                    <td>{{ $sale['voucher_count']}}</td>
                    <td>{{ $sale['voucher_sale']}}</td>
                    <td>{{ $sale['total_sale']}}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align: right;"><strong>Total Sales:</strong></td>
                <td style="text-align:right;"><strong>{{ $camp_total }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>

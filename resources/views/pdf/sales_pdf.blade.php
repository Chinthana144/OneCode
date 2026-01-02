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
            <h4>{{$camp_name}} Sales Report</h4>
        </div>
    </div>
    <div>
        <p>Sales reports from {{ $start_date }} to {{ $end_date }}</p>
    </div>

    <table id="tbl_main">
        <thead>
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Contatc No</th>
                <th>Package</th>
                <th>Duration</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
             @foreach ($data as $sale)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ Str::substr($sale->purchaseDate, 0, 10) }}</td>
                    <td>{{ $sale->fullname }}</td>
                    <td>{{ $sale->username }}</td>
                    <td>{{ $sale->name }}</td>
                    <td>{{ $sale->duration }} days</td>
                    <td style="text-align:right;">{{ $sale->price }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align: right;"><strong>Total Sales:</strong></td>
                <td style="text-align:right;"><strong>{{ $data->sum('price') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>

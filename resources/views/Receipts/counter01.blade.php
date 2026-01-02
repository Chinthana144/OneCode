<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CloudTik Counter</title>

    <script>
        window.onload = function() {
            window.print();

            setTimeout(() => {
                window.close();
                window.location.href = "/home";
            }, 500);
        }
    </script>

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid black;
        }

        th,
        td {
            border: 1px solid black;
            padding: 2px;
            text-align: center;
        }
    </style>
</head>

<body>
    <h3>Counter Report</h3>

    <table class="table">
        <tr>
            <th>Package Name</th>
            <th>Count</th>
            <th>Total</th>
        </tr>
        @foreach ($totals as $total)
            <tr>
                <td>{{ $total->package->name }}</td>
                <td>{{ $total->total_count }}</td>
                <td>{{ $total->total_price }}</td>
            </tr>
        @endforeach
        <tr>
            <th>Total</th>
            <th>{{ $invoice_count }}</th>
            <th>{{ $counter_total }}</th>
        </tr>
    </table>

</body>

</html>

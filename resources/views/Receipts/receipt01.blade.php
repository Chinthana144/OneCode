<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CloudTik Receipt</title>

    <script>
        window.onload = function() {
            window.print();

            setTimeout(() => {
                window.close();
                history.back();
            }, 500);
        }
    </script>

    <style>
        #h3_header {
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
        }

        #p_slogan {
            text-align: center;
        }

        #p_footer {
            text-align: center;
        }
    </style>
</head>

<body>
    <h3 id="h3_header">{{ $subscription->camp->name }}</h3>
    <p>
        Purchase Date: {{ $subscription->purchaseDateTime }}
        <br>
        Customer: {{ $subscription->customer->fullname }}
        <br>
        Contact: {{ $subscription->customer->phone }}
        <br>
        Package: {{ $subscription->package->name }}
        <br>
        Duration: {{ $subscription->package->duration }} (days)
    </p>

    <p id="p_slogan">------ Thank You ------</p>

    {{-- <p id="p_footer">-------------------------------</p> --}}
</body>

</html>

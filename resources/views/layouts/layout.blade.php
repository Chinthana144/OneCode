<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/company/trizent_icon.ico') }}">
    <title>Trizent CloudTik</title>


    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">

    {{-- <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" /> --}}
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous"> --}}

    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>

    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

</head>

<body>

    @include('layouts.navbar')

    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTYDmcVEzKLbAqWEQ4iXCPYVshvCf5tW9zANTKfAdVQSZe" crossorigin="anonymous">
    </script> --}}
    <script src="{{ asset('js/navbar.js') }}"></script>
</body>

</html>

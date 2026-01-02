<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CloudTik Network</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <style>
        .div_camp {
            border: 2px blue solid;
            border-radius: 10px;
            padding: 10px 0px;
        }
    </style>
</head>

<body>
    <h1>Camp Portal</h1>
    <div class="card">
        <div class="card-header">
            <h5>Select Camp</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($allowed_camps as $camp)
                    <div class="col-3 text-center">
                        <div class="div_camp">
                            <h4>{{ $camp->camps->name }}</h4>
                            <a href="/gotoCamp/{{ $camp->camps->id }}" class="btn btn-primary">Select Camp</a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</body>

</html>

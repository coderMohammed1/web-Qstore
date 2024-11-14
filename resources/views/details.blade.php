<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
        #alert {
            text-align: center !important;
            margin-top: 20px;
        }

        #sh {
            text-align: center !important;
            border: solid red 1px;
        }

        #f1 {
            display: flex;
            justify-content: center;
        }

        #btn {
            width: 100% !important;
        }
    </style>
</head>
<body>
    @include("nav")

    <br>
    <div class="container">
        <div id="sh" class="shadow p-3 mb-1 bg-body rounded">{{$cname->First_Name}}'s order</div>

        <form id="f1" method="POST">
            @csrf
            <button class="btn btn-outline-secondary mt-2" id="btn" name="dilivered" type="submit">Diliverd!</button>
        </form>
    </div>

    @foreach ($data as $item)
    
    <div class="mt-4" style="width: 60% !important; margin:auto;">
        <div class="card" style="width: 100%; text-align: center;">
            <div class="card-header">
            {{$item['pname']}}
            </div>

            <ul class="list-group list-group-flush">
            <li class="list-group-item">Price: {{$item['price']}}</li>
            </ul>
        </div>
    </div>
    <br>
    @endforeach
</body>
</html>
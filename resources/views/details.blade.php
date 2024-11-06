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
    </style>    
</head>
<body>
    @include("nav")
    
    @foreach ($data as $item)
        <h1>name:</h1>
        <p>{{$item["pname"]}}</p>
        <br>

        <h1>price:</h1>
        <p>{{$item["price"]}}</p>
        <br>
    @endforeach
</body>
</html>
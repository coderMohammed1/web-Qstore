<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
        #alert {
            text-align: center !important;
            margin-top: 20px;
             
        }
        .ord{
            align-items: center; 
            text-align: center;
            margin-top: 10px; 
        }
    </style>
</head>
<body>
    @include("nav")
    <br>
    {{-- TODO: search bar --}}
    @foreach ($orders as $cust)
    <div class="card ord" style="width: 100%;">
        <div class="card-body">
          <h5 class="card-title">{{$cust["fname"]}}</h5>
          <p class="card-text">{{$cust["email"]}}</p>
          <a href="/details?cid={{$cust['cust']}}" class="btn btn-primary">Order details!</a> 
        </div>
      </div>    
      @endforeach
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>my cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/cart.css">
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

        #f1 form {
            display: contents;
        }

        #btn {
            width: 50% !important;
        }
    </style>
</head>
<body>
    @include('nav')

    <div class="container">
        <div id="sh" class="shadow p-3 mb-1 bg-body rounded"> Welcome {{$_SESSION["info"]->First_Name}}</div>

        <div id="f1">
            <form method="POST">
                @csrf
                <button class="btn btn-outline-secondary mt-3" id="btn" name="out" type="submit">check out</button>
            </form>

            <form action="/cart/delete/all" method="POST">
                @csrf
                 <button id="btn" class="btn btn-outline-warning mt-3" name="delall">Delete all items!</button>
            </form>
        </div>
    </div>

    <br>

    @if(isset($data))
        <main id="main" style="margin-top: 2%;">
            @foreach ($data as $product)
                <div class="container cont" style="width: 60%; margin-bottom: 3%">
                    <div class="item">
                        <div style="display: flex;">
                            <img class="img0" src="{{$product['img']}}" alt="err">
                            {{-- {{'data:'.$product['type'].';base64,'.base64_encode($product['img'])}} --}}
                            <div style="margin-left: 5px; width: 100%; display: flex;" class="tits">
                                <h3 class="tit">{{$product["pname"]}}</h3>
                                <h3 class="price">{{$product["price"]}}$</h3>
                            </div>
                        </div>
                        
                        <div class="inputs" style="display: flex;">
                            <form method="POST" action="/cart/delete">@csrf <button name="delc" value="{{$product['cpid']}}" class="del">delete</button></form>
                            <input title="Update the page to see the new total!" type="number" class="quant" value="{{$product['quant']}}">
                            <input type="hidden" name="pid" id="pid" value="{{$product['cpid']}}">
                            <input type="hidden" name="rpid" id="rpid" value="{{$product['pid']}}">
                        </div>
                    </div>
                </div>
            @endforeach
            <br>
            <p style="margin-left: 5%">total: {{$product["tot"]}}$</p>    
        </main>
    @endif

    @if(isset($invalid)) {{--for quanity checks--}}
        <script>alert($invalid);</script>
    @endif
        
    @if(isset($Done))
        <div id="alert" class="alert alert-success">
            {{ $Done }} 
        </div>
    @endif

    @if(isset($error))
        <div id="alert" class="alert alert-warning">
            {{ $error }} 
        </div>
    @endif

    @if (isset($refrech))
        <script>
            setTimeout(() => {
                window.location.replace(window.location.href);
             }, 4000); 

        </script>
    @endif
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/cart.js"></script>    
</body>
</html>

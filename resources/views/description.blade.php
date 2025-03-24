<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Description</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
        #alert {
            text-align: center !important;
            margin-top: 20px;
            
        }

        #des{
            word-wrap: break-word;
            white-space: pre-wrap;
            margin: 0 !important;
        }

    </style>
</head>
<body>
    @include("nav")
    <div class="container" style="margin-top:3px">
        <div style="text-align: center; border-bottom: 2px red solid">
            <h1>{{$product->p_name}}</h1>
        </div>
        <br>

        <div style="display: flex; align-items: center; width:100%;">
            <img style="margin: auto; max-width: 80%" src="{{url($product->img)}}" alt="error">
        </div>

        <br>

        <div style="text-align: center; border-bottom: 2px red solid;">
            <h5>Price:{{$product->price}}$</h5>
        </div>

        <div style="text-align: center;">
            <h2>Description:</h2>

            @if($_SESSION["info"]->roles == "s")
                <form method="POST" action="/product/description">
                    @csrf
                    <textarea name="Ndescription" class="form-control" rows="5"  style="resize: vertical;">{{$product->description}}</textarea>
                    <br>
                    <button name="update" value="{{$product->pid}}" class="btn btn-dark" style="width: 100%">Edit</button>
                </form>
            @else
                <p id="des">{{$product->description}}</p>
            @endif
        </div>

        <div style="display: block; align-items: center; text-align: center; width:100%">
            <h2 style="margin: auto">Seller information:</h2>
            <br>
            <table style="border-collapse: collapse; margin:auto;">
                <tr>
                    <th style="border: 1px solid black; padding: 3px;">Manufacturer</th>
                    <th style="border: 1px solid black; padding: 3px;">Seller Name</th>
                    <th style="border: 1px solid black; padding: 3px;">Seller email</th>
                </tr>

                <tr>
                    <td style="border: 1px solid black; padding: 3px;">{{$product->Manfacturer}}</td>
                    <td style="border: 1px solid black; padding: 3px;">{{$product->First_Name}} {{$product->Last_name}}</td>
                    <td style="border: 1px solid black; padding: 3px;">{{$product->email}}</td>
                </tr>

            </table>
            <br>
        </div>
    </div>   
</body>
</html>
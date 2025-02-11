<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Details</title>
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

        aside {
            position: relative;
            right: auto;
            top: auto;
            
            margin: 20px auto;
            width: 100%;
            text-align: center;
                
        }

        #sh {
                font-size: 1rem;
            }

      
    </style>
</head>
<body>
    @include("nav")

    <!-- Main container -->
    <div class="container">
        <!-- Customer's Order -->
        <div class="row mt-3">
            <div id="sh" class="col-12 shadow p-3 mb-1 bg-body rounded text-center">
                {{$customerdet[0]['First_Name']}}'s order
            </div>
        </div>

        <!-- Form -->
        <div class="row">
            <div class="col-12">
                <form id="f1" method="POST" class="mt-3">
                    @csrf
                    <button class="btn btn-outline-secondary w-100" id="btn" value="{{$customerdet[0]['mid']}}" name="dilivered" type="submit">Delivered!</button>
                </form>
            </div>
        </div>

        <!-- Order Items -->
        <div class="row mt-4">
            @foreach ($data as $item)
            <div class="col-12 col-md-6 col-lg-4 mx-auto mb-4">
                <div class="card text-center">
                    <div class="card-header">
                        {{$item['pname']}}
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Price: {{$item['price']}}</li>
                    </ul>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">quantity: {{$item['oq']}}</li>
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Additional Info (Aside) -->
    <aside>
        <h4>Additional Info</h4>
        <br>
        <table class="table table-bordered" style="width: 50%; margin:auto;">
            <tbody>
                <tr>
                    <td>Email</td>
                    <td>{{$customerdet[0]['email']}}</td>
                </tr>
                <tr>
                    <td>Country</td>
                    <td>{{$customerdet[0]['country']}}</td>
                </tr>
                <tr>
                    <td>City</td>
                    <td>{{$customerdet[0]['city']}}</td>
                </tr>
                <tr>
                    <td>Street</td>
                    <td>{{$customerdet[0]['street']}}</td>
                </tr>
            </tbody>
        </table>
    </aside>
</body>
</html>

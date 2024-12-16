<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shoping center</title>
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
    <div class="container mb-2" >
        <form  method="post">
            @csrf
            <label class="form-label mt-4 ">SEARCH:</label>
            <input class="form-control " type="text" name="search" placeholder="product or Manfacturer name:" required/>
            <button class="btn btn-dark mt-1 w-100" type="submit" name="send01">search</button>
            <a class="btn btn-info mt-1 w-100" href="/customers">Back!</a>
        </form>
    </div>

    <main id = 'main' style='display: flex; flex-wrap: wrap;'>
        @foreach ($data as $product)
            <div style="width: 300px; margin-left: 15px; margin-top: 4px; border: 2px blue solid; margin-bottom:7px">

                <div style="border-bottom: 1px red solid; margin-bottom: 3.5px;">
                    <img src="{{'data:'.$product['type'].';base64,'.base64_encode($product['img'])}}" style="width:296px; height:200px !important;" alt="no_img">
                </div>

                <div style="border-bottom: 1px red solid;">
                    <p> Name: {{$product["p_name"]}}</p>
                </div>

                <div style="border-bottom: 1px red solid;">
                    <p> Price: {{$product["price"]}}$</p>
                </div>

                <form action = "/product/description" method = "post">
                    @csrf
                    <div style="border-bottom: 1px red solid;">   
                        <button value="{{$product['ID']}}" style="width:100%; background-color:#0dcaf0;border: none;height: 24px; padding-bottom:35px" name = "descr" type = "submit" >Description</button>
                    </div>
                </form>    

                <form action = "/customers/add" method = "post">
                    @csrf
                    <div>   
                        <button value="{{$product['ID']}}" style="width:100%; background-color: #21dda6;border: none;height: 24px; padding-bottom:37px" name = "buy" type = "submit" >Add to cart</button>
                    </div>
                </form>           
            </div>
        @endforeach
        
    </main>
</body>
<script src="/assets/js/customer.js"></script>
</html>
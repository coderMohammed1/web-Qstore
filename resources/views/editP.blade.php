<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Edit your products</title>

    <style>
        #alert{
            text-align: center !important;
            margin-top: 5px;
        }

        #sh{
            text-align: center !important;
            border: solid red 1px;
        }

        .inputs{
            border: none;
            width: 100%;
        }

        .custom-file-upload {
            margin: 0px;
            cursor: pointer;
            background-color: #21dda6;
            color: #f51bc4;
        }

        input[type="file"] {
            display: none;
        }
        
    </style>

</head>
<body>
    @include("nav")
    <br>
    <div id="sh" class="shadow p-3 mb-1 bg-body rounded">Welcome:{{$_SESSION["info"]->First_Name}}</div>
    <br>
    {{-- edit the description part --}}

    <main id = 'main' style='display: flex; flex-wrap: wrap;'>

        @foreach ($data as $product)
            <div style="width: 300px; margin-left: 15px; margin-top: 4px; border: 2px blue solid; margin-bottom:7px">

                <div style="border-bottom: 1px red solid; margin-bottom: 3.5px;">
                    <img src="{{'data:'.$product['type'].';base64,'.base64_encode($product['img'])}}" style="width:296px; height:200px !important;" alt="no_img">
                </div>

                <div style="border-bottom: 1px red solid;">
                    <form style="display: flex;" action="/editProducts" method="post">
                        @csrf
                        Name: <input name="pname2" title="click to edit!" type="text" class="inputs" value=" {{$product['p_name']}}">

                        <button value="{{$product['ID']}}" title="confirm the edit" type="submit" style="border: none; background: none; padding: 0;" name="editName">
                            <i style="font-size:24px; margin-left:5px" class="fa">&#xf040;</i>
                        </button>
                    </form> 
                </div>

                <div style="border-bottom: 1px red solid;">
                    <form style="display: flex;" action="/editProducts" method="post">
                        @csrf
                        Price:<input name="price2" title="click to edit!" type="text" class="inputs" value=" {{$product['price']}}$">

                         <button value="{{$product['ID']}}" title="confirm the edit" type="submit" style="border: none; background: none; padding: 0;" name="editPrice">
                            <i style="font-size:24px; margin-left:5px" class="fa">&#xf040;</i>
                        </button>
                    </form>
                </div>

                <form action = "/product/description" method = "post">
                    @csrf
                    <div style="border-bottom: 1px red solid;">   
                        <button value="{{$product['ID']}}" style="width:100%; background-color:#0dcaf0;border: none;height: 24px; padding-bottom:35px" name = "descr" type = "submit" >Description</button>
                    </div>
                </form>

                <form action = "/editProducts" method = "post" enctype="multipart/form-data">
                    @csrf
                    <div style="display: flex;">   
                        <button title="confirm your edit" value="{{$product['ID']}}" style="width:100%; background-color: #21dda6;border: none;height: 24px; padding-bottom:37px" name = "pimg" type = "submit" >Edit the image!</button>

                        <label title="pick an image" class="custom-file-upload">
                            <input required type="file" name="img2" accept=".jpg, .jpeg, .png, .gif, .bmp, .tiff, .tif, .webp, .ico, .heic, .heif, .jfif, .psd, .raw, .eps, .ai, .cdr">
                            <i style="font-size:24px" class="fa">&#xf0c6;</i>
                        </label>

                    </div>
                </form>

                <form action = "/editProducts/delete" method = "post">
                    @csrf
                    <div style="border-bottom: 1px red solid;">   
                        <button value="{{$product['ID']}}" style="width:100%; border: none;height: 24px; padding-bottom:35px; background-color: #dc3545; color:white;" name = "delp" type = "submit" >Delete !</button>
                    </div>
                </form>
            </div>
        @endforeach

        @if(isset($error))
            <div id="alert" class="alert alert-danger">
                {{ $error }} <!-- Display the error message -->
            </div>
        @endif

        @if(isset($succsess))
            <div id="alert" class="alert alert-success">
                {{ $succsess }}
            </div>
        @endif
        
    </main>
</body>
<script src="/assets/js/customer.js"></script>
</body>
</html>
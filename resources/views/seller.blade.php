<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sell</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        #alert{
            text-align: center !important;
            margin-top: 5px;
        }

        #tex {
            width: 100%;
            max-height: 300px;
        }

    .custom-file-upload {
        /* border: 1px solid #ccc; */
        /* display: inline-block; */
        padding: 6px 12px;
        cursor: pointer;
    }

    input[type="file"] {
        display: none;
    }
    </style>
</head>
<body>
    @include('nav')

    <main class="container mt-3">
        <form action="/seller" method="post" enctype="multipart/form-data"> 
            @csrf
            <label  class="form-label" for="">Product name:</label>
            <input required type="text"  class="form-control" name="pname" id="" autocomplete="off">
            <label  class="form-label" for="">Price:</label>

            <input required  class="form-control" type="number" name="price" maxlength="25">
            <label  class="form-label" for="">Manufacturer:</label>
            <input required type="text"  class="form-control" name="Manu" id="">

            <label  class="form-label" for="">Descreption:</label>
            <textarea required  class="form-control" id="tex" name="desc" rows="2" autocomplete="off"></textarea>

            <label class="custom-file-upload">
                Add an image:
                <input required type="file" name="img" accept=".jpg, .jpeg, .png, .gif, .bmp, .tiff, .tif, .webp, .svg, .ico, .heic, .heif, .jfif, .psd, .raw, .eps, .ai, .cdr">
                <i style="font-size:24px" class="fa">&#xf0c6;</i>
            </label>

            <br>
            <button value="{{$_SESSION['info']->ID}}" class="btn btn-outline-success mt-3" name="sell" type="submit">Upload</button>
            <a class="btn btn-outline-warning mt-3" href="/editProducts">Edit your products</a>
        </form>

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

<script>
     var texar =document.getElementById("tex");
    texar.style.cssText = `height: ${texar.scrollHeight}px; overflow-y: hidden`;
    texar.addEventListener("input", function(){
        this.style.height ="auto";
        this.style.height = `${this.scrollHeight}px`;

    });
</script>
</body>
</html>
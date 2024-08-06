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
    <main id = 'main' style='display: flex; flex-wrap: wrap; margin-left: 7%;'>
        @foreach ($data as $product)
            <div style="width: 300px; margin-left: 15px; margin-top: 4px; border: 2px blue solid; margin-bottom:7px">

                <div style="border-bottom: 1px red solid; margin-bottom: 3.5px;">
                    <img src="/imgs/stores.jpeg" style="width:296px; max-height:2px !important;" alt="no_img">
                </div>

                <div style="border-bottom: 1px red solid;">
                    <p> Name: iphoen</p>
                </div>

                <div style="border-bottom: 1px red solid;">
                    <p> Price:9999$</p>
                </div>

                <form action = "/qmaker/teacher/stats.php" method = "post">
                    
                    <div style="border-bottom: 1px red solid;">   
                        <button style="width:100%; background-color:#0dcaf0;border: none;height: 24px; padding-bottom:35px" name = "descr" type = "submit" >Description</button>
                    </div>
                </form>    

                <form action = "/qmaker/teacher/stats.php" method = "post">
                    
                    <div>   
                        <button style="width:100%; background-color: #21dda6;border: none;height: 24px; padding-bottom:37px" name = "buy" type = "submit" >Add to cart</button>
                    </div>
                </form>           
            </div>
        @endforeach
    </main>
</body>
</html>
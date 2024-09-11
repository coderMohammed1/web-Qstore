<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>my cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/cart.css">
    <style>
        #alert {
            text-align: center !important;
            margin-top: 20px;
             
        }

        #sh{
            text-align: center !important;
            border: solid red 1px;
        }

        #f1{
            display: flex;
            justify-content: center;
        }
        #btn{
            width: 50% !important;
        }
    </style>
</head>
<body>
    @include('nav')

    <div class="container">
        <div id="sh" class="shadow p-3 mb-1 bg-body rounded"> Welcome Mohammed</div>

        <form id = "f1" method="POST">
            <button class="btn btn-outline-secondary mt-3 " id="btn" name="out" type="submit">check out</button>
            <a id="btn" class="btn btn-outline-warning mt-3" name="update" href="https://'.$ip.'/qmaker/profile.php">delte all items!</a>
        </form>
    </div>

    <br>

    <main id="main" style="margin-top: 2%;">
        <div class="container" style="width:60%; margin-left:auto !important; margin-bottom: 3%" id="cont">
            <div id="item" style="margin-left:15% !important;">
                <div style="display: flex;">
                    <img id="img0" src="data:image/jpeg;base64,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" alt="err">
                    
                    <div style=" margin-left:5px; width:100%; display: flex;">
                        <h3 id="tit">logitic 15 mouse (blue)</h3>
                        <h3 id="price">999$</h3>
                    </div>
                </div>
                
                <div id="inputs" style="display: flex;">
                    <button id="del">
                        delete
                    </button>

                    <input type="number" id="quant">
                </div>
            </div>
        </div> 
        <br>
        <p style="margin-left: 5%">total: 999$</p>
    </main>
    <script src="assets/js/cart.js"></script>
</body>
</html>

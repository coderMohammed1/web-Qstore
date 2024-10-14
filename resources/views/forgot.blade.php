<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password!</title>
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
    <div class="container" style="width:65%; margin-top: 60px">
        <form action="/resetp" method="POST">
            @csrf
            <label class="form-label">email:</label>
            <input name="remail" required type="email" class="form-control">

            <br>
            <button name="reset" style="width: 100%" type="submit" class="btn btn-outline-warning">Send!</button>
        </form>
    </div>

    @if(isset($Done))
            <div id="alert" class="alert alert-success">
                {{ $Done }} 
            </div>
    @endif
</body>
</html>
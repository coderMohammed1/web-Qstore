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
            <label class="form-label">Password:</label>
            <input placeholder="review password policy bellow!" name="newpass" required type="password" class="form-control">

            <br>
            <button name="change" style="width: 100%" type="submit" class="btn btn-outline-warning">Send!</button>
            <br>

            <br>
            <a style="width: 100%" class="btn btn-outline-success" href="/assets/html/passwords.html" target="_blank">Password policy</a>
        </form>
    </div>

    @if(isset($error))
            <div id="alert" class="alert alert-danger">
                {{ $error }} 
            </div>
    @endif

    @if(isset($Done))
    <div id="alert" class="alert alert-success">
        {{ $Done }} 
    </div>
    @endif
{{-- 
    <script>
        alert("Password: allowed symbols: 0-9 and a-z and A-Z and @ and # and _. Your password should contain 8 or more characters and at least: one capital letter, one small letter, and one number.");
    </script> --}}
</body>
</html>
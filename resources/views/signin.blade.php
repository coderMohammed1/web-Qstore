<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Signin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
        #alert{
            text-align: center !important;
            margin-top: 5px;
        }
    </style>
    <script src="https://www.google.com/recaptcha/api.js"></script>
</head>
<body>
    @include('nav')
    <main class="container mt-3">
        <form id="sigin" action="/signin" method="post"> 
            @csrf
            <label  class="form-label" for="">Email:</label>
            <input required type="email"  class="form-control" name="email" id="">

            <label  class="form-label" for="">Password:</label>
            <input required  class="form-control" type="password" name="password">

            <button class="g-recaptcha btn btn-outline-success mt-3" data-sitekey="{{config('services.recaptcha.site_key')}}" data-callback='onSubmit' data-action='submit' type="submit">login</button>
            <a class="btn btn-outline-dark mt-3" href="/register">signup instead</a>
            <a class="btn btn-outline-warning mt-3" href="/resetp">forgot your password?</a>
        </form>

        @if(isset($error))
            <div id="alert" class="alert alert-danger">
                {{ $error }} <!-- Display the error message -->
            </div>
        @endif

        @if(isset($succsess))
            <div id="alert" class="alert alert-success">
                {{ $succsess }} <!-- Display the error message -->
            </div>
        @endif
    </main>

    <script>
        function onSubmit(token) {
           document.getElementById("sigin").submit(); //form id
        }
    </script>
</body>
</html>
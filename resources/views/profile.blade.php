<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">

    <style>
        #alert{
            text-align: center !important;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    @include('nav')

    <main class="container mt-3">
        <form  action="/profile" method="post"> 
            @csrf
            <label  class="form-label" for="">First Name:</label>
            <input value="{{$_SESSION["info"]->First_Name}}" required type="text"  class="form-control" name="fname" id="">

            <label for="">Last name</label>
            <input value="{{$_SESSION["info"]->Last_name}}" required type="text" class="form-control" name="lname" id="">

            
            <label  class="form-label" for="">Birth data-sitekey:</label>
            <input value="{{$_SESSION["info"]->birthdate}}" required  class="form-control" type="date" name="bdate">

            <label  class="form-label" for="">Password:</label>
            <input  class="form-control" type="password" name="password" placeholder="Keep it empty in case if u do not want to change." minlength="8">

            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-outline-dark" type="submit">Update</button>
                <a class="btn btn-outline-warning" href="/assets/html/passwords.html" target="_blank">Review password policy</a>
            </div>
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

</body>
</html>
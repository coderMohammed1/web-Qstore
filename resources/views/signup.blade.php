<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
        #alert {
            text-align: center !important;
            margin-top: 5px;
        }
    </style>
     <script src="https://www.google.com/recaptcha/api.js"></script>
</head>

<body>
    @include("nav")
    <div class="container">
        <form id="sig" action="/register" method="POST">
            @csrf
            <label class="form-label" for="">First name:</label>
            <input maxlength="50" class="form-control" type="text" name="name" required>
            <br>

            <label class="form-label" for="">Last name:</label>
            <input maxlength="50" class="form-control" type="text" name="lname" required>
            <br>

            <label class="form-label" for="">Date of birth:</label>
            <input class="form-control" type="date" name="age" required>
            <br>

            <label class="form-label" for="">Email:</label>
            <input class="form-control" placeholder="please use a real email" name="email" type="email" required>
            <br>

            <label class="form-label" for="">Password:</label>
            <input class="form-control" name="password" type="password" placeholder="take a look at password rules bellow" required>
            <br>

            <label for="exampleSelect" class="form-label">register as:</label>
            <select required class="form-select" name="role" id="sel"  aria-label="Default select example">
                <option selected>Select an option</option>
                <option value="c">Customer</option>
                <option value="s">Seller</option>
            </select> 
            <br>

            <div id = "customerf"></div>
            <br>
            <button class="g-recaptcha btn btn-outline-dark" data-sitekey="{{config('services.recaptcha.site_key')}}" data-callback='onSubmit'  data-action='register' type="submit" name="reg_sub" id="mo">Register</button>
            <a class="btn btn-outline-success" href="/signin">Sign in instead</a>
            <a class="btn btn-outline-warning" href="/assets/html/passwords.html" target="_blank">Review password policy</a>
        </form>
    </div>

    @if(isset($error))
            <div id="alert" class="alert alert-danger">
                {{ $error }} 
            </div>
    @endif

    @if(isset($succsess))
            <div id="alert" class="alert alert-success">
                {{ $succsess }} 
            </div>
    @endif
    {{-- <script src="assets/js/signup.js"></script> --}}
    {{-- <script>
        alert("Password: allowed symbols: 0-9 and a-z and A-Z and @ and # and _. Your password should contain 8 or more characters and at least: one capital letter, one small letter, and one number.");
    </script> --}}
    <script>
        function onSubmit(token) {
           document.getElementById("sig").submit(); //form id
        }
    </script>
</body>
</html>

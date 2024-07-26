<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Activate your account!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
        #alert {
            text-align: center !important;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php require_once 'nav.php'; ?>

    @if(isset($token) and $token->roles == "c")
        <div class="container">
            <form action="/activate" method="POST">
                @csrf
                <label class="form-label" for="">country:</label>
                <input maxlength="50" class="form-control" type="text" name="country" required>
                <br>

                <label class="form-label" for="">city:</label>
                <input maxlength="50" class="form-control" type="text" name="city" required>
                <br>

                <label class="form-label" for="">street:</label>
                <input maxlength="50" class="form-control" type="text" name="street" required>
                <br>

                {{-- <input type="hidden" value="c" name="role"> --}}
                <button class="btn btn-outline-dark" type="submit" name="complete" id="mo" value={{$token->token}}>complete your data!</button>
            </form>
        </div>
    @endif


    @if(isset($token) and $token->roles == "s")
        <div class="container">
            <form action="/activate" method="POST">
                @csrf
                <button class="btn btn-outline-warning" type="submit" name="seller" id="mo" value={{$token->token}}>Activate!</button>
            </form>
        </div>  
    @endif

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
</body>
</html>
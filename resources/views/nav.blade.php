<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nav</title>
    <style>

      /* for phones navbar issue */
        .navbar-nav-scroll {
          --bs-scroll-height: auto !important;
        } 

        #logo {
            width: 50px;
        }
        #bar {
            width: 100% !important;
            justify-content: center;
        }
        .black-background {
          background-color: #737c85 !important;
        }
        .black-background a {
          color: rgb(205 201 235 / 90%) !important;
        }
    </style>
</head>
<body>

  @if (session_status() == PHP_SESSION_NONE) 
    <?php session_start(); ?>
  @endif

  <nav id="navers" class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img id="logo" src="{{ asset('imgs/webqstore.jpeg') }}" alt="error">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarScroll">
        <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;" id="bar">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/register">Signup</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/profile">Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="/signin">Login</a>
          </li>
          <li class="nav-item">
            @if(!isset($_SESSION["info"]))
              <a class="nav-link active" href="/">Home Page</a>
            @else
              @if(isset($_SESSION["cust"]))
                <a class="nav-link active" href="/customers">Home Page</a>
              @else
                <a class="nav-link active" href="/seller">Home Page</a>
              @endif
            @endif
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="/logout">Logout</a>
          </li>
          @if (isset($_SESSION["info"]) && $_SESSION["info"]->roles == "c")
          <li class="nav-item">
            <a class="nav-link active" href="/cart">Cart</a>
          </li>
          @endif
          @if (isset($_SESSION["info"]) && $_SESSION["info"]->roles == "s")
          <li class="nav-item">
            <a class="nav-link active" href="/orders">Orders</a>
          </li>
          @endif
        </ul>
      </div>
    </div>
  </nav>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  </script>
</body>
</html>

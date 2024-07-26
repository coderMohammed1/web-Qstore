<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
        #alert {
            text-align: center !important;
            margin-top: 20px; /* Adjust the margin-top value as needed */
            
        }
    </style>
</head>
<body>    
   <?php require_once 'nav.php'; ?>
   
   @if(isset($error))
   <div id="alert" class="alert alert-danger mx-auto w-50">
       {{ $error }} <!-- Display the error message -->
   </div>
  @endif
</body>
</html>
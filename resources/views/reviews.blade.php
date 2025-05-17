<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reviews</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link rel="stylesheet" href="/assets/css/review.css">
    <script src="https://www.google.com/recaptcha/api.js"></script>

</head>
<body>
    @include("nav")
    
    @foreach ($data as $rate) 
        <div style="margin-bottom:15px" class="card text-center mt-4">
            <div id="name" class="card-header">
                name: {{$rate['First_Name']}}
            </div>

            <div class="card-body">
                <p id="conta" class="card-text">{{$rate['review']}}</p>
            </div>

            <div class="card-footer text-muted" id="rate_box">
                Rating: {{$rate["rate"]}}/5
            
                 @if ($rate["user_id"] == $_SESSION["info"]->ID) {{-- so u only see the delete-button on ur own comments --}}
                    <form action="/review/delete" method="POST">
                        @csrf
                        <button type="submit" name="delp" class="delete-button" value="{{$rate['rvid']}}">
                            <i style="font-size:24px; color:red; margin-left:5px" class="fa delete-icon">&#xf014;</i>
                        </button>

                        <input name="prid" type="hidden" value="{{$rate['product_id']}}">
                    </form>
                @endif

            </div>            

        </div>
    @endforeach
    
    <footer id="box">
        <form class="container" id="for" method="post">
            @csrf
            <input required type="number" title="your rating out of 5" name="rating" id="rateme" max="5" min="0">
            <label for="" style="margin-right: 5px;"> /5</label>
               
            <textarea required style="width:100%;" class="form-control" id="tex" name="post" rows="1" autocomplete="off"></textarea>
            <button class="g-recaptcha btn btn-outline-dark" data-sitekey="{{config('services.recaptcha.site_key')}}" data-callback='onSubmit'  data-action='register' type="submit"  name="rate">Rate</button>
        </form>
    
        <a style="text-decoration: none; display: block; margin-left: 5px; width: 24px;" href="/review#navers">
            <i style="width:100%;" class="fa fa-arrow-up" aria-hidden="true"></i>
        </a>
    </footer>

    <script>
        function onSubmit(token) {
           document.getElementById("for").submit(); //form id
        }
    </script>
</body>
</html>
<?php
use Illuminate\Support\Facades\Http;

function recaptcha($recap){
    $g_response = Http::asForm()->post("https://www.google.com/recaptcha/api/siteverify",[
        'secret' => config('services.recaptcha.secret_key'),
        'response' => $recap,
        'remoteip' => \request()->ip()
    ]); 

    return $g_response;
}

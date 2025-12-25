<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,height=device-height, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'UniCrib') }}</title>
        <x-icon/>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <style>
            *{
                color:white;
            }
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head >
    <body style="background:linear-gradient(rgba(35,0,70,0.8),rgba(3,56,89,0.8));background-size: cover; background-position: center;background-repeat:no-repeat;height:100%;">
        <div style="height:auto;justify-content:center;margin-right:auto;margin-left:auto;margin-bottom:15%;margin-top:15%;">
            
            <div onmouseover="this.style.cursor='pointer'"style="background-image:url('{{asset('images/application-logo.jpeg')}}'),linear-gradient(rgba(35,0,70,0.8),rgba(3,56,89,0.8));background-size: cover; background-position: center;background-repeat:no-repeat;border-radius:10px;background-blend-mode: darken;position:relative;z-index:10;justify-content:space-between;margin-left:auto;margin-right:auto;opacity:0.9;width:fit-content;padding:50px;">
                <div style="height:auto;justify-content:center;width:100%;">
                    <a href="{{url('/')}}" style="all:unset;display:flex;align-items:center;text-align:center;flex-direction:column;justify-content:center;"><x-application-logo style="height:50px;border-radius:50px;width:50px;object-fit:cover;"/><h1 style="font-size:50px; font-weight:bold;color:white;">UniCrib</h1></a>
                        <fieldset style="border:2px gray solid; padding:10px 10px 10px 10px;border-radius:10px;">
                            <legend style="color:white;">Welcome</legend>
                            <br>
                            {{ $slot }}
                        </fieldset>
                </div>
            </div>
        </div>
    </body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8">

    <title>Driver app</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link href="{{ asset('assets/driver/css/styles.css') }}" rel="stylesheet">

    <link rel="dns-prefetch" href="//fonts.gstatic.com">

</head>

<body>
    
    @yield('content')
        
    @if(\App\Models\DriverAppSetting::pluck('is_see_map')->first())
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAN67x01Vtwzd3XUnoDerz_GKwPiU_QfTA"></script>
    @endif
    <script>
    var APP_URL = '{{env('APP_URL')}}';
    </script>
    <script src="//js.pusher.com/3.0/pusher.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/driver/js/bootbox.min.js') }}"></script>
    <script src="{{ asset('assets/driver/js/scripts.js') }}"></script>

    @include('driver.popups.packagesOftour')

</body>

</html>

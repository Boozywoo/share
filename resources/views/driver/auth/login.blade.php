<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html" ; charset="utf-8">

    <title>Driver app</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="{{ asset('assets/driver/css/login.css') }}" rel="stylesheet">
</head>

<body>
    <div class="simple-login-container">
        <h2>Вход в водительское приложение</h2>
        <br>
        <form method="POST" action="{{ route('driver.login') }}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12 form-group">
                    <input id="phone" placeholder="Мобильный телефон" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required autofocus>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    <input id="password" placeholder="Пароль" type="password" class="form-control" name="password" required autocomplete="current-password">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    <button type="submit" class="btn btn-block btn-login">
                        Войти
                    </button>
                </div>
            </div>
            @if(!env('COPYRIGHT_OFF'))
                <div class="row">
                    <div class="col-md-12" style="font-size: 12px; text-align: center;">
                        Разработка -
                        <a href="https://www.transport-manager.by">Transport Manager</a>
                    </div>
                </div>
            @endif
        </form>
    </div>
</body>
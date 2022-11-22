<!doctype html>
<html lang="ru">
<head>
    <meta http-equiv="refresh" content="{{ $seconds ?? 1 }};{{ $url }}">
</head>
    <body>
    Вы будете перенаправлены на <a href="{{ $url }}"> запрашиваемую страницу</a> через {{ $seconds ?? 'несколько' }} секунд.
    </body>
</html>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta property="og:image" content="url(/public/assets/index/images/for_clients/meta.png)"/>

    <title>@yield('title') {{ trans('admin.home.panel' )}}</title>

    <script src="https://api-maps.yandex.ru/2.1/?apikey={{ env('YANDEX_API_KEY') }}&lang=ru_RU" async type="text/javascript"></script>

    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <link rel="shortcut icon" href="{{asset('/favicon.ico')}}" type="image/x-icon"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" async></script>

    <link rel="apple-touch-icon" href="{{asset('/apple-touch-icon.png')}} "/>
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('/apple-touch-icon-57x57.png')}} "/>
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset('/apple-touch-icon-72x72.png')}} "/>
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('/apple-touch-icon-76x76.png')}} "/>
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset('/apple-touch-icon-114x114.png')}} "/>
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset('/apple-touch-icon-120x120.png')}} "/>
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset('/apple-touch-icon-144x144.png')}} "/>
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset('/apple-touch-icon-152x152.png')}} "/>
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/apple-touch-icon-180x180.png')}} "/>
    @include('panel::partials.styles')
</head>
<body class="fixed-sidebar @yield('body_class') @yield('body_bg')">
    <div class="{!! !stripos(url()->current(), '/admin/auth/login') ? 'test_img' : 'default_login-page_bg' !!}">
        @yield('root')

        <div id="blueimp-gallery" class="blueimp-gallery">
            <div class="slides"></div>
            <h3 class="title"></h3>
            <a class="prev">‹</a>
            <a class="next">›</a>
            <a class="close">×</a>
            <a class="play-pause"></a>
            <ol class="indicator"></ol>
        </div>
        @php($setting = \App\Models\Setting::query()->first())
        <script>
            var urlUpload = '{{ route('panel::upload') }}';
            var urlUploadRedactor = '{{ route('panel::upload-redactor') }}';
            var urlUploadFroala = '{{ route('panel::upload-froala') }}';
            var InputCodeValue = '{{ config('app.inputCode') }}';
            var APP_URL = '{{env('APP_URL')}}';
            window.user_sip = '{!!  auth()->user() ? auth()->user()->sip : null !!}';
        </script>

        @include('panel::partials.scripts')
        @include('admin.tours.popups.edit')
        @include('admin.tours.popups.editRent')
        @include('admin.packages.popups.add')
        @include('admin.packages.popups.index')
    </div>
</body>
</html>

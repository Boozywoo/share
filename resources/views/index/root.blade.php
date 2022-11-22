<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@if(!empty($setting->index_title))
            {{$setting->index_title}}
        @else
            Пассажирские перевозки | transport-manager.by
        @endif
    </title>

    <meta name="description" @if($setting->index_description) content="{{$setting->index_description}}"
          @else content="@yield('meta_description')" @endif/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="yandex-verification" content="c608577f82a51b1f"/>

    <meta property="og:image" content="url(/public/assets/index/images/for_clients/meta.png)"/>

    @if(env('APP_ENV') == 'production') 
        {!! \App\Models\Setting::getField('seo_head') ?? '' !!}
    @endif
    
    @include('index.home.partials.thems-styles')
    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/panel/css/toast.css') }}">
    <link rel="shortcut icon" href="{{asset('/favicon.ico')}}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{ asset('assets/index/css/template.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/index/css/secondStylesFile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/index/css/orderPage.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/index/css/shedulePage.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/index/css/personalCabinet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/index/css/thirdStylesFile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/index/css/adaptiveDesign.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/index/css/adaptiveDesignDifferentPages.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/index/css/changeBusOrientation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/index/css/style.css') }}">
    @yield('meta_og')
    @yield('meta_pagination')

    @if(!empty(\App\Models\Setting::getField('field_code_jivo')))
        {!! html_entity_decode(\App\Models\Setting::getField('field_code_jivo')) !!}
    @endif
    
</head>

<body class="index">
{!! \App\Models\Setting::getField('seo_body') ?? '' !!}
@yield('main')
@php($timeHash = time())

<script src="{{ asset('assets/index/js/markup/reviewsSlider.js?'.$timeHash) }}"></script>
<script src="{{ asset('assets/index/js/markup/shedulePage.js?'.$timeHash) }}"></script>
<script src="{{ asset('assets/index/js/markup/scrollToTop.js?'.$timeHash) }}"></script>
<script src="{{ asset('assets/index/js/markup/adaptiveMainMenu.js?'.$timeHash) }}"></script>
<script src="{{ asset('assets/index/js/markup/createScrollToPageSections.js?'.$timeHash) }}"></script>
{{--<script src="{{ asset('assets/index/js/markup/stickerMainMenu.js?'.$timeHash) }}"></script>--}}
<script src="{{ asset('assets/index/js/markup/setLinksToMainPageToItems.js?'.$timeHash) }}"></script>
<script src="{{ asset('assets/index/js/markup/showHideAuthorizationPopups.js?'.$timeHash) }}"></script>
<script src="{{ asset('assets/index/js/markup/order/compareTicketBlocks.js?'.$timeHash) }}"></script>
<script src="{{ asset('assets/index/js/markup/order/editCustomerData.js?'.$timeHash) }}"></script>
<script src="{{ asset('assets/index/js/markup/order/orderAccordion.js?'.$timeHash) }}"></script>
<script src="{{ asset('assets/index/js/markup/personalCabinet/myTickets.js?'.$timeHash) }}"></script>
<script src="{{ asset('assets/index/js/markup/personalCabinet/reviewPopup.js?'.$timeHash) }}"></script>
<script src="{{ asset('assets/index/js/markup/addClassNameToBody.js?'.$timeHash) }}"></script>
<script src="{{ asset('assets/index/js/markup/changeActiveDay.js?'.$timeHash) }}"></script>
<script src="{{ asset('assets/index/js/main.js?'.$timeHash) }}"></script>
<script src="{{ asset('assets/index/js/app.js?'.$timeHash) }}"></script>

@stack('scripts')
@stack('popups')
</body>
</html>
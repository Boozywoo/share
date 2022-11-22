<div class="registrationContacts mainWidth">
    <div class="contacts left">
        <div class="custom">
            {{--<a class="topLogo left" href="{{ route('index.home') }}">
                <div class="custom">
                    @if($setting->mainImage)
                        <img src="{{ $setting->mainImage->load() }}">
                    @endif
                </div>
            </a>--}}
            @if($setting->company_name)
            <p class="span text-dark">{{ $setting->company_name }}</p>
            {{--<p class="span">сервис пассажирских перевозок</p>--}}
            @endif
            <p class="span clarification">
            </p>
        </div>
    </div>

    @if($setting->android_link)
    <a class="left" href="{{ $setting->android_link }}" style="position: relative; z-index: 2;">
        <img id="android" src="/assets/index/images/android.png">
    </a>
    @endif

    @if($setting->ios_link)
    <a href="{{ $setting->ios_link }}" style="position: relative; z-index: 2;">
        <img id="ios" src="/assets/index/images/ios.png">
    </a>
    @endif

    <div class="registration right">
        @if(auth()->user() && auth()->user()->client)
        <div class="custom authorization">
            <ul>
                <li><a class="text-dark" href="{{ route('index.profile.settings.index') }}">{{trans('index.profile.personal_account')}}</a></li>
                <li><a class="text-dark" href="{{ route('index.auth.logout') }}">{{ trans('admin.filter.exit') }}</a></li>
                <li>
                    @include('index.partials.elements.auth.language')
                </li>
                {{--<li><a class="downloadApplication">{{trans('index.partials.download_app')}}</a></li>--}}
                <br style="clear:both" />
            </ul>
            @include('index.partials.elements.auth.register')
            @include('index.partials.elements.auth.entry')
        </div>
        @else
        <div class="custom authorization">
            
            <ul class="my-row">

                <li><a class="showHideRButton text-dark">{{ trans('index.authorization.registration') }}</a></li>
                <li><a class="showHideEntButton text-dark">{{ trans('index.messages.auth.login') }}</a></li>
                <li>@include('index.partials.elements.auth.language')</li>

                {{--<li><a class="downloadApplication">{{trans('index.partials.download_app')}}</a></li>--}}
                <br style="clear:both" />
            </ul>
            @include('index.partials.elements.auth.register')
            @include('index.partials.elements.auth.entry')
        </div>
        @endif
    </div>
    <br style="clear: both">
</div>
@include('index.partials.header.menu')
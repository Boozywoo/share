<div class="menuAndLogoWrapp" style="z-index: 1000">
    <div class="menuAndLogo mainWidth">
        {{--<div class="showHideMainMenuButtWrapp">
                <a class="showHideMainMenuButt">
                    <div></div>
                    <div></div>
                    <div></div>
                </a>
        </div>--}}
        <a class="topLogo left" href="{{ route('index.home') }}">
            <div class="custom">
                @if($setting->mainImage)
                    <img src="{{ $setting->mainImage->load() }}">
                @endif
            </div>
        </a>
        <div class="right">
            <ul class="nav menu topMenu mainPage jmoddiv jmodinside">
                @if($setting->main_site)
                    <li class="default">
                        <a href="{{ $setting->main_site }}">{{trans('index.partials.return_to_website')}}</a>
                    </li>
                @endif

                <li class="default current mainPage {{ request()->url() == route('index.home') ? 'menu_active' : '' }} ">
                    <a href="{{ route('index.home') }}">{{trans('index.partials.checkout')}}</a>
                </li>
               {{-- @if(auth()->user() && auth()->user()->client)
                    <li class="hiddenForDesctop personalCabinet {{ str_contains(request()->url(), route('index.profile.settings.index')) ? 'menu_active' : '' }}">
                        <a class="personalArea" href="{{ route('index.profile.settings.index') }}">{{trans('index.profile.personal_account')}}</a>
                    </li>
                @endif--}}
                {{--<li class="hiddenForDesctop checkTicket">--}}
                    {{--<a href="check_ticket.php">{{trans('index.partials.check_ticket')}}</a>--}}
                {{--</li>--}}
                {{--<li class="about">
                    <a href="{{ route('index.page', 'o-nas') }}">{{trans('index.partials.about_company')}}</a>
                </li>--}}
            </ul>
        </div>
    </div>
</div>				
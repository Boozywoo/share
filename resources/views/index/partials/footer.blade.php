<footer>
    <div class="blocksWrapper">
        <div class="leftBlock">
            <div class="custom">
                {{--@if($setting->mainImage)
                    <img class="footerLogo" src="{{ $setting->mainImage->load() }}">
                @endif--}}
                <p class="adress">{{ $setting->address }}</p>
                <p>
                    {!! $setting->text_footer !!}
                </p>
            </div>
        </div>
        <div class="centralBlock">
            <div class="custom">
                <div class="footerContacts">
                    {{--<p class="operators">
                        --}}{{--<span>(</span>--}}{{--
                        --}}{{--<img src="{{ asset('assets/index/images/mobileLogos/velcom.png') }}"><span>velcom,</span>
                        <img src="{{ asset('assets/index/images/mobileLogos/mts.png') }}"><span>мтс</span>--}}{{--
                        --}}{{--<img src="{{ asset('assets/index/images/mobileLogos/life.png') }}"><span>life,</span>
                        <img src="{{ asset('assets/index/images/mobileLogos/viber.png') }}"><span>viber</span>--}}{{--
                        --}}{{--<span> )</span>--}}{{--
                    </p>--}}
                    @php($setting->phone_one = explode(';', $setting->phone_one))
                    @php($setting->phone_two = explode(';', $setting->phone_two))
                    @php($setting->phone_tree = explode(';', $setting->phone_tree))
                    <p class="phone operators">
                        {{ $setting->phone_one[0] }}
                        @if(in_array('viber', $setting->phone_one))
                            <img src="{{ asset('assets/index/images/mobileLogos/viber.png') }}">
                        @endif
                        @if(in_array('whatsapp', $setting->phone_one))
                            <img src="{{ asset('assets/index/images/mobileLogos/whatsapp.png') }}">
                        @endif
                    </p>
                    <p class="phone operators">
                        {{ $setting->phone_two[0] }}
                        @if(in_array('viber', $setting->phone_two))
                            <img src="{{ asset('assets/index/images/mobileLogos/viber.png') }}">
                        @endif
                        @if(in_array('whatsapp', $setting->phone_two))
                            <img src="{{ asset('assets/index/images/mobileLogos/whatsapp.png') }}">
                        @endif
                    </p>
                    <p class="phone operators">
                        {{ $setting->phone_tree[0] }}
                        @if(in_array('viber', $setting->phone_tree))
                            <img src="{{ asset('assets/index/images/mobileLogos/viber.png') }}">
                        @endif
                        @if(in_array('whatsapp', $setting->phone_tree))
                            <img src="{{ asset('assets/index/images/mobileLogos/whatsapp.png') }}">
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="rightBlock">
            <div class="custom">
                <div class="socialLinks">
                    <p class="title">{{trans('index.partials.soc_net')}}</p>
                    <ul class="text-center">
                        @if ($setting->account_ok)
                            <li><a href="{{$setting->account_ok}}">ok</a></li>
                        @endif
                        @if ($setting->account_vk)
                            <li><a href="{{$setting->account_vk}}">vk</a></li>
                        @endif
                        @if ($setting->account_f)
                            <li><a href="{{$setting->account_f}}">f</a></li>
                        @endif
                        @if ($setting->account_i)
                            <li><a href="{{$setting->account_i}}">I</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="paymentMethods">
                <div class="custom">
                    {{--<p class="title">{{trans('index.partials.payments_methods')}}</p>--}}
                    {{--<ul class="cards">--}}
                    {{--<li><img src="/images/cards/1.png"></li>--}}
                    {{--<li><img src="/images/cards/2.png"></li>--}}
                    {{--<li><img src="/images/cards/3.png"></li>--}}
                    {{--<li><img src="/images/cards/4.png"></li>--}}
                    {{--<li><img src="/images/cards/5.png"></li>--}}
                    {{--<li><img src="/images/cards/6.png"></li>--}}
                    {{--</ul>--}}
                </div>
            </div>
            <div class="bottomMenu"></div>
        </div>
        <br style="clear: both">
        <div class="copyright">
            {{--<div class="custom">
                <p>{{ $setting->copyright }}</p>
            </div>--}}
            @if(!env('COPYRIGHT_OFF'))
                <div class="custom">
                    <p>{{trans('index.partials.development')}} - <a href="//transport-manager.by/">Transport
                            Manager</a> ©</p>
                </div>
            @endif
        </div>
    </div>
</footer>
<a class="upButton"></a>
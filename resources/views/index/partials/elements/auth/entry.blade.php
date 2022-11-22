<div class="entrfFormWrapperPU">
    {!! Form::open(['route' => 'index.auth.do-login', 'class' => 'enterForm js_ajax-form']) !!}        
        <div class="row-entry">
            <div style="position: absolute">
                @php($codes = \App\Models\Setting::pluck('phone_codes')->first())
                @php($phone_codes = [])
                @php($phoneCodes = \App\Models\Client::CODE_PHONES)
                @foreach($phoneCodes as $key => $phoneCode)
                    @if(in_array($key, explode(",", $codes)))
                        @php($phone_codes[$key] = '+'.$phoneCode)
                    @endif
                @endforeach
                {!! Form::select('phone-code', $phone_codes, \App\Models\Route::first()->phone_code,['style' => 'padding: 10% 0; transition: border-color .15s ease-in-out 0s,
                    box-shadow .15s ease-in-out 0s; font-size: 14px;', 'id' => 'country-codes']) !!}
            </div>
            <div class="phone-entry">
                {!! Form::text('phone', '+', ['id' => 'password', 'class' => 'js_mask-phone', 
                    'placeholder' => trans('index.profile.phone')]) !!}
            </div>
        </div>
        <div class="passwInpWrapper">
            {!! Form::password('password', ['placeholder' => trans('index.profile.password')]) !!}
            {{--<a href="#" class="dontRemember">{{trans('index.partials.dont_remember')}}</a>--}}
        </div>
        <p class="acceptanceOfConditions left" style="margin-top: 11px">
            <span>
               <a id="myBtnPass"  href="#">{{trans('index.partials.forget_password')}}</a>
            </span>
        </p>


        <input type="submit" class="enterButton" value="{{ trans('index.messages.auth.login') }}">
        <p class="remember right"><input type="checkbox" name="remember" value="1" id="rememberMe">
            <label for="rememberMe">{{trans('index.partials.remember')}}</label>
        </p>
        <br style="clear: both"/>
        <div class="divigingLineWrapp">
            <div class="divigingLine"></div> 
        </div>
        {{--<p class="enterInscription">{{trans('index.partials.login_with_help')}}</p>--}}
        <div class="enterFromSocialWrapp">
            @include('index.partials.elements.auth.social')
        </div>
    {!! Form::close() !!}
<!-- The Modal -->
    <div id="myModalPass" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="closepas">&times;</span>
            {!! Form::open(['route' => 'index.auth.forget', 'class' => 'enterForm js_ajax-form']) !!}
            <b>Востановление пароля</b><br>
            <div class="passwInpWrapper">
            {!! Form::text('phone', '+', ['class' => 'js_mask-phone', 'placeholder' => trans('index.profile.phone'), 'style' => 'width: 100%']) !!}
            </div>
            <input type="submit" class="forgetButton"  value="{{ trans('index.messages.auth.forget_button') }}">
            {!! Form::close() !!}
            <br style="clear: both">
        </div>
    </div>
@include('index.partials.elements.pop_up.forget_password')

</div>
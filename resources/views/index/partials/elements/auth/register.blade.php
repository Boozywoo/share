<div class="registrationFormWrapperPU">
    {!! Form::open(['route' => 'index.auth.do-register', 'class' => 'registraitonForm js_ajax-form']) !!}
        {!! Form::text('first_name', null, ['class' => 'left', 'placeholder' => trans('index.profile.name')]) !!}
        {!! Form::text('email', null, ['class' => 'right', 'placeholder' => 'Email']) !!}
        <div class="row-reg">
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
                    box-shadow .15s ease-in-out 0s; font-size: 13.8px;', 'id' => 'country-codes']) !!}
            </div>
            <div class="phone-reg">
                {!! Form::text('phone', '+', ['id' => 'password', 'class' => 'js_mask-phone',
                    'placeholder' => trans('index.profile.phone')]) !!}
            </div>
            {!! Form::text('password', null, ['class' => 'left', 'placeholder' => trans('index.profile.password')]) !!}
        </div>
        
        <p class="acceptanceOfConditions familiarized left">
            <input type="checkbox" name="confirm" value="1">
            <span>{{trans('index.partials.acquainted')}}
            <!-- <a href="{{ route('index.page', 'usloviya-polzovatelskogo-soglasheniya') }}" target="_blank"> -->
                <a id="myBtn"  href="#">
                <!-- <a  href="pravila.docx"> -->
                {{trans('index.partials.rules')}}</a>
            </span>
        </p>
{{--        {!! Form::text('password_confirm', null, ['class' => 'right', 'placeholder' => trans('index.profile.confirm_of_pass')]) !!}--}}

        <div class="bottomBlock">
            <!--
            <p class="statusTitle">Ваш статус:</p>
            <ul class="status left">
                <li><a>Инвалид I или II группы</a></li>
                <li><a class="active">Студент</a></li>
                <li><a>Пенсионер</a></li>
            </ul>
            -->
            <input type="submit" class="register right" value="{{trans('index.schedules.continue')}}">
            <div class="bottomBorder"></div>
            {{--<p class="enterInscription">{{trans('index.partials.login_with_help')}}</p>--}}
            <div class="enterFromSocialWrapp">
                @include('index.partials.elements.auth.social')
            </div>
        </div>
    {!! Form::close() !!}
    @include('index.partials.elements.pop_up.rules_registration')
</div>
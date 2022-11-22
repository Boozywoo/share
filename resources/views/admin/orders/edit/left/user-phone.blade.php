<div class="form-group" >
    <div class="input-group ">
        <div style="width: 100%">
            <select style="-webkit-appearance: none; -moz-appearance: none; appearance: none;" id="country"
                    class="form-control">
                @if (isset($client))
                    @foreach(\App\Models\Client::CODE_PHONES as $abbr => $code)
                        <option @if ($client->code_phone == $code) selected @endif value="{{$abbr}}">+{{$code}}</option>
                    @endforeach
                @else
                    @php($phoneCode = config('app.inputCode'))
                    @if (isset($order, $order->tour))
                        @php($phoneCode = $order->tour->route->phone_code)
                    @elseif(isset($route))
                        @php($phoneCode = $route->phone_code)
                    @elseif(isset($tour, $tour->route))
                        @php($phoneCode = $tour->route->phone_code )
                    @endif

                    @foreach(\App\Models\Client::CODE_PHONES as $abbr => $code)
                        <option @if ($phoneCode === $abbr) selected @endif value="{{$abbr}}">+{{$code}}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <span class="input-group-addon"><span class="fa fa-phone"></span></span>
        <input
            @if (isset($client))
                value="{{$client->edit_phone}}"
            @elseif (isset($incomming_phone))
                value="{{substr($incomming_phone, ($phoneCode == 'ru' ? -10 : -9 ))}}"
            @endif
            data-url-client-info="{{ route('admin.orders.getClientInfo') }}" autofocus
            type="text" id="phone" name="phone" 
            class="form-control js_orders-client-phone"
        >

        <span class="btn btn-warning input-group-addon js_orders-client-search">
            <span class="fa fa-search"></span>
        </span>
    </div>
    <p class="error-block"></p>
</div>

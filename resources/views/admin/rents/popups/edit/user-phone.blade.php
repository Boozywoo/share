<div class="form-group">
    <div class="input-group">
        <div style="width: 100%">
            <select style="-webkit-appearance: none; -moz-appearance: none; appearance: none;" id="country"
                    class="form-control">
                @if (isset($client))
                    <option @if ($client->code_phone == 7) selected @endif value="ru">+7</option>
                    <option @if ($client->code_phone == 375) selected @endif value="by">+375</option>
                    <option @if ($client->code_phone == 380) selected @endif value="ua">+380</option>
                @else
                    <option @if (config('app.inputCode') === 'ru') selected @endif value="ru">+7</option>
                    <option @if (config('app.inputCode') === 'by') selected @endif value="by">+375</option>
                    <option @if (config('app.inputCode') === 'ua') selected @endif value="ua">+380</option>
                @endif


            </select>
        </div>
        <span class="input-group-addon"><span class="fa fa-phone"></span></span>
        <input
                @if (isset($client))
                value="{{$client->edit_phone}}"
                @elseif (isset($incomming_phone))
                value="{{substr($incomming_phone, -9)}}"
                @endif
                data-url-client-info="rents/getClientInfo"
                type="text" id="phone" name="phone" class="form-control js_orders-client-phone">

        <span class="btn btn-warning input-group-addon js_orders-client-search">
            <span class="fa fa-search"></span>
        </span>
    </div>
    <p class="error-block"></p>
</div>

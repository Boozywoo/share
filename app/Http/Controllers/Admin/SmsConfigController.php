<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config;
use App\Models\SmsConfig;
use App\Models\SmsProvider;
use Illuminate\Support\Facades\DB;

class SmsConfigController extends Controller
{

    private $_fields = ['sms_send', 'sms_sender', 'sms_api_login', 'sms_api_password', 'is_latin'];
    private $type = 'sms';

    public function edit()
    {
        $config = Config::whereType('sms')->get();
        $smsconfig = SmsConfig::orderby('orderby', 'asc') ->get();
        $providers = SmsProvider::orderby('default', 'desc')->get();
        return view('admin.config.sms.edit', compact('config', 'smsconfig', 'providers'));
    }

    public function store(Request $request)
    {

        if ($request->has('provider_name') && is_array($request->provider_name)) {

            $smsProviders = SmsProvider::all();

            $exists = data_get($smsProviders, '*.id');
            $afterUpdate = array_keys($request->provider_name);

            $toDelete = array_diff($exists, $afterUpdate);

            SmsProvider::destroy($toDelete);
            foreach ($toDelete as $td) {
                SmsConfig::where('id_smsprovider','=',$td)->delete();
            }

            foreach ($request->provider_name as $key => $value) {

                if (is_numeric($key)) {
                    SmsProvider::where('id', $key)
                      ->update([
                        'name' => $value,
                        'number_prefix' => $request->provider_number_prefix[$key],
                        'sms_send' => $request->provider_sms_send[$key],
                        'sms_sender' => $request->provider_sms_sender[$key],
                        'sms_api_login' => $request->provider_sms_api_login[$key],
                        'sms_api_password' => $request->provider_sms_api_password[$key],
                        'default' => ($request->provider_default == $key) ? true : false,
                        'active' => $request->provider_active[$key],
                        'is_latin' => $request->provider_is_latin[$key]
                      ]);
                } else {
                    $smsprov = SmsProvider::create([
                      'name' => $value,
                      'number_prefix' => $request->provider_number_prefix[$key],
                      'sms_send' => ($request->provider_sms_send[$key]),
                      'sms_sender' => $request->provider_sms_sender[$key],
                      'sms_api_login' => $request->provider_sms_api_login[$key],
                      'sms_api_password' => $request->provider_sms_api_password[$key],
                      'default' => ($request->provider_default == $key) ? true : false,
                      'active' => $request->provider_active[$key],
                      'is_latin' => $request->provider_is_latin[$key]
                    ]);
                    $data = array (
                        array ('orderby'=>'1', 'key'=>'date', 'id_smsprovider' => $smsprov->id),
                        array ('orderby'=>'2', 'key'=>'auto', 'id_smsprovider' => $smsprov->id),
                        array ('orderby'=>'3', 'key'=>'booking', 'id_smsprovider' => $smsprov->id),
                        array ('orderby'=>'4', 'key'=>'price', 'id_smsprovider' => $smsprov->id),
                        array ('orderby'=>'5', 'key'=>'places_count', 'id_smsprovider' => $smsprov->id),
                        array ('orderby'=>'6', 'key'=>'to', 'id_smsprovider' => $smsprov->id),
                        array ('orderby'=>'7', 'key'=>'from', 'id_smsprovider' => $smsprov->id),
                        array ('orderby'=>'8', 'key'=>'driver_phone', 'id_smsprovider' => $smsprov->id),
                        array ('orderby'=>'9', 'key'=>'places', 'id_smsprovider' => $smsprov->id),
                        array ('orderby'=>'10', 'key'=>'driver_name', 'id_smsprovider' => $smsprov->id),
                        array ('orderby'=>'11', 'key'=>'ticket', 'id_smsprovider' => $smsprov->id),
                        array ('orderby'=>'12', 'key'=>'info', 'id_smsprovider' => $smsprov->id),
                        array ('orderby'=>'13', 'key'=>'pay_link', 'id_smsprovider' => $smsprov->id),
                    );
                    SmsConfig::insert($data);

                }

            }
        }

        $defaultProvider = SmsProvider::find($request->provider_default);

        if ($defaultProvider) {
            foreach ($this->_fields as $field) {
                if ($defaultProvider[$field]) {
                    Config::where('type', $this->type)
                      ->where('key', $field)
                      ->update(['value' => $defaultProvider[$field]]);
                }
            }
        }


        $smsconfig = SmsConfig::orderBy('orderby', 'asc')->get();
        foreach ($smsconfig as $field) {
            if ($request->has($field->key)) {
                $show = true;
            } else {
                $show = false;
            }

            SmsConfig::where('id', $field->id)
              ->update(['show' => $show]);
        }

        return $this->responseSuccess();
    }

    public function moveup(Request $request) {
        \DB::table('sms_config')->where('orderby','=',$request->orderby-1)->where('id_smsprovider','=',$request->smsprovider)->increment('orderby');
        \DB::table('sms_config')->where('id','=',$request->idrow)->where('id_smsprovider','=',$request->smsprovider)->decrement('orderby');
        return array($request->smsprovider, $request->orderby);
    }

    public function movedown(Request $request) {
        SmsConfig::where('orderby','=',$request->orderby+1)->where('id_smsprovider','=',$request->smsprovider)->decrement('orderby');
        SmsConfig::where('id','=',$request->idrow)->where('id_smsprovider','=',$request->smsprovider)->increment('orderby');
        return array($request->smsprovider, $request->orderby);
    }
}
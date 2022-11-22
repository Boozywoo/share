<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsConfig extends Model
{

    protected $table = 'sms_config';

    protected $fillable = [
        'orderby', 'key', 'show'
    ];

    protected $casts = [
      'id' => 'integer',
      'orderby'=>'integer',
      'key'=>'string',
      'scoring'=>'boolean'
    ];

    public static function getConfig($phone='') {
        $SpCur = null;
        $SmsProviders = SmsProvider::all();
        foreach ($SmsProviders as $sp) {
            if(preg_match("/^$sp->number_prefix/", $phone)) {
                $SpCur = $sp;
            }
        }
        if($SpCur == null) $SpCur = SmsProvider::where('default', '=', 1)->first();
        $records = self::where('id_smsprovider','=',$SpCur->id)->orderBy('orderby', 'asc')->get();
        $toArray = $records->keyBy('key')->toArray();
        return $toArray;
    }
}
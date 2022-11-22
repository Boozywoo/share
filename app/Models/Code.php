<?php

namespace App\Models;

use App\Traits\ClearPhone;
use App\Traits\PhoneTrait;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use PhoneTrait, ClearPhone;
    
    protected $fillable = [
        'order_id', 'code', 'phone'
    ];

    //Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}

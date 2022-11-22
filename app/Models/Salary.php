<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'user_id', 'admin_id', 'sum', 'currency_id'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id','id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}

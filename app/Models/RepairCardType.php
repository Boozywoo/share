<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairCardType extends Model
{
    protected $fillable = ['name'];


    public function repair_cards()
    {
        return $this->hasMany(RepairCard::class, 'repair_card_type_id', 'id')->with('pivot_items');
    }

    public function items()
    {

        return $this->belongsToMany(RepairCardTemplate::class, 'repair_card_type_items', 'type_id', 'template_id');
    }
}

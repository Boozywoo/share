<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairCard extends Model
{
    protected $fillable = ['repair_card_type_id', 'repair_id', 'comment'];

    public function repair()
    {
        return $this->hasOne(Repair::class, 'id', 'repair_id');
    }

    public function template()
    {
        return $this->hasOne(RepairCardType::class, 'id', 'repair_card_type_id');
    }

    public function items()
    {
        return $this->belongsToMany(RepairCardTemplate::class, 'repair_card_items', 'repair_card_id', 'repair_card_template_id')->withPivot(['comment', 'id']);
    }

    public function pivot_items()
    {
        return $this->hasMany(RepairCardItem::class);
    }
}

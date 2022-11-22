<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairCardTemplate extends Model
{
    protected $fillable = ['name', 'parent_id', 'company_id', 'is_photo', 'is_comment'];

    public function childs()
    {
        return $this->hasMany(RepairCardTemplate::class, 'parent_id');
    }
    public function parent()
    {
        return $this->hasOne(RepairCardTemplate::class, 'id','parent_id');
    }

    public function types()
    {
        return $this->belongsToMany(RepairCardType::class, 'repair_card_type_items', 'template_id', 'type_id');
    }
}

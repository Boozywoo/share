<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class WishesHistory extends Model
{

    public function getInstanceData()
    {
        return $this->instance::where('id', '=', $this->instance_id)->first();
    }

}

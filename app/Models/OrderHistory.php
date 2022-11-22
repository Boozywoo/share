<?php
/**
 * Created by PhpStorm.
 * User: Hohol
 * Date: 12/03/2019
 * Time: 10:48
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHistory  extends Model
{
    protected $table = 'order_history';

    protected $fillable = [
        'order_id', 'action', 'source', 'client_id', 'comment', 'operator_id',
    ];

    const ACTIVE_CREATE = 'create';
    const ACTIVE_UPDATE = 'update';
    const ACTIVE_CANCEL = 'cancel';
    const ACTIVE_DELETE = 'delete';
    const ACTIVE_RECOVER = 'recover';


    //Relationships

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }



    //Scopes
}
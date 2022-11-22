<?php

namespace App\Models;

use App\Traits\ImageableTrait;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use ImageableTrait;
    protected $fillable = ['name', 'status', 'comment', 'user_id', 'driver_id', 'incident_template_id', 'department_id', 'company_id'];
    protected $dates = ['date_exec'];

    const STATUS_OPEN = 'open';
    const STATUS_DECIDED = 'decided';
    const STATUS_NOT_DECIDED = 'not_decided';

    const STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_DECIDED,
        self::STATUS_NOT_DECIDED
    ];

    const IMAGE_TYPE_IMAGE = 'image';

    const IMAGES_PARAMS = [
        self::IMAGE_TYPE_IMAGE => [
            'multiple' => true,
            'params' => [
                'admin' => [
                    'w' => 50,
                    'fit' => 'max',
                ],
            ],
        ],
    ];

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function template()
    {
        return $this->belongsTo(IncidentTemplate::class, 'incident_template_id', 'id');
    }

    //
    public function getPermissionAttribute(){
        $user = auth()->user();
        if(!$user){
            return false;
        }

        if($this->company_id != 0 && $user->company_id != $this->company_id){
            return false;
        }
        if($user->id == $this->user_id){
            return true;
        }
        if($user->department_id == $this->department_id){
            return true;
        }
        if($user->roles->whereIn('slug',Role::ROLES)->count() > 0){
            return true;
        }
        return false;

    }
}

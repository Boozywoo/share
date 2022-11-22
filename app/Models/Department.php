<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'director_id', 'company_id', 'superdepartment_id'];


    //relationships

    public function director()
    {
        return $this->hasOne(User::class, 'id' ,'director_id');
    }

    public function company(){

        return $this->belongsTo(Company::class);
    }

    public function users(){

        return $this->hasMany(User::class,'department_id','id');
    }

    // all subdepartments of this department
    public function subdepartments()
    {
        return $this->hasMany(Department::class, 'superdepartment_id');
    }

    // superdepartment, for which this department is subdepartment
    public function superdepartment()
    {
        return $this->belongsTo(Department::class, 'superdepartment_id');
    }

    public function buses()
    {
        return $this->belongsToMany(Bus::class, 'department_bus');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPlace extends Model
{
    protected $fillable = [
        'order_id', 'number', 'appearance', 'price', 'status_id', 'status_old_price',
        'is_handler_price','is_return_ticket',
        'surname', 'name', 'patronymic', 'passport', 'card', 'birth_day', 'station_from_id', 'station_to_id',
        'phone', 'email', 'doc_type', 'doc_number', 'gender', 'country_id'
    ];

    const FILLABLE_TEXT = ['first_name', 'last_name', 'middle_name', 'phone', 'email', 'passport'];
    const FILLABLE_ALL = ['first_name', 'last_name', 'middle_name', 'phone', 'email', 'passport', 'doc_type', 'doc_number', 'gender', 'country_id', 'birth_day'];

    protected $dates = ['birth_day'];
    protected $casts = [
        'price' => 'float',
    ];

    public function getFullNameAttribute()
    {
        return $this->surname . ' ' . $this->name . ' ' . $this->patronymic;
    }

    //Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function socialStatus()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class)->withPivot('old_price', 'new_price')->orderBy('pivot_old_price', 'desc');
    }

    public function getFirstNameAttribute() {
        return $this->name;
    }

    public function setFirstNameAttribute($text)
    {
        return $this->attributes['name'] = $text;
    }

    public function getMiddleNameAttribute() {
        return $this->patronymic;
    }

    public function setMiddleNameAttribute($text)
    {
        return $this->attributes['patronymic'] = $text;
    }

    public function getLastNameAttribute() {
        return $this->surname;
    }

    public function setLastNameAttribute($text)
    {
        return $this->attributes['surname'] = $text;
    }

    public function getInitialsAttribute()
    {
        return $this->surname . " " . mb_substr($this->name,0, 1) . "." . mb_substr($this->patronymic,0, 1).".";
    }

    public function transferAddress($getCity = false) {
        return $this->order->transferAddress($getCity);
    }

}

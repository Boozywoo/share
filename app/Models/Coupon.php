<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
	protected $fillable = [
		'name', 'code', 'percent', 'status', 'max_uses', 'uses',
        'date_start', 'date_finish',
	];
	
	protected $dates = ['date_start', 'date_finish'];

	const STATUS_ACTIVE = 'active';
	const STATUS_DISABLE = 'disable';

	const STATUSES = [
		self::STATUS_ACTIVE,
		self::STATUS_DISABLE,
	];

	//Scopes
	public function scopeFilter($query, $data)
	{
		$name = array_get($data, 'name');
		$status = array_get($data, 'status');
		$code = array_get($data, 'code');
		$query
			->when($name, function ($q) use ($name) {
				return $q->where('name', 'like', "%$name%");
			})
			->when($status, function ($q) use ($status) {
				return $q->where('status', $status);
			})
			->when($code, function ($q) use ($code) {
				return $q->where('code', $code);
			})
		;
		return $query;
	}

	public function scopeActive($query, $tour)
	{
		return $query
			->where('max_uses', '>', \DB::raw('uses'))
			->where('date_start', '<=', $tour->date_start->format('y-m-d'))
			->where('date_finish', '>=', $tour->date_start->format('y-m-d'))
			->whereStatus(self::STATUS_ACTIVE);
	}
}

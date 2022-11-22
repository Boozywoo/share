<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
	protected $fillable = [
		'tour_id', 'client_id', 'company_id', 'driver_id', 'rating', 'type', 'comment'
	];

	const TYPE_POSITIVE = 'positive';
	const TYPE_NEGATIVE = 'negative';

	const TYPES = [
		self::TYPE_POSITIVE,
		self::TYPE_NEGATIVE,
	];

	//Relationships
	public function order()
	{
		return $this->belongsTo(Order::class);
	}

	public function client()
	{
		return $this->belongsTo(Client::class);
	}

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function getPrettyRatingAttribute()
	{
		$result = '';
		for ($i = 1; $i <= $this->rating; $i++) {
			$result .= trans('pretty.rating');
		}
		return $result;
	}

	//Scopes
	public function scopeFilter($query, $data)
	{
		$type = array_get($data, 'type');
		$orderId = array_get($data, 'order_id');
		$routeId = array_get($data, 'route_id');
		$clientId = array_get($data, 'client_id');
		$routes = array_get($data, 'routes');
		$query
			->when($type, function ($q) use ($type) {
				return $q->where('type', $type);
			})
			->when($orderId, function ($q) use ($orderId) {
				return $q->where('order_id', $orderId);
			})
			->when($clientId, function ($q) use ($clientId) {
				return $q->where('client_id', $clientId);
			})
			->when($routeId, function ($q) use ($routeId) {
				return $q->whereHas('order.tour', function ($q) use ($routeId) {
					$q->filter(['route_id' => $routeId]);
				});
			})
			->when($routes, function ($q) use ($routes) {
				return $q->whereHas('order.tour', function ($q) use ($routes) {
					$q->filter(['routes' => $routes]);
				});
			})
		;
		return $query;
	}
}

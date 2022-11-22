<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Http\Requests\Index\SettingsRequest;
use App\Models\Client;
use App\Models\Order;
use App\Models\Review;
use App\Models\Status;
use App\Models\Tour;
use Carbon\Carbon;
use App\Models\City;

use PDF;
use App\Models\OrderHistory;
use Request;

class ProfileController extends Controller
{
	public function settings()
	{
        $timezonelist = City::getTimezoneList();
		$statuses = Status::filter(['status' => Status::STATUS_ACTIVE])->pluck('name', 'id');
		return view('index.profile.settings', compact('statuses','timezonelist'));
	}

	public function settingsUpdate(SettingsRequest $request)
	{
	    $dataUpdate = request()->only(['first_name','middle_name','last_name', 'passport',
            'email', 'phone',  'status_id', 'birth_day', 'card','timezone']);
	    $dataUpdate['birth_day'] = date('Y-m-d', strtotime($dataUpdate['birth_day']));;
		auth()->user()->client()->update($dataUpdate);

		if ($password = request('password')) {
			auth()->user()->client()->update(['password' => $password]);
		}

		return $this->responseSuccess(['message' => trans('index.messages.settings')]);
	}

	public function reviews()
	{
		$reviews = Review::filter(['client_id' => auth()->user()->client_id])
			->latest()
			->with('order.tour.route')
			->get();

		return view('index.profile.reviews', compact('reviews'));
	}

	public function tickets()
	{
		$url = request()->fullUrl();
		$type = parse_url($url, PHP_URL_QUERY);

		$futureOrders = Order::filter(['client_id' => auth()->user()->client_id])
			->whereHas('tour', function ($q) {
                $q->where('date_start', '>=', Carbon::now()->format('Y-m-d'))
                    ->where('status', '!=', Tour::STATUS_COMPLETED);
            })
			->whereIn('type', [Order::TYPE_WAITING, Order::TYPE_EDITED])
			->active()
			->latest()
			->with('tour.route')
			->get();

		$completedOrders = Order::filter(['client_id' => auth()->user()->client_id])
			->whereHas('tour', function ($q) {
				$q->where('status', Tour::STATUS_COMPLETED);
			})
			->where('type', Order::TYPE_COMPLETED)
			->active()
			->latest()
			->with('tour.route')
			->get();

		$disabledOrders = Order::whereHas('tour')
            ->filter([
                'client_id' => auth()->user()->client_id,
                'status' => Order::STATUS_DISABLE,
            ])
			->latest()
			->with('tour.route')
			->get();


		return view('index.profile.tickets', compact('futureOrders', 'completedOrders', 'disabledOrders', 'type'));
	}

	public function showOrder(Order $order)
	{
		if ($order->client_id != auth()->user()->client_id) abort(404);
		$arr_price = [];
        foreach($order->orderPlaces as $op){
            array_push($arr_price, $op->price);
        }

        $old_price = array_sum($arr_price);
		return view('index.profile.tickets.show', compact('order', 'old_price'));
	}

	public function ticketsCancel()
	{
		$order = Order::find(request('id'));

		if ($order && $order->client_id != auth()->user()->client_id) abort(404);
		
		if ($order->tour->date_start < Carbon::now()->format('Y-m-d') && $order->tour->status != Tour::STATUS_COMPLETED) return $this->responseError(['message' => trans('index.messages.profile.orders.cancel.error')]);

        $orderHistory = new OrderHistory();
        $orderHistory->order_id = $order->id;
        $orderHistory->action = OrderHistory::ACTIVE_CANCEL;
        $orderHistory->source = Order::SOURCE_SITE;
        $orderHistory->client_id = auth()->user()->client_id;
        $orderHistory->save();
		$order->update(['status' => Order::STATUS_DISABLE]);
		return $this->responseSuccess(['message' => trans('index.messages.profile.orders.cancel.success')]);
	}

	public function updateEmail() {
      	if (request('email')) {
			$email_exists = Client::whereEmail(request('email'))
				->where('id', '!=', auth()->user()->client_id)
				->exists();

			if(!$email_exists) {
				$client = Client::find(auth()->user()->client_id);

				$client->email = request('email');
				$client->save();
				return $this->responseSuccess(['message' => trans('index.messages.settings')]);
			} else {
				return $this->responseError(['message' => trans('index.profile.please_enter_email_already_exists')]);
			}
		}
	}

	public function createReview(Request $request)
	{
		$order = Order::find(request('orderId'));
		$star = request('star');
		$comment = request('comment');

		$review = '';

		if(isset($order->review)) {
			$review = Review::find($order->review->id);
		} else {
			$review = new Review();
			$review->order_id = $order->id;
			$review->client_id = $order->client->id;
			$review->company_id = $order->tour->driver->company->id;
			$review->driver_id = $order->tour->driver->id;
		}

		$review->comment = $comment;
		$review->rating = $star;
        $review->save();
	}
}
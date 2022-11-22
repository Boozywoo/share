<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Api\ApiController;
use App\Models\Image;
use App\Models\Client;
use App\Models\Order;
use App\Models\Status;
use App\Services\Social\SocialToOrderService;
use Websecret\Panel\Http\Controllers\UploadController;

class ClientController extends ApiController
{
	public function confirmStatus(UploadController $uploadController)
	{
		$request['model'] = Client::class;
		$request['type'] = 'main';
		$request['params'] = 'index.profile';

		if (!$client = Client::find(request('client_id'))) return $this->responseError();

		try {
			\DB::beginTransaction();
			$imagesData = $uploadController->images(request())->getData(true);
			if (array_get($imagesData, 'result') != 'success') {
				return $this->responseError();
			}

			$imageData = array_get($imagesData, 'files.0');

			$client->images()->delete();

			$newImage = new Image();
			$newImage->path = $imageData['filename'];
			$newImage->type = Client::IMAGE_TYPE_IMAGE;
			$newImage->main = 1;

			$client->images()->save($newImage);

			$client->status_state = Client::STATUS_STATE_DRIVER_OK;
			$client->update();
			\DB::commit();

			return $this->responseSuccess(['path' => (string)$client->mainImage->load('original', $client)]);
		} catch (\Exception $e) {
			\DB::rollBack();
			return $this->responseError();
		}
	}

	public function cancelStatus()
	{
		if (!$order = Order::find(request('order_id'))) return $this->responseError();

		if ($order->client->status_state == Client::STATUS_STATE_NEW) return $this->responseError();

		$order->client->status_id = null;
		$order->client->status_state = Client::STATUS_STATE_DRIVER_OK;
		$order->client->update();

		$order = SocialToOrderService::index($order, $order->tour, $order->price);
		$order->update();

		return $this->responseSuccess();
	}

	public function statuses()
	{
		foreach (Status::all() as $status) {
			$statuses[] = ['id' => $status->id, 'name' => $status->name];
		}

		return $this->responseSuccess(['statuses' => $statuses]);
	}

}
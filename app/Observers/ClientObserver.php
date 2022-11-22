<?php

namespace App\Observers;

use App\Models\Client;
use App\Models\Token;
use Carbon\Carbon;

class ClientObserver
{
	public function creating(Client $client)
	{
		$client->status = $client->status == Client::STATUS_SYSTEM ?  Client::STATUS_SYSTEM : Client::STATUS_ACTIVE;
		$client->reputation = Client::REPUTATION_NEW;
	}

	public function created(Client $client)
	{
		do {
			$token  = str_random(32);
		} while (Token::whereApiToken($token)->first());
		$client->token()->create(['api_token' => $token]);
		$client->user()->create([]);
	}

	public function saving(Client $client)
	{
		$changed = $client->getDirty();
		$changedStatusId = array_get($changed, 'status_id');

		if ($changedStatusId) {
			$client->date_social = null;
			$client->status_state = Client::STATUS_STATE_NEW;
		}
	}
}
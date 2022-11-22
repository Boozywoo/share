<?php

namespace App\Jobs\Client;

use App\Models\Client;
use App\Models\User;
use App\Notifications\User\ClientsImportNotification;
use App\Traits\ClearPhone;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class ClientImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ClearPhone;

    protected $results;
    protected $user;

    public function __construct($results, User $user)
    {
        $this->results = $results;
        $this->user = $user;
    }

    public function handle()
    {
        $duplicates = [];
        $wrongPhones = [];
        $wrongFirstNames = [];

        \DB::beginTransaction();
        try {
            $clients = [];
            foreach ($this->results as $key => $result) {
                $error = false;
                $firstName = isset($result['imya']) ?  $result['imya'] : '';
                $lastName = isset($result['familiya']) ?  $result['familiya'] : '';
                $middleName = isset($result['otchestvo']) ?  $result['otchestvo'] : '';
                $phone = isset($result['telefon']) ?  '380'.preg_replace('/[^0-9.]+/', '', $result['telefon']) : '';
                $success = isset($result['success']) ?  $result['success'] : 0;
                $failed = isset($result['failed']) ?  $result['failed'] : 0;
                //$status = isset($result['cheryy_spisok']) ?  Client::STATUS_DISABLE : Client::STATUS_ACTIVE;
                $status = Client::STATUS_ACTIVE;

                if(strlen(trim($firstName))=='') $firstName = '.';
                
                $result = [
                    'key' => $key + 2,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'middle_name' => $middleName,
                    'phone' => $phone,
                ];

                $phone = $this->clearPhone($phone);

                if (Client::wherePhone($phone)->first()) {
                    $duplicates[] = $result;
                    $error = true;
                }

                if (!$firstName) {
                    $wrongFirstNames[] = $result;
                    $error = true;
                }

                /*if (strlen($phone) != 12) {
                    $wrongPhones[] = $result;
                    $error = true;
                }*/

                if ($error) {

                    continue;
                }
                
                Client::create([
                    'phone' => $phone,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'middle_name' => $middleName,
                    'order_success' => $success,
                    'order_error' => $failed,
                    'status' => $status,
                ]);
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return \Log::info('client_import:'. $e->getMessage());
        }

        \DB::commit();

        $this->user->notify(new ClientsImportNotification($duplicates, $wrongFirstNames, $wrongPhones));
    }
}

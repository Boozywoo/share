<?php

namespace App\Console\Commands;

use App\Jobs\Client\ClientImportJob;
use App\Models\User;
use Illuminate\Console\Command;

use App\Models\Client;
use App\Notifications\User\ClientsImportNotification;
use App\Traits\ClearPhone;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class InsertClients extends Command
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ClearPhone;

    protected $signature = 'insert:clients';
    protected $description = 'внесение клиентов';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $resultData = [];
        $data = \Excel::load(storage_path('app/clients.xlsx'))->get();
        foreach ($data as $item)
            $resultData[] = ['telefon' => $item['phone'], 'imya' => $item['name'], 'surname' => $item['surname']];

        $duplicates = [];
        $wrongPhones = [];
        $wrongFirstNames = [];

        \DB::beginTransaction();
        try {
            foreach ($resultData as $key => $result) {
                $error = false;

                $firstName = isset($result['imya']) ?  $result['imya'] : '';
                $lastName = isset($result['surname']) ?  $result['surname'] : '';
                $phone = isset($result['telefon']) ?  $this->clearPhone($result['telefon']) : '';

                $result = [
                    'key' => $key + 2,
                    'first_name' => $firstName,
                    'phone' => $phone,
                ];

                $phone = $this->clearPhone($phone);

                /*if (Client::wherePhone($phone)->first()) {
                    $duplicates[] = $result;
                    $error = true;
                }*/

                if (!$firstName) {
                    $wrongFirstNames[] = $result;
                    $error = true;
                }

                if (strlen($phone) != 12) {
                    $wrongPhones[] = $result;
                    $error = true;
                }

                if ($error) continue;

                Client::updateOrCreate (['phone' => $phone],[
                    'phone' => $phone,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ]);
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return \Log::info('client_import:'. $e->getMessage());
        }

        \DB::commit();
    }


}

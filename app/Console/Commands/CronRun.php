<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\User;
use Illuminate\Console\Command;
use App\Models\Cron;
use App\Jobs\Client\ClientImportJob;
use App\Traits\ClearPhone;

class CronRun extends Command
{
    use ClearPhone;
    protected $signature = 'cron:run';

    public function handle()
    {
        //#Start
        ini_set('max_execution_time', 25900);
        ini_set("memory_limit", "1G");
        $storage = storage_path();


        $cron = Cron::where('is_active', 1)->first();

        if (!empty($cron)) {
            //проверка на созданный файл

            if ( is_file($storage . "/app/cron/cron." . $cron->type . ".lockfile") && (time() - filemtime($storage . "/app/cron/cron." . $cron->type . ".lockfile")) > 3600)
                unlink($storage . "/app/cron/cron." . $cron->type . ".lockfile");

            if (is_file($storage . "/app/cron/cron." . $cron->type . ".lockfile")) {
                echo 'client_import_cron: запуск типа - ' . $cron->type . ' не возможен, существует активное задание';
                return \Log::info('client_import_cron: запуск типа - ' . $cron->type . ' не возможен, существует активное задание');
            } else {
                \Log::info('client_import_cron: Запуск типа - ' . $cron->type);
                fopen($storage . "/app/cron/cron." . $cron->type . ".lockfile", 'c');
            }

            switch ($cron->type_file) {
                case "xlsx":


                    $results = \Excel::load($storage . $cron->params)->get();
                    dispatch(new ClientImportJob($results, User::where('id', $cron->user_id)->first()));
                    unlink($storage . $cron->params);
                    $cron->is_active = '0';
                    $cron->save();
                    echo 'данных загружены';
                    break;
                case "xls":

                    $results = \Excel::load($storage . $cron->params)->get();
                    dispatch(new ClientImportJob($results, User::where('id', $cron->user_id)->first()));
                    unlink($storage . $cron->params);
                    $cron->is_active = '0';
                    $cron->save();
                    echo 'данных загружены';
                    break;
                case "csv":

                    $data = array_map('str_getcsv', file($storage . $cron->params));
                    $password =  bcrypt("sB4^@1B&oEzR");
                    \DB::beginTransaction();
                    try {
                        if (!empty($data)) {
                            foreach ($data as $key => $val) {
                                if ($key == 0) continue;

    /*if(($key % 100) ==0){ print_r(date("Y.m.d H:i:s",time())." - ".$key."\r\n");

    };*/
                                $error = false;
                                $firstName = isset($val[1]) ? \DB::connection()->getPdo()->quote($val[1]) : '';
                                $lastName = isset($val[0]) ? \DB::connection()->getPdo()->quote($val[0]) : '';
                                $middleName = isset($val[2]) ? \DB::connection()->getPdo()->quote($val[2]) : '';
                                $phone = isset($val[3]) ?  preg_replace('/[^0-9.]+/', '', $val[3]) : '';
                                $success = isset($val[4]) ? $val[4] : 0;
                                $failed = isset($val[5]) ? $val[5] : 0;
                                //$status = isset($result['cheryy_spisok']) ?  Client::STATUS_DISABLE : Client::STATUS_ACTIVE;
                                $status = Client::STATUS_ACTIVE;

                                //if (strlen(trim($firstName)) == '') $firstName = '.';

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


                                if ($error) continue;


                                if (!$this->is_utf8($firstName) || !$this->is_utf8($lastName) || !$this->is_utf8($middleName)) {
                                    $firstName = iconv("windows-1251", "UTF-8", $firstName);
                                    $lastName = iconv("windows-1251", "UTF-8", $lastName);
                                    $middleName = iconv("windows-1251", "UTF-8", $middleName);
                                }


                                if (trim($firstName) == "''") {
                                    $firstName = \DB::connection()->getPdo()->quote('.');
                                }

                                \DB::insert("INSERT INTO `clients` ( `status_id`, `first_name`, `middle_name`, `last_name`, `passport`, `email`, `phone`, `card`, `password`, `status`, `reputation`, `register`, `comment`, `date_social`, `order_success`, `order_error`, `remember_token`, `created_at`, `updated_at`, `status_state`, `birth_day`, `company_id`) VALUES ( NULL,  $firstName, $middleName, $lastName, NULL, NULL, '$phone', NULL, '', '$status', 'new', '0', '', NULL, '$success', '$failed', NULL, '" . date('Y-m-d H:i:s', time()) . "', '" . date('Y-m-d H:i:s', time()) . "', '', NULL, NULL)");

                                \DB::insert("INSERT INTO `users` ( `client_id`, `password`,`created_at`,`updated_at`,`status`) VALUES ( '".\DB::getPdo()->lastInsertId()."',  '".$password."',now(),now(),'active')");





                                /*Client::create([
                                    'phone' => $phone,
                                    'first_name' => $firstName,
                                    'last_name' => $lastName,
                                    'middle_name' => $middleName,
                                    'order_success' => $success,
                                    'order_error' => $failed,
                                    'status' => $status,
                                ]);*/


                            }
                        } else
                            echo 'данных не загружены. Данные не нашлись';
                    } catch (\Exception $e) {
                        unlink($storage . "/app/cron/cron." . $cron->type . ".lockfile");
                        \DB::rollBack();
                        return \Log::info('client_import_cron:' . $e->getMessage());
                    }





                    unlink($storage . $cron->params);
                    unlink($storage . "/app/cron/cron." . $cron->type . ".lockfile");

                    $cron->is_active = '0';

                    $cron->save();
                    \DB::commit();


                    break;

                default:
                    echo 'no method';


            }


        } else {

            echo 'данных не обнаружено';
        }

    }

    function is_utf8($str)
    {
        $c = 0;
        $b = 0;
        $bits = 0;
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $c = ord($str[$i]);
            if ($c > 128) {
                if (($c >= 254)) return false;
                elseif ($c >= 252) $bits = 6;
                elseif ($c >= 248) $bits = 5;
                elseif ($c >= 240) $bits = 4;
                elseif ($c >= 224) $bits = 3;
                elseif ($c >= 192) $bits = 2;
                else return false;
                if (($i + $bits) > $len) return false;
                while ($bits > 1) {
                    $i++;
                    $b = ord($str[$i]);
                    if ($b < 128 || $b > 191) return false;
                    $bits--;
                }
            }
        }
        return true;
    }
}
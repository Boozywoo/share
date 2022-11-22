<?php

namespace App\Services\Tour;

use App\Models\Client;
use App\Models\Route;
use App\Models\RouteStation;
use App\Models\Station;
use App\Models\Tour;
use App\Models\User;
use App\Traits\ClearPhone;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Order\StoreOrderService;



class OrderImportService
{
     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ClearPhone;

    protected $results;
    protected $user;
    protected $tour;
    public $error;

    public function __construct($results, User $user, Tour $tour)
    {
        $this->results = $results;
        $this->user = $user;
        $this->tour = $tour;

    }

    public function import()
    {
        $required_inputs=array();
        $error1='';
        $error2='';
        $error3='';
        $routes = Route::find($this->tour->route_id);
        if(!empty($routes))
        $required_inputs = explode(",",$routes->required_inputs);

        \DB::beginTransaction();
        try {
            $clients = [];

            foreach ($this->results as $key => $result) {
                $this->tour = Tour::find($this->tour->id);

                $data = array();

                $firstName = isset($result['imya']) ? $result['imya'] : '';
                $lastName = isset($result['familiya']) ? $result['familiya'] : '';
                $middleName = isset($result['otchestvo']) ? $result['otchestvo'] : '';
                $phone = isset($result['telefon']) ? preg_replace('/[^0-9.]+/', '', $result['telefon']) : '';
                $vremya_posadki = isset($result['vremya_posadki']) ? $result['vremya_posadki'] : '';
                preg_match('/\((.*)\)/', $result['mesto_posadki'], $maches_pos);
                preg_match('/\((.*)\)/', $result['mesto_vysadki'], $maches_vys);

                $mesto_posadki = isset($maches_pos[1]) ? $maches_pos[1] : '';
                $mesto_vysadki = isset($maches_vys[1]) ? $maches_vys[1] : '';
                $mesto = isset($result['mesto']) ? $result['mesto'] : '';
                $kol_vo_poezdok = isset($result['kol_vo_poezdok']) ? $result['kol_vo_poezdok'] : '';
                $kommentariy = isset($result['kommentariy']) ? $result['kommentariy'] : '';
                $pasport = isset($result['pasport']) ? $result['pasport'] : ' ';
                $nomer_aviareysa = isset($result['nomer_vashego_reysa']) ? $result['nomer_vashego_reysa'] : ' ';

                if(!empty($required_inputs)){
                    $routes->required_inputs = str_replace('first_name',trans('admin_labels.first_name')." ",$routes->required_inputs);
                    $routes->required_inputs = str_replace('phone',trans('admin_labels.phone')." ",$routes->required_inputs);
                    $routes->required_inputs = str_replace('last_name',trans('admin_labels.last_name')." ",$routes->required_inputs);
                    $routes->required_inputs = str_replace('flight_number',trans('admin_labels.flight_number'),$routes->required_inputs);

                    foreach ($required_inputs as $val){
                        if($val=='phone'){
                            if(empty(trim($phone))) $error3 = 'Не заполнены обязательные поля :'.$routes->required_inputs.' <br>';
                        }
                        if($val=='first_name' ){
                            if(empty(trim($firstName))) $error3 = 'Не заполнены обязательные поля :'.$routes->required_inputs.' <br>';
                        }
                        if($val=='last_name' ){
                            if(empty(trim($lastName))) $error3 = 'Не заполнены обязательные поля :'.$routes->required_inputs.' <br>';
                        }

                        if($val=='flight_number' ){
                            if(empty(trim($nomer_aviareysa))) $error3 = 'Не заполнены обязательные поля :'.$routes->required_inputs.' <br>';
                        }

                    }
                }



                $status = Client::STATUS_ACTIVE;
                if (strlen(trim($firstName)) == '') $firstName = '.';

                $phone = $this->clearPhone($phone);
                //$phoneCodes = \App\Models\Client::CODE_PHONES;

                if (empty(trim($phone))) {

                    $error2 = "Не все данные о пассажире заполнены<br>";
                    continue;
                }

                if($error3!='') continue;
                $client = Client::wherePhone($phone)->first();
                $data['_token'] = csrf_token();
                $data['id'] = '';
                $data['order_slug'] = '';
                $data['tour_id'] = $this->tour->id;
                $data['confirm'] = 1;
                $data['flight_number'] = $nomer_aviareysa;


                $data['new_order'] = 1;
                $data['phone'] = $phone;

                $data['last_name'] = $lastName;
                $data['first_name'] = $firstName;


                if (empty(Station::query()->select('id')->where('name', $mesto_posadki)->first()->id)) {
                    $data['station_to_id'] = RouteStation::query()->where('route_id', $this->tour->route_id)->orderBy('order', "DESC")->get()->first()->station_id;
                } else {
                    $data['station_to_id'] = Station::query()->select('id')->where('name', $mesto_posadki)->first()->id;
                }

                if (empty(Station::query()->select('id')->where('name', $mesto_vysadki)->first()->id)) {
                    $data['station_from_id'] = RouteStation::query()->where('route_id', $this->tour->route_id)->orderBy('order', "DESC")->get()->last()->station_id;
                } else {
                    $data['station_from_id'] = Station::query()->select('id')->where('name', $mesto_vysadki)->first()->id;
                }


                $data['type_pay'] = 'cash-payment';
                $data['comment'] = '';
                if (!empty($kommentariy))
                    $data['comment'] = $kommentariy;
                $data['status'] = "active";
                $data['type'] = "waiting";

                $data['date_social'] = '';
                $data['slug'] = $this->tour->id;
                $data['source'] = 'operator';

                $data['created_user_id'] = \Auth::id();
                $data['operator_id'] = \Auth::id();
                if ($client) {
                    $data['client_id'] = $client->id;
                    if ($this->tour->getFreePlacesCountAttribute() > 0) {
                        $data['pull'] = 0;
                        if ($this->tour->reservation_by_place) {
                            $data['places_with_number'] = 1;
                            $data['places'] = array('0' => $mesto);
                        } else {
                            $data['places_with_number'] = 0;
                            $data['places'] = array('0' => '');
                        }
                        StoreOrderService::index($data, $this->tour);
                    } else {

                        if ($this->tour->is_reserve) {
                            $data['status'] = "reserve";
                            $data['places_with_number'] = 0;
                            $data['places'] = array('0' => '');
                            $data['pull'] = 1;
                            StoreOrderService::index($data, $this->tour);
                        } else {
                            $error1 = "Вы пытаетесь загрузить большое количество людей для этого автобуса<br>";
                        }

                    }
                } else {
                    $model = Client::create([
                        'phone' => $phone,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'middle_name' => $middleName,
                        'order_success' => 0,
                        'order_error' => 0,
                        'status' => Client::STATUS_ACTIVE,
                    ]);

                    $data['client_id'] = $model->id;
                    if ($this->tour->getFreePlacesCountAttribute() > 0) {
                        if ($this->tour->reservation_by_place) {
                            $data['places_with_number'] = 1;
                            $data['places'] = array('0' => $mesto);
                        } else {
                            $data['places_with_number'] = 0;
                            $data['places'] = array('0' => '');
                        }
                        $data['pull'] = 0;
                       StoreOrderService::index($data, $this->tour);
                    } else {

                        if ($this->tour->is_reserve) {
                            $data['status'] = "reserve";
                            $data['places_with_number'] = 0;
                            $data['places'] = array('0' => '');
                            $data['pull'] = 1;
                             StoreOrderService::index($data, $this->tour);
                        } else {

                            $error1 = "Вы пытаетесь загрузить большое количество людей для этого автобуса<br>";
                        }

                    }


                }


            }

        } catch (\Exception $e) {
            \DB::rollBack();

            return array('action'=>'1','msg'=>$e->getMessage());

        }

        \DB::commit();
        return array('action'=>'2','msg'=> $error1.$error2.$error3);

        //$this->user->notify(new ClientsImportNotification($duplicates, $wrongFirstNames, $wrongPhones));

    }
}
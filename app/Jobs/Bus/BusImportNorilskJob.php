<?php

namespace App\Jobs\Bus;

use App\Models\Bus;
use App\Models\BusType;
use App\Models\CarColor;
use App\Models\Company;
use App\Models\CustomerCompany;
use App\Models\CustomerDepartment;
use App\Models\CustomerPersonality;
use App\Models\Template;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class BusImportNorilskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $buses;

    public function __construct($buses)
    {
        $this->buses = $buses;
    }

    public function handle()
    {
        foreach ($this->buses as $buses) {

            foreach ($buses as $bus) {
//            $company = $company ? $company : Company::first();
//            $template = Template::where('name', $bus['nazvanie_shablona'])->first();
//            $template = $template ? $template : Template::first();
                if (empty($bus['nomernoy_znak'])) {
                    continue;
                }
                $isset = Bus::where('number', $bus['nomernoy_znak'])->first();
                $company = Company::where('name', 'like', '%' . $bus['sobstvennikperevozchik'] . '%')->first();
                $department = $company ?? $company->departments()->where('name', 'LIKE', '%' . 'Колонна ' . $bus['kolonna'] . '%')->first();
                $busType = BusType::where('name', 'LIKE', '%' . $bus['naimenovanie'] . '%')->first();
                $carColor = CarColor::where('name', 'LIKE', '%' . $bus['tsvet'] . '%')->first();
                try {
                    if (!empty($bus['direktsiyayurlitso'])) {
                        $customerCompany = CustomerCompany::where('slug', 'like', Str::slug($bus['direktsiyayurlitso']))->first();
                        if (empty($customerCompany)) {
                            $customerCompany = CustomerCompany::create(['name' => $bus['direktsiyayurlitso'], 'slug' => '']);
                        }
                    }
                    if (!empty($bus['struktura'])) {
                        $customerDepartment = CustomerDepartment::where('slug', 'like', Str::slug($bus['struktura']))->first();
                        if (empty($customerDepartment)) {
                            $customerDepartment = CustomerDepartment::create(['name' => $bus['struktura'], 'slug' => '']);
                        }
                    }
                    if (!empty($bus['fakt_napravlenie'])) {
                        $factReferral = CustomerDepartment::where('slug', 'like', Str::slug($bus['fakt_napravlenie']))->first();
                        if (empty($factReferral)) {
                            $factReferral = CustomerDepartment::create(['name' => $bus['fakt_napravlenie'], 'slug' => '']);
                        }
                    }
                    if (!empty($bus['zakazchik'])) {
                        $customerPersonality = CustomerPersonality::where('slug', 'like', '%' . Str::slug($bus['zakazchik']) . '%')->first();
                        if (empty($customerPersonality)) {
                            $customerPersonality = CustomerPersonality::create(['name' => $bus['zakazchik'], 'slug' => '']);
                        }
                    }
                } catch (\Exception $exception) {
                    
                }
                $statuses = $this->getStatuses($bus['sostoyanie_na_liniineispraavenbez_voditelya']);
                $template = Template::all()->first();

                $busData = [
                    'name' => $bus['marka_model_transportnogo_sredstva'],
                    'name_tr' => $bus['marka_model_transportnogo_sredstva'],
                    'vin' => $bus['vin'],
                    'driver_category' => $bus['kategoriya'],
                    'bus_type_id' => $busType->id ?? 0,
                    'year' => $bus['god_vypuska'],
                    'engine_model' => $bus['model_dvigatelya'],
                    'engine_number' => $bus['dvigatel'],
                    'engine_power' => $bus['moshchnost_dvs_lskvt'],
                    'chassis_number' => $bus['shassi'],
                    'body_number' => $bus['kuzov'],
                    'color' => $carColor->slug ?? '',
                    'weight_allowed' => $bus['razreshennaya_maks_massa_kg'],
                    'weight_empty' => $bus['massa_bez_nagruzki_kg'],
                    'manufacturer' => $bus['organizatsiya_izgotovitel'],
                    'vehicle_passport' => $bus['pts'],
                    'vehicle_passport_date' => Carbon::parse($bus['data_vydachi_pts'])->format('d.m.Y'),
                    'registration_certificate' => $bus['svid_vo_o_registratsii'],
                    'registration_certificate_date' => Carbon::parse($bus['data_vydachi'])->format('d.m.Y'),
                    'insurance_policy' => $bus['strakhovoy_polyus'],
                    'insurance_day' => Carbon::parse($bus['strakhovoy_polyus_deystvitelen_do'])->format('d.m.Y'),
                    'diagnostic_card_number' => $bus['diagnosticheskaya_karta_tekhosmotr'],
                    'diagnostic_card_date' => Carbon::parse($bus['diagnosticheskaya_karta_deystvitelna_do'])->format('d.m.Y'),
                    'number' => $bus['nomernoy_znak'],
                    'garage_number' => $bus['gar'],
                    'company_id' => $company->id,
                    'inventory_number' => $bus['inventarnyy'],
                    'commissioning_date' => Carbon::parse($bus['data_vvodav_ekspluatatsiyu'])->format('d.m.Y'),
                    'operating_mileage' => $bus['probeg_s_nachala_ekspluatatsii_km'] > 0 ?? 0,
                    'balance_price' => $bus['balansovaya_stoimost_rub'],
                    'residual_price' => $bus['ostatochnaya_stoimost_rub'],
                    'transport_tax' => $bus['transportnyy_nalog_rub'],
                    'property_tax' => $bus['nalog_na_imushchestvo_v_mesyats_rub'],
                    'owner_legally' => $bus['sobstvennik_po_bukhuchetukompaniya_perevozchik'],
                    'structure_department' => $bus['strukturnoe_podrazdelenie'],
                    'department_id' => $department->id ?? null,
                    'status' => $statuses['status'],
                    'location_status' => $statuses['location_status'],
                    'customer_director' => $customerPersonality->slug ?? '',
                    'customer_company' => $customerCompany->slug ?? '',
                    'customer_department' => $customerDepartment->slug ?? '',
                    'fact_referral' => $factReferral->slug ?? '',
                    'tires' => stripos($bus['primechanie_oe'], 'зима') ? 'winter' : 'summer',
                    'template_id' => $template->id,
                    'places' => 5,
                    'comment' => $bus['primechanie_oe'],
                    'day_before_revision' => 1,
                    'day_before_insurance' => 1,
                    'password' => '1111'
                ];
                if (empty($isset)) {

                    $result = Bus::firstOrCreate(['number' => $bus['nomernoy_znak']], $busData);
                    $result->departments()->sync($busData['department_id']);
                } else {
                    $isset->update($busData);
                    $isset->departments()->sync($busData['department_id']);

                }

            }
        }
    }

    private function getStatuses($value)
    {
        $result = [
            'status' => Bus::STATUS_DISABLE,
            'location_status' => Bus::LOCATION_IN_GARAGE,
        ];
        switch ($value) {

            case 'Автомобиль в простое':
                $result['status'] = Bus::STATUS_ACTIVE;
                break;
            case 'В ожидании реализации':
                $result['status'] = Bus::STATUS_DISABLE;
                break;
            case 'В ожидании списания':
                $result['status'] = Bus::STATUS_DISABLE;
                break;
            case 'В работе':
                $result['status'] = Bus::STATUS_ACTIVE;
                break;
            case 'На линии':
                $result['status'] = Bus::STATUS_ACTIVE;
                break;
            case 'Простой':
                $result['status'] = Bus::STATUS_OF_REPAIR;
                break;
            case 'Простой в ожидании запчастей':
                $result['status'] = Bus::STATUS_REPAIR;
                break;
            case 'Простой в ожидании оценки':
                $result['status'] = Bus::STATUS_REPAIR;
                break;
            case 'Простой в ожидании ремонта':
                $result['status'] = Bus::STATUS_REPAIR;
                break;
            case 'Ремонт':
                $result['status'] = Bus::STATUS_REPAIR;
                break;
            case 'Техническое обслуживание':
                $result['status'] = Bus::STATUS_REPAIR;
                break;
            case 'Автомобиль в ожидании закупа':
                $result['status'] = Bus::STATUS_DISABLE;

                break;
            case ' Автомобиль в ожидании закупа 2 я очередь':
                $result['status'] = Bus::STATUS_DISABLE;
                break;
        }
        return $result;
    }
}

<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Bus;

class OrderOutfitRequest extends Request
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'date_from' => 'required|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'comment' => 'nullable',
        ];
        if(!$this->has('id')){
            $rules['fuel'] = 'required|numeric';
            if (($bus_id = $this->get('car_id')) && !$this->get('id')) {
                $bus = Bus::find($bus_id);
                if ($bus->odometer && $bus->odometer > 0) {
                    $rules['odometer'] = 'required|numeric|min:' . $bus->odometer;
                }
            } else {
                $rules['odometer'] = 'required|numeric';
            }
        }

        return $rules;
    }

    public function attributes()
    {
        $data = parent::attributes();
        $data['date_to'] = __('admin_labels.date_to');
        $data['date_from'] = __('admin_labels.date_from');
        return $data;
    }
}

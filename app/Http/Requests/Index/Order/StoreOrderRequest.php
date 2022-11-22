<?php

namespace App\Http\Requests\Index\Order;

use App\Http\Requests\Request;
use App\Models\Order;
use App\Models\Setting;
use App\Traits\ClearPhone;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends Request
{
    use ClearPhone;

    public function rules()
    {
        $rules = [];
        $phoneRule = '';
        switch ($this->get('phone-code', 'by')) {
            case 'by':
                $phoneRule = 'size:12';
                break;
            case 'ru':
                $phoneRule = 'size:11';
                break;
            case 'ua':
                $phoneRule = 'size:12';
                break;
            case 'de':
                $phoneRule = 'digits_between:10,13';
                break;
            case 'dee':
                $phoneRule = 'digits_between:10,13';
                break;
            case 'il':
                $phoneRule = 'size:12';
                break;
            case 'cz':
                $phoneRule = 'size:12';
                break;
            case 'us':
                $phoneRule = 'size:11';
                break;
            case 'fi':
                $phoneRule = 'size:11';
                break;
            case 'no':
                $phoneRule = 'digits_between:10,13';
                break;
            case 'pl':
                $phoneRule = 'digits_between:10,13';
                break;
            case 'uz':
                $phoneRule = 'size:12';
                break;
            case 'tm':
                $phoneRule = 'size:12';
                break;
            case 'md':
                $phoneRule = 'size:11';
                break;
            case 'az':
                $phoneRule = 'size:12';
                break;
            case 'fr':
                $phoneRule = 'size:11';
                break;
            case 'tj':
                $phoneRule = 'size:12';
                break;
            case 'gr':
                $phoneRule = 'size:12';
                break;
        }

        if (!auth()->user() || !auth()->user()->client_id) {
            $rules = [
                'first_name' => 'required',
                'agree_personal_data' => 'accepted',
                'phone' => [
                    'required',
                    $phoneRule,
                ]
            ];

            if ($order = Order::find(session('order.id'))) {
                $fields = explode(',', $order->tour->route->required_inputs);
                foreach ($fields as $field) {
                    if (empty($rules[$field])) {
                        $rules[$field] = ['required'];
                    }
                }
            }
        }
        return $rules;
    }

    protected function validationData()
    {
        $data = $this->all();
        if (isset($data['phone'])) {
            $data['phone'] = $this->clearPhone($data['phone']);
        }

        return $data;
    }

    public function attributes()
    {
        return [
            'first_name' => trans('admin_labels.first_name'),
            'last_name' => trans('admin_labels.last_name_in'),
            'middle_name' => trans('admin_labels.middle_name'),
            'phone' => trans('admin_labels.phone'),
            'passport' => trans('admin_labels.passport'),
            'birth_day' => trans('admin_labels.birth_day'),
            'card' => trans('admin_labels.card'),
            'agree_personal_data' => trans('admin_labels.agree_personal_data'),
            'flight_number' => trans('admin_labels.flight_number'),
            'doc_type' => trans('admin_labels.doc_type'),
            'doc_number' => trans('admin_labels.doc_number'),
            'gender' => trans('admin_labels.gender'),
            'country_id' => trans('admin_labels.country_id'),
        ];
    }

    public function messages()
    {
        return [
            'required' => trans('validation.index.required'),
            'phone.unique' => trans('validation.index.custom.phone_unique'),
            'phone.size' => trans('validation.index.custom.phone_size'),
        ];
    }
}
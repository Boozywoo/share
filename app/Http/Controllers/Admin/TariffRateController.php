<?php

namespace App\Http\Controllers\Admin;

use App\Models\TariffRate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TariffRateController extends Controller
{
    public function store()
    {
        if ($id = request('id')) {
            $rate = TariffRate::find($id);
            $rate->update(request()->all());
        } else {
            TariffRate::create(request()->all());
        }

        return $this->responseSuccess();
    }

    public function edit(TariffRate $rate)
    {
        return view('admin.tariff_rates.edit', compact('rate')
            + ['entity' => 'tariff_rates', 'maxReadonly' => true, 'tariff' => $rate->tariff, 'minValue' => $rate->min]);
    }

    public function delete(TariffRate $rate)
    {
        $rate->delete();
        return $this->responseSuccess();
    }
}

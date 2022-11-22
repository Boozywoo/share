<?php


namespace App\Repositories;


use App\Models\Route;

class SelectApiRepository
{
    public function cityFrom($status = ['active'])
    {
        $data = Route::with('stationsActive.city')
            ->where('status', Route::STATUS_ACTIVE)
            ->get();
        dd($data);
    }
}
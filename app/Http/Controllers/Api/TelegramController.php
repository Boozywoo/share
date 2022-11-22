<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TelegramController extends Controller
{
    public function StartCommand()
    {
        phpinfo();
        \Log::info(print_r(\request()->all(),1));
    }
}

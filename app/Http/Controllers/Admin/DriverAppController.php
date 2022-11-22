<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverAppSetting;

use App\Http\Requests\Admin\SettingRequest;
use Illuminate\Support\Facades\File; 

class DriverAppController extends Controller
{
    public function edit()
    {
        $notification = [
            'no'=> 'Нет',
            'push'=> 'Пуш уведомления',
            'sms'=> 'СМС',
            // 'all'=> 'Пуш уведомления + СМС',
          ];
        $d_a_setting = DriverAppSetting::first();
        
        $path = public_path('assets/driver/images');

        if(!File::exists($path)) {
            File::makeDirectory($path, 0777, true, true);
        } else {
            $images = File::allFiles(public_path('assets/driver/images'));
        }

        return view('admin.settings.driverapp.edit', compact('d_a_setting', 'notification'))->with(array('images' => $images));
    }

    public function store()
    {
        $setting = DriverAppSetting::first();
        $setting->update(request()->all());

        return $this->responseSuccess();
    }

    public function imageUploadPost(SettingRequest $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
  
        $imageName = 'background.jpg';  
   
        $request->image->move(public_path('assets/driver/images/'), $imageName);
   
        return back()->with('image', $imageName);
    }
}

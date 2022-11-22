<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;

use App\Http\Requests\Admin\SettingRequest;
use Illuminate\Support\Facades\File; 

class ClientsInterfaceSettingController extends Controller
{
    protected $entity = 'settings.clientsInterfaceSettings';

    public function imageUpload()
    {
        $path = public_path('assets/index/images/for_clients');
        
        if(!File::exists($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        
        $pathAM = public_path('assets/index/images/for_clients/am');
        $pathPM = public_path('assets/index/images/for_clients/pm');
        $pathMobile = public_path('assets/index/images/for_clients/mobile');

        if(!File::exists($pathAM)) {
            File::makeDirectory($pathAM, 0777, true, true);
        } else {
            $imagesAM = File::allFiles(public_path('assets/index/images/for_clients/am'));
        }

        if(!File::exists($pathPM)) {
            File::makeDirectory($pathPM, 0777, true, true);
        } else{
            $imagesPM = File::allFiles(public_path('assets/index/images/for_clients/pm'));
        }

        if(!File::exists($pathMobile)) {
            File::makeDirectory($pathMobile, 0777, true, true);
        } else{
            $imagesMobile = File::allFiles(public_path('assets/index/images/for_clients/mobile'));
        }

        $settings = SiteSetting::first();

        return view('admin.' . $this->entity . '.edit', ['entity' => $this->entity], compact('settings'))
        ->with(array('imagesAM' => $imagesAM))->with(array('imagesPM' => $imagesPM))->with(array('imagesMobile' => $imagesMobile));
    }

    public function imageDelete() {
        $path = 'assets/index/images/for_clients/' . request('imagePath');
        if(file_exists($path)) {
            File::delete($path);
        } else {
            return('File does not exists.');
        }
    }
    
    public function imageUploadPost(SettingRequest $request)
    {
        $time = request('time');

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
  
        $imageName = $time . time() . '.' .$request->image->extension();  
   
        $request->image->move(public_path('assets/index/images/for_clients/' . $time . '/'), $imageName);
   
        return back()->with('image', $imageName);
    }

    public function saveFrame()
    {
        $setting = SiteSetting::first();
        $setting->update(request()->all());
        
        return $this->responseSuccess();
    }
}


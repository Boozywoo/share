<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\InterfaceSettingsRequest;
use App\Http\Controllers\Controller;
use App\Models\InterfaceSetting;
use Illuminate\Support\Facades\Auth;

class InterfaceSettingsController extends Controller
{
    protected $entity = 'settings.interfaceSettings';

    public function edit () 
    {
        // NEED REFACTORING
        // $bgImgName = basename($path); // old logic
        $selectedColorTheme = InterfaceSetting::getUserInterfaceSettings()->theme_name;

        if(!empty(Auth::user()->bg_image->ui_adm_img)) {
            $path = public_path('assets/admin/images/' . Auth::user()->bg_image->ui_adm_img);
            $bgImgName = Auth::user()->bg_image->ui_adm_img;
        }else {
            $bgImgName = basename(
                public_path('/assets/admin/images/bg-images/default-bg-img.jpg')
            );
        }
        
        return view(
            'admin.' . $this->entity . '.edit', 
            [
                'entity' => $this->entity, 
                'selectedColorTheme' => $selectedColorTheme,
                'bgImgName' => $bgImgName, 
            ]
        );
    }

    public function store (InterfaceSettingsRequest $request)
    {
        $user = Auth::user();
        
        $selectedThemeName = $request->theme_color_admin_panel;
    
        $user->interface_setting_id = InterfaceSetting::where(
            'theme_name', $selectedThemeName)->first()->id;
        $user->save();

        return redirect()->route('admin.settings.interface_settings.edit');
    }
}

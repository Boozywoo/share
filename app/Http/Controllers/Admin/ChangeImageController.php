<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Models\BgImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChangeImageController extends Controller
{
    /**
     * Set background image for current user
     * 
     * $request['file_to_upload'] - is file name
     * 
     * @param Illuminate\Http\SettingRequest $request
     * @return 
     */
    public function change_background_image(SettingRequest $request)
    {
        // NEED REFACTORING

        $user = Auth::user();

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Upload image file to path
        $request->image->move(public_path('/assets/admin/images/bg-images/'), 'User-bg-' . $user->id . '.jpg');

        // Update database data
        // if user has background, need update background and delete old file
        if($user->bg_image_id) {
            $bg = BgImage::where('user_id', $user->id)->first();
            // drop old file
            $path = public_path();
            Storage::delete($path . '/assets/admin/images/bg-images/' . $bg->ui_adm_img);
            // update file
            // $bg->ui_adm_img = str_replace('/tmp/', '', $uploadedFile) . '.jpg';
            $bg->ui_adm_img = 'User-bg-' . $user->id . '.jpg';
            $bg->save();
        // if user dont has bg need created new bg
        } else {
            $bgImage = new BgImage(
                // ['ui_adm_img' => str_replace('/tmp/', '', $uploadedFile) . '.jpg']
                ['ui_adm_img' => 'User-bg-' . $user->id . '.jpg']
            );
            $user->bg_image()->save($bgImage);
    
            $bg = BgImage::where('user_id', $user->id)->first();
            $user->bg_image_id = $bg->id;
            $user->save();
        }
        
        return back();
    }

}

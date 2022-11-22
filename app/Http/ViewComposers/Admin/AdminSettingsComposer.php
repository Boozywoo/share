<?php

namespace App\Http\ViewComposers\Admin;

use Illuminate\View\View;
use App\Models\InterfaceSetting;
use Illuminate\Support\Facades\Auth;

class AdminSettingsComposer
{
    protected $themeName;
    protected $bgHoverColor;
    protected $wrapperColor;
    protected $menuFocusStyle;
    protected $textLink;
    protected $menuHoverStyle;
    protected $textLinkHover;
    protected $phoneBtnBgStyle;
    protected $bgWarning;
    protected $confirmPopupBgColor;
    protected $confirmPopupFontColor;

    protected $userCustomBgUrl;

    public function __construct(InterfaceSetting $settings)
    {
        $theme = InterfaceSetting::getUserInterfaceSettings();

        $this->themeName = $theme->theme_name;
        $this->bgHoverColor = $theme->bgHoverColor;
        $this->wrapperColor = $theme->wrapperColor;
        $this->menuFocusStyle = $theme->menuFocusStyle;
        $this->menuHoverStyle = $theme->menuHoverStyle;
        $this->phoneBtnBgStyle = $theme->phoneBtnBgStyle;
        $this->bgWarning = $theme->bgWarning;
        $this->confirmPopupBgColor = $theme->confirmPopupBgColor;
        $this->confirmPopupFontColor = $theme->confirmPopupFontColor;
        $this->textLink = $theme->textLink;
        $this->textLinkHover = $theme->textLinkHover;

        // NEED REFACTORING
        if(!empty(Auth::user()->bg_image)) {
            $this->userCustomBgUrl = asset(
                '/assets/admin/images/bg-images/' . Auth::user()->bg_image->ui_adm_img
            );
        } else {
            $this->userCustomBgUrl = asset(
                '/assets/admin/images/bg-images/default-bg-img.jpg'
            ); 
        }
    }

    public function compose(View $view)
    {
        $view->with(
            [
                'themeName' => $this->themeName,
                'bgHoverColor' => $this->bgHoverColor,
                'wrapperColor' => $this->wrapperColor,
                'menuFocusStyle' => $this->menuFocusStyle,
                'menuHoverStyle' => $this->menuHoverStyle,
                'phoneBtnBgStyle' => $this->phoneBtnBgStyle,
                'bgWarning' => $this->bgWarning,
                'confirmPopupBgColor' => $this->confirmPopupBgColor,
                'confirmPopupFontColor' => $this->confirmPopupFontColor,
                'textLink' => $this->textLink,
                'textLinkHover' => $this->textLinkHover,

                'userCustomBgUrl' => $this->userCustomBgUrl,
            ]
        );
    }
}
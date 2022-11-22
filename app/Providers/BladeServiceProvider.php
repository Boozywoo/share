<?php

namespace App\Providers;

use App\Services\Prettifier;
use Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::directive('dd', function($data){
            return "<?php dd(with($data)) ;?>";
        });
        
        Blade::directive('dump', function($data){
            return "<?php dump(with($data)) ;?>";
        });

        Blade::directive('price', function($price){
            return '<?= \App\Services\Prettifier::prettifyPrice(with(' . $price . ')) ?>';
        });

		Blade::directive('phone', function($phone){
			return '<?= \App\Services\Prettifier::prettifyPhone(with(' . $phone . ')) ?>';
		});

		Blade::directive('textarea', function($text){
			return '<?= \App\Services\Prettifier::prettifyTextArea(with(' . $text . ')) ?>';
		});

		Blade::directive('date', function($date){
			return "<?php echo with($date)->day . ' ' . trans('dates.month.long.' . with($date)->month) . ' ' . with($date)->year . ' ' . trans('dates.year.short') ;?>";
		});

		Blade::directive('time', function($arguments){
			list($time, $interval) = explode(',',str_replace(['(',')',' ', "'"], '', $arguments));
			return '<?= \App\Services\Prettifier::prettifyTime('. $time. ' ,' . $interval . ') ?>';
		});

        Blade::directive('exists', function($data, $list = null){
            return "<?php 
               if (empty($data)){
                   echo '<span class=\"js-egis-empty text-danger\">'.trans('admin_labels.n/a').'</span>';
               } else {
                    echo $data;
               }
            ?>";
        });
    }

    public function register()
    {
        //
    }
}

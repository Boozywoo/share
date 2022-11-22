<?php

namespace App\Services\Notification;

use App\Models\Company;
use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\Wishes;
use App\Services\Notification\Providers\AbstractNotifyProvider;
use Exception;

class NotificationService
{
    protected $type;
    const PROVIDERS = [
        Wishes::class => \App\Services\Notification\Providers\WishesProvider::class,
    ];

    public function __construct(){
        $this->type = NotificationType::where('slug', 'default')->first();
    }

    public function newRegistration($user)
    {
        $type = NotificationType::where('slug', 'register')->first();
        $data = new Notification;
        $data->user_id = $user->id;
        $data->source_url = route('admin.users.edit', $user->id);
        $data->source = trans('admin.notifications.text.registration');
        $data->small_text = trans('admin.notifications.text.small_text_registration');
        $data->text = ' ';
        $data->approved = 0;
        $data->read = 0;
        $data->denied = 0;
        $data->new = 1;
        $data->for_all = 0;
        $data->type_id = $type->id ?? $this->type->id;
        $data->save();

        //
        $hr = Company::getIdsHrCompany($user->company_id);
        $data->responsible()->attach($hr);
    }


    public function notify($model, $data)
    {
        $providerClass = self::PROVIDERS[get_class($model)];

        if (!$providerClass) {
            throw new Exception('Provider not found');
        }

        /** @var AbstractNotifyProvider $provider */
        $provider = new $providerClass;

        return $provider->notify($model, $data);
    }


    public function wishes(Wishes $wishes, $type, $ids, $dataInfo)
    {
        $type = $type ?? $this->type;
        $data = new Notification;
        $data->user_id = $wishes->applicant_id;
        $data->source_url = route('admin.wishes.edit', $wishes->id);
        $data->source = $dataInfo['source'];
        $data->small_text = $dataInfo['small_text'];
        $data->text = ' ';
        $data->approved = 0;
        $data->read = 0;
        $data->denied = 0;
        $data->new = 1;
        $data->for_all = 0;
        $data->type_id = $type->id;
        $data->save();

        $data->responsible()->attach($ids);
    }
}

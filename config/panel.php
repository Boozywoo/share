<?php

return [

    'models_path' => '\App',

    'address_model' => App\Models\Address::class,
    'phone_model' => \Websecret\Panel\Models\Phone::class,
    'image_model' => App\Models\Image::class,

    'autocomplete_url' => 'autocomplete/autocomplete',
    'upload_url' => 'upload/images',
    'upload_redactor_url' => 'upload/redactor/images',
    'upload_froala_url' => 'upload/froala/images',

    'address_view' => 'panel::partials.form.address',
    'addresses_view' => 'panel::partials.form.addresses',
    'images_view' => 'admin.partials.form.images',
    'phones_view' => 'panel::partials.form.phones',

];
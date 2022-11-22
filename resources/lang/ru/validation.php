<?php

return [
    "accepted" => "Вы должны дать :attribute.",
    "active_url" => "Поле :attribute должно быть полным URL.",
    "after" => "Поле :attribute должно быть датой после :date.",
    "alpha" => "Поле :attribute может содержать только буквы.",
    "alpha_dash" => "Поле :attribute может содержать только буквы, цифры и тире.",
    "alpha_num" => "Поле :attribute может содержать только буквы и цифры.",
    "before" => "Поле :attribute должно быть датой перед :date.",
    "between" => [
        "numeric" => "Поле :attribute должно быть между :min и :max.",
        "file" => "Поле :attribute должно быть от :min до :max Килобайт.",
        "string" => "Поле :attribute должно быть от :min до :max символов.",
    ],

    "first_name" => "Поле имя может содержать только буквы.",
    "middle_name" => "Поле отчество может содержать только буквы.",
    "last_name" => "Поле фамилия может содержать только буквы.",

    "confirmed" => "Пароль не совпадает с подтверждением",
    "different" => "Поля :attribute и :other должны различаться.",
    "digits" => "Поле :attribute должно содержать :digits цифр.",
    "digits_between" => "Поле :attribute должно содержать от :min до :max цифр.",
    "email" => "Поле email имеет неверный формат",
    "exists" => "Выбранное значение для :attribute уже существует.",
    "image" => "Поле :attribute должно быть картинкой.",
    "in" => "Выбранное значение для :attribute неверно.",
    "integer" => "Поле :attribute должно быть целым числом.",
    "ip" => "Поле :attribute должно быть полным IP-адресом.",
    "match" => "Поле :attribute имеет неверный формат.",
    "max" => [
        "numeric" => "Поле :attribute должно быть меньше :max.",
        "file" => "Поле :attribute должно быть меньше :max Килобайт.",
        "string" => "Поле :attribute должно быть короче :max символов.",
    ],
    "mimes" => "Поле :attribute должно быть файлом одного из типов: :values.",
    "min" => [
        "numeric" => "Поле :attribute должно быть не менее :min.",
        "file" => "Поле :attribute должно быть не менее :min Килобайт.",
        "string" => "Поле :attribute должно быть не короче :min символов.",
    ],
    "not_in" => "Выбранное значение для :attribute не верно.",
    "numeric" => "Поле :attribute должно быть числом.",
    "regex" => "Поле :attribute имеет неверный формат.",
    "required" => 'Поле ":attribute" обязательно для заполнения.',
    'required_with' => 'Укажите :attribute',
    "same" => "Значение :attribute должно совпадать со значенеим :other.",
    "size" => [
        "numeric" => "Поле :attribute должно быть :size.",
        "file" => "Поле :attribute должно быть :size Килобайт.",
        "string" => "Поле :attribute должно быть длиной :size символов.",
        "array" => "Поле :attribute не заполнено.",
    ],
    "unique" => "Такое значение поля :attribute уже существует.",
    "url" => "Поле :attribute имеет неверный формат.",

    'attributes' => [
        'title' => 'Заголовок',
        'slug' => 'Ссылка',
    ],

    'index' => [
        'required' => 'Укажите :attribute',
        'custom' => [
            'hard_password' => 'Пожалуйста, введите более сложный пароль',
            'phone_unique' => 'Данный телефон занят',
            'email_unique' => 'Данный Email занят',
            'phone_exists' => 'Пользователь с данным телефоном не найден',
            'phone_size' => 'Некорректный номер телефона',

            'places_required' => 'Выберите хоть одно место',
            'not_selected' => 'Рейс не выбран',
            'load_clients' => 'Клиенты загружаются',

            'login_error' => 'Неверный логин или пароль',
            'login_forget' => 'Номер телефона не существует',
            'code' => 'Неверный код',
            'password_min_six' => 'Длина пароля минимум 6 символов',
            'old_password_wrong' => 'Старый пароль введен неверно',
            'date_after_today' => 'Нельзя указывать прошедшую дату',
            'date_error' => 'Неверный формат',
            'no_template_places' => 'Вы должны создать "расположение мест" с количеством сидячих мест :places',
            'confirm_accepted' => 'Вы должны принять условия',
            'black_list' => 'Ошибка авторизации, свяжитесь с диспетчером!',
        ],
    ],
    'black_list' => 'Ошибка бронирования, свяжитесь с диспетчером!',
    'limit_order_route' => 'Запрещено ездить дважды по одному направлению в день',
    'limit_tour' => 'Бронь на данный рейс уже есть',
    'limit_time' => 'Упс... Истекло время для бронирования (10 мин). Повторите попытку!',
    'error_sms_code' => 'Неверный смс код!',
    'no_exist' => 'Не найден',
    'empty_city_from' => 'Пустое поле city_from_id',
    'timestamp' => 'Не правильный формат даты (timestamp)',
    'old_password_need' => 'Поле Старый пароль необходимо для смены пароля',

    'interfaceSettings' => [
        'theme_color_admin_panel' => 'Данные введены в неверном формате' 
    ],

    'company_exists'=> 'Такой компании не существует',
    'department_exists'=> 'Такого отдела не существует',
    'director_exists'=> 'Такого начальника не сущетсвует',
    'role_exists'=> 'Такой роли не существует',
    'after_or_equal' => '":attribute" должен быть позже ":date".',
];
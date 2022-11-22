<?php

return [
  "accepted" => "You have to agree with :attribute.",
  "active_url" => "Field :attribute must be full url.",
  "after" => "Field :attribute must be date after :date.",
  "alpha" => "Field :attribute can contain only letters.",
  "alpha_dash" => "Field :attribute can contain only letters, numbers and dashes.",
  "alpha_num" => "Field :attribute can contain only letters and numbers.",
  "before" => "Field :attribute must be the date before :date.",
  "between" => [
    "numeric" => "Field :attribute should be between :min and :max.",
    "file" => "Field :attribute should be from :min to :max Kilobytes.",
    "string" => "Field :attribute should be from :min to :max symbols.",
  ],
  "confirmed" => "Password does not match confirmation",
  "different" => "Field :attribute and :other should vary.",
  "digits" => "Field :attribute should be contain :digits numbers.",
  "digits_between" => "Field :attribute should be contain from :min to :max numbers.",
  "email" => "Field :attribute has the wrong format",
  "exists" => "Selected value for :attribute already exists.",
  "image" => "Field :attribute should be a picture.",
  "in" => "Selected value for :attribute not true.",
  "integer" => "Field :attribute must be an integer.",
  "ip" => "Field :attribute must be a full IP address.",
  "match" => "Field :attribute has the wrong format.",
  "max" => [
    "numeric" => "Field :attribute should be less :max.",
    "file" => "Field :attribute should be less :max Kilobytes.",
    "string" => "Field :attribute should be shorter :max символов.",
  ],
  "mimes" => "Field :attribute must be a file of one of the types: :values.",
  "min" => [
    "numeric" => "Field :attribute must be at least :min.",
    "file" => "Field :attribute must be at least :min Kilobytes.",
    "string" => "Field :attribute should be no shorter :min символов.",
  ],
  "not_in" => "Selected value for :attribute not true.",
  "numeric" => "Field :attribute must be an integer.",
  "regex" => "Field :attribute has the wrong format.",
  "required" => 'Field ":attribute" required.',
  'required_with' => 'Indicate :attribute',
  "same" => "Value :attribute must match the value :other.",
  "size" => [
    "numeric" => "Field :attribute should be :size.",
    "file" => "Field :attribute should be :size Kilobytes.",
    "string" => "Field :attribute should be long :size символов.",
  ],
  "unique" => "Selected value for :attribute already exists.",
  "url" => "Field :attribute has the wrong format.",

  'attributes' => [
    'title' => 'Headline',
    'slug' => 'Link',
  ],

  'index' => [
    'required' => 'Indicate :attribute',
    'custom' => [
      'hard_password' => 'Please enter a more complex password.',
      'phone_unique' => 'This phone is busy',
      'email_unique' => 'This Email is busy',
      'phone_exists' => 'User with this phone not found',
      'phone_size' => 'Invalid phone number',
      'login_error' => 'Wrong login or password',
        'login_forget' => 'The phone number does not exist',
      'places_required' => 'Choose at least one place',
      'not_selected' => 'No tour selected',
      'load_clients' => 'Clients are loading',
      'password_min_six' => 'Password length at least 6 characters',
      'old_password_wrong' => 'The old password is incorrect',
      'date_after_today' => 'Cannot indicate past date',
      'date_error' => 'Wrong format',
      'no_template_places' => 'You must create a "seating arrangement" with the number of seats :places',
      'confirm_accepted' => 'You must accept the terms',
    ],
  ],
  'black_list' => 'Booking error, contact the dispatcher!',
  'limit_order_route' => 'It is forbidden to travel twice in the same direction per day',
  'limit_tour' => 'Booking for this flight is already there',
  'limit_time' => 'Oops ... Time for reservation has expired (10 min). Try again!',
  'error_sms_code' => 'Invalid SMS code!',
  'no_exist' => 'Not found',
  'empty_city_from' => 'Empty field city_from_id',
  'timestamp' => 'Invalid date format (timestamp)',
  'old_password_need' => 'The Old password field is required to change the password',
    'company_exists' => 'No such company exists',
    'department_exists' => 'This department does not exist',
    'director_exists' => 'There is no such boss',
    'role_exists' => 'This role does not exist',
];
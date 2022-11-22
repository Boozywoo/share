<?php

return [
	"accepted" => "You must agree with :attribute.",
	"active_url" => "Field :attribute must be a full URL.",
	"after" => "Field :attribute must be a date after :date.",
	"alpha" => "Field :attribute can only contain letters.",
	"alpha_dash" => "Field :attribute can only contain letters, numbers and dashes.",
	"alpha_num" => "Field :attribute can only contain letters and numbers.",
	"before" => "Field :attribute must be a date before :date.",
	"between" => [
		"numeric" => "Field :attribute must be between :min and :max.",
		"file" => "Field :attribute must be from :min to :max Kilobyte.",
		"string" => "Field :attribute must be from :min to :max characters.",
	],
	"confirmed" => "Password does not match the confirmation",
	"different" => "The fields :attribute and :other must be different.",
	"digits" => "Field :attribute must contain :digits of digits.",
	"digits_between" => "Field :attribute must contain from :min to :max digits.",
	"email" => "Field :attribute has an invalid format",
	"exists" => "The selected value for :attribute already exists.",
	"image" => "Field :attribute must be a picture.",
	"in" => "The selected value for :attribute is not true.",
	"integer" => "Field :attribute must be an integer.",
	"ip" => "Field :attribute must be a full IP address.",
	"match" => "Field :attribute has an invalid format.",
	"max" => [
		"numeric" => "Field :attribute must be less than :max.",
		"file" => "The field :attribute should be less :max Kilobyte.",
		"string" => "The field :attribute must be shorter :max characters.",
	],
	"mimes" => "Field :attribute must be a file of one of the following types: :values.",
	"min" => [
		"numeric" => "Field :attribute must be at least :min.",
		"file" => "The field :attribute must be at least :min Kilobyte.",
		"string" => "The field :attribute must be at least shorter :min characters.",
	],
	"not_in" => "The selected value for :attribute is not true.",
	"numeric" => "Field :attribute must be a number.",
	"regex" => "Field :attribute has an invalid format.",
	"required" => "Field :attribute Required.",
	'required_with' => 'Specify :attribute',
	"same" => "Value :attribute must match the value :other.",
	"size" => [
		"numeric" => "Field :attribute must be: size.",
		"file" => "Field :attribute must be :size Kilobyte.",
		"string" => "The field :attribute must be a length of :size characters.",
	],
	"unique" => "This field value :attribute already exists.",
	"url" => "Field :attribute has an invalid format.",

	'attributes' => [
		'title' => 'Title',
		'slug' => 'Link',
	],

	'index' => [
		'required' => 'Specify :attribute',
		'custom' => [
			'hard_password' => 'Please enter a more complex password',
			'phone_unique' => 'This phone is already in use',
			'email_unique' => 'This Email is already in use',
			'phone_exists' => 'User with this phone number is not found',
			'phone_size' => 'Incorrect phone number',
			'login_error' => 'Invalid login or password',
			'password_min_six' => 'Password length is at least 6 characters',
			'old_password_wrong' => 'The old password was entered incorrectly',
			'date_after_today' => 'Can not specify a past date',
			'date_error' => 'Invalid format',
			'no_template_places' => 'You must create a "location of seats" with the number of seats :places',
			'confirm_accepted' => 'You must accept the terms',
		],
	]

];
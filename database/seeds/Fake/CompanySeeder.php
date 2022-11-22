<?php

use App\Models\Bus;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
	public function run()
	{
		Company::query()->delete();

		$templates = Template::all();

		$faker = [
			0 => [
				'name' => 'ООО "МегаАвто"',
				'responsible' => 'Иванов Иван',
				'position' => 'Директор',
				'phone' => '375111111111',
				'phone_sub' => '375221111111',
				'buses' => [
					0 => [
						'name' => 'Тестовый автобус подменки 4',
						'number' => '4445',
						'template_id' => $templates->random()->id,
						'drivers' => [
							0 => [
								'full_name' => 'Водитель подменки 4',
								'birth_day' => '02.02.1993',
								'phone' => '+375 (29) 444-44-45',
								'password' => 'test'
							]
						]
					],
					1 => [
						'name' => 'Тестовый автобус подменки 3',
						'number' => '3334',
						'template_id' => $templates->random()->id,
						'drivers' => [
							0 => [
								'full_name' => 'Водитель подменки 3',
								'birth_day' => '02.02.1993',
								'phone' => '+375 (29) 333-33-34',
								'password' => 'test'
							]
						]
					],
					2 => [
						'name' => 'Тестовый автобус подменки 2',
						'number' => '2223',
						'template_id' => $templates->random()->id,
						'drivers' => [
							0 => [
								'full_name' => 'Водитель подменки 2',
								'birth_day' => '02.02.1993',
								'phone' => '+375 (29) 222-22-23',
								'password' => 'test'
							]
						]
					],
					3 => [
						'name' => 'Тестовый автобус подменки 1',
						'number' => '1112',
						'template_id' => $templates->random()->id,
						'drivers' => [
							0 => [
								'full_name' => 'Водитель подменки 1',
								'birth_day' => '02.02.1993',
								'phone' => '+375 (29) 111-11-12',
								'password' => 'test'
							]
						]
					],
					4 => [
						'name' => 'Тестовый автобус 4',
						'number' => '4444',
						'template_id' => $templates->random()->id,
						'drivers' => [
							0 => [
								'full_name' => 'Водитель тестового автобуса 4',
								'birth_day' => '02.02.1993',
								'phone' => '+375 (29) 444-44-44',
								'password' => 'test'
							]
						]
					],
					5 => [
						'name' => '	Тестовый автобус 3',
						'number' => '3333',
						'template_id' => $templates->random()->id,
						'drivers' => [
							0 => [
								'full_name' => 'Водитель тестового Автобуса 3',
								'birth_day' => '02.02.1993',
								'phone' => '+375 (29) 333-33-33',
								'password' => 'test'
							]
						]
					],
					6 => [
						'name' => 'Тестовый автобус 2',
						'number' => '2222',
						'template_id' => $templates->random()->id,
						'drivers' => [
							0 => [
								'full_name' => 'Водитель тестового Автобуса 2',
								'birth_day' => '02.02.1993',
								'phone' => '+375 (29) 222-22-22',
								'password' => 'test'
							]
						]
					],
					7 => [
						'name' => 'Тестовый автобус 1',
						'number' => '1111',
						'template_id' => $templates->random()->id,
						'drivers' => [
							0 => [
								'full_name' => 'Водитель тестового Автобуса 1',
								'birth_day' => '02.02.1993',
								'phone' => '+375 (29) 111-11-11',
								'password' => 'test'
							]
						]
					],
				]
			],
			1 => [
				'name' => 'ООО "Свои Люди"',
				'responsible' => 'Сидоров Иван',
				'position' => 'Менеджер',
				'phone' => '375112222222',
				'phone_sub' => '375222222222',
				'buses' => [
					0 => [
						'name' => 'Mercedes Benz 1',
						'number' => '3333',
						'template_id' => $templates->random()->id,
						'drivers' => [
							0 => [
								'full_name' => 'Степан Петрович',
								'birth_day' => '02.02.1993',
								'phone' => 375447622173,
								'password' => 'test'
							]
						]
					],
					1 => [
						'name' => 'Mercedes Benz 2',
						'number' => '3311',
						'template_id' => $templates->random()->id,
						'drivers' => [
							0 => [
								'full_name' => 'Василий',
								'birth_day' => '04.01.1995',
								'phone' => 375441435512,
								'password' => 'test'
							]
						]
					],
					2 => [
						'name' => 'Mercedes Benz 3',
						'number' => '3344',
						'template_id' => $templates->random()->id,
						'drivers' => [
							0 => [
								'full_name' => 'Инакентий Иванович',
								'birth_day' => '01.11.1990',
								'phone' => 375441435513,
								'password' => 'test'
							]
						]
					],
					3 => [
						'name' => 'Mercedes Benz 4',
						'number' => '3312',
						'template_id' => $templates->random()->id,
						'drivers' => [
							0 => [
								'full_name' => 'Егор Васильевич',
								'birth_day' => '11.11.1969',
								'phone' => 375441435514,
								'password' => 'test'
							]
						]
					],
				]
			]
		];

		$companySync = [];
		foreach ($faker as $companyItem) {
			$company = Company::create([
				'name' => $companyItem['name'],
				'responsible' => $companyItem['responsible'],
				'position' => $companyItem['position'],
				'phone' => $companyItem['phone'],
				'phone_sub' => $companyItem['phone_sub'],
			]);
			foreach ($companyItem['buses'] as $busKey => $busItem) {
				$places = $templates->where('id', $busItem['template_id'])->first()->count_places;
				$bus = Bus::create([
					'company_id' => $company->id,
					'name' => $busItem['name'],
					'number' => $busItem['number'],
					'template_id' => $busItem['template_id'],
					'places' => $places,
				]);
				$this->saveImage($bus, "bus" . $busKey);
				foreach ($busItem['drivers'] as $driverKey => $driverItem) {
					$driver = Driver::create([
//						'bus_id' => $bus->id,
						'full_name' => $driverItem['full_name'],
						'birth_day' => $driverItem['birth_day'],
						'phone' => $driverItem['phone'],
						'password' => $driverItem['password']
					]);
					$this->saveImage($driver, "driver" . $driverKey);
				}
			}
			$companySync += [$company->id];
		}
		$user = User::first();
		$user->companies()->sync($companySync);


		//Drivers
	}

	protected function saveImage($item, $name)
	{
		$data = [
			'images' =>
				[$item::IMAGE_TYPE_IMAGE => [
					'path' => [
						0 => public_path('assets' . DIRECTORY_SEPARATOR . 'index' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'faker' . DIRECTORY_SEPARATOR . $name . '.jpg')
					],
					'main' => 1,
					'order' => 1,
				]]
		];

		$item->syncImages($data);
	}
}

<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
	public function run()
	{
		Page::where('id', '!=', 0)->delete();

		$pages = [
			0 => [
				'title' => 'О нас',
				'content' => 'Текст о нас',
			],
			1 => [
				'title' => 'Условия пользовательского соглашения',
				'content' => 'Условия пользовательского соглашения текст',
			],
            2 => [
                'title' => 'Правила пассажирских перевозок',
                'content' => 'Правила пассажирских перевозок текст',
            ],
		];

		foreach ($pages as $item) {
			$page = Page::create($item);
		}

	}
}

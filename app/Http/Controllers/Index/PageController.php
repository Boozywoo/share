<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
	public function index($slug)
	{
		$page = Page::whereSlug($slug)->first();

		if (!$page) {
			abort(404);
		}

		if (view()->exists('index.pages.templates.' . $page->slug)) {
			return view('index.pages.templates.' . $page->slug, compact('page'));
		}

		return view('index.pages.index', compact('page'));
	}

}
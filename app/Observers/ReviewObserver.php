<?php

namespace App\Observers;

use App\Models\Review;

class ReviewObserver
{
	public function saving(Review $review)
	{
		if ($review->rating >= 3) {
			$review->type = Review::TYPE_POSITIVE;
		} else {
			$review->type = Review::TYPE_NEGATIVE;
		}
	}
}
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Services\RatingService;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    protected $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    public function rate(Request $request,$offerId)
    {
        $request->validate([
            'rating'=>'required|integer|min:1|max:5',
            'review'=>'nullable|string|max:300'
        ]);

        $rating = $this->ratingService->rateOffer(
            $offerId,
            Auth::id(),
            $request->all()
        );

       
        return ApiResponse::success(
    $rating
);
    }
}
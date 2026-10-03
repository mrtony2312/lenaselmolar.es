<?php

namespace App\Http\Controllers;

use App\Domain\Merchant\GoogleFeedGenerator;
use App\Http\Controllers\Merchant\GoogleFeedController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FeedController extends Controller
{
    /**
     * Legacy URL (GMC / browser preview). Same XML as /feeds/google-shopping.xml.
     */
    public function googleMerchant(Request $request, GoogleFeedGenerator $generator): Response
    {
        return app(GoogleFeedController::class)->view($request, $generator);
    }
}

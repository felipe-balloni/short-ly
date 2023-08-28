<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShortURLResource;
use App\Http\Resources\ShortURLVisitResource;
use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use App\Models\ShortURL;
use Illuminate\Http\Request;

class ShortURLVisitController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return ShortURLVisitResource::collection(
            ShortURLVisit::query()->where("short_url_id", $request->id)->get()
        )->response();
    }
}

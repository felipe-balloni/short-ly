<?php

namespace App\Http\Controllers;

use App\Models\ShortURL;

class StatisticController extends Controller
{
    public function __invoke()
    {
        $shortUrl = ShortURL::query()
            ->withCount([
                'visits',
                'visits as referer_url_count' => function ($query) {
                    $query->whereNotNull('referer_url');
                },
            ])
            ->get();

        // return json api format
        return response()->json([
            'data' => [
                'total' => $shortUrl->count(),
                'total_visits' => $shortUrl->sum('visits_count'),
                'total_referer_url' => $shortUrl->sum('referer_url_count'),
            ],
        ]);
    }
}

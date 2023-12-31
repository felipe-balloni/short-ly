<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShortURLRequest;
use App\Http\Resources\ShortURLResource;
use App\Models\ShortURL;
use AshAllenDesign\ShortURL\Classes\Builder;
use AshAllenDesign\ShortURL\Exceptions\ShortURLException;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ShortURLController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ShortURLResource::collection(
            ShortURL::query()
                ->withCount([
                    'visits',
                    'visits as referer_url_count' => function ($query) {
                        $query->whereNotNull('referer_url');
                    },
                ])
                ->when(request('search'), function ($query) {
                    $query->where(function ($query) {
                        $query->where('destination_url', 'like', '%'.request('search').'%')
                            ->orWhere('url_key', 'like', '%'.request('search').'%');
                    });
                })
                ->orderBy(request('sort_by', 'id'), request('sort_direction', 'desc'))
                ->paginate(request('per_page', 10))
        )->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws ShortURLException
     */
    public function store(ShortURLRequest $request, Builder $builder)
    {
        $url_key = $this->generateUrlKey($request->url_key);

        $shortURL = $builder->destinationUrl($request->destination_url)
            ->urlKey($url_key)
            ->trackVisits()
            ->make();

        return (new ShortURLResource($shortURL))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Generate a URL key.
     */
    protected function generateUrlKey(?string $url_key): string
    {
        return $url_key ?? Str::random(rand(6, 8));
    }

    /**
     * Display the specified resource.
     */
    public function show(ShortURL $shortURL)
    {
        return (new ShortURLResource($shortURL
            ->loadCount([
                'visits',
                'visits as referer_url_count' => function ($query) {
                    $query->whereNotNull('referer_url');
                },
            ])
        ))->response();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShortURLRequest $request, ShortURL $shortURL)
    {
        $url_key = $this->generateUrlKey($request->url_key ?? $shortURL->url_key);

        $shortURL->update([
            'destination_url' => $request->destination_url,
            'url_key' => $url_key,
            'default_short_url' => $this->buildDefaultShortUrl($url_key),
        ]);

        return (new ShortURLResource($shortURL))
            ->response();
    }

    /**
     * Build the default short URL based on the given URL key.
     *
     * @param  string  $url_key The URL key to build the short URL with.
     * @return string The built default short URL.
     */
    private function buildDefaultShortUrl(string $url_key): string
    {
        $baseUrl = config('short-url.default_url') ?? config('app.url');
        $baseUrl .= '/';

        if (config('short-url.prefix') !== null) {
            $baseUrl .= trim(config('short-url.prefix'), '/').'/';
        }

        return $baseUrl.$url_key;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShortURL $shortURL)
    {
        $shortURL->delete();

        return response()->noContent();
    }
}

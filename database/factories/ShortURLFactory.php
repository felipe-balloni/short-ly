<?php

namespace Database\Factories;

use App\Models\User;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ShortURLFactory extends Factory
{
    protected $model = ShortURL::class;

    public function definition(): array
    {
        $baseUrl = config('short-url.default_url') ?? config('app.url');
        $baseUrl .= '/';

        if (config('short-url.prefix') !== null) {
            $baseUrl .= trim(config('short-url.prefix'), '/').'/';
        }

        $urlKey = Str::random(rand(6, 8));

        $destinationUrl = [
            'https://www.google.com/',
            'https://www.youtube.com/',
            'https://www.facebook.com/',
            'https://www.uol.com.br/',
            'https://www.twitter.com/',
            'https://www.mercadolivre.com.br/',
            'https://www.linkedin.com/',
            'https://clinicaexperts.com.br/',
            'https://www.apple.com/',
            'https://www.amazon.com/',
            'https://www.netflix.com/',
            'https://www.yahoo.com/',
            'https://www.twitch.tv/',
            'https://www.github.com/',
            'https://www.espn.com/',
        ];

        return [
            'user_id' => User::factory(),
            'destination_url' => $this->faker->randomElement($destinationUrl),
            'default_short_url' => url($baseUrl.$urlKey),
            'url_key' => $urlKey,
            'single_use' => false,
            'forward_query_params' => true,
            'track_visits' => true,
            'track_ip_address' => true,
            'track_operating_system' => true,
            'track_operating_system_version' => true,
            'track_browser' => true,
            'track_browser_version' => true,
            'track_referer_url' => true,
            'track_device_type' => true,
        ];
    }
}

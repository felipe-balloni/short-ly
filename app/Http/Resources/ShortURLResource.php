<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortURLResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'destination_url' => $this->destination_url,
            'short_url' => $this->default_short_url,
            'url_key' => $this->url_key,
            'visits_count' => $this->visits_count ?? 0,
            'referer_url_count' => $this->referer_url_count ?? 0,
        ];
    }
}

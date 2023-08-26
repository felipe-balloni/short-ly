<?php

namespace App\Observers;


use AshAllenDesign\ShortURL\Models\ShortURL;

class ShortURLObserver
{
    /**
     * Handle the ShortURL "created" event.
     */
    public function creating(ShortURL $shortURL): void
    {
        if(auth()->check()) {
            $shortURL->user_id = auth()->user()->id;
        }
    }
}

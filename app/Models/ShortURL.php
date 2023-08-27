<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use AshAllenDesign\ShortURL\Models\ShortURL as BaseShortURL;

/**
 * @method static paginate(mixed $request)
 */
class ShortURL extends BaseShortURL
{
    use BelongsToTenant;
}

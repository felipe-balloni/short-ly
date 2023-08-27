<?php

namespace App\Traits;

use App\Models\Scopes\TenantScope;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static addGlobalScope(TenantScope $param)
 * @method static creating(\Closure $param)
 */
trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->user_id = auth()->user()->id;
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

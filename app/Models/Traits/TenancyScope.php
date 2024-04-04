<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait TenancyScope
{
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('tenancy', function (Builder $builder) {
            $builder->whereAccountLogged();
        });
    }

    public function scopeWithoutTenancyScope(Builder $query)
    {
        $query->withoutGlobalScope('tenancy');
    }
}

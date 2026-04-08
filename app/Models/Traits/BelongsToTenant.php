<?php

namespace App\Models\Traits;

use App\Models\Tenant;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::creating(function ($model) {
            if (! $model->tenant_id && Filament::hasTenant()) {
                $model->tenant_id = Filament::getTenant()->id;
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder) {
            if (Filament::hasTenant()) {
                $builder->where('tenant_id', Filament::getTenant()->id);
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}

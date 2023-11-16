<?php

namespace App\Models;

use Illuminate\Support\Str;

trait HasUuid
{
    public const UUID = 'uuid';

    public static function boot(): void
    {
        parent::boot();

        self::creating(function ($model): void {
            $model->uuid = Str::uuid()->toString();
        });
    }
}
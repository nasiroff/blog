<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'is_main'
    ];

    public function scopeMain(Builder $query)
    {
        $query->where('is_main', true);
    }

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

}

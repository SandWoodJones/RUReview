<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['user_id', 'meal_id', 'rating', 'comment'])]
class Review extends Model
{
    protected $casts = ['rating' => 'integer'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function meal(): BelongsTo {
        return $this->belongsTo(Meal::class);
    }

    public function image(): HasOne {
        return $this->hasOne(Image::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['review_id', 'image_data', 'mime_type'])]
#[Hidden(['image_data'])]
class Image extends Model
{
    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['date'])]
class DailyMenu extends Model
{
    protected $casts = ['date' => 'date:Y-m-d'];

    public function meals(): HasMany
    {
        return $this->hasMany(Meal::class);
    }
}

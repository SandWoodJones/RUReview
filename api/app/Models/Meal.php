<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['daily_menu_id', 'type', 'protein', 'protein_vegan', 'beans', 'carb_extra', 'salad_extra', 'dessert'])]
class Meal extends Model
{
    public function dailyMenu(): BelongsTo
    {
        return $this->belongsTo(DailyMenu::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}

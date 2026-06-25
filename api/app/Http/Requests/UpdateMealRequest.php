<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'protein' => ['required', 'string', 'max:255'],
            'protein_vegan' => ['required', 'string', 'max:255'],
            'beans' => ['required', Rule::in(['preto', 'carioca'])],
            'carb_extra' => ['required', 'string', 'max:255'],
            'salad_extra' => ['required', 'string', 'max:255'],
            'dessert' => ['required', 'string', 'max:255']
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date_format:Y-m-d'],
            'type' => ['required', Rule::in(['almoco', 'janta'])],
            'protein' => ['required', 'string', 'max:255'],
            'protein_vegan' => ['required', 'string', 'max:255'],
            'beans' => ['required', Rule::in(['preto', 'carioca'])],
            'carb_extra' => ['required', 'string', 'max:255'],
            'salad_extra' => ['required', 'string', 'max:255'],
            'dessert' => ['required', 'string', 'max:255']
        ];
    }
}

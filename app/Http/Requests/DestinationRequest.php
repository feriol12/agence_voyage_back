<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class DestinationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $rules = [
            'name' => 'sometimes|required|string|max:255',
            'country' => 'sometimes|required|string|max:100',
            'continent' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url|max:500',
            'visa_required' => 'boolean',
            'is_active' => 'boolean',
        ];

        if ($this->isMethod('POST')) {
            $rules['name'] = 'required|string|max:255';
            $rules['country'] = 'required|string|max:100';
        }

        return $rules;
    }
}

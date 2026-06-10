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
        // $user = auth()->user();
    return true; // TEMPORAIRE - à remettre en place après l'auth
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
            return [
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'continent' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url|max:500',
            'visa_required' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}

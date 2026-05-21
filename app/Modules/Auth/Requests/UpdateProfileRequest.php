<?php

namespace App\Modules\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname'   => 'nullable|string|max:255',
            'lastname'    => 'nullable|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'city'        => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country'     => 'nullable|string|max:100',
            'newsletter'  => 'nullable|boolean',
            'birthday'    => 'nullable|date',
            'avatar'      => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'firstname.max' => 'Le prénom ne doit pas dépasser 255 caractères.',
            'lastname.max'  => 'Le nom ne doit pas dépasser 255 caractères.',
            'birthday.date' => 'Veuillez fournir une date de naissance valide.',
        ];
    }
}

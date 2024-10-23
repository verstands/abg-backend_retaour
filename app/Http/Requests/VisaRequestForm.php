<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisaRequestForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'numero' => ['required'],
            'nom' => ['required'],
            'postnom' => ['required'],
            'prenm' => ['required'],
            'datenaissance' => ['required'],
            'nationalite' => ['required'],
            'sexe' => ['required'],
            'passeport' => ['required'],
            'adresse' => ['required'],
            'telephone' => ['required'],
            'etat' => ['required'],
            'id_typevisa' => ['required'],

        ];
    }
}

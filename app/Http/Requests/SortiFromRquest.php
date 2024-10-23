<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SortiFromRquest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom_emateur' => ['required'],
            'nom_recepteur' => ['required'],
            'matricule' => ['required'],
            'telephone' => ['required'],
            'pays_provenance' => ['required'],
            'pays_destinateut' => ['required'],
            'montant' => ['required'],
            'motif' => ['required'],
            'montant' => ['required'],
            'etat' => ['required'],
        ];
    }
}

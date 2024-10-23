<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntreFromRquest extends FormRequest
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
            'nom_emateur' => ['required'],
            'nom_recepteur' => ['required'],
            'matricule' => ['required'],
            'telephone' => ['required'],
            'pays_provenance' => ['required'],
            'pays_destinateut' => ['required'],
            'motif' => ['required'],
            'montant' => ['required'],
            'etat' => ['required'],
        ];
    }
}

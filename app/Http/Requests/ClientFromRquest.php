<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientFromRquest extends FormRequest
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
            'nom_client' => ['required'],
            'telephone' => ['required'],
            'id_conteneur' => ['required'],
            'montant' => ['required'],
            'etat' => ['required'],
            'montantpayer' => ['required'],
            'marchandises' => ['required', 'array'],
            'marchandises.*.produit' => ['required', 'string'],
            'marchandises.*.qte' => ['required', 'integer'], 

        ];
    }
}

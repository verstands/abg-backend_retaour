<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetteFromRquest extends FormRequest
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
            'motif_dette' => ['nullable'], // Champ motif_dette peut être vide
            'montant_dette' => ['nullable', 'numeric'], // Champ montant_dette peut être vide, mais s'il est présent, il doit être numérique
            'id_transaction' => ['nullable', 'exists:transactions,id'], // Champ id_transaction peut être vide, mais s'il est présent, il doit correspondre à un enregistrement existant dans la table transactions
            'montantpayer' => ['required'], // Champ id_transaction peut être vide, mais s'il est présent, il doit correspondre à un enregistrement existant dans la table transactions
            'etat_dette' => ['required'], // Champ id_transaction peut être vide, mais s'il est présent, il doit correspondre à un enregistrement existant dans la table transactions
        ];
    }
    

}

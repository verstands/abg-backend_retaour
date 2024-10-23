<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClotureRequest extends FormRequest
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
            'entredubai' => ['required'],
            'sortidubai' => ['required'],
            'entreKinhsasa' => ['required'],
            'sortiKinhsasa' => ['required'],
            'depenseDubai' => ['required'],
            'depenseKinshasa' => ['required'],
            'dettepartenaire' => ['required'],
            'detteclient' => ['required'],
            'balanceDubai' => ['required'],
            'balanceKinshasa' => ['required']
        ];
    }
}

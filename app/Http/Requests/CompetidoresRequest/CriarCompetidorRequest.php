<?php

namespace App\Http\Requests\CompetidoresRequest;

use Illuminate\Foundation\Http\FormRequest;

class CriarCompetidorRequest extends FormRequest
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
            'nome' => ['required'],
            'data_nascimento' => ['required', 'date', 'before:today'],
            'idade' => ['required'],
            'peso' => ['required'],
            'faixa' => ['required'],
            'sexo' => ['required']
        ];
    }
}

<?php

namespace App\Http\Requests\CategoriaRequest;

use Illuminate\Foundation\Http\FormRequest;

class CriarCategoriaRequest extends FormRequest
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
            'idade_inicio' => ['required', 'gt:0'],
            'idade_fim' => ['required', 'gt:idade_inicio'],
            'peso_inicio' => ['required', 'gt:0'],
            'peso_fim' => ['required', 'gt:peso_inicio'],
            'sexo' => ['required', 'in:1,2,3'],
        ];
    }
}

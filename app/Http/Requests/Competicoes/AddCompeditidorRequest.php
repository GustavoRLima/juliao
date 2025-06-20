<?php

namespace App\Http\Requests\Competicoes;

use Illuminate\Foundation\Http\FormRequest;

class AddCompeditidorRequest extends FormRequest
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
            'competidores.*.competidor_id' => ['required', 'exists:competidores,id'],
            'competidores.*.categoria_id' => ['required', 'exists:categorias,id'],
            'competidores.*.faixa' => ['required'],
        ];
    }
}

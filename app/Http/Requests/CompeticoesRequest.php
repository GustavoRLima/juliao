<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompeticoesRequest extends FormRequest
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
            'descricao' => ['required'],
            'data_evento' => ['required', 'date', 'after:today']
        ];
    }

    function messages()
    {
        return [
            'data_evento.after' => 'A data do evento tem que ser posterior a data de hoje!'
        ];
    }
}

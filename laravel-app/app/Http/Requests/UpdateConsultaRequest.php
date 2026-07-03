<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConsultaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'motivo_consulta' => ['nullable', 'string'],
            'diagnostico'     => ['nullable', 'string'],
            'tratamiento'     => ['required', 'string'],
            'observaciones'   => ['nullable', 'string'],

            'medicamentos'                => ['nullable', 'array'],
            'medicamentos.*.nombre'       => ['nullable', 'string', 'max:255'],
            'medicamentos.*.dosis'        => ['nullable', 'string', 'max:100'],
            'medicamentos.*.frecuencia'   => ['nullable', 'string', 'max:100'],
            'medicamentos.*.duracion'     => ['nullable', 'string', 'max:100'],
            'medicamentos.*.indicaciones' => ['nullable', 'string'],
        ];
    }
}

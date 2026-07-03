<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsultaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación. Reemplazan al viejo sanitizar() manual:
     * Laravel valida ANTES de tocar la base de datos y Blade escapa la
     * salida automáticamente, por lo que no hace falta htmlspecialchars.
     */
    public function rules(): array
    {
        return [
            'medico_matricula'     => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9]+$/'],
            'medico_nombre'        => ['nullable', 'string', 'max:255'],
            'medico_especialidad'  => ['nullable', 'string', 'max:255'],

            'paciente_nombre'      => ['required', 'string', 'max:255'],
            'paciente_apellido'    => ['required', 'string', 'max:255'],
            'paciente_dni'         => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'paciente_fecha_nac'   => ['nullable', 'date', 'before_or_equal:today'],
            'paciente_sexo'        => ['nullable', 'in:M,F,O'],
            'paciente_telefono'    => ['nullable', 'string', 'max:30'],
            'paciente_rasgos'      => ['nullable', 'string'],

            'motivo_consulta'      => ['nullable', 'string'],
            'diagnostico'          => ['nullable', 'string'],
            'tratamiento'          => ['required', 'string'],
            'observaciones'        => ['nullable', 'string'],

            'medicamentos'                 => ['nullable', 'array'],
            'medicamentos.*.nombre'        => ['nullable', 'string', 'max:255'],
            'medicamentos.*.dosis'         => ['nullable', 'string', 'max:100'],
            'medicamentos.*.frecuencia'    => ['nullable', 'string', 'max:100'],
            'medicamentos.*.duracion'      => ['nullable', 'string', 'max:100'],
            'medicamentos.*.indicaciones'  => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'medico_matricula.regex' => 'La matrícula solo puede contener letras y números.',
            'paciente_dni.regex'     => 'El DNI solo puede contener números.',
        ];
    }
}

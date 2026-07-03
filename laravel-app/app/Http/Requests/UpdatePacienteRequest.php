<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pacienteId = $this->route('paciente')->id;

        return [
            'nombre'              => ['required', 'string', 'max:255'],
            'apellido'            => ['required', 'string', 'max:255'],
            'dni'                 => [
                'nullable', 'string', 'max:20', 'regex:/^[0-9]+$/',
                Rule::unique('pacientes', 'dni')->ignore($pacienteId),
            ],
            'fecha_nacimiento'    => ['nullable', 'date', 'before_or_equal:today'],
            'sexo'                => ['nullable', 'in:M,F,O'],
            'telefono'            => ['nullable', 'string', 'max:30'],
            'rasgos_particulares' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'dni.regex'  => 'El DNI solo puede contener números.',
            'dni.unique' => 'Ya existe otro paciente con ese DNI.',
        ];
    }
}

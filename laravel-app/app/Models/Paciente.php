<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    protected $table = 'pacientes';

    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'fecha_nacimiento',
        'sexo',
        'telefono',
        'rasgos_particulares',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }

    public function getSexoLabelAttribute(): ?string
    {
        return match ($this->sexo) {
            'M' => 'Masculino',
            'F' => 'Femenino',
            'O' => 'Otro',
            default => null,
        };
    }

    public function getEdadAttribute(): ?int
    {
        return $this->fecha_nacimiento?->age;
    }
}

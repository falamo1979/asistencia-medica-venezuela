<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consulta extends Model
{
    protected $table = 'consultas';

    protected $fillable = [
        'paciente_id',
        'medico_id',
        'motivo_consulta',
        'diagnostico',
        'tratamiento_aplicado',
        'observaciones',
        'anulada_at',
        'motivo_anulacion',
        'anulada_por',
    ];

    protected $casts = [
        'anulada_at' => 'datetime',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function medico(): BelongsTo
    {
        return $this->belongsTo(Medico::class);
    }

    public function medicamentosSuministrados(): HasMany
    {
        return $this->hasMany(MedicamentoSuministrado::class);
    }

    public function anuladaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'anulada_por');
    }

    /** Solo consultas vigentes (no anuladas). */
    public function scopeActiva(Builder $query): Builder
    {
        return $query->whereNull('anulada_at');
    }

    public function isAnulada(): bool
    {
        return $this->anulada_at !== null;
    }
}

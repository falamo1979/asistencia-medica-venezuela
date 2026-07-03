<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicamentoSuministrado extends Model
{
    protected $table = 'medicamentos_suministrados';

    protected $fillable = [
        'consulta_id',
        'medicamento_id',
        'dosis',
        'frecuencia',
        'duracion',
        'indicaciones',
    ];

    public function consulta(): BelongsTo
    {
        return $this->belongsTo(Consulta::class);
    }

    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class);
    }
}

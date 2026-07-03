<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medico extends Model
{
    protected $table = 'medicos';

    protected $fillable = [
        'matricula',
        'nombre',
        'apellido',
        'especialidad',
    ];

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class);
    }
}

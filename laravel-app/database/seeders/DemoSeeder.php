<?php

namespace Database\Seeders;

use App\Models\Consulta;
use App\Models\Medicamento;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Datos de ejemplo para probar la app rápidamente.
     * Ejecutar con: php artisan db:seed --class=DemoSeeder
     */
    public function run(): void
    {
        $medico = Medico::firstOrCreate(
            ['matricula' => 'MED001'],
            ['nombre' => 'Ana', 'apellido' => 'Pérez', 'especialidad' => 'Medicina General']
        );

        $paciente = Paciente::firstOrCreate(
            ['dni' => '12345678'],
            ['nombre' => 'Juan', 'apellido' => 'González', 'sexo' => 'M', 'telefono' => '0412-0000000']
        );

        $consulta = Consulta::create([
            'paciente_id'          => $paciente->id,
            'medico_id'            => $medico->id,
            'motivo_consulta'      => 'Fiebre y dolor de cabeza',
            'diagnostico'          => 'Síndrome viral',
            'tratamiento_aplicado' => 'Reposo e hidratación. Control en 48 horas.',
            'observaciones'        => 'Sin signos de alarma.',
        ]);

        $paracetamol = Medicamento::firstOrCreate(['nombre' => 'Paracetamol']);

        $consulta->medicamentosSuministrados()->create([
            'medicamento_id' => $paracetamol->id,
            'dosis'          => '500mg',
            'frecuencia'     => 'Cada 8 horas',
            'duracion'       => '3 días',
            'indicaciones'   => 'Tomar después de las comidas.',
        ]);
    }
}

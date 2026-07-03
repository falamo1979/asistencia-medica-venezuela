<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'consultas_hoy'   => Consulta::activa()->whereDate('created_at', today())->count(),
            'consultas_total' => Consulta::activa()->count(),
            'pacientes_total' => Paciente::count(),
            'medicos_total'   => Medico::count(),
        ];

        $ultimas = Consulta::activa()
            ->with(['paciente', 'medico'])
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', compact('stats', 'ultimas'));
    }
}

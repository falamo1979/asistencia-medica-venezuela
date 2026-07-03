<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HistorialController extends Controller
{
    /**
     * Lista las atenciones filtrando por rango de fechas y médico, con paginación.
     */
    public function index(Request $request): View
    {
        $data = $request->validate([
            'desde'     => ['nullable', 'date'],
            'hasta'     => ['nullable', 'date'],
            'medico_id' => ['nullable', 'integer', 'exists:medicos,id'],
        ]);

        $desde    = $data['desde'] ?? now()->toDateString();
        $hasta    = $data['hasta'] ?? $desde;
        $medicoId = $data['medico_id'] ?? null;

        $registros = Consulta::activa()
            ->with(['paciente', 'medico'])
            ->whereDate('created_at', '>=', $desde)
            ->whereDate('created_at', '<=', $hasta)
            ->when($medicoId, fn ($q) => $q->where('medico_id', $medicoId))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $medicos = Medico::orderBy('nombre')->orderBy('apellido')->get();

        return view('historial.index', compact('registros', 'desde', 'hasta', 'medicoId', 'medicos'));
    }
}

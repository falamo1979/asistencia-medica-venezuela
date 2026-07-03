<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePacienteRequest;
use App\Models\Paciente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PacienteController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));

        $pacientes = Paciente::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('nombre', 'ilike', "%{$q}%")
                      ->orWhere('apellido', 'ilike', "%{$q}%")
                      ->orWhere('dni', 'ilike', "%{$q}%");
                });
            })
            ->withCount('consultas')
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->paginate(12)
            ->withQueryString();

        return view('pacientes.index', compact('pacientes', 'q'));
    }

    public function show(Paciente $paciente): View
    {
        $paciente->load([
            'consultas' => fn ($q) => $q->latest(),
            'consultas.medico',
            'consultas.medicamentosSuministrados.medicamento',
        ]);

        return view('pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente): View
    {
        return view('pacientes.edit', compact('paciente'));
    }

    public function update(UpdatePacienteRequest $request, Paciente $paciente): RedirectResponse
    {
        $paciente->update($request->validated());

        return redirect()
            ->route('pacientes.show', $paciente)
            ->with('success', 'Ficha del paciente actualizada correctamente.');
    }
}

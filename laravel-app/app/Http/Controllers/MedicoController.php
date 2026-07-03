<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MedicoController extends Controller
{
    public function index(): View
    {
        $medicos = Medico::withCount('consultas')
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->paginate(15);

        return view('medicos.index', compact('medicos'));
    }

    public function edit(Medico $medico): View
    {
        return view('medicos.edit', compact('medico'));
    }

    public function update(Request $request, Medico $medico): RedirectResponse
    {
        $data = $request->validate([
            'matricula'    => [
                'required', 'string', 'max:50', 'regex:/^[A-Za-z0-9]+$/',
                Rule::unique('medicos', 'matricula')->ignore($medico->id),
            ],
            'nombre'       => ['required', 'string', 'max:255'],
            'apellido'     => ['nullable', 'string', 'max:255'],
            'especialidad' => ['nullable', 'string', 'max:255'],
        ]);

        $medico->update($data);

        return redirect()->route('medicos.index')
            ->with('success', 'Médico actualizado correctamente.');
    }
}

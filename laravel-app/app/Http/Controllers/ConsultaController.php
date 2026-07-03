<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConsultaRequest;
use App\Http\Requests\UpdateConsultaRequest;
use App\Models\Consulta;
use App\Models\Medicamento;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ConsultaController extends Controller
{
    /** Roles que pueden crear/editar/anular consultas (recepción solo consulta/imprime). */
    private function ensureCanManage(): void
    {
        abort_unless(
            Auth::user()->hasRole(User::ROLE_ADMIN, User::ROLE_MEDICO),
            Response::HTTP_FORBIDDEN,
            'No tenés permisos para modificar consultas.'
        );
    }

    public function create(): View
    {
        return view('consultas.create');
    }

    /**
     * Registra la consulta completa (médico + paciente + tratamiento + medicamentos)
     * dentro de una transacción. CSRF lo aplica Laravel automáticamente.
     */
    public function store(StoreConsultaRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $consulta = DB::transaction(function () use ($data) {
            $medico = Medico::firstOrNew(['matricula' => $data['medico_matricula']]);

            if (! $medico->exists && empty($data['medico_nombre'])) {
                abort(422, 'El nombre del médico es obligatorio para un registro nuevo.');
            }

            $medico->nombre = ($data['medico_nombre'] ?? '') ?: $medico->nombre;
            $medico->especialidad = ($data['medico_especialidad'] ?? '') ?: $medico->especialidad;
            $medico->save();

            $dni = $data['paciente_dni'] ?? null;
            $paciente = $dni ? Paciente::where('dni', $dni)->first() : null;

            if (! $paciente) {
                $paciente = Paciente::create([
                    'nombre'              => $data['paciente_nombre'],
                    'apellido'            => $data['paciente_apellido'],
                    'dni'                 => $dni ?: null,
                    'fecha_nacimiento'    => $data['paciente_fecha_nac'] ?? null,
                    'sexo'                => $data['paciente_sexo'] ?? null,
                    'telefono'            => $data['paciente_telefono'] ?? null,
                    'rasgos_particulares' => $data['paciente_rasgos'] ?? null,
                ]);
            }

            $consulta = Consulta::create([
                'paciente_id'          => $paciente->id,
                'medico_id'            => $medico->id,
                'motivo_consulta'      => $data['motivo_consulta'] ?? null,
                'diagnostico'          => $data['diagnostico'] ?? null,
                'tratamiento_aplicado' => $data['tratamiento'],
                'observaciones'        => $data['observaciones'] ?? null,
            ]);

            $this->syncMedicamentos($consulta, $data['medicamentos'] ?? []);

            return $consulta;
        });

        return redirect()
            ->route('consultas.show', $consulta)
            ->with('success', "Consulta registrada exitosamente (N.º {$consulta->id}).");
    }

    public function show(Consulta $consulta): View
    {
        $consulta->load([
            'paciente',
            'medico',
            'medicamentosSuministrados.medicamento',
            'anuladaPor',
        ]);

        return view('consultas.show', compact('consulta'));
    }

    public function edit(Consulta $consulta): View|RedirectResponse
    {
        $this->ensureCanManage();

        if ($consulta->isAnulada()) {
            return redirect()->route('consultas.show', $consulta)
                ->with('error', 'No se puede editar una consulta anulada.');
        }

        $consulta->load('medicamentosSuministrados.medicamento', 'paciente', 'medico');

        return view('consultas.edit', compact('consulta'));
    }

    public function update(UpdateConsultaRequest $request, Consulta $consulta): RedirectResponse
    {
        $this->ensureCanManage();

        if ($consulta->isAnulada()) {
            return redirect()->route('consultas.show', $consulta)
                ->with('error', 'No se puede editar una consulta anulada.');
        }

        $data = $request->validated();

        DB::transaction(function () use ($consulta, $data) {
            $consulta->update([
                'motivo_consulta'      => $data['motivo_consulta'] ?? null,
                'diagnostico'          => $data['diagnostico'] ?? null,
                'tratamiento_aplicado' => $data['tratamiento'],
                'observaciones'        => $data['observaciones'] ?? null,
            ]);

            // Se reemplaza el set completo de medicamentos.
            $consulta->medicamentosSuministrados()->delete();
            $this->syncMedicamentos($consulta, $data['medicamentos'] ?? []);
        });

        return redirect()->route('consultas.show', $consulta)
            ->with('success', 'Consulta actualizada correctamente.');
    }

    public function anular(Request $request, Consulta $consulta): RedirectResponse
    {
        $this->ensureCanManage();

        if ($consulta->isAnulada()) {
            return redirect()->route('consultas.show', $consulta)
                ->with('error', 'La consulta ya estaba anulada.');
        }

        $request->validate([
            'motivo_anulacion' => ['required', 'string', 'min:5', 'max:500'],
        ], [], ['motivo_anulacion' => 'motivo de anulación']);

        $consulta->update([
            'anulada_at'       => now(),
            'motivo_anulacion' => $request->input('motivo_anulacion'),
            'anulada_por'      => Auth::id(),
        ]);

        return redirect()->route('consultas.show', $consulta)
            ->with('success', 'La consulta fue anulada.');
    }

    public function pdf(Consulta $consulta): Response
    {
        $consulta->load([
            'paciente',
            'medico',
            'medicamentosSuministrados.medicamento',
        ]);

        $pdf = Pdf::loadView('consultas.pdf', compact('consulta'))->setPaper('a4');

        return $pdf->stream("consulta-{$consulta->id}.pdf");
    }

    /**
     * Crea los registros de medicamentos suministrados a partir del input.
     */
    private function syncMedicamentos(Consulta $consulta, array $medicamentos): void
    {
        foreach ($medicamentos as $med) {
            if (empty($med['nombre'])) {
                continue;
            }

            $medicamento = Medicamento::firstOrCreate(['nombre' => $med['nombre']]);

            $consulta->medicamentosSuministrados()->create([
                'medicamento_id' => $medicamento->id,
                'dosis'          => $med['dosis'] ?? null,
                'frecuencia'     => $med['frecuencia'] ?? null,
                'duracion'       => $med['duracion'] ?? null,
                'indicaciones'   => $med['indicaciones'] ?? null,
            ]);
        }
    }
}

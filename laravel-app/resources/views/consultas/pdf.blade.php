<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #2b333b;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .doc { padding: 4px 8px; }
        .header {
            border-bottom: 2px solid #2f6f6a;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }
        .header .name { font-size: 18px; font-weight: bold; color: #2f6f6a; }
        .header .sub { color: #6b7580; font-size: 11px; }
        .meta {
            text-align: right;
            font-size: 11px;
            color: #6b7580;
            margin-top: -30px;
        }
        h2 {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #2f6f6a;
            border-bottom: 1px solid #e2e6ea;
            padding-bottom: 4px;
            margin: 18px 0 8px;
        }
        table { width: 100%; border-collapse: collapse; }
        .kv td { padding: 3px 0; vertical-align: top; }
        .kv td.k { color: #6b7580; width: 130px; font-weight: bold; }
        .meds { margin-top: 6px; }
        .meds th, .meds td {
            border: 1px solid #e2e6ea;
            padding: 6px 8px;
            text-align: left;
            font-size: 11px;
        }
        .meds th { background: #f4f6f8; color: #6b7580; }
        .anulada {
            border: 1px solid #b04a4a;
            background: #f8eeee;
            color: #b04a4a;
            padding: 8px 10px;
            margin-bottom: 14px;
            font-size: 11px;
        }
        .footer {
            margin-top: 48px;
            border-top: 1px solid #e2e6ea;
            padding-top: 8px;
            font-size: 11px;
            color: #6b7580;
        }
        .sign {
            margin-top: 50px;
            width: 260px;
            border-top: 1px solid #2b333b;
            padding-top: 4px;
            text-align: center;
            font-size: 11px;
        }
    </style>
</head>
<body>
<div class="doc">
    <div class="header">
        <div class="name">Centro de Asistencia Médica</div>
        <div class="sub">Registro clínico</div>
        <div class="meta">
            Consulta N.º {{ $consulta->id }}<br>
            {{ $consulta->created_at->format('d/m/Y H:i') }}
        </div>
    </div>

    @if ($consulta->isAnulada())
        <div class="anulada">
            <strong>CONSULTA ANULADA</strong> — {{ $consulta->motivo_anulacion }}
        </div>
    @endif

    <h2>Paciente</h2>
    <table class="kv">
        <tr>
            <td class="k">Nombre</td><td>{{ $consulta->paciente->nombre_completo }}</td>
        </tr>
        <tr>
            <td class="k">DNI / Cédula</td><td>{{ $consulta->paciente->dni ?: '—' }}</td>
        </tr>
        <tr>
            <td class="k">Sexo</td><td>{{ $consulta->paciente->sexo_label ?: '—' }}</td>
        </tr>
        @if ($consulta->paciente->fecha_nacimiento)
            <tr>
                <td class="k">Nacimiento</td>
                <td>{{ $consulta->paciente->fecha_nacimiento->format('d/m/Y') }} ({{ $consulta->paciente->edad }} años)</td>
            </tr>
        @endif
    </table>

    <h2>Médico tratante</h2>
    <table class="kv">
        <tr>
            <td class="k">Profesional</td>
            <td>{{ $consulta->medico->nombre }} {{ $consulta->medico->apellido }}</td>
        </tr>
        <tr>
            <td class="k">Matrícula</td><td>{{ $consulta->medico->matricula }}</td>
        </tr>
        @if ($consulta->medico->especialidad)
            <tr>
                <td class="k">Especialidad</td><td>{{ $consulta->medico->especialidad }}</td>
            </tr>
        @endif
    </table>

    <h2>Detalle clínico</h2>
    <table class="kv">
        @if ($consulta->motivo_consulta)
            <tr><td class="k">Motivo</td><td>{{ $consulta->motivo_consulta }}</td></tr>
        @endif
        @if ($consulta->diagnostico)
            <tr><td class="k">Diagnóstico</td><td>{{ $consulta->diagnostico }}</td></tr>
        @endif
        <tr><td class="k">Tratamiento</td><td>{{ $consulta->tratamiento_aplicado }}</td></tr>
        @if ($consulta->observaciones)
            <tr><td class="k">Observaciones</td><td>{{ $consulta->observaciones }}</td></tr>
        @endif
    </table>

    @if ($consulta->medicamentosSuministrados->isNotEmpty())
        <h2>Medicamentos indicados</h2>
        <table class="meds">
            <thead>
                <tr>
                    <th>Medicamento</th>
                    <th>Dosis</th>
                    <th>Frecuencia</th>
                    <th>Duración</th>
                    <th>Indicaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($consulta->medicamentosSuministrados as $ms)
                    <tr>
                        <td>{{ $ms->medicamento->nombre }}</td>
                        <td>{{ $ms->dosis ?: '—' }}</td>
                        <td>{{ $ms->frecuencia ?: '—' }}</td>
                        <td>{{ $ms->duracion ?: '—' }}</td>
                        <td>{{ $ms->indicaciones ?: '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="sign">
        {{ $consulta->medico->nombre }} {{ $consulta->medico->apellido }}<br>
        Matrícula {{ $consulta->medico->matricula }}
    </div>

    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i') }} · Centro de Asistencia Médica
    </div>
</div>
</body>
</html>

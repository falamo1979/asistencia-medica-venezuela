@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
    <div class="page-header">
        <h1>Administración</h1>
        <p>Gestión del personal y los médicos del centro.</p>
    </div>

    @include('partials.admin_nav')

    <div class="detail-head" style="margin-top:20px;">
        <h2 class="section-title" style="margin:0;">Usuarios del sistema</h2>
        <button type="button" class="btn-primary"
                data-modal-target="modal-usuario"
                data-action="{{ route('usuarios.store') }}"
                data-method="POST" data-mode="create" data-title="Nuevo usuario">+ Nuevo usuario</button>
    </div>

    <div class="tabla-container">
        @forelse ($usuarios as $usuario)
            @if ($loop->first)
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Médico vinculado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
            @endif
                        <tr>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td><span class="badge badge-role">{{ $usuario->role_label }}</span></td>
                            <td>
                                @if ($usuario->medico)
                                    {{ $usuario->medico->nombre }} {{ $usuario->medico->apellido }}
                                @else
                                    <span class="muted">—</span>
                                @endif
                            </td>
                            @php
                                $uv = [
                                    'name' => $usuario->name,
                                    'email' => $usuario->email,
                                    'role' => $usuario->role,
                                    'medico_id' => $usuario->medico_id,
                                ];
                            @endphp
                            <td class="cell-actions">
                                <button type="button" class="link-btn"
                                        data-modal-target="modal-usuario"
                                        data-action="{{ route('usuarios.update', $usuario) }}"
                                        data-method="PUT" data-mode="edit"
                                        data-title="Editar a {{ $usuario->name }}"
                                        data-values='@json($uv)'>Editar</button>
                            </td>
                        </tr>
            @if ($loop->last)
                    </tbody>
                </table>
            @endif
        @empty
            <p class="empty-state">No hay usuarios.</p>
        @endforelse
    </div>

    {{ $usuarios->links('partials.pagination') }}

    @include('usuarios._form_modal')
@endsection

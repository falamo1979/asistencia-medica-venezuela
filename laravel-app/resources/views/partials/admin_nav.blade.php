<div class="tabs">
    <a href="{{ route('usuarios.index') }}"
       class="tab {{ request()->routeIs('usuarios.*') ? 'is-active' : '' }}">Usuarios</a>
    <a href="{{ route('medicos.index') }}"
       class="tab {{ request()->routeIs('medicos.*') ? 'is-active' : '' }}">Médicos</a>
</div>

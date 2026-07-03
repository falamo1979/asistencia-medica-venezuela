<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $usuarios = User::with('medico')->orderBy('name')->paginate(15);
        $medicos = Medico::orderBy('nombre')->orderBy('apellido')->get();

        return view('usuarios.index', compact('usuarios', 'medicos'));
    }

    public function create(): View
    {
        $medicos = Medico::orderBy('nombre')->orderBy('apellido')->get();

        return view('usuarios.create', compact('medicos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', 'unique:users,email'],
            'role'      => ['required', Rule::in(array_keys(User::ROLES))],
            'medico_id' => ['nullable', 'integer', 'exists:medicos,id'],
            'password'  => ['required', 'confirmed', Password::min(6)],
        ]);

        User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'role'      => $data['role'],
            'medico_id' => $data['role'] === User::ROLE_MEDICO ? ($data['medico_id'] ?? null) : null,
            'password'  => Hash::make($data['password']),
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario): View
    {
        $medicos = Medico::orderBy('nombre')->orderBy('apellido')->get();

        return view('usuarios.edit', compact('usuario', 'medicos'));
    }

    public function update(Request $request, User $usuario): RedirectResponse
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario->id)],
            'role'      => ['required', Rule::in(array_keys(User::ROLES))],
            'medico_id' => ['nullable', 'integer', 'exists:medicos,id'],
            'password'  => ['nullable', 'confirmed', Password::min(6)],
        ]);

        $usuario->fill([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'role'      => $data['role'],
            'medico_id' => $data['role'] === User::ROLE_MEDICO ? ($data['medico_id'] ?? null) : null,
        ]);

        if (! empty($data['password'])) {
            $usuario->password = Hash::make($data['password']);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }
}

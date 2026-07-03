<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/* ---------- Público (visitante) ---------- */
Route::view('/', 'landing')->name('landing');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

/* ---------- Requiere sesión iniciada ---------- */
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/consultas/nueva', [ConsultaController::class, 'create'])->name('consultas.create');
    Route::post('/consultas', [ConsultaController::class, 'store'])->name('consultas.store');
    Route::get('/consultas/{consulta}', [ConsultaController::class, 'show'])->whereNumber('consulta')->name('consultas.show');
    Route::get('/consultas/{consulta}/editar', [ConsultaController::class, 'edit'])->whereNumber('consulta')->name('consultas.edit');
    Route::put('/consultas/{consulta}', [ConsultaController::class, 'update'])->whereNumber('consulta')->name('consultas.update');
    Route::patch('/consultas/{consulta}/anular', [ConsultaController::class, 'anular'])->whereNumber('consulta')->name('consultas.anular');
    Route::get('/consultas/{consulta}/pdf', [ConsultaController::class, 'pdf'])->whereNumber('consulta')->name('consultas.pdf');

    Route::get('/historial', [HistorialController::class, 'index'])->name('historial.index');

    Route::get('/pacientes', [PacienteController::class, 'index'])->name('pacientes.index');
    Route::get('/pacientes/{paciente}', [PacienteController::class, 'show'])->name('pacientes.show');
    Route::get('/pacientes/{paciente}/editar', [PacienteController::class, 'edit'])->name('pacientes.edit');
    Route::put('/pacientes/{paciente}', [PacienteController::class, 'update'])->name('pacientes.update');

    /* ---------- Administración (solo admin) ---------- */
    Route::middleware('admin')->group(function () {
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/nuevo', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{usuario}/editar', [UserController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{usuario}', [UserController::class, 'update'])->name('usuarios.update');

        Route::get('/medicos', [MedicoController::class, 'index'])->name('medicos.index');
        Route::get('/medicos/{medico}/editar', [MedicoController::class, 'edit'])->name('medicos.edit');
        Route::put('/medicos/{medico}', [MedicoController::class, 'update'])->name('medicos.update');
    });
});

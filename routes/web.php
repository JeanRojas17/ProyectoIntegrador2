<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConductorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnvioController;
use App\Http\Controllers\MiMapaRBLController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'solo.lectura'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // -----------------------------------------------------------------
    // Gestion completa: solo administrador
    // -----------------------------------------------------------------
    Route::middleware('role:administrador')->group(function () {
        Route::get('clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
        Route::post('clientes', [ClienteController::class, 'store'])->name('clientes.store');
        Route::get('clientes/{cliente}/edit', [ClienteController::class, 'edit'])->whereNumber('cliente')->name('clientes.edit');
        Route::put('clientes/{cliente}', [ClienteController::class, 'update'])->whereNumber('cliente')->name('clientes.update');
        Route::delete('clientes/{cliente}', [ClienteController::class, 'destroy'])->whereNumber('cliente')->name('clientes.destroy');

        Route::get('conductores/create', [ConductorController::class, 'create'])->name('conductores.create');
        Route::post('conductores', [ConductorController::class, 'store'])->name('conductores.store');
        Route::get('conductores/{conductor}/edit', [ConductorController::class, 'edit'])->whereNumber('conductor')->name('conductores.edit');
        Route::put('conductores/{conductor}', [ConductorController::class, 'update'])->whereNumber('conductor')->name('conductores.update');
        Route::delete('conductores/{conductor}', [ConductorController::class, 'destroy'])->whereNumber('conductor')->name('conductores.destroy');

        Route::get('vehiculos/create', [VehiculoController::class, 'create'])->name('vehiculos.create');
        Route::post('vehiculos', [VehiculoController::class, 'store'])->name('vehiculos.store');
        Route::get('vehiculos/{vehiculo}/edit', [VehiculoController::class, 'edit'])->whereNumber('vehiculo')->name('vehiculos.edit');
        Route::put('vehiculos/{vehiculo}', [VehiculoController::class, 'update'])->whereNumber('vehiculo')->name('vehiculos.update');
        Route::delete('vehiculos/{vehiculo}', [VehiculoController::class, 'destroy'])->whereNumber('vehiculo')->name('vehiculos.destroy');

        Route::get('rutas/create', [RutaController::class, 'create'])->name('rutas.create');
        Route::post('rutas', [RutaController::class, 'store'])->name('rutas.store');
        Route::get('rutas/{ruta}/edit', [RutaController::class, 'edit'])->whereNumber('ruta')->name('rutas.edit');
        Route::put('rutas/{ruta}', [RutaController::class, 'update'])->whereNumber('ruta')->name('rutas.update');
        Route::delete('rutas/{ruta}', [RutaController::class, 'destroy'])->whereNumber('ruta')->name('rutas.destroy');

        Route::get('envios/create', [EnvioController::class, 'create'])->name('envios.create');
        Route::post('envios', [EnvioController::class, 'store'])->name('envios.store');
        Route::get('envios/{envio}/edit', [EnvioController::class, 'edit'])->whereNumber('envio')->name('envios.edit');
        Route::put('envios/{envio}', [EnvioController::class, 'update'])->whereNumber('envio')->name('envios.update');
        Route::delete('envios/{envio}', [EnvioController::class, 'destroy'])->whereNumber('envio')->name('envios.destroy');
    });

    // -----------------------------------------------------------------
    // Lectura general: administrador y usuario de consulta
    // -----------------------------------------------------------------
    Route::middleware('role:administrador,consulta')->group(function () {
        Route::get('clientes', [ClienteController::class, 'index'])->name('clientes.index');
        Route::get('clientes/{cliente}', [ClienteController::class, 'show'])->whereNumber('cliente')->name('clientes.show');

        Route::get('conductores', [ConductorController::class, 'index'])->name('conductores.index');
        Route::get('conductores/{conductor}', [ConductorController::class, 'show'])->whereNumber('conductor')->name('conductores.show');

        Route::get('vehiculos', [VehiculoController::class, 'index'])->name('vehiculos.index');
        Route::get('vehiculos/{vehiculo}', [VehiculoController::class, 'show'])->whereNumber('vehiculo')->name('vehiculos.show');

        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/exportar', [ReporteController::class, 'exportar'])->name('reportes.exportar');
    });

    // -----------------------------------------------------------------
    // Rutas y mapa: administrador, consulta y conductor (solo lectura)
    // El conductor solo ve las rutas de sus envíos asignados.
    // -----------------------------------------------------------------
    Route::middleware('role:administrador,consulta,conductor')->group(function () {
        Route::get('rutas', [RutaController::class, 'index'])->name('rutas.index');
        Route::get('rutas/{ruta}', [RutaController::class, 'show'])->whereNumber('ruta')->name('rutas.show');

        Route::get('/mimaparbl', [MiMapaRBLController::class, 'index'])->name('mimaparbl.index');
    });

    // -----------------------------------------------------------------
    // Envios: lectura segun rol (se filtra el contenido en el controlador)
    // -----------------------------------------------------------------
    Route::middleware('role:administrador,consulta,conductor,cliente')->group(function () {
        Route::get('envios', [EnvioController::class, 'index'])->name('envios.index');
        Route::get('envios/{envio}', [EnvioController::class, 'show'])->whereNumber('envio')->name('envios.show');
    });

    // -----------------------------------------------------------------
    // Conductor: actualizar el estado de sus pedidos (RF20)
    // -----------------------------------------------------------------
    Route::middleware('role:administrador,conductor')->group(function () {
        Route::patch('envios/{envio}/estado', [EnvioController::class, 'actualizarEstado'])
            ->whereNumber('envio')
            ->name('envios.estado');
    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistroController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CarnetController as AdminCarnetController;
use App\Http\Controllers\Admin\AccesoController as AdminAccesoController;
use App\Http\Controllers\Admin\EstudianteController as AdminEstudianteController;
use App\Http\Controllers\Admin\SolicitudPasswordController as AdminSolicitudPasswordController;
use App\Http\Controllers\Admin\LaboratorioController as AdminLaboratorioController;
use App\Http\Controllers\Profesor\DashboardController as ProfesorDashboardController;
use App\Http\Controllers\Profesor\AccesoController as ProfesorAccesoController;
use App\Http\Controllers\Estudiante\DashboardController as EstudianteDashboardController;
use App\Http\Controllers\Estudiante\CarnetController as EstudianteCarnetController;
use App\Http\Controllers\Estudiante\AccesoQRController as EstudianteAccesoQRController;
use App\Http\Controllers\Estudiante\CambiarPasswordController;


/*
|--------------------------------------------------------------------------
| Web Routes - Sistema Completo ISTPET
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registro Público
Route::get('/registro', [RegistroController::class, 'showRegistroForm'])->name('registro');
Route::post('/registro', [RegistroController::class, 'registro'])->name('registro.post');

// Recuperación de Contraseña
Route::get('/recuperar-password', [RegistroController::class, 'showRecuperarForm'])->name('recuperar.password');
Route::post('/recuperar-password', [RegistroController::class, 'enviarRecuperacion'])->name('recuperar.enviar');

/*
|--------------------------------------------------------------------------
| Rutas de Administrador
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Gestión de Carnets
    Route::get('/carnets', [AdminCarnetController::class, 'index'])->name('carnets.index');
    Route::get('/carnets/crear', [AdminCarnetController::class, 'create'])->name('carnets.create');

    // ⚠️ IMPORTANTE: Rutas específicas ANTES de rutas con parámetros
    Route::post('/carnets/generar-masivo', [AdminCarnetController::class, 'generarMasivo'])->name('carnets.generar-masivo');
    Route::get('/carnets/descargar-masivo', [AdminCarnetController::class, 'descargarMasivo'])->name('carnets.descargar-masivo');

    // Ahora sí las rutas con {id}
    Route::post('/carnets', [AdminCarnetController::class, 'store'])->name('carnets.store');
    Route::get('/carnets/{id}', [AdminCarnetController::class, 'show'])->name('carnets.show');
    Route::get('/carnets/{id}/descargar', [AdminCarnetController::class, 'descargar'])->name('carnets.descargar');
    Route::post('/carnets/{id}/toggle-estado', [AdminCarnetController::class, 'toggleEstado'])->name('carnets.toggle');
    Route::post('/carnets/{id}/renovar', [AdminCarnetController::class, 'renovar'])->name('carnets.renovar');

    // Gestión de Accesos
    Route::get('/accesos', [AdminAccesoController::class, 'index'])->name('accesos.index');
    Route::get('/accesos/estadisticas', [AdminAccesoController::class, 'estadisticas'])->name('accesos.estadisticas');
    Route::get('/accesos/laboratorio/{id}/estudiantes', [AdminAccesoController::class, 'estudiantesEnLab'])->name('accesos.estudiantes-lab');
    Route::get('/accesos/{id}', [AdminAccesoController::class, 'show'])->name('accesos.show');
    Route::delete('/accesos/{id}', [AdminAccesoController::class, 'destroy'])->name('accesos.destroy');

    // CRUD de Estudiantes
    Route::get('/estudiantes', [AdminEstudianteController::class, 'index'])->name('estudiantes.index');
    Route::get('/estudiantes/crear', [AdminEstudianteController::class, 'create'])->name('estudiantes.create');
    Route::post('/estudiantes', [AdminEstudianteController::class, 'store'])->name('estudiantes.store');
    Route::get('/estudiantes/{id}', [AdminEstudianteController::class, 'show'])->name('estudiantes.show');
    Route::get('/estudiantes/{id}/editar', [AdminEstudianteController::class, 'edit'])->name('estudiantes.edit');
    Route::put('/estudiantes/{id}', [AdminEstudianteController::class, 'update'])->name('estudiantes.update');
    Route::delete('/estudiantes/{id}', [AdminEstudianteController::class, 'destroy'])->name('estudiantes.destroy');
    Route::post('/estudiantes/{id}/toggle-estado', [AdminEstudianteController::class, 'toggleEstado'])->name('estudiantes.toggle');
    Route::post('/estudiantes/{id}/reset-password', [AdminEstudianteController::class, 'resetPassword'])->name('estudiantes.reset-password');

    // Búsqueda AJAX
    Route::get('/estudiantes-buscar', [AdminEstudianteController::class, 'buscarAjax'])->name('estudiantes.buscar');

    // Solicitudes de Cambio de Contraseña
    Route::get('/solicitudes-password', [AdminSolicitudPasswordController::class, 'index'])->name('solicitudes.index');
    Route::post('/solicitudes-password/{id}/atender', [AdminSolicitudPasswordController::class, 'atender'])->name('solicitudes.atender');
    Route::post('/solicitudes-password/{id}/rechazar', [AdminSolicitudPasswordController::class, 'rechazar'])->name('solicitudes.rechazar');

    // Gestión de Laboratorios
    Route::get('/laboratorios', [AdminLaboratorioController::class, 'index'])->name('laboratorios.index');
    Route::get('/laboratorios/{id}/qr', [AdminLaboratorioController::class, 'generarQR'])->name('laboratorios.generar-qr');
    Route::get('/laboratorios/qr/descargar-todos', [AdminLaboratorioController::class, 'descargarTodosQR'])->name('laboratorios.descargar-qr-todos');
});

/*
|--------------------------------------------------------------------------
| Rutas de Profesor
|--------------------------------------------------------------------------
*/

Route::prefix('profesor')->name('profesor.')->middleware('profesor')->group(function () {

    // Dashboard
    Route::get('/dashboard', [ProfesorDashboardController::class, 'index'])->name('dashboard');

    // Control de Accesos
    Route::get('/accesos', [ProfesorAccesoController::class, 'index'])->name('accesos.index');
    Route::get('/accesos/historial', [ProfesorAccesoController::class, 'historial'])->name('accesos.historial');

    // Ver detalles de un acceso específico (DEBE IR DESPUÉS de /accesos/historial)
    Route::get('/accesos/{id}', [ProfesorDashboardController::class, 'verDetalleAcceso'])->name('accesos.detalle');

    // Registrar entrada y salida
    Route::post('/accesos/registrar-entrada', [ProfesorAccesoController::class, 'registrarEntrada'])->name('accesos.registrar-entrada');
    Route::post('/accesos/registrar-salida', [ProfesorAccesoController::class, 'registrarSalida'])->name('accesos.registrar-salida');
    Route::post('/accesos/{id}/salida', [ProfesorAccesoController::class, 'registrarSalidaDirecta'])->name('accesos.salida-directa');

    // Estudiantes en laboratorio (tiempo real)
    Route::get('/laboratorio/{id}/estudiantes', [ProfesorAccesoController::class, 'estudiantesEnLab'])->name('accesos.estudiantes-lab');
    Route::post('/accesos/{id}/marcar-ausente', [ProfesorAccesoController::class, 'marcarAusente'])->name('accesos.marcar-ausente');

    // Búsqueda de estudiantes AJAX
    Route::get('/buscar-estudiante', [ProfesorAccesoController::class, 'buscarEstudiante'])->name('accesos.buscar-estudiante');
});

/*
|--------------------------------------------------------------------------
| Rutas de Estudiante
|--------------------------------------------------------------------------
*/

// Cambiar contraseña (sin middleware para permitir acceso con password temporal)
Route::prefix('estudiante')->name('estudiante.')->middleware('estudiante')->group(function () {
    Route::get('/cambiar-password', [CambiarPasswordController::class, 'showForm'])->name('cambiar-password.form');
    Route::post('/cambiar-password', [CambiarPasswordController::class, 'cambiar'])->name('cambiar-password.post');
});

// Rutas protegidas que requieren cambio de contraseña
Route::prefix('estudiante')->name('estudiante.')->middleware(['estudiante', 'password.change'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [EstudianteDashboardController::class, 'index'])->name('dashboard');

    // Mi Carnet
    Route::get('/carnet', [EstudianteCarnetController::class, 'show'])->name('carnet.show');
    Route::get('/carnet/descargar', [EstudianteCarnetController::class, 'descargar'])->name('carnet.descargar');
    Route::get('/carnet/visualizar', [EstudianteCarnetController::class, 'visualizar'])->name('carnet.visualizar');

    // Escaneo de QR para accesos
    Route::get('/acceso/escanear', [EstudianteAccesoQRController::class, 'mostrarEscaneo'])->name('acceso.escanear');
    Route::post('/acceso/procesar-qr', [EstudianteAccesoQRController::class, 'procesarQR'])->name('acceso.procesar-qr');
    Route::get('/acceso/historial', [EstudianteAccesoQRController::class, 'miHistorial'])->name('acceso.historial');
});

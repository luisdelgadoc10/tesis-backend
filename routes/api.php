<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PublicCuestionarioController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\EstablecimientoController;
use App\Http\Controllers\ClasificacionController;
use App\Http\Controllers\FuncionController;
use App\Http\Controllers\ActividadEconomicaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SubfuncionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\NivelRiesgoController;
use App\Http\Controllers\CuestionarioController;
use App\Http\Controllers\TwilioController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rutas públicas sin autenticación
Route::get('/encuesta/{token}', [PublicCuestionarioController::class, 'show']);
Route::post('/encuesta/{token}', [PublicCuestionarioController::class, 'store']);
//Route::post('/encuesta/responder', [PublicCuestionarioController::class, 'store']);



// 🧾 Generar y mostrar PDF en el navegador
Route::get('/clasificaciones/{id}/pdf', [ReporteController::class, 'clasificacionPdf']);
// 💬 Enviar link del PDF al WhatsApp (usa Twilio)
Route::post('/send-report/{id}', [ReporteController::class, 'enviarLinkPdfWssp']);
Route::post('/send-survey', [TwilioController::class, 'sendSurveyLink']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    ////// USUARIOS //////
    Route::apiResource('users', UserController::class);
    Route::post('/users/{id}/restore', [UserController::class, 'restore']);
    // Actualizar usuario
    Route::put('/users/{id}', [UserController::class, 'update']);

    

    ////// ROLES //////

    // Listar todos los roles (incluyendo opción de filtrar estado / withTrashed por query)
    Route::get('/roles', [RoleController::class, 'index']);
    // Crear nuevo rol
    Route::post('/roles', [RoleController::class, 'store']);
    // Mostrar rol específico
    Route::get('/roles/{id}', [RoleController::class, 'show']);
    // Actualizar rol
    Route::put('/roles/{id}', [RoleController::class, 'update']);
    // Soft delete (pasa a papelera y estado=0)
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
    // Restaurar rol eliminado
    Route::patch('/roles/{id}/restore', [RoleController::class, 'restore']);
    // Activar / desactivar rol sin eliminarlo
    Route::patch('/roles/{id}/activate', [RoleController::class, 'activate']);
    Route::patch('/roles/{id}/deactivate', [RoleController::class, 'deactivate']);



    ////// PERMISOS //////

    // Listar todos los permisos (incluyendo opción de filtrar estado / withTrashed por query)
    Route::get('/permisos', [PermissionController::class, 'index']);
    // Crear nuevo permiso
    Route::post('/permisos', [PermissionController::class, 'store']);
    // Mostrar permiso específico
    Route::get('/permisos/{id}', [PermissionController::class, 'show']);
    // Actualizar permiso
    Route::put('/permisos/{id}', [PermissionController::class, 'update']);
    // Soft delete (pasa a papelera y estado=0)
    Route::delete('/permisos/{id}', [PermissionController::class, 'destroy']);
    // Restaurar permiso eliminado
    Route::patch('/permisos/{id}/restore', [PermissionController::class, 'restore']);
    // Activar / desactivar permiso sin eliminarlo
    Route::patch('/permisos/{id}/activate', [PermissionController::class, 'activate']);
    Route::patch('/permisos/{id}/deactivate', [PermissionController::class, 'deactivate']);




    ////// ESTABLECIMIENTOS //////
    Route::get('/establecimientos', [EstablecimientoController::class, 'index']);        // Solo activos
    Route::get('/establecimientos/todos', [EstablecimientoController::class, 'todos']);  // Todos (incl. eliminados)
    Route::post('/establecimientos', [EstablecimientoController::class, 'store']);
    Route::get('/establecimientos/{id}', [EstablecimientoController::class, 'show']);
    Route::put('/establecimientos/{id}', [EstablecimientoController::class, 'update']);
    Route::delete('/establecimientos/{id}', [EstablecimientoController::class, 'destroy']);
    Route::patch('/establecimientos/{id}/restore', [EstablecimientoController::class, 'restore']);

    ////// CLASIFICACIONES //////

    // Listar clasificaciones con filtros opcionales (establecimiento_id, funcion_id)
    Route::get('/clasificaciones', [ClasificacionController::class, 'index']);
    // Crear nueva clasificación (envía datos de un establecimiento y actividad económica al modelo ML)
    Route::post('/clasificaciones', [ClasificacionController::class, 'store']);
    // Mostrar clasificación específica
    Route::get('/clasificaciones/{id}', [ClasificacionController::class, 'show']);
    // Soft delete de una clasificación (estado = 0)
    Route::delete('/clasificaciones/{id}', [ClasificacionController::class, 'destroy']);
    // Restaurar clasificación eliminada
    Route::patch('/clasificaciones/{id}/restore', [ClasificacionController::class, 'restore']);


    ////// REPORTES //////

    



    ////// NIVELES DE RIESGO //////

    // Listar todos los niveles de riesgo
    Route::get('/niveles-riesgo', [NivelRiesgoController::class, 'index']);
    // Crear nuevo nivel de riesgo
    Route::post('/niveles-riesgo', [NivelRiesgoController::class, 'store']);
    // Mostrar nivel de riesgo específico
    Route::get('/niveles-riesgo/{id}', [NivelRiesgoController::class, 'show']);
    // Actualizar nivel de riesgo
    Route::put('/niveles-riesgo/{id}', [NivelRiesgoController::class, 'update']);


    
    ////// FUNCIONES //////

    // Listar todas las funciones (activas e inactivas)
    Route::get('/funciones', [FuncionController::class, 'index']);
    // Listar solo funciones activas
    Route::get('/funciones/activas', [FuncionController::class, 'activas']);
    // Crear nueva función
    Route::post('/funciones', [FuncionController::class, 'store']);
    // Mostrar función específica
    Route::get('/funciones/{id}', [FuncionController::class, 'show']);
    // Actualizar función
    Route::put('/funciones/{id}', [FuncionController::class, 'update']);
    // Soft delete (estado = 0)
    Route::delete('/funciones/{id}', [FuncionController::class, 'destroy']);
    // Restaurar función eliminada
    Route::post('/funciones/{id}/restore', [FuncionController::class, 'restore']);

    ////// SUBFUNCIONES //////

    // Listar todas las subfunciones
    Route::get('/subfunciones', [SubfuncionController::class, 'index']);
    // Crear nueva subfunción
    Route::post('/subfunciones', [SubfuncionController::class, 'store']);
    // Mostrar subfunción específica
    Route::get('/subfunciones/{id}', [SubfuncionController::class, 'show']);
    // Actualizar subfunción
    Route::put('/subfunciones/{id}', [SubfuncionController::class, 'update']);


    ////// ACTIVIDAD ECONOMICA //////

    // Listar todas
    Route::get('/actividades', [ActividadEconomicaController::class, 'index']);
    // Listar solo activas
    Route::get('/actividades/activas', [ActividadEconomicaController::class, 'activas']);
    // Crear nueva
    Route::post('/actividades', [ActividadEconomicaController::class, 'store']);
    // Mostrar específica
    Route::get('/actividades/{id}', [ActividadEconomicaController::class, 'show']);
    // Actualizar
    Route::put('/actividades/{id}', [ActividadEconomicaController::class, 'update']);
    // Soft delete
    Route::delete('/actividades/{id}', [ActividadEconomicaController::class, 'destroy']);
    // Restaurar
    Route::post('/actividades/{id}/restore', [ActividadEconomicaController::class, 'restore']);

    ////// CUESTIONARIO DE SATISFACCION //////
    Route::post('/cuestionario', [CuestionarioController::class, 'store']);
    Route::get('/cuestionario/{clasificacion}', [CuestionarioController::class, 'show']);
    
});


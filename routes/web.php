<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReporteController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/clasificaciones/{id}/pdf', [ReporteController::class, 'clasificacionPdf']);
//Route::get('/clasificaciones/{id}/enviar-wssp', [ReporteController::class, 'enviarClasificacionPdfWssp']);





<?php

namespace App\Http\Controllers;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="API TESIS - Sistema de Clasificación de Riesgo",
 *         description="Documentación de la API del sistema de tesis"
 *     ),
 *     @OA\Server(
 *         url="http://localhost:8000",
 *         description="Servidor de desarrollo"
 *     )
 * )
 * 
 * @OA\Components(
 *     @OA\SecurityScheme(
 *         securityScheme="sanctum",
 *         type="http",
 *         scheme="bearer",
 *         bearerFormat="JWT",
 *         description="Ingresa tu token de autenticación Sanctum"
 *     )
 * )
 * 
 * @OA\PathItem(path="/api")
 */
class OpenApiSpec
{
}
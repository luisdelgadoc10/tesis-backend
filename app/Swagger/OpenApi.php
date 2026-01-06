<?php

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="API TESIS",
 *         version="1.0.0",
 *         description="Sistema de clasificación de riesgo"
 *     ),
 *     @OA\Server(
 *         url=L5_SWAGGER_CONST_HOST,
 *         description="Servidor API"
 *     )
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Ingresa el token de autenticación"
 * )
 */
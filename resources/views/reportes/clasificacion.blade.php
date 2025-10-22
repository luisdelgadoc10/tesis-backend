<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Clasificación #{{ $clasificacion->id }}</title>
    <style>
        @page {
            margin: 16mm 14mm;
        }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            font-size: 9.5pt;
            color: #1e293b;
            line-height: 1.45;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #0d9488;
        }

        header h1 {
            font-size: 18pt;
            margin: 0;
            color: #24412f;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .section {
            margin-bottom: 22px;
        }

        .section-title {
            font-size: 12pt;
            color: white;
            background-color: #24412f;
            font-weight: 600;
            margin-bottom: 12px;
            padding: 8px 12px;
            border-radius: 4px;
        }

        .grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .card {
            background-color: #ffffff;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            flex: 1;
            min-width: 210px;
            font-size: 9.5pt;
            box-shadow: 0 2px 4px rgba(36, 65, 47, 0.06);
        }

        .field {
            margin: 5px 0;
        }

        .field-label {
            font-weight: 600;
            color: #24412f;
            display: inline-block;
            min-width: 120px;
        }

        .field-value {
            color: #1e293b;
        }

        /* Tabla con más contraste */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
            font-size: 9pt;
            border: 1px solid #94a3b8;
        }

        th {
            background-color: #24412f;
            color: white;
            font-weight: 600;
            padding: 10px;
            text-align: left;
            border: 1px solid #24412f;
        }

        td {
            padding: 10px;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
        }

        tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .no-data {
            text-align: center;
            color: #64748b;
            font-style: italic;
            padding: 14px;
            font-size: 9pt;
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
        }

        footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8.5pt;
            color: #475569;
            padding-top: 10px;
            border-top: 1px solid #cbd5e1;
        }
    </style>
</head>
<body>
    <header>
        <h1>Reporte de Clasificación #{{ $clasificacion->id }}</h1>
    </header>

    <!-- Sección 1: Datos del Establecimiento -->
    <div class="section">
        <h2 class="section-title">Datos del Establecimiento</h2>
        <div class="grid">
            <div class="card">
                <p class="field"><span class="field-label">Nombre Comercial:</span> <span class="field-value">{{ $clasificacion->establecimiento->nombre_comercial ?? 'N/A' }}</span></p>
                <p class="field"><span class="field-label">Razón Social:</span> <span class="field-value">{{ $clasificacion->establecimiento->razon_social ?? 'N/A' }}</span></p>
                <p class="field"><span class="field-label">RUC:</span> <span class="field-value">{{ $clasificacion->establecimiento->ruc ?? 'N/A' }}</span></p>
                <p class="field"><span class="field-label">Dirección:</span> <span class="field-value">{{ $clasificacion->establecimiento->direccion ?? 'N/A' }}</span></p>
            </div>
            <br>
            <div class="card">
                <p class="field"><span class="field-label">Actividad Económica:</span> <span class="field-value">{{ $clasificacion->establecimiento->actividadEconomica->descripcion ?? 'N/A' }}</span></p>
                <p class="field"><span class="field-label">Propietario:</span> <span class="field-value">{{ $clasificacion->establecimiento->propietario ?? 'N/A' }}</span></p>
                <p class="field"><span class="field-label">Estado:</span> 
                    <span class="field-value">{{ $clasificacion->establecimiento->estado ? 'Activo' : 'Inactivo' }}</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Sección 2: Datos de Contacto -->
    <div class="section">
        <h2 class="section-title">Datos de Contacto</h2>
        <div class="grid">
            <div class="card">
                <p class="field"><span class="field-label">Teléfono:</span> <span class="field-value">{{ $clasificacion->establecimiento->telefono ?? 'N/A' }}</span></p>
                <p class="field"><span class="field-label">Correo Electrónico:</span> <span class="field-value">{{ $clasificacion->establecimiento->correo_electronico ?? 'N/A' }}</span></p>
            </div>
        </div>
    </div>

    <!-- Sección 3: Clasificación y Análisis -->
    <div class="section">
        <h2 class="section-title">Clasificación y Análisis</h2>
        <div class="grid">
            <div class="card">
                <p class="field"><span class="field-label">Función:</span> <span class="field-value">{{ $clasificacion->funcion->nombre ?? 'N/A' }}</span></p>
                <p class="field"><span class="field-label">Fecha:</span> <span class="field-value">{{ $clasificacion->fecha_clasificacion?->format('d/m/Y') ?? 'N/A' }}</span></p>
                <p class="field"><span class="field-label">Estado:</span> <span class="field-value">{{ $clasificacion->estado ? 'Activo' : 'Inactivo' }}</span></p>
            </div>
        </div>

        @php
            $resultado = $clasificacion->detalle?->resultado_modelo ?? null;
        @endphp

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subfunción</th>
                    <th>Incendio</th>
                    <th>Colapso</th>
                    <th>Riesgo Final</th>
                    <th>Precisión (%)</th>
                    <th>Tiempo (ms)</th>
                </tr>
            </thead>
            <tbody>
                @if($clasificacion->detalle)
                    <tr>
                        <td>1</td>
                        <td>
                            {{ $resultado['subfuncion_salud'] 
                                ?? $resultado['subfuncion_comercio'] 
                                ?? $resultado['subfuncion_encuentro']
                                ?? $resultado['subfuncion_hospedaje']
                                ?? $resultado['subfuncion_educacion']
                                ?? $resultado['subfuncion_industrial']
                                ?? $resultado['subfuncion_oficinas']
                                ?? $resultado['subfuncion_almacen']
                                ?? 'N/A' }}
                        </td>
                        <td>{{ $clasificacion->detalle->riesgo_incendio ?? 'N/A' }}</td>
                        <td>{{ $clasificacion->detalle->riesgo_colapso ?? 'N/A' }}</td>
                        <td>{{ $clasificacion->detalle->riesgo_final ?? 'N/A' }}</td>
                        <td>{{ isset($resultado['confianza']) ? $resultado['confianza'] : 'N/A' }}%</td>
                        <td>{{ $resultado['tiempo_ms'] ?? 'N/A' }}</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="7" class="no-data">No hay detalle de análisis disponible</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <footer>
        Sistema de Clasificación de Riesgo © {{ date('Y') }} — Generado automáticamente
    </footer>
</body>
</html>
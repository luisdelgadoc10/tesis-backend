<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clasificacion;
use App\Models\ClasificacionDetalle;
use PDF;
use Twilio\Rest\Client;

class ReporteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/clasificaciones/{id}/pdf",
     *     summary="Generar reporte PDF de clasificación",
     *     description="Genera el reporte de clasificación en PDF y lo muestra directamente en el navegador",
     *     tags={"Reportes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la clasificación",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="PDF generado correctamente",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/pdf"
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Clasificación no encontrada"
     *     )
     * )
     */
    public function clasificacionPdf($id)
    {
        $clasificacion = Clasificacion::with([
            'establecimiento.actividadEconomica',
            'funcion',
            'detalle'
        ])->findOrFail($id);

        $pdf = PDF::loadView('reportes.clasificacion', compact('clasificacion'));

        return $pdf->stream("clasificacion_{$id}.pdf");
    }

    /**
     * @OA\Post(
     *     path="/api/clasificaciones/{id}/enviar-whatsapp",
     *     summary="Enviar enlace del PDF por WhatsApp",
     *     description="Envía por WhatsApp el enlace del reporte PDF generado para una clasificación",
     *     tags={"Reportes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la clasificación",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"telefono"},
     *             @OA\Property(
     *                 property="telefono",
     *                 type="string",
     *                 example="+51999999999",
     *                 description="Número de teléfono con código de país"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reporte enviado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al enviar el mensaje por WhatsApp"
     *     )
     * )
     */
    public function enviarLinkPdfWssp(Request $request, $id)
    {
        $request->validate([
            'telefono' => 'required|string',
        ]);

        $detalle = ClasificacionDetalle::where('clasificacion_id', $id)->first();

        $numero = $request->telefono;
        $link = env('APP_URL') . "/api/clasificaciones/{$id}/pdf";

        $twilio = new \Twilio\Rest\Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));

        // 🕒 Iniciar conteo de tiempo
        $start = microtime(true);

        try {
            $mensaje = $twilio->messages->create(
                "whatsapp:$numero",
                [
                    "from" => "whatsapp:" . env('TWILIO_WHATSAPP_NUMBER'),
                    "body" => "📄 Aquí tienes tu reporte generado: $link",
                ]
            );

            // 🕒 Finalizar conteo y calcular tiempo en segundos
            $end = microtime(true);
            $tiempoS = round($end - $start, 2);

            // ✅ Guardar solo si es la primera vez que se envía
            if (!$detalle) {
                $detalle = new ClasificacionDetalle();
                $detalle->clasificacion_id = $id;
                $detalle->tiempo_envio_reporte = $tiempoS;
                $detalle->save();
            } elseif ($detalle->tiempo_envio_reporte === null) {
                $detalle->tiempo_envio_reporte = $tiempoS;
                $detalle->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Reporte enviado exitosamente.',
                'tiempo_s' => $tiempoS,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al enviar mensaje Twilio: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el mensaje: ' . $e->getMessage(),
            ], 500);
        }
    }
}

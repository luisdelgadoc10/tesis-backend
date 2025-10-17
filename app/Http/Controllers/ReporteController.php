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
     * Genera el PDF y lo muestra en el navegador.
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
     * Envía por WhatsApp el link del reporte PDF.
     */
    public function enviarLinkPdfWssp(Request $request, $id)
    {
        $request->validate([
            'telefono' => 'required|string',
        ]);

        // Verificar si ya se envió antes
        $detalle = ClasificacionDetalle::where('clasificacion_id', $id)->first();

        if ($detalle && $detalle->tiempo_envio_reporte !== null) {
            return response()->json([
                'success' => false,
                'message' => 'El reporte ya fue enviado anteriormente.',
            ], 409); // 409 Conflict
        }

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

            // 🕒 Finalizar conteo y calcular tiempo en milisegundos
            $end = microtime(true);
            $tiempoMs = round(($end - $start) * 1000, 2); // ✅ Igual que tu modelo de ML

            // Guardar solo si nunca se había enviado
            if (!$detalle) {
                $detalle = new ClasificacionDetalle();
                $detalle->clasificacion_id = $id;
            }

            $detalle->tiempo_envio_reporte = $tiempoMs;
            $detalle->save();

            return response()->json([
                'success' => true,
                'message' => 'Reporte enviado exitosamente.',
                'tiempo_ms' => $tiempoMs,
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

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
                // Si existe pero aún no se ha registrado el tiempo, también lo guarda
                $detalle->tiempo_envio_reporte = $tiempoS;
                $detalle->save();
            }
            // Si ya tiene tiempo registrado, no se modifica nada más

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

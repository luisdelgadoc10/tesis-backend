<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clasificacion;
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
        // Validar el número recibido
        $request->validate([
            'telefono' => 'required|string',
        ]);

        $numero = $request->telefono; // ← toma el número del POST
        $link = "https://overbig-melaine-shakily.ngrok-free.dev/api/clasificaciones/{$id}/pdf";

        $twilio = new \Twilio\Rest\Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
        
        $mensaje = "📄 Aquí tienes tu reporte generado: $link";

        $twilio->messages->create(
            "whatsapp:$numero", // ← ahora usa el número recibido
            [
                "from" => "whatsapp:" . env('TWILIO_WHATSAPP_NUMBER'),
                "body" => $mensaje,
            ]
        );

        return response()->json(['success' => true, 'message' => "Mensaje enviado a $numero"]);
    }
}

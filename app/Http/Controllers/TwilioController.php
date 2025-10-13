<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class TwilioController extends Controller
{
    public function sendReport(Request $request)
    {
        $request->validate([
            'phone' => 'required|string', // Ej: +51999999999
            'report' => 'required|string', // nombre del archivo, ej: clasificacion_2.pdf
        ]);

        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $whatsapp_from = env('TWILIO_WHATSAPP_FROM');
        $twilio = new Client($sid, $token);

        $url = "https://overbig-melaine-shakily.ngrok-free.dev/storage/reports/" . $request->report;

        $message = $twilio->messages->create(
            "whatsapp:" . $request->phone,
            [
                "from" => "whatsapp:" . $whatsapp_from,
                "body" => "Aquí tienes tu reporte PDF 📄",
                "mediaUrl" => [$url],
            ]
        );

        return response()->json([
            'status' => 'success',
            'sid' => $message->sid,
            'to' => $request->phone,
        ]);
    }
}

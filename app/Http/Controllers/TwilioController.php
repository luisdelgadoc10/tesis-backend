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

        $sid = env('TWILIO_ACCOUNT_SID');
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

    public function sendSurveyLink(Request $request)
    {
        $request->validate([
            'phone' => 'required|string', // Ej: +51999999999
            'token' => 'required|string', // Token de la encuesta
        ]);

        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $whatsapp_from = env('TWILIO_WHATSAPP_NUMBER');
        $twilio = new Client($sid, $token);

        // URL pública de la encuesta
        $surveyUrl = "https://overbig-melaine-shakily.ngrok-free.dev/api/encuesta/" . $request->token;

        $body = "👋 Hola! Gracias por participar.\n\nPor favor completa la encuesta de satisfacción en el siguiente enlace:\n\n{$surveyUrl}\n\n¡Tu opinión es muy importante para nosotros! 🙌";

        try {
            $message = $twilio->messages->create(
                "whatsapp:" . $request->phone,
                [
                    "from" => "whatsapp:" . $whatsapp_from,
                    "body" => $body,
                ]
            );

            return response()->json([
                'status' => 'success',
                'sid' => $message->sid,
                'to' => $request->phone,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al enviar mensaje Twilio: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}

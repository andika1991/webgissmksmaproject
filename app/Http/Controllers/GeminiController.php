<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class GeminiController extends Controller
{
    public function predict(Request $request)
    {
        $apiKey = env('GOOGLE_API_KEY');
        $client = new Client([
            'base_uri' => 'https://generativelanguage.googleapis.com/',
        ]);

        // Ambil input prompt dari request atau buat default
        $promptText = $request->input('prompt', 'Tolong prediksi zonasi SMK terdekat berdasarkan lokasi saya');

        try {
            $response = $client->post('v1beta/models/gemini-1.5-turbo:generateText', [
                'query' => ['key' => $apiKey],
                'json' => [
                    'prompt' => [
                        'text' => $promptText
                    ],
                    'temperature' => 0.7,
                    'maxOutputTokens' => 300,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return response()->json([
                'success' => true,
                'result' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Chatbot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $message = $request->input('message');
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $chatbot = Chatbot::first();
        if (!$chatbot) {
            $chatbot = Chatbot::create([
                'nom' => 'Assistant',
                'description' => 'Chatbot par défaut',
                'personnalite' => 'Basique'
            ]);
        }

        // Call Ollama for response
        $response = $this->generateResponseWithOllama($message);

        try {
            Consultation::create([
                'user_id' => $user->id,
                'chatbot_id' => $chatbot->id,
                'message_user' => $message,
                'message_chatbot' => $response,
                'type_interaction' => 'question'
            ]);
        } catch (\Exception $e) {
            // ignore DB failure
        }

        return response()->json([
            'message' => $response,
            'timestamp' => now()->format('H:i')
        ]);
    }

    private function generateResponseWithOllama($userMessage)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://localhost:11434/api/generate');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'model' => 'phi',
                'prompt' => $userMessage,
                'stream' => false,
            ]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120);

            $response = curl_exec($ch);
            curl_close($ch);

            if ($response === false) {
                return "Désolé, le chatbot n'est pas disponible en ce moment.";
            }

            $data = json_decode($response, true);
            return $data['response'] ?? "Désolé, je n'ai pas pu générer une réponse.";

        } catch (\Exception $e) {
            return "Erreur: " . $e->getMessage();
        }
    }
}
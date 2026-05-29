<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Chatbot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ChatBotController extends Controller {
    
    public function sendMessage(Request $request) {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $message = $request->input('message');
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $chatbot = Chatbot::first() ?? Chatbot::create([
            'nom' => 'Assistant IA',
            'description' => 'Chatbot pour le bien-être étudiant',
            'personnalite' => 'Encourageant et bienveillant'
        ]);

        // Détection de crise
        $isCrisis = $this->detectCrisis($message);

        $response = $this->generateResponseWithGroq($message, $isCrisis);

        try {
            Consultation::create([
                'user_id' => $user->id,
                'chatbot_id' => $chatbot->id,
                'message_user' => $message,
                'message_chatbot' => $response,
                'type_interaction' => $isCrisis ? 'crisis' : 'question'
            ]);
        } catch (\Exception $e) {
            Log::error('DB Error: ' . $e->getMessage());
        }

        // Gamification : Parler au chatbot rapporte 5 XP (max une fois par message)
        $user->addPoints(5);

        return response()->json([
            'message' => $response,
            'trigger_crisis' => $isCrisis,
            'timestamp' => now()->format('H:i')
        ]);
    }

    /**
     * Détecte les mots-clés de crise grave dans le message.
     */
    private function detectCrisis($message) {
        $keywords = [
            'suicide', 'suicidaire', 'mourir', 'tuer', 'dépression', 'panique', 
            'angoisse', 'crise de larmes', 'scarification', 'automutilation', 
            'finir ma vie', 'ne plus vivre', 'envie de crever', 'm\'effondrer',
            'mefondrer', 'deprime', 'déprime', 'harcèlement', 'harcelement'
        ];

        $messageLower = mb_strtolower($message);
        foreach ($keywords as $word) {
            if (mb_strpos($messageLower, $word) !== false) {
                return true;
            }
        }
        return false;
    }

    private function generateResponseWithGroq($userMessage, $isCrisis = false) {
        $apiKey = env('GROQ_API_KEY');
        
        if (!$apiKey) {
            Log::error('GROQ_API_KEY is missing from .env');
            return "Erreur: API Key manquante. Si vous êtes en détresse, veuillez utiliser les numéros d'urgence affichés ci-dessous ou appeler le 3114 (numéro national de prévention du suicide).";
        }

        if (!class_exists('GuzzleHttp\Client')) {
            Log::error('GuzzleHttp Client not found');
            return "Erreur: Guzzle non installé.";
        }

        try {
            $client = new \GuzzleHttp\Client();
            
            // Adapter le prompt système en cas de détresse psychologique détectée
            if ($isCrisis) {
                $systemPrompt = "Tu es un assistant IA d'écoute bienveillant spécialisé pour les étudiants en détresse psychologique. Ton interlocuteur montre des signes de crise grave ou d'idées noires. Reste EXTRÊMEMENT doux, empathique, rassurant, calme et concis. Rappelle-lui chaleureusement qu'il n'est pas seul et que des ressources d'aide d'urgence sont affichées à l'instant même à son écran pour lui offrir un accompagnement humain qualifié. Ne cherche pas à remplacer un thérapeute, conseille-lui doucement de contacter les professionnels de santé ou les hotlines gratuites d'urgence.";
            } else {
                $systemPrompt = "Tu es un assistant d'écoute psychologique bienveillant, encourageant et chaleureux pour les étudiants. Réponds en français de manière concise, positive et empathique. Encourage-les à s'exprimer librement sur leurs émotions, études ou difficultés.";
            }

            Log::info('Sending request to Groq API with key: ' . substr($apiKey, 0, 20) . '...');

            $response = $client->post('https://api.groq.com/openai/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'llama-3.1-8b-instant',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt
                        ],
                        [
                            'role' => 'user',
                            'content' => $userMessage
                        ]
                    ],
                    'max_tokens' => 200,
                    'temperature' => 0.7,
                ],
                'timeout' => 30,
            ]);

            Log::info('Groq API response received');
            $data = json_decode($response->getBody(), true);
            
            return $data['choices'][0]['message']['content'] ?? "No response";

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Groq RequestException: ' . $e->getMessage());
            return "Je suis désolé, je rencontre des difficultés techniques à me connecter pour vous répondre. Sachez que vous n'êtes pas seul. Si vous ne vous sentez pas bien ou si vous avez des idées noires, vous pouvez composer gratuitement le 3114 (numéro national de prévention du suicide) ou le 112 (urgences), des professionnels formés sont à votre écoute 24h/24.";
        } catch (\Exception $e) {
            Log::error('Groq Exception: ' . $e->getMessage());
            return "Une erreur s'est produite lors de la génération de ma réponse. S'il s'agit d'une urgence psychologique, veuillez utiliser les numéros de hotline disponibles ci-dessous ou appeler le 3114.";
        }
    }
}
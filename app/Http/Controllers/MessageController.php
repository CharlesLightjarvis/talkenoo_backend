<?php

namespace App\Http\Controllers;

use App\Events\Example;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    /**
     * Envoie un message et le diffuse en temps réel.
     */
    public function sendMessage(Request $request)
    {
        $messageContent = $request->input('content');
        broadcast(new Example($messageContent))->toOthers();
        Log::info("Message envoyé via WebSocket: " . $messageContent); // Log pour debug
        return response()->json(['status' => 'Message sent!', 'message' => $messageContent], 200);
    }




    /**
     * Récupère tous les messages.
     */
    public function index()
    {
        $messages = Message::all();
        return response()->json($messages);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}

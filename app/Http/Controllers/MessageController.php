<?php

namespace App\Http\Controllers;

use App\Events\Example;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Envoie un message et le diffuse en temps réel.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'sender_id' => 'required|integer',
            'receiver_id' => 'required|integer',
        ]);

        $message = Message::create([
            'content' => $request->input('content'),
            'sender_id' => $request->input('sender_id'),
            'receiver_id' => $request->input('receiver_id'),
        ]);

        // Diffuser l'événement en temps réel
        broadcast(new Example($message))->toOthers();

        return response()->json(['status' => 'Message sent!', 'message' => $message], 200);
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

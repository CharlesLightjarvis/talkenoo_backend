<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class Example implements ShouldBroadcastNow
{
    use SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('chat'); // Assurez-vous que ce canal correspond à celui utilisé dans Flutter
    }

    public function broadcastAs()
    {
        return 'Example-Event'; // Nom de l’événement tel qu'il sera émis
    }

    public function broadcastWith()
    {
        return ['message' => $this->message];
    }
}

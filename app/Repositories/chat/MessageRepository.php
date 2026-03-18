<?php
namespace App\Repositories\chat;

use App\Models\Message;

class MessageRepository
{
    public function create($data)
    {
        return Message::create($data);
    }

    public function getByConversation($conversationId)
    {
        return Message::where('conversation_id', $conversationId)
            ->orderBy('id')
            ->get();
    }
}
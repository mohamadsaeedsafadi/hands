<?php
namespace App\Repositories\chat;

use App\Models\Conversation;

class ConversationRepository
{
    public function create($data)
    {
        return Conversation::create($data);
    }

    public function findByRequest($requestId)
    {
        return Conversation::where('service_request_id', $requestId)->first();
    }

    public function findById($id)
    {
        return Conversation::findOrFail($id);
    }
}
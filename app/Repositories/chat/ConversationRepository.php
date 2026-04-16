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
     public function getUserConversations($userId)
    {
        return Conversation::with([
        'user',
        'provider',
        'messages' => function ($q) {
            $q->latest()->limit(1);
        }
    ])
    ->where(function ($q) use ($userId) {
        $q->where('user_id', $userId)
          ->orWhere('provider_id', $userId);
    })
    ->latest()
    ->paginate(10);
    }
}
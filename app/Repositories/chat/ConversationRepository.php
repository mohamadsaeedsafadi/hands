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
    
    $conversations = Conversation::with([
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

    $serviceRequestIds = $conversations->pluck('service_request_id')->filter()->unique();

    $serviceRequests = \App\Models\ServiceRequest::with('category')
        ->whereIn('id', $serviceRequestIds)
        ->get()
        ->keyBy('id');

    $conversations->through(function ($conversation) use ($serviceRequests) {
        $serviceRequest = $serviceRequests->get($conversation->service_request_id);

        $conversation->service_name = $serviceRequest?->category?->name ?? null;

        return $conversation;
    });

    return $conversations;
}
}
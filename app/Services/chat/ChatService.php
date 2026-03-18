<?php
namespace App\Services\chat;

use App\Repositories\chat\ConversationRepository;
use App\Repositories\chat\MessageRepository;
use App\Events\MessageSent;
use App\Models\Conversation;

class ChatService
{
    public function __construct(
        protected ConversationRepository $conversationRepo,
        protected MessageRepository $messageRepo
    ) {}

 public function createConversation($user, $provider, $serviceRequest)
{
    $existing = Conversation::where('service_request_id', $serviceRequest->id)
        ->where('user_id', $user->id)
        ->where('provider_id', $provider->id)
        ->first();

    if ($existing) {
        return $existing;
    }

    return Conversation::create([
        'service_request_id' => $serviceRequest->id,
        'user_id' => $user->id,
        'provider_id' => $provider->id
    ]);
}

    public function sendMessage($conversation, $senderId, $message)
    {
        $msg = $this->messageRepo->create([
            'conversation_id' => $conversation->id,
            'sender_id' => $senderId,
            'message' => $message
        ]);

        broadcast(new MessageSent($msg))->toOthers();

        return $msg->load('sender'); 
    }

    public function getMessages($conversationId)
    {
        return $this->messageRepo
            ->getByConversation($conversationId)
            ->load('sender');
    }
}
<?php
use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {

    $conversation = Conversation::find($conversationId);

    if (!$conversation) return false;

    return $conversation->user_id === $user->id
        || $conversation->provider_id === $user->id;
});
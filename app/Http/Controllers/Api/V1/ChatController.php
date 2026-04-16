<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Conversation;
use App\Services\chat\ChatService;
use Illuminate\Http\Request;
class ChatController extends Controller
{
    public function __construct(protected ChatService $chatService) {}

    public function send(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'required|string|max:250'
        ]);

        $conversation = Conversation::findOrFail($conversationId);

        $user = $request->user();

       
        if (
            $conversation->user_id !== $user->id &&
            $conversation->provider_id !== $user->id
        ) {
            
            
            return ApiResponse::error('Unauthorized', 403);
        }

        $msg = $this->chatService->sendMessage(
            $conversation,
            $user->id,
            $request->message
        );

      
        return ApiResponse::success($msg);
    }

    public function messages(Request $request, $conversationId)
{
    $conversation = Conversation::findOrFail($conversationId);

    if (
        $conversation->user_id !== $request->user()->id &&
        $conversation->provider_id !== $request->user()->id
    ) {
          return ApiResponse::error('Unauthorized', 403);

    }

   
    return ApiResponse::success(
    $this->chatService->getMessages($conversationId)
);
}
public function myChats(Request $request)
{
    $chats = $this->chatService->myConversations($request->user());

  return ApiResponse::success(
    $chats
);
}
}
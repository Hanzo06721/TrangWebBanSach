<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Post;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ChatController extends Controller
{
    public function messages(Request $request, Post $post): JsonResponse
    {
        abort_unless(Schema::hasTable('chat_messages'), 404);
        $user = $request->user();

        abort_unless(
            $post->UserID === $user->UserID
            || Transaction::where('PostID', $post->PostID)->where('BuyerID', $user->UserID)->exists(),
            403
        );

        $messages = ChatMessage::with('sender')
            ->where('PostID', $post->PostID)
            ->orderBy('CreatedAt')
            ->get();

        return response()->json(['messages' => $messages->map(fn (ChatMessage $message) => [
            'id' => $message->MessageID,
            'message' => $message->Message,
            'sender_id' => $message->SenderID,
            'sender_name' => $message->sender->FullName ?? 'Người dùng',
            'created_at' => $message->CreatedAt?->format('d/m/Y H:i'),
            'mine' => $message->SenderID === $user->UserID,
        ])]);
    }

    public function store(Request $request, Post $post): JsonResponse
    {
        abort_unless(Schema::hasTable('chat_messages'), 404);
        $request->validate(['message' => ['required', 'string', 'max:1000']]);
        $user = $request->user();
        $transaction = Transaction::where('PostID', $post->PostID)->first();

        abort_unless(
            $transaction && (
                $post->UserID === $user->UserID
                || $transaction->BuyerID === $user->UserID
            ),
            403
        );

        $message = ChatMessage::create([
            'MessageID' => ChatMessage::generateId(),
            'PostID' => $post->PostID,
            'TransactionID' => $transaction->TransactionID,
            'SenderID' => $user->UserID,
            'Message' => $request->string('message')->trim()->toString(),
        ]);

        return response()->json(['ok' => true, 'message' => [
            'id' => $message->MessageID,
            'message' => $message->Message,
            'sender_id' => $message->SenderID,
            'sender_name' => $user->FullName,
            'created_at' => $message->CreatedAt?->format('d/m/Y H:i'),
            'mine' => true,
        ]], 201);
    }
}

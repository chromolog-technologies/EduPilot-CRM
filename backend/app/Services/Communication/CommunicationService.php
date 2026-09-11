<?php

namespace App\Services\Communication;

use App\Enums\ConversationChannel;
use App\Enums\ConversationStatus;
use App\Enums\MessageStatus;
use App\Enums\MessageType;
use App\Enums\SenderType;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Student;
use App\Models\User;

class CommunicationService
{
    public function conversationsForStudent(Student $student)
    {
        return $student->conversations()->with('messages')->latest()->get();
    }

    public function messages(Conversation $conversation)
    {
        return $conversation->messages()->latest()->paginate(50);
    }

    public function sendMessage(User $user, Conversation $conversation, string $body): Message
    {
        return Message::create([
            'organization_id' => $user->organization_id,
            'conversation_id' => $conversation->id,
            'sender_type' => SenderType::User,
            'sender_id' => $user->id,
            'message_type' => MessageType::Text,
            'body' => $body,
            'status' => MessageStatus::Sent,
            'sent_at' => now(),
        ]);
    }

    public function firstOrCreateWhatsappConversation(User $user, Student $student): Conversation
    {
        return Conversation::firstOrCreate(
            [
                'organization_id' => $user->organization_id,
                'student_id' => $student->id,
                'channel' => ConversationChannel::Whatsapp,
            ],
            [
                'status' => ConversationStatus::Open,
            ]
        );
    }
}

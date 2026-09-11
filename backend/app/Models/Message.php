<?php

namespace App\Models;

use App\Enums\MessageStatus;
use App\Enums\MessageType;
use App\Enums\SenderType;
use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'conversation_id',
        'sender_type',
        'sender_id',
        'message_type',
        'body',
        'external_message_id',
        'status',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sender_type' => SenderType::class,
            'message_type' => MessageType::class,
            'status' => MessageStatus::class,
            'sent_at' => 'datetime',
        ];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }
}

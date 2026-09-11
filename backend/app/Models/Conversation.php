<?php

namespace App\Models;

use App\Enums\ConversationChannel;
use App\Enums\ConversationStatus;
use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'student_id',
        'channel',
        'external_conversation_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'channel' => ConversationChannel::class,
            'status' => ConversationStatus::class,
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ConversationTag extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'slug',
        'color',
    ];

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class, 'conversation_tag', 'tag_id', 'conversation_id');
    }
}

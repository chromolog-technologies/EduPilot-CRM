<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowupSequenceStep extends Model
{
    protected $fillable = [
        'sequence_id',
        'step_order',
        'delay_value',
        'delay_unit',
        'channel',
        'action_type',
        'template_id',
        'message_content',
        'conditions',
        'is_active',
    ];

    protected $casts = [
        'conditions' => 'array',
        'is_active' => 'boolean',
    ];

    public function sequence(): BelongsTo
    {
        return $this->belongsTo(FollowupSequence::class, 'sequence_id');
    }
}

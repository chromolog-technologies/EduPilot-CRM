<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowupSequence extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'trigger_type',
        'status',
        'stop_on_reply',
        'stop_on_conversion',
        'created_by',
    ];

    protected $casts = [
        'stop_on_reply' => 'boolean',
        'stop_on_conversion' => 'boolean',
    ];

    public function steps(): HasMany
    {
        return $this->hasMany(FollowupSequenceStep::class, 'sequence_id')->orderBy('step_order');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}

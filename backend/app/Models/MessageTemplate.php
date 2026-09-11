<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'name',
        'category',
        'channel',
        'language',
        'content',
        'variables',
        'external_template_id',
        'status',
        'created_by',
        'approved_at',
    ];

    protected $casts = [
        'variables' => 'array',
        'approved_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}

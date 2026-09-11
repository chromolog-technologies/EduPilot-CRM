<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuickReply extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'shortcut',
        'content',
        'language',
        'category',
        'created_by',
        'status',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}

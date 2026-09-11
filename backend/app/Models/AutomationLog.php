<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutomationLog extends Model
{
    protected $fillable = [
        'organization_id',
        'automation_type',
        'entity_type',
        'entity_id',
        'action',
        'status',
        'details',
        'executed_at',
    ];

    protected $casts = [
        'details' => 'array',
        'executed_at' => 'datetime',
    ];
}

<?php

namespace App\Enums;

enum FollowupStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Overdue = 'overdue';
}

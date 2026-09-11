<?php

namespace App\Enums;

enum ActivityType: string
{
    case Call = 'call';
    case Note = 'note';
    case Meeting = 'meeting';
    case Email = 'email';
    case Whatsapp = 'whatsapp';
    case StageChange = 'stage_change';
    case Assignment = 'assignment';
    case Document = 'document';
    case Other = 'other';
}

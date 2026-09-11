<?php

namespace App\Enums;

enum FollowupType: string
{
    case Call = 'call';
    case Whatsapp = 'whatsapp';
    case Email = 'email';
    case Meeting = 'meeting';
    case Other = 'other';
}

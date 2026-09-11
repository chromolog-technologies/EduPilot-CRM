<?php

namespace App\Enums;

enum ConversationChannel: string
{
    case Whatsapp = 'whatsapp';
    case Email = 'email';
    case Sms = 'sms';
}

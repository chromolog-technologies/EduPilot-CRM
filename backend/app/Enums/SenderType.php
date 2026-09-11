<?php

namespace App\Enums;

enum SenderType: string
{
    case User = 'user';
    case Student = 'student';
    case System = 'system';
}

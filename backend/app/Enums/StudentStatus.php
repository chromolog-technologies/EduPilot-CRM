<?php

namespace App\Enums;

enum StudentStatus: string
{
    case Inquiry = 'inquiry';
    case Active = 'active';
    case Enrolled = 'enrolled';
    case Lost = 'lost';
}

<?php

namespace App\Enums;

enum LeadTemperature: string
{
    case Cold = 'cold';
    case Warm = 'warm';
    case Hot = 'hot';
}

<?php

namespace App\Enums;

enum DocumentStatus: string
{
    case Pending = 'pending';
    case Uploaded = 'uploaded';
    case UnderReview = 'under_review';
    case Approved = 'approved';
    case Rejected = 'rejected';
}

<?php

namespace App\Events;

use App\Models\Student;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentStageChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Student $student,
        public ?int $fromStageId,
        public int $toStageId
    ) {}
}

<?php

namespace App\AI\Contracts;

interface AiProvider
{
    public function complete(string $prompt, array $options = []): string;
}

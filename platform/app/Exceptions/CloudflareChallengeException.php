<?php

namespace App\Exceptions;

use RuntimeException;

class CloudflareChallengeException extends RuntimeException
{
    public function __construct(
        public readonly string $targetUrl,
        string $message = 'Cloudflare challenge required.',
    ) {
        parent::__construct($message);
    }
}

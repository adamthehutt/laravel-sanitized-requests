<?php
declare(strict_types=1);

namespace AdamTheHutt\SanitizedRequests;

interface SanitizingRequest
{
    public function sanitize(Sanitizer $sanitizer): void;
}

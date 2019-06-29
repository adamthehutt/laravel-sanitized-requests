<?php
declare(strict_types=1);

namespace AdamTheHutt\SanitizedRequests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @mixin   FormRequest

 * @method  sanitize(Sanitizer $sanitizer): void
 */
trait SanitizesInput
{
    protected function prepareForValidation(): void
    {
        $sanitizer = new Sanitizer($this);
        $this->sanitize($sanitizer);

        $this->replace($sanitizer->getSanitized());
    }
}

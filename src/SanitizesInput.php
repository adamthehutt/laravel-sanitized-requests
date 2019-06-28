<?php
declare(strict_types=1);

namespace AdamTheHutt\SanitizedRequests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @mixin   FormRequest
 *
 * @method  sanitize(Sanitizer $sanitizer): void
 */
trait SanitizesInput
{
    protected function validationData()
    {
        $this->sanitizeCopy = $this->all();

        $sanitizer = new Sanitizer($this);
        $this->sanitize($sanitizer);

        $this->replace($sanitizer->input);

        return parent::validationData();
    }
}

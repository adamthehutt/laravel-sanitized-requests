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
    /** @var array */
    protected $sanitizeCopy;

    protected function validationData()
    {
        $this->sanitizeCopy = $this->all();

        $sanitizer = new Sanitizer($this->sanitizeCopy);
        $this->sanitize($sanitizer);

        $this->replace($this->sanitizeCopy);

        return parent::validationData();
    }
}

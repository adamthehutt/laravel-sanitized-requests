<?php
declare(strict_types=1);

namespace AdamTheHutt\SanitizedRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

/**
 * @mixin   FormRequest
 *
 * @method  sanitize(array $input): void
 */
trait SanitizesInput
{
    /** @var array */
    protected $sanitizeCopy;

    /** @var array */
    protected $sanitizeDotCopy;

    public function sanitizer(): Sanitizer
    {
        return new Sanitizer($this->sanitizeCopy);
    }

    protected function validationData()
    {
        $this->sanitizeCopy = $this->all();
        $this->sanitizeDotCopy = Arr::dot($this->sanitizeCopy);

        $this->sanitize($this->sanitizeCopy);
        $this->replace($this->sanitizeCopy);

        return parent::validationData();
    }
}

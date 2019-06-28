<?php
declare(strict_types=1);

namespace AdamTheHutt\SanitizedRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Sanitizer
{
    /** @var array */
    public $input;

    /** @var array */
    public $inputDots;

    /** @var array */
    public $sanitized;

    /**
     * Accepts either a form request object or raw input array
     *
     * @param FormRequest|array $mixed
     */
    public function __construct($mixed)
    {
        $this->input     = $mixed instanceof FormRequest ? $mixed->all() : $mixed;
        $this->inputDots = Arr::dot($this->input);
        $this->sanitized = $this->input;
    }

    public function forgetIfEmpty(string $dotPath): self
    {
        $this->manipulate($dotPath, function ($value, $key) {
            if (empty($value) || (is_array($value) && empty(array_filter($value)))) {
                Arr::forget($this->sanitized, $key);
            }
        });

        return $this;
    }

    public function castToInt(string $dotPath): self
    {
        return $this->typeCast($dotPath, "integer");
    }

    public function castToFloat(string $dotPath): self
    {
        return $this->typeCast($dotPath, "float");
    }

    public function castToBool(string $dotPath): self
    {
        return $this->typeCast($dotPath, "boolean");
    }

    public function castToString(string $dotPath): self
    {
        return $this->typeCast($dotPath, "string");
    }

    public function trimWhitespace(string $dotPath): self
    {
        $this->manipulate($dotPath, function ($value, $key) {
            Arr::set($this->sanitized, $key, trim($value));
        });

        return $this;
    }

    public function getSanitized(): array
    {
        return $this->sanitized;
    }

    private function parseDotPath(string $dotPath): array
    {
        // Straight key
        if (array_key_exists($dotPath, $this->inputDots)) {
            return [$dotPath];

        // Nested trailing wildcard
        } elseif (Str::endsWith($dotPath, ".*")) {
            $parentKey = Str::replaceLast(".*", "", $dotPath);
            $childKeys = array_keys(Arr::get($this->input, $parentKey, []));
            return array_map(function($key) use ($parentKey) {
                    return $parentKey . '.' . $key;
                }, $childKeys);

        // Lone or inner wildcard
        } elseif (Str::contains($dotPath, "*")) {
            $regex = '^'.str_replace([".", "*"], ["\.", ".+"], $dotPath).'$';
            return preg_grep("/$regex/", array_keys($this->inputDots));

        // Nested array
        } elseif (Str::contains($dotPath, ".")) {
            $regex = '^'.$dotPath;
            $matches = preg_grep("/$regex/", array_keys($this->inputDots));
            if (count($matches)) {
                return [$dotPath];
            }
        }

        return [];
    }

    private function manipulate(string $dotPath, \Closure $function)
    {
        $keys = $this->parseDotPath($dotPath);
        foreach ($keys as $key) {
            $value = Arr::get($this->input, $key, null);
            $function($value, $key);
        }
    }

    private function typeCast(string $dotPath, string $type): self
    {
        $this->manipulate($dotPath, function ($value, $key) use($type) {
            settype($value, $type);
            Arr::set($this->sanitized, $key, $value);
        });

        return $this;
    }
}

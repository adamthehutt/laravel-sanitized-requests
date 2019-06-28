<?php
declare(strict_types=1);

namespace AdamTheHutt\SanitizedRequests;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Sanitizer
{
    /** @var array */
    public $input;

    /** @var array */
    public $inputDots;

    public function __construct(array &$input)
    {
        $this->input     = $input;
        $this->inputDots = Arr::dot($input);
    }

    public function forgetIfEmpty(string $dotPath): self
    {
        $this->manipulate($dotPath, function ($value, $key) {
            if (empty($value) || (is_array($value) && empty(array_filter($value)))) {
                Arr::forget($this->input, $key);
            }
        });

        return $this;
    }

    public function castToInt(string $dotPath): self
    {
        $this->manipulate($dotPath, function ($value, $key) {
            Arr::set($this->input, $key, (int) $value);
        });

        return $this;
    }

    public function castToFloat(string $dotPath): self
    {
        $this->manipulate($dotPath, function ($value, $key) {
            Arr::set($this->input, $key, (float) $value);
        });

        return $this;
    }

    public function castToBool(string $dotPath): self
    {
        $this->manipulate($dotPath, function ($value, $key) {
            Arr::set($this->input, $key, (bool) $value);
        });

        return $this;
    }

    public function trimWhitespace(string $dotPath): self
    {
        $this->manipulate($dotPath, function ($value, $key) {
            Arr::set($this->input, $key, trim($value));
        });

        return $this;
    }

    private function parseDotPath(string $dotPath): array
    {
        // Straight key
        if (array_key_exists($dotPath, $this->inputDots)) {
            return [$dotPath];

        // Nested trailing wildcard
        } elseif (Str::endsWith($dotPath, ".*")) {
            $parentKey = Str::replaceLast(".*", "", $dotPath);
            $childKeys = array_keys(Arr::get($this->input, $parentKey));
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
}

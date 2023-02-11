<?php

declare(strict_types=1);

namespace Backendbase\Utility\Arrays;

use function is_int;
use function is_string;

class ArrayExtractor
{
    private function __construct(private array $model)
    {
    }

    public static function withModel(array $model): self
    {
        return new self($model);
    }

    public static function extract(array $model, array $data): array
    {
        return self::withModel($model)
            ->findIntersections($data);
    }

    public function findIntersections(array $data): array
    {
        $result = [];
        foreach ($this->model as $modelKey => $modelValue) {
            if (is_string($modelKey)) {
                $result[$modelKey] = $this->findValue($modelKey, $modelValue, $data);
            }

            if (! is_int($modelKey)) {
                continue;
            }

            $result[$modelValue] = $this->getValue($modelValue, $data);
        }

        return $result;
    }

    private function findValue($modelKey, $modelValue, array $data)
    {
        if (is_string($modelKey)) {
            return self::withModel($modelValue)->findIntersections($data[$modelKey]);
        }

        return $this->getValue($modelValue, $data);
    }

    private function getValue($key, array $target)
    {
        return $target[$key] ?? null;
    }
}

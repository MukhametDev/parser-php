<?php

namespace Framework\Traits;

trait Errorable
{
    protected array $errors = [];

    public function addError(string $field, \Exception $error): void
    {
         $this->errors[$field][] = $error;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function clearErrors(): void
    {
        $this->errors = [];
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}

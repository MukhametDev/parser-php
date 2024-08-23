<?php

namespace Framework\Traits;

trait IDGeneratorTrait
{
    public function generateId(): int
    {
        return $this->id++;
    }
}

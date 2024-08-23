<?php

namespace Framework\Traits;

trait FileHandlerTrait
{
    public function insertToFile(string $content): void
    {
        file_put_contents($this->filename, $content, FILE_APPEND);
    }

    public function clearFile(): void
    {
        file_put_contents($this->filename, '');
        $this->log("File cleared: {$this->filename}");
    }
}

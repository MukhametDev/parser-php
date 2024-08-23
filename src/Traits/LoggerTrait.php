<?php

namespace Framework\Traits;

trait LoggerTrait
{
    protected function logError(string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}]  {$message}\n";

        file_put_contents($this->logFilename, $logMessage, FILE_APPEND);
    }
}

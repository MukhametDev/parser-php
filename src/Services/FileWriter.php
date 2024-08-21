<?php

namespace Framework\Services;

class FileWriter
{
    public function writeToFile(string $filePath, string $fileContent): void
    {
        $flags = FILE_APPEND;

        if (!file_exists($filePath)) {
            $flags = 0;
        }

        file_put_contents($filePath, $fileContent, $flags);
    }
}

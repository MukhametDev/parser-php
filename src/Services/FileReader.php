<?php

namespace Framework\Services;

class FileReader
{
    public function readFromFile(string $filePath): string
    {
        return file_get_contents($filePath);
    }
}

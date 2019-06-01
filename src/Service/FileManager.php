<?php

namespace App\Service;

use App\Service\Traits\FileSystemAwareTrait;

class FileManager
{
    use FileSystemAwareTrait;

    public function __construct($projectDir)
    {
        $this->rootDir = $projectDir;
    }

    public function prepareFolder(string $destination): void
    {
        $folder = pathinfo($destination, PATHINFO_DIRNAME);

        if (!is_dir($folder) && !mkdir($folder, 0777, true) && !is_dir($folder)) {
            throw new \RuntimeException(sprintf('Folder "%s" was not created', $folder));
        }
    }

    public function save(string $destination, string $data)
    {
        $this->prepareFolder($destination);

        return file_put_contents($destination, $data);
    }

    public function remove(string $path): bool
    {
        return unlink($path);
    }

    public function getRootDir(): string
    {
        return $this->rootDir;
    }
}

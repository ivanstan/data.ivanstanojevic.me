<?php

namespace App\Service\System;

use App\Entity\File;

class FileManager
{
    /** @var string */
    private $rootDir;

    public function __construct(string $rootDir)
    {
        $this->rootDir = $rootDir;
    }

    public function getAbsolutePath(File $file): string
    {
        return $this->rootDir.'/'.$file->getDestination();
    }

    public function save(string $destination, string $data)
    {
        $folder = pathinfo($destination, PATHINFO_DIRNAME);

        if (!is_dir($folder) && !mkdir($folder, 0777, true) && !is_dir($folder)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $folder));
        }

        return file_put_contents($destination, $data);
    }

    public function remove(string $path): bool
    {
        return unlink($path);
    }
}

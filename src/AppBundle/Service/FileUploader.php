<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;

    public function __construct(string $targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file): string
    {
        $filename = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($this->targetDir, $filename);
        return $filename;
    }

    public function getTargetDir(): string
    {
        return $this->targetDir;
    }
}
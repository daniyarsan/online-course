<?php

namespace App\Services\AsyncUploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class AsyncUploader
{
    public const TEMP_DIR = 'tmp';

    private string $uploadDir;

    public function __construct(string $uploadDir)
    {
        $this->uploadDir = $uploadDir;

        if (!file_exists($this->uploadDir)) {
            if (!mkdir($this->uploadDir, 0777, true)) {
                throw new \Exception("Failed to create $this->uploadDir");
            }
        }
    }

    public function process(UploadedFile $file, string $fileName, int $chunk = 0, int $chunks = 0): ?string
    {
        $originalFilePath = $this->uploadDir . '/' . $fileName;

        $out = @fopen("{$originalFilePath}.part", $chunk == 0 ? "wb" : "ab");
        if (!$out) {
            throw new \Exception('Failed to open output stream');
        }

        $in = @fopen($file->getPathname(), "rb");

        if (!$in) {
            throw new \Exception('Failed to open input stream');
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }
        @fclose($in);
        @fclose($out);
        @unlink($file->getPathname());

        if (!$chunks || $chunk == $chunks - 1) {
            $uniqueFileName = uniqid() . "_" . $fileName;
            $targetFilePath = $this->uploadDir . '/' . $uniqueFileName;
            rename("{$originalFilePath}.part", $targetFilePath);

            return $uniqueFileName;
        }

        return false;
    }
}
<?php

namespace App\Helper;

use SplFileInfo;

class FileHelper
{
    const DIRS = [
        'tmp' => '%upload%/tmp/',
        'spreadsheet' => '%upload%/spreadsheet/',
    ];

    private $projectDir;
    private $uploadDir;

    public function __construct(string $projectDir, string $uploadDir)
    {
        $this->projectDir = $projectDir;
        $this->uploadDir = $uploadDir;
    }

    /**
     * Return a folder from a list of prefined ones.
     *
     * @param string $foldername
     * @param bool $string (Optional) Whether to return a string or not
     * @return SplFileInfo|string
     */
    public function getFolder(string $foldername, bool $string = false)
    {
        if (!isset(self::DIRS[$foldername])) {
            throw new \RuntimeException('Folder does not exist');
        }

        $path = str_replace(
            [
                '%project%',
                '%upload%',
            ],
            [
                $this->projectDir,
                $this->uploadDir,
            ],
            self::DIRS[$foldername]
        );

        $file = new SplFileInfo($path);
        $this->ensureFolderExists($file);

        if ($string) {
            return $file->getPathname() . '/';
        } else {
            return $file;
        }
    }

    private function ensureFolderExists(SplFileInfo $file): void
    {
        if (!$file->isDir()) {
            $success = mkdir($file->getPathname(), 0777, true);
            if (!$success) {
                throw new \RuntimeException();
            }
        }
    }
}

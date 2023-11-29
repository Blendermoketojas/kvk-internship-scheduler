<?php

namespace App\Helpers\File;

use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public static function getFileNamesFolder($folderPath): array
    {
        $files = Storage::files($folderPath);

        $fileNames = [];
        foreach ($files as $file) {
            $fileInfo = pathinfo($file);
            $fileNames[] = $fileInfo['filename'];
        }

        return $fileNames;
    }
}

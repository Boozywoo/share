<?php

namespace App\Helpers;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileHelper
{
    /**
     * @param string $extension
     * @return string
     */
    public static function getFileType(string $extension): string
    {
        $extensionsImage = ['jpeg','png','jpg','gif','svg'];
        if(in_array($extension, $extensionsImage)){
            return 'image';
        }
        return 'file';
    }

    /**
     * @param UploadedFile $file
     * @param string $path
     * @return File
     */
    public static function saveFile(UploadedFile $file, string $path): File
    {
        $src = Storage::disk('public')->putFile($path, $file);

        return File::create(
            [
                'original_name' => $file->getClientOriginalName(),
                'name' => basename($src),
                'src' => $src,
                'size' => $file->getSize(),
                'extension' => $file->getClientOriginalExtension(),
                'type' => self::getFileType($file->getClientOriginalExtension()),
            ]
        );
    }
}
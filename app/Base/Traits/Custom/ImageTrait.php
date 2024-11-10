<?php

namespace App\Base\Traits\Custom;

use Illuminate\Support\Facades\File;

/**
 *
 */
trait ImageTrait
{
    use HttpExceptionTrait;
    /**
     * Undocumented function
     *
     * @param [type] $file
     * @param [type] $path
     * @return string
     */
    public function uploadImage($file, $path = "global", $quality = 60): string
    {
        $extension = $file->getClientOriginalExtension();
        $fileRename = time() . uniqid() . '.' . $extension;
        $uploadPath = public_path('uploads' . '/' . $path . '/');

        // Make directory if it doesn't exist
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $tempPath = $file->getRealPath();

        // Create image resource based on file extension
        if ($extension == 'jpeg' || $extension == 'jpg') {
            $image = imagecreatefromjpeg($tempPath);
            imagejpeg($image, $uploadPath . $fileRename, $quality);
        } elseif ($extension == 'png') {
            $image = imagecreatefrompng($tempPath);
            imagepng($image, $uploadPath . $fileRename);
        } else {
            return $this->throwHttpExceptionForWebAndApi(__("Unsupported image type"), 422);
        }

        // Free up memory
        imagedestroy($image);

        return 'uploads' . '/' . $path . '/' . $fileRename;
    }

    /**
     * Upload new images and delete old stored images
     *
     * @param string $path
     * @param [type] $file
     * @param [type] $old_record
     * @return string
     */
    public function updateImage($file, string | null $old_file_name, $path = "global", $quality = 60): string
    {
        $file_rename = $this->uploadImage($file, $path, $quality);
        if (File::exists(public_path('uploads' . '/' . $path . '/' . $old_file_name))) {
            File::delete(public_path('uploads' . '/' . $path . '/' . $old_file_name));
        }

        return $file_rename;
    }

    public function deleteImage(string | null $file_name, $path = "global")
    {
        if (File::exists(public_path('uploads' . '/' . $path . '/' . $file_name))) {
            File::delete(public_path('uploads' . '/' . $path . '/' . $file_name));
        }

        return false;
    }
}

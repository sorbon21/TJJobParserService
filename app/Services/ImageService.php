<?php

namespace App\Services;

use GuzzleHttp\Client;

class ImageService
{
    public static function cropImage(string $path, $percentage = 0.1): void
    {

        $originalImage = imagecreatefromjpeg($path);
        $originalWidth = imagesx($originalImage);
        $originalHeight = imagesy($originalImage);
        $newWidth = $originalWidth; // 10% of the width
        $newHeight = $originalHeight - ($originalHeight * $percentage); // 10% of the height
        $croppedImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($croppedImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
        imagejpeg($croppedImage, $path, 100); // Adjust the quality as needed
        imagedestroy($originalImage);
        imagedestroy($croppedImage);
    }


}

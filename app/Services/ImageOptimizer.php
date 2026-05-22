<?php
namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageOptimizer
{
    public static function store(UploadedFile $file, string $folder, int $maxWidth = 1200): string
    {
        $extension = 'webp';
        $filename = Str::uuid() . '.' . $extension;
        $path = storage_path('app/public/' . $folder);

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $img = Image::read($file);

        // Redimensionner si plus large que le max
        if ($img->width() > $maxWidth) {
            $img->resize($maxWidth, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        // Convertir en WebP qualité 80%
        $img->toWebp(80)->save($path . '/' . $filename);

        // Générer miniature 300px pour les listings
        $thumb = Image::read($file);
        if ($thumb->width() > 300) {
            $thumb->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        $thumbPath = $path . '/thumb_' . $filename;
        $thumb->toWebp(75)->save($thumbPath);

        return $folder . '/' . $filename;
    }
}

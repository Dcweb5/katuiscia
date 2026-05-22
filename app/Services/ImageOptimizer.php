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

        $img = Image::decode($file->getRealPath());

        // Redimensionner si plus large que le max
        if ($img->width() > $maxWidth) {
            $img->resize($maxWidth, null);
        }

        // Sauvegarder en WebP (qualité 80%)
        $img->save($path . '/' . $filename, quality: 80);

        // Générer miniature 300px pour les listings
        $thumb = Image::decode($file->getRealPath());
        if ($thumb->width() > 300) {
            $thumb->resize(300, null);
        }
        $thumbPath = $path . '/thumb_' . $filename;
        $thumb->save($thumbPath, quality: 75);

        return $folder . '/' . $filename;
    }
}

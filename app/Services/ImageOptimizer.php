<?php
namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageOptimizer
{
    public static function store(UploadedFile $file, string $folder, int $maxWidth = 1600): string
    {
        $filename = Str::uuid() . '.webp';
        $path = storage_path('app/public/' . $folder);

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        // Essayer d'obtenir le chemin du fichier (certaines configs retournent un chemin relatif)
        $filePath = $file->getRealPath() ?: $file->getPathname();
        if (!$filePath || !file_exists($filePath)) {
            // Fallback: utiliser le contenu binaire
            $img = Image::decode($file->get());
        } else {
            $img = Image::decode($filePath);
        }

        if ($img->width() > $maxWidth) {
            $img->resize($maxWidth, null);
        }

        $img->save($path . '/' . $filename, quality: 90);

        // Miniature 300px
        $thumbData = $file->get();
        $thumb = Image::decode($thumbData);
        if ($thumb->width() > 300) {
            $thumb->resize(300, null);
        }
        $thumb->save($path . '/thumb_' . $filename, quality: 75);

        return $folder . '/' . $filename;
    }
}

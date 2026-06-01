<?php
namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageOptimizer
{
    public static function store(UploadedFile $file, string $folder, int $maxWidth = 1600): string
    {
        @ini_set('memory_limit', '256M');
        $filename = Str::uuid() . '.webp';
        $path = storage_path('app/public/' . $folder);

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        // Essayer d'obtenir le chemin du fichier
        $filePath = $file->getRealPath() ?: $file->getPathname();
        if ($filePath && file_exists($filePath) && is_file($filePath)) {
            $img = Image::decode($filePath);
        } else {
            // Fallback: décoder le contenu binaire directement
            $img = Image::decodeBinary($file->get());
        }

        if ($img->width() > $maxWidth) {
            $img->scale(width: $maxWidth);
        }

        $img->save($path . '/' . $filename, quality: 90);

        // Miniature 300px — décoder depuis le fichier déjà sauvegardé
        $savedPath = $path . '/' . $filename;
        if (file_exists($savedPath)) {
            $thumb = Image::decode($savedPath);
            if ($thumb->width() > 300) {
                $thumb->scale(width: 300);
            }
            $thumb->save($path . '/thumb_' . $filename, quality: 75);
        }

        return $folder . '/' . $filename;
    }
}

<?php

namespace app\components;

use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Media helper — URL resolution and safe image storage for uploads.
 * Paths are stored relative to /web (e.g. "uploads/hero/abc.jpg").
 */
class Media
{
    /** Public URL for a stored relative path (or external URL). */
    public static function url(?string $path, string $fallback = 'uploads/seed/royal_noir_living.jpg'): string
    {
        $path = $path ?: $fallback;
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }
        return Yii::getAlias('@web') . '/' . ltrim($path, '/');
    }

    /**
     * Saves an uploaded image under web/uploads/<folder>/ with a random, safe file name.
     * Returns the relative path, or null when nothing was uploaded.
     */
    public static function store(?UploadedFile $file, string $folder): ?string
    {
        if ($file === null || $file->error !== UPLOAD_ERR_OK) {
            return null;
        }
        $folder = preg_replace('/[^a-z0-9_\-]/i', '', $folder) ?: 'misc';
        $dir = Yii::getAlias('@uploads/' . $folder);
        FileHelper::createDirectory($dir, 0775, true);

        $ext = strtolower($file->extension);
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return null;
        }
        $name = date('Ymd') . '_' . bin2hex(random_bytes(10)) . '.' . $ext;

        return $file->saveAs($dir . DIRECTORY_SEPARATOR . $name) ? 'uploads/' . $folder . '/' . $name : null;
    }

    /** Stores many uploaded files and returns the list of relative paths. */
    public static function storeMany(array $files, string $folder): array
    {
        $paths = [];
        foreach ($files as $file) {
            if ($p = self::store($file, $folder)) {
                $paths[] = $p;
            }
        }
        return $paths;
    }

    /** Deletes a previously uploaded file (never touches seed assets). */
    public static function delete(?string $path): void
    {
        if (!$path || str_starts_with($path, 'uploads/seed/') || !str_starts_with($path, 'uploads/')) {
            return;
        }
        $full = Yii::getAlias('@app/web/' . $path);
        $real = realpath($full);
        $root = realpath(Yii::getAlias('@uploads'));
        if ($real && $root && str_starts_with($real, $root) && is_file($real)) {
            @unlink($real);
        }
    }
}

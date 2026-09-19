<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;

class StoreImages
{
    public const THUMB = 300;

    public const MEDIUM = 800;

    public const LARGE = 1600;

    public const QUALITY = 82;

    public static function extension(): string
    {
        return function_exists('imagewebp') ? 'webp' : 'jpg';
    }

    public static function uniqueName(string $prefix = ''): string
    {
        $base = ($prefix !== '' ? $prefix.'_' : '').time().'_'.Str::random(10);

        return $base.'.'.self::extension();
    }

    public static function processGallery(UploadedFile $file, string $filename): void
    {
        self::ensureProductDirs();
        $source = $file->getRealPath();

        self::writeVariant($source, "uploads/products/thumbnails/{$filename}", self::THUMB);
        self::writeVariant($source, "uploads/products/medium/{$filename}", self::MEDIUM);
        self::writeVariant($source, "uploads/products/{$filename}", self::LARGE);
    }

    public static function processListingThumb(UploadedFile $file, string $filename): void
    {
        self::ensureProductDirs();
        self::writeVariant($file->getRealPath(), "uploads/products/thumbnails/{$filename}", self::THUMB);
    }

    private static function writeVariant(string $sourcePath, string $dest, int $maxEdge): void
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($sourcePath);
        $image->scaleDown(width: $maxEdge, height: $maxEdge);
        Storage::disk('public')->put($dest, self::encode($image));
    }

    private static function encode(ImageInterface $image): string
    {
        if (function_exists('imagewebp')) {
            return (string) $image->toWebp(quality: self::QUALITY);
        }

        return (string) $image->toJpeg(quality: self::QUALITY);
    }

    private static function ensureProductDirs(): void
    {
        $disk = Storage::disk('public');
        foreach (['uploads/products', 'uploads/products/thumbnails', 'uploads/products/medium'] as $path) {
            if (! $disk->exists($path)) {
                $disk->makeDirectory($path);
            }
        }
    }
}

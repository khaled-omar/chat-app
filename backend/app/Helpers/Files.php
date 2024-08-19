<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (! function_exists('getPath')) {
    /**
     * Get path from public storage
     */
    function getPath(?string $file, string $disk = 'public'): ?string
    {
        $storage = Storage::disk($disk);

        return filled($file) && $storage->exists($file) ? $storage->url($file) : null;
    }
}

if (! function_exists('storeFile')) {
    /**
     * store file in public storage
     */
    function storeFile($file, $storagePath, string $disk = 'public'): ?string
    {
        $storage = Storage::disk($disk);
        $fileName = $storagePath.'/'.Str::uuid().'.'.$file->getClientOriginalExtension();
        try {
            $storage->put($fileName, file_get_contents($file));

            return $fileName;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}

if (! function_exists('deleteFile')) {
    /**
     * delete file in public storage
     */
    function deleteFile(string $file, string $disk = 'public'): ?string
    {
        $storage = Storage::disk($disk);
        try {
            $storage->delete($file);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

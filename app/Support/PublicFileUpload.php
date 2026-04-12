<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicFileUpload
{
    /**
     * Move an upload into public/uploads/{subdir} and return a path relative to public (e.g. uploads/covers/uuid.jpg).
     */
    public static function move(UploadedFile $file, string $subdir): string
    {
        $subdir = trim($subdir, '/');
        $dir = public_path('uploads/'.$subdir);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext = $file->getClientOriginalExtension();
        $name = Str::uuid().($ext !== '' ? '.'.$ext : '');
        $file->move($dir, $name);

        return 'uploads/'.$subdir.'/'.$name;
    }

    /**
     * Delete a file under public/ given its path relative to public (uploads/...).
     */
    public static function deletePublic(?string $relativeToPublic): void
    {
        if (! $relativeToPublic || ! str_starts_with($relativeToPublic, 'uploads/')) {
            return;
        }
        $path = public_path($relativeToPublic);
        if (is_file($path)) {
            @unlink($path);
        }
    }

    /**
     * Remove a previously stored path (new public/uploads/... or legacy Storage paths).
     */
    public static function deleteStored(?string $path): void
    {
        if (! $path) {
            return;
        }
        if (str_starts_with($path, 'uploads/')) {
            self::deletePublic($path);

            return;
        }
        if (str_starts_with($path, 'books/')) {
            Storage::disk('local')->delete($path);

            return;
        }
        Storage::disk('public')->delete($path);
    }

    /** Payment proof screenshot: public/uploads/payment-proofs/{orderId}/file.ext */
    public static function movePaymentProof(UploadedFile $file, int $orderId): string
    {
        return self::move($file, 'payment-proofs/'.$orderId);
    }
}

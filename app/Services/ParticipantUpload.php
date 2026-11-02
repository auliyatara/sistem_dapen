<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ParticipantUpload
{
    public static function validate(Request $request): void
    {
        $request->validate([
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'list_file_dokumen' => 'nullable|array',
            'list_file_dokumen.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif,webp,doc,docx,xls,xlsx|max:20480',
            'list_dokumen_nama' => 'nullable|array',
            'list_dokumen_nama.*' => 'required|string|max:100',
        ]);
    }

    public static function store(UploadedFile $file, string $directory, array &$created): string
    {
        $name = Str::uuid().'.'.$file->extension();
        $path = $directory.'/'.$name;
        $created[] = $path;
        try {
            if (!Storage::disk('public')->putFileAs($directory, $file, $name)) throw new \RuntimeException('File gagal disimpan.');
            $source = Storage::disk('public')->path($path);
            $mirror = public_path('storage/'.$path);
            // Existing installations may use a physical public/storage directory instead of a link.
            if (realpath(dirname($source)) !== realpath(dirname($mirror))) {
                if (!is_dir(dirname($mirror)) && !mkdir(dirname($mirror), 0755, true)) throw new \RuntimeException('Folder berkas tidak dapat dibuat.');
                if (!copy($source, $mirror)) throw new \RuntimeException('Salinan berkas gagal disimpan.');
            }
        } catch (\Throwable $error) {
            self::remove([$path]);
            throw $error;
        }
        return $name;
    }

    public static function remove(array $paths): void
    {
        foreach ($paths as $path) {
            Storage::disk('public')->delete($path);
            $mirror = public_path('storage/'.$path);
            if (is_file($mirror)) unlink($mirror);
        }
    }
}

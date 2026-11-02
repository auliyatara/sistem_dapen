<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class KpiEvidenceCompressor
{
    public const MAX_SIZE = 2097152;

    public function compress(string $source, string $destination, string $mimeType): void
    {
        if ($mimeType === 'application/pdf') {
            $this->compressPdf($source, $destination);
            return;
        }

        $this->compressImage($source, $destination, $mimeType);
    }

    private function compressPdf(string $source, string $destination): void
    {
        $binary = config('services.ghostscript.binary');

        if (!is_file($binary)) {
            throw new \RuntimeException('Ghostscript tidak ditemukan. Hubungi administrator untuk konfigurasi kompresi PDF.');
        }

        foreach (['/ebook', '/screen'] as $quality) {
            $process = new Process([
                $binary,
                '-sDEVICE=pdfwrite',
                '-dCompatibilityLevel=1.4',
                "-dPDFSETTINGS={$quality}",
                '-dNOPAUSE',
                '-dQUIET',
                '-dBATCH',
                "-sOutputFile={$destination}",
                $source,
            ]);
            $process->setTimeout(120);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new \RuntimeException('Kompresi PDF gagal diproses.');
            }

            clearstatcache(true, $destination);
            if (is_file($destination) && filesize($destination) <= self::MAX_SIZE) {
                return;
            }
        }
    }

    private function compressImage(string $source, string $destination, string $mimeType): void
    {
        $image = match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($source),
            'image/png' => imagecreatefrompng($source),
            default => false,
        };

        if (!$image) {
            throw new \RuntimeException('Format gambar tidak dapat dikompres.');
        }

        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);
        $scale = 1;

        while ($scale >= 0.2) {
            $width = max(1, (int) round($originalWidth * $scale));
            $height = max(1, (int) round($originalHeight * $scale));
            $canvas = imagecreatetruecolor($width, $height);

            if ($mimeType === 'image/png') {
                imagealphablending($canvas, false);
                imagesavealpha($canvas, true);
                imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
            }

            imagecopyresampled($canvas, $image, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);
            $saved = $mimeType === 'image/jpeg'
                ? imagejpeg($canvas, $destination, 82)
                : imagepng($canvas, $destination, 9);
            imagedestroy($canvas);

            clearstatcache(true, $destination);
            if ($saved && filesize($destination) <= self::MAX_SIZE) {
                imagedestroy($image);
                return;
            }

            $scale -= 0.1;
        }

        imagedestroy($image);
    }

}

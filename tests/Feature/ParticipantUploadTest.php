<?php
namespace Tests\Feature;

use App\Services\ParticipantUpload;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ParticipantUploadTest extends TestCase
{
    public function test_unique_names_preserve_both_files_and_cleanup_removes_copies()
    {
        Storage::fake('public');
        $created = [];
        try {
            $first = ParticipantUpload::store(UploadedFile::fake()->createWithContent('same.pdf', '%PDF-first'), 'kepesertaan_dokumen', $created);
            $second = ParticipantUpload::store(UploadedFile::fake()->createWithContent('same.pdf', '%PDF-second'), 'kepesertaan_dokumen', $created);
            $this->assertNotSame($first, $second);
            Storage::disk('public')->assertExists($created);
            foreach ($created as $path) $this->assertFileExists(public_path('storage/'.$path));
        } finally { ParticipantUpload::remove($created); }
        Storage::disk('public')->assertMissing($created);
        foreach ($created as $path) $this->assertFileDoesNotExist(public_path('storage/'.$path));
    }

    public function test_unsupported_or_oversized_attachments_are_rejected_before_storage()
    {
        foreach ([UploadedFile::fake()->create('bad.exe', 1, 'application/octet-stream'), UploadedFile::fake()->create('large.pdf', 20481, 'application/pdf')] as $file) {
            $request = new Request();
            $request->files->set('list_file_dokumen', [$file]);
            try {
                ParticipantUpload::validate($request);
                $this->fail('Invalid upload was accepted.');
            } catch (ValidationException $error) {
                $this->assertArrayHasKey('list_file_dokumen.0', $error->errors());
            }
        }
    }
}

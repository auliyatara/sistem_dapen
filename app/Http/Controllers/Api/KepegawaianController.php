<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
// use App\Services\User\UserService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class KepegawaianController extends Controller
{    
    private const MAX_LAMPIRAN_SIZE = 2097152;
    // protected $UserService;

    // public function __construct(UserService $UserService){
    //     $this->UserService = $UserService;
    // }

    public function index(){
        // $operation = $this->UserService->index();
        $operation = Pegawai::all();
        return $this->response($operation);
    }

    public function show($id){
        // $operation = $this->UserService->find($id);
        $operation = Pegawai::where('id',$id)->get();
        return $this->responseFirst($operation);
    }

    public function lampiran(string $filename)
    {
        $path = public_path('lampiran/' . basename($filename));

        abort_unless(is_file($path), 404, 'Lampiran tidak ditemukan.');

        return response()->file($path);
    }

    public function store(Request $request){
        try {            
           
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'nip' => 'required|string|max:50',
                'nik' => 'required|string|max:50',
                'tempat_lahir' => 'required|string|max:100',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'agama' => 'required|string|max:50',
                'alamat_ktp' => 'required|string',
                'alamat_saat_ini' => 'required|string',
                'nomor_hp_1' => 'required|string|max:20',
                'nomor_hp_2' => 'nullable|string|max:20',
                'email_1' => 'required|email|max:255',
                'email_2' => 'nullable|email|max:255',
                'masa_kerja' => 'required|date',
                'sisa_masa_kerja' => 'nullable|string|max:100',
                'tanggal_pengangkatan' => 'required|date',
                'perkiraan_pensiun' => 'required|date|after:tanggal_pengangkatan',
                'jabatan' => 'required|in:Pengurus,Pegawai',
                'lampiran' => 'required|array|min:1',
                'lampiran.*' => 'file|mimes:pdf,jpg,jpeg,png|max:40960',
            ]);

            $validated['usia'] = Carbon::parse($validated['tanggal_lahir'])->age;
            $validated['sisa_masa_kerja'] = $this->calculateSisaMasaKerja($validated['perkiraan_pensiun']);

            $validated['lampiran'] = $this->storeLampiran($request->file('lampiran'));
            
            $operation = Pegawai::create($validated);
            return $this->responseCreate($operation);
        } catch (\Exception $e) {
            return $this->responseCreate($e->getMessage(),true);
        }
    }

    public function update(Request $request, $id){
        try {            
           
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'nip' => 'required|string|max:50',
                'nik' => 'required|string|max:50',
                'tempat_lahir' => 'required|string|max:100',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'agama' => 'required|string|max:50',
                'alamat_ktp' => 'required|string',
                'alamat_saat_ini' => 'required|string',
                'nomor_hp_1' => 'required|string|max:20',
                'nomor_hp_2' => 'nullable|string|max:20',
                'email_1' => 'required|email|max:255',
                'email_2' => 'nullable|email|max:255',
                'masa_kerja' => 'required|date',
                'sisa_masa_kerja' => 'nullable|string|max:100',
                'tanggal_pengangkatan' => 'required|date',
                'perkiraan_pensiun' => 'required|date|after:tanggal_pengangkatan',
                'jabatan' => 'required|in:Pengurus,Pegawai',
                'lampiran' => 'nullable|array|min:1',
                'lampiran.*' => 'file|mimes:pdf,jpg,jpeg,png|max:40960',
            ]);

            $validated['usia'] = Carbon::parse($validated['tanggal_lahir'])->age;
            $validated['sisa_masa_kerja'] = $this->calculateSisaMasaKerja($validated['perkiraan_pensiun']);

            if ($request->hasFile('lampiran')) {
                $validated['lampiran'] = $this->storeLampiran($request->file('lampiran'));
            }
            $operation = Pegawai::where('id',$id)->update($validated);

            return $this->responseUpdate($operation);
        } catch (\Exception $e) {
            return $this->responseUpdate($e->getMessage(),true);
        }
    }

    public function updatealternate(Request $request){
        try {
            $data = $request->all();
            $id = $data['user_id'];
            $check = $request->validate([
                'user_username' => [
                    'required',
                    Rule::unique('users')->ignore($id, 'user_id'),
                ],
                'user_email' => [
                    'required',
                    Rule::unique('users')->ignore($id, 'user_id'),
                ],
                'file' => ['sometimes','mimes:jpeg,png,jpg,gif,svg'],
            ]);

            unset($data['_token']);

            $data['user_foto']= 'foto_user'.time().'.'.request()->file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('user_foto/', $request->file('file'), $data['user_foto']);
            $request->file('file')->move(public_path('storage/app/public/user_foto'), $data['user_foto']);
            if(!isset($data['password']))unset($data['password']);
            else $data['password'] = Hash::make($data['password']);
            unset($data['file']);
            $operation = User::where('user_id',$id)->update($data);
            return $this->responseUpdate($operation);
        } catch (\Exception $e) {
            return $this->responseUpdate($e->getMessage(),true);
        }
    }

    public function destroy($id){
        try {            
            
            $pegawai = Pegawai::destroy("id", $id);
            // $pegawai->delete();
            return $this->responseDelete($pegawai);
        } catch (\Exception $e) {
            return $this->responseDelete($e->getMessage());
        }
    }

    /** Simpan path beberapa lampiran dalam satu kolom JSON. */
    private function storeLampiran(array $files): string
    {
        $lampiran = [];

        foreach ($files as $file) {
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('lampiran/' . $filename);

            if ($file->getSize() > self::MAX_LAMPIRAN_SIZE) {
                $this->compressLampiran($file->getPathname(), $destination, $file->getMimeType());
            } else {
                $file->move(public_path('lampiran'), $filename);
            }

            if (!is_file($destination) || filesize($destination) > self::MAX_LAMPIRAN_SIZE) {
                @unlink($destination);
                throw new \RuntimeException('Lampiran tidak dapat dikompres hingga ukuran maksimal 2 MB.');
            }

            $lampiran[] = [
                'path' => 'lampiran/' . $filename,
                'name' => basename($file->getClientOriginalName()),
            ];
        }

        return json_encode($lampiran);
    }

    private function compressLampiran(string $source, string $destination, string $mimeType): void
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

            if (is_file($destination) && filesize($destination) <= self::MAX_LAMPIRAN_SIZE) {
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

            if ($saved && filesize($destination) <= self::MAX_LAMPIRAN_SIZE) {
                imagedestroy($image);
                return;
            }

            $scale -= 0.1;
        }

        imagedestroy($image);
    }

    private function calculateSisaMasaKerja(string $tanggalPensiun): string
    {
        $today = Carbon::today();
        $pensionDate = Carbon::parse($tanggalPensiun)->startOfDay();

        if ($pensionDate->lessThanOrEqualTo($today)) {
            return '0 Tahun 0 Bulan 0 Hari';
        }

        $interval = $today->diff($pensionDate);

        return sprintf('%d Tahun %d Bulan %d Hari', $interval->y, $interval->m, $interval->d);
    }
}

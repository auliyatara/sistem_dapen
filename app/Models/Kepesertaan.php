<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Kepesertaan extends Model
{
    use HasFactory,LogsActivity;

    protected $primaryKey = 'kepesertaan_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];
    protected $appends = ['kelengkapan'];

    public function scopeWithCompleteness($query)
    {
        return $query->withCount(['kepesertaan_dokumen as dokumen_tersedia' => function ($documents) {
                $documents->whereNotNull('kepesertaan_dokumen_file')->where('kepesertaan_dokumen_file', '<>', '')
                    ->whereNotNull('kepesertaan_dokumen_judul')->where('kepesertaan_dokumen_judul', '<>', '');
        }]);
    }

    public function getKelengkapanAttribute(): array
    {
        $fields = ['kepesertaan_nama' => 'Nama', 'kepesertaan_nik' => 'NIK', 'kepesertaan_nip' => 'NIP',
            'kepesertaan_jenis_kelamin' => 'Jenis kelamin', 'kepesertaan_tempat_lahir' => 'Tempat lahir',
            'kepesertaan_tanggal_lahir' => 'Tanggal lahir', 'kepesertaan_telphone_1' => 'Telepon utama',
            'kepesertaan_email_1' => 'Email utama', 'kepesertaan_alamat' => 'Alamat',
            'kepesertaan_gaji_pokok' => 'PHDP', 'kepesertaan_terhitung_mulai_tanggal' => 'TMT peserta',
            'kepesertaan_status_kepesertaan' => 'Status kepesertaan', 'kepesertaan_status_jabatan' => 'Status jabatan',
            'kepesertaan_terhitung_mulai_tanggal_pensiun' => 'Tanggal pensiun'];
        $missing = [];
        foreach ($fields as $field => $label) {
            $value = $this->getAttribute($field);
            if ($value === null || trim((string) $value) === '' || str_starts_with((string) $value, '0000-00-00')) $missing[] = $label;
        }
        $documentCount = $this->dokumen_tersedia;
        if ($documentCount === null && $this->exists) {
            $documentCount = $this->kepesertaan_dokumen()->whereNotNull('kepesertaan_dokumen_file')
                ->where('kepesertaan_dokumen_file', '<>', '')->whereNotNull('kepesertaan_dokumen_judul')
                ->where('kepesertaan_dokumen_judul', '<>', '')->count();
        }
        if ((int) $documentCount === 0) $missing[] = 'Dokumen pendukung';
        return ['lengkap' => !$missing, 'kurang' => $missing];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} kepesertaan")->logAll();
    }

    public function kepesertaan_dokumen()
    {
        return $this->hasMany(KepesertaanDokumen::class, 'kepesertaan_dokumen_kepesertaan_id');
    }

    public function kepesertaan_iuran()
    {
        return $this->hasMany(KepesertaanIuran::class, 'kepesertaan_iuran_kepesertaan_id');
    }
}

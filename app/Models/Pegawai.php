<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $primaryKey = "id";
    protected $table = "pegawai";
    protected $appends = ['masa_kerja_durasi'];

    public function getMasaKerjaDurasiAttribute()
    {
        if (!$this->masa_kerja || str_starts_with($this->masa_kerja, '0000-00-00')) return null;
        $start = \Carbon\Carbon::parse($this->masa_kerja, 'Asia/Jakarta')->startOfDay();
        $today = \Carbon\Carbon::today('Asia/Jakarta');
        if ($start->isAfter($today)) return 'Belum mulai bekerja';
        $interval = $start->diff($today);
        return sprintf('%d Tahun %d Bulan %d Hari', $interval->y, $interval->m, $interval->d);
    }

    public function getUsiaAttribute($value)
    {
        if (!$this->tanggal_lahir || str_starts_with($this->tanggal_lahir, '0000-00-00')) return null;
        $birth = \Carbon\Carbon::parse($this->tanggal_lahir, 'Asia/Jakarta')->startOfDay();
        $today = \Carbon\Carbon::today('Asia/Jakarta');
        return $birth->isAfter($today) ? null : $birth->diff($today)->y;
    }

    public function getSisaMasaKerjaAttribute($value)
    {
        if (!$this->perkiraan_pensiun || str_starts_with($this->perkiraan_pensiun, '0000-00-00')) return null;
        $today = \Carbon\Carbon::today('Asia/Jakarta');
        $pension = \Carbon\Carbon::parse($this->perkiraan_pensiun, 'Asia/Jakarta')->startOfDay();
        if ($pension->lessThanOrEqualTo($today)) return '0 Tahun 0 Bulan 0 Hari';
        $interval = $today->diff($pension);
        return sprintf('%d Tahun %d Bulan %d Hari', $interval->y, $interval->m, $interval->d);
    }

    protected $fillable = [
        'nama',
        'nip',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'usia',
        'jenis_kelamin',
        'agama',
        'alamat_ktp',
        'alamat_saat_ini',
        'nomor_hp_1',
        'nomor_hp_2',
        'email_1',
        'email_2',
        'masa_kerja',
        'sisa_masa_kerja',
        'tanggal_pengangkatan',
        'perkiraan_pensiun',
        'jabatan',
        'lampiran',
        'links',
    ];
}

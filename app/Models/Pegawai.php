<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $primaryKey = "id";
    protected $table = "pegawai";

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
    ];
}

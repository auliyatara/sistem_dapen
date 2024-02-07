<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class notifikasi extends Model
{
    use HasFactory;

    protected $primaryKey = 'notifikasi_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'notifikasi_id',
        'notifikasi_judul',
        'notifikasi_keterangan',
        'notifikasi_status',
        'notifikasi_url',
        'notifikasi_penerima_user_id',
        'notifikasi_pengirim_user_id',
        'notifikasi_pengirim_user_nama',
    ];

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->notifikasi_id = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
        });
    }
}

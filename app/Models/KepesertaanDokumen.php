<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class KepesertaanDokumen extends Model
{
    use HasFactory;

    protected $primaryKey = 'kepesertaan_dokumen_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kepesertaan_dokumen_id',
        'kepesertaan_dokumen_kepesertaan_id',
        'kepesertaan_dokumen_judul',
        'kepesertaan_dokumen_file',
    ];

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->kepesertaan_dokumen_id = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
        });
    }

    public function kepesertaan()
    {
        return $this->belongsTo(Kepesertaan::class);
    }
}

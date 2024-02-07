<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class InformasiTambahan extends Model
{
    use HasFactory,LogsActivity;

    protected $primaryKey = 'informasi_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'informasi_id',
        'informasi_tanggal',
        'informasi_judul',
        'informasi_deskripsi',
    ];

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->informasi_id = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} informasi tambahan")->logAll();
    }
}

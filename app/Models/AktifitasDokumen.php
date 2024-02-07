<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class AktifitasDokumen extends Model
{
    use HasFactory;

    protected $primaryKey = 'aktifitas_dokumen_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->aktifitas_dokumen_id = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
        });
    }

    public function aktifitas()
    {
        return $this->belongsTo(Aktifitas::class);
    }
}

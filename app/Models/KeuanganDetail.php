<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class KeuanganDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'keuangan_detail_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->keuangan_detail_id = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
        });
    }

    public function keuangan()
    {
        return $this->belongsTo(Keuangan::class);
    }

    public function keuangan_bulan()
    {
        return $this->hasMany(KeuanganBulan::class, 'keuangan_bulan_keuangan_detail_id');
    }
}

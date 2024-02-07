<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class InvestasiSubdata extends Model
{
    use HasFactory;
    protected $primaryKey = 'investasi_subdata_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->investasi_subdata_id = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
        });
    }

    public function investasi()
    {
        return $this->belongsTo(Investasi::class);
    }

    public function investasi_subdata_nilai_wajar()
    {
        return $this->hasMany(InvestasiSubdataBulan::class, 'investasi_subdata_bulan_investasi_subdata_id');
    }
}

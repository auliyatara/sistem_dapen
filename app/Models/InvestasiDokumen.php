<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class InvestasiDokumen extends Model
{
    use HasFactory;

    protected $primaryKey = 'investasi_dokumen_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->investasi_dokumen_id = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
        });
    }
    
    public function investasi()
    {
        return $this->belongsTo(Investasi::class);
    }
}

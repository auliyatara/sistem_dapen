<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class KepesertaanIuran extends Model
{
    use HasFactory;
    protected $primaryKey = 'kepesertaan_iuran_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->kepesertaan_iuran_id = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
        });
    }

    public function kepesertaan()
    {
        return $this->belongsTo(Kepesertaan::class);
    }

}

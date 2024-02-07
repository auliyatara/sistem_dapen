<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class ProgramKegiatan extends Model
{
    use HasFactory;

    protected $primaryKey = 'program_kegiatan_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'program_kegiatan_id',
        'program_kegiatan_program_id',
        'program_kegiatan_nama',
    ];

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->program_kegiatan_id = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
        });
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}

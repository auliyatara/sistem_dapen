<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class ProgramPelaksanaan extends Model
{
    use HasFactory;

    protected $primaryKey = 'program_pelaksanaan_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'program_pelaksanaan_id',
        'program_pelaksanaan_program_id',
        'program_pelaksanaan_nama',
    ];

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->program_pelaksanaan_id = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
        });
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}

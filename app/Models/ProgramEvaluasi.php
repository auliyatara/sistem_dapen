<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class ProgramEvaluasi extends Model
{
    use HasFactory;

    protected $primaryKey = 'program_evaluasi_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'program_evaluasi_id',
        'program_evaluasi_program_id',
        'program_evaluasi_nama',
    ];

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->program_evaluasi_id = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
        });
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class program extends Model
{
    use HasFactory,LogsActivity;

    protected $primaryKey = 'program_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'program_id',
        'program_nama',
        'program_type',
    ];

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->program_id = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
        });
    }

    public function program_kegiatan()
    {
        return $this->hasMany(ProgramKegiatan::class, 'program_kegiatan_program_id');
    }

    public function program_pelaksanaan()
    {
        return $this->hasMany(ProgramPelaksanaan::class, 'program_pelaksanaan_program_id');
    }

    public function program_evaluasi()
    {
        return $this->hasMany(ProgramEvaluasi::class, 'program_evaluasi_program_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} program")->logAll();
    }
}

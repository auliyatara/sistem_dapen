<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Kepesertaan extends Model
{
    use HasFactory,LogsActivity;

    protected $primaryKey = 'kepesertaan_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} kepesertaan")->logAll();
    }

    public function kepesertaan_dokumen()
    {
        return $this->hasMany(KepesertaanDokumen::class, 'kepesertaan_dokumen_kepesertaan_id');
    }
}

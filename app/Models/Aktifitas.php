<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Aktifitas extends Model
{
    use HasFactory,LogsActivity;

    protected $primaryKey = 'aktifitas_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} aktifitas")->logAll();
    }

    public function aktifitas_dokumen()
    {
        return $this->hasMany(AktifitasDokumen::class, 'aktifitas_dokumen_aktifitas_id');
    }
}

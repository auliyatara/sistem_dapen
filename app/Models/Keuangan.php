<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class Keuangan extends Model
{
    use HasFactory,LogsActivity;

    protected $primaryKey = 'keuangan_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} akuntansi dan keuangan")->logAll();
    }

    public function keuangan_detail()
    {
        return $this->hasMany(KeuanganDetail::class, 'keuangan_detail_keuangan_id');
    }
}

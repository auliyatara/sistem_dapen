<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Investasi extends Model
{
    use HasFactory,LogsActivity;

    protected $primaryKey = 'investasi_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} investasi")->logAll();
    }

    public function investasi_dokumen()
    {
        return $this->hasMany(InvestasiDokumen::class, 'investasi_dokumen_investasi_id');
    }

    public function investasi_nilai_wajar()
    {
        return $this->hasMany(InvestasiBulan::class, 'investasi_bulan_investasi_id');
    }

    public function investasi_subdata()
    {
        return $this->hasMany(InvestasiSubdata::class, 'investasi_subdata_investasi_id');
    }
}

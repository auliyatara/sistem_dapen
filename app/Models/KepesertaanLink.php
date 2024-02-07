<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class KepesertaanLink extends Model
{
    use HasFactory,LogsActivity;

    protected $primaryKey = 'kepesertaan_link_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kepesertaan_link_id',
        'kepesertaan_link_nama',
        'kepesertaan_link_url'
    ];

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->kepesertaan_link_id = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} link kepesertaan")->logAll();
    }

}

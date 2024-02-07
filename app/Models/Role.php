<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Role extends Model
{
    use HasFactory,LogsActivity;

    protected $primaryKey = 'role_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'role_id',
        'role_name',
        'role_status'
    ];

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->role_id = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
        });
    }

    public function role_access()
    {
        return $this->hasMany(RoleAccess::class, 'role_access_role_id');
    }

    public function user()
    {
        return $this->hasMany(User::class,'user_role_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} role")->logAll();
    }
}

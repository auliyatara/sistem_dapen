<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class RoleAccess extends Model
{
    use HasFactory;

    protected $primaryKey = 'role_access_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->role_access_id = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
        });
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class,'role_access_menu_id');
    }
}

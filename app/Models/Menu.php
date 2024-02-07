<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class Menu extends Model
{
    use HasFactory;

    protected $primaryKey = 'menu_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->menu_id = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
        });
    }

    public function user_access()
    {
        return $this->hasMany(UserAccess::class,'user_access_menu_id');
    }
}

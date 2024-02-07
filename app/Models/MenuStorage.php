<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuStorage extends Model
{
    use HasFactory;
    protected $primaryKey = 'menu_storage_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];
}

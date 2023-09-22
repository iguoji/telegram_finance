<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 可填充字段
     */
    protected $fillable = [
        'uid',
        'is_bot',
        'first_name',
        'last_name',
        'username',
        'language_code',
        'is_premium',
    ];
}

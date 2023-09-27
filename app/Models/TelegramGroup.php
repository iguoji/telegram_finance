<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramGroup extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * 可填充字段
     */
    public $fillable = [
        'id',
        'old_id',
        'new_id',
        
        'type',
        'title',
        'status',
        'operators',
        'inviter',
    ];
}

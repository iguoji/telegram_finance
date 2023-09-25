<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramRobot extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['user'];

    /**
     * 可填充字段
     */
    public $fillable = [
        'id',

        'token',
        
        'commands',
        'webhook',

        'private',
        'private_keyboard',
        'group_default',
        'group_operator',
        'group_administrator',

        'trial_duration',
    ];

    /**
     * 类型转换
     */
    protected $casts = [
        'commands'              =>  'array',
        'webhook'               =>  'array',

        'private'               =>  'array',
        'private_keyboard'      =>  'array',
        'group_default'         =>  'array',
        'group_operator'        =>  'array',
        'group_administrator'   =>  'array',
    ];

    /**
     * 所属用户
     */
    public function user()
    {
        return $this->belongsTo(TelegramUser::class, 'id', 'id');
    }
}

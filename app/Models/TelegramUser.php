<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramUser extends Model
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
        'status',
        'is_bot',
        'first_name',
        'last_name',
        'username',
        'token',
        'photo',
        'description',
        'short_description',
        'commands',
        'webhook',
        'language_code',
        'is_premium',
        'added_to_attachment_menu',
        'can_join_groups',
        'can_read_all_group_messages',
        'supports_inline_queries',
    ];

    /**
     * 类型转换
     */
    protected $casts = [
        'commands'  =>  'array',
        'webhook'   =>  'array',
    ];

    /**
     * 完整名称
     */
    protected function name() : Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => ($attributes['last_name'] ?? '') . $attributes['first_name'],
        );
    }
}

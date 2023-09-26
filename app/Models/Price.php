<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 可填充属性
     */
    public $fillable = [
        'telegram_robot_id',
        'type',
        'address',
        'label',
        'number',
        'callback',
        'year',
        'month',
        'day',
    ];

    /**
     * 所属机器人
     */
    public function robot()
    {
        return $this->belongsTo(TelegramRobot::class, 'telegram_robot_id', 'id');
    }

    /**
     * 获取时间
     */
    public function date() : Attribute
    {
        return new Attribute(
            get: function (mixed $value, array $attributes) {
                $str = '';
                if ($attributes['year'] >= 1) {
                    $str .= $attributes['year'] . '年';
                }
                if ($attributes['month'] >= 1) {
                    $str .= $attributes['month'] . '个月';
                }
                if ($attributes['day'] >= 1) {
                    $str .= $attributes['day'] . '天';
                }
                return $str;
            }
        );
    }
}

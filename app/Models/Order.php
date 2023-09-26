<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

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
    protected $with = ['price'];

    /**
     * 所属用户
     */
    public function user()
    {
        return $this->belongsTo(TelegramUser::class, 'user_id', 'id');
    }

    /**
     * 所属机器人
     */
    public function robot()
    {
        return $this->belongsTo(TelegramRobot::class, 'robot_id', 'id');
    }

    /**
     * 所属价格
     */
    public function price()
    {
        return $this->belongsTo(Price::class, 'price_id', 'id');
    }

    /**
     * 生成订单号码
     */
    public function generateId()
    {
        return date('YmdHis') . rand(100000, 999999);
    }

    /**
     * 订单状态
     */
    public function changeStatus(int $status, string $hash = null)
    {
        // 开始事务
        $that = $this;
        DB::transaction(function() use($that, $status, $hash){
            // 修改用户的时间
            if ($that->status == 2 && $status == 0) {
                // 原本是待支付，现在取消，无需要调整时间
            } else if ($that->status == 0 && $status == 2) {
                // 原本是失败，现在改成待支付，无需要调整时间
            } else {
                $start_at = $that->user->vip_at ? strtotime($that->user->vip_at) : time();
                $op = $status === 1 ? '+' : '-';
                $end_at = strtotime($op . ($that->price->year ?? 0) . ' year ' . $op . ($that->price->month ?? 0) . ' month ' . $op . ($that->price->day ?? 0) . ' days', $start_at);
                $that->user->vip_at = date('Y-m-d H:i:s', $end_at);
                $that->user->saveOrFail();
            }

            // 修改状态
            $that->status = $status;
            if (!is_null($hash)) {
                $that->hash = $hash;
            }
            $that->saveOrFail();

            // 更新用户缓存
            Cache::forever('telegram:user:' . $that->user->id, $that->user);
        });
    }
}

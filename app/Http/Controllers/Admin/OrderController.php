<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TelegramRobot;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * 订单列表
     */
    public function index(Request $request)
    {
        // 状态列表
        $statuses = [
            ['text' => '全部', 'value' => ''],
            ['text' => '成功', 'value' => '1'],
            ['text' => '待支付', 'value' => '2'],
            ['text' => '失败', 'value' => '0'],
        ];

        // 机器人列表
        $robots = TelegramRobot::orderBy('created_at')->get();

        // 查询对象
        $query = Order::with(['user', 'robot', 'price']);

        // 条件：按状态查询
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        // 条件：按订单查询
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }
        // 条件：按机器人查询
        if ($request->filled('robot_id')) {
            $query->where('robot_id', $request->robot_id);
        }
        // 条件：按用户查询
        if ($request->filled('user')) {
            $query->where(function($query2) use($request){
                $query2->whereRelation('user', 'username', $request->user)
                    ->orWhereRelation('user', 'first_name', $request->user);
            });
        }

        // 查询订单
        $orders = $query->orderByDesc('created_at')->paginate();

        // 返回结果
        return view('admin.order.index', [
            'orders'        =>  $orders,
            'statuses'      =>  $statuses,
            'robots'        =>  $robots,
        ]);
    }

    /**
     * 更改状态
     */
    public function changeStatus(Request $request, string $id, string $status)
    {
        // 查询订单
        $order = Order::where('id', $id)->firstOrFail();

        // 更改状态
        $order->changeStatus($status);

        // 返回结果
        return success();
    }
}

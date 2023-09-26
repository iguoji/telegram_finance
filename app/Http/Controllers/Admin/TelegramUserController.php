<?php

namespace App\Http\Controllers\Admin;

use App\Models\TelegramUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TelegramUserController extends Controller
{
    /**
     * 用户列表
     */
    public function index(Request $request)
    {
        // 状态列表
        $statuses = [
            ['text' => '全部', 'value' => ''],
            ['text' => '正常', 'value' => '1'],
            ['text' => '冻结', 'value' => '0'],
        ];

        // 查询对象
        $query = TelegramUser::where('is_bot', 0);

        // 条件：按状态查询
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        // 条件：按编号查询
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }
        // 条件：按用户查询
        if ($request->filled('user')) {
            $query->where(function($query2) use($request){
                $query2->where('username', $request->user)
                    ->orWhere('first_name', $request->user);
            });
        }

        // 查询数据
        $users = $query->orderByDesc('created_at')->paginate();

        // 返回结果
        return view('admin.telegram.user.index', [
            'users'         =>  $users,
            'statuses'      =>  $statuses,
        ]);
    }
}

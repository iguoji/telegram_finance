<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Robot\StoreRequest;
use App\Http\Requests\Admin\Robot\UpdateRequest;
use App\Models\Robot;
use Illuminate\Http\Request;
use App\Telegram\Robot as TelegramRobot;

class RobotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $robots = Robot::paginate();

        return view('admin.robot.index', [
            'robots'    =>  $robots
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        // 已经存在
        $robot = Robot::where('token', $request->token)->first();
        if (!empty($robot)) {
            return error('很抱歉、该机器人已经存在！');
        }

        // 查询机器人
        $telegramRobot = new TelegramRobot();
        $telegramRobot->token = $request->token;
        $data = $telegramRobot->getMe();

        // 机器人对象
        $robot = new Robot();
        $robot->status = 0;
        $robot->token = $request->token;
        $robot->rid = $data['id'];
        $robot->first_name = $data['first_name'];
        $robot->last_name = $data['last_name'] ?? null;
        $robot->username = $data['username'];
        $robot->can_join_groups = $data['can_join_groups'];
        $robot->can_read_all_group_messages = $data['can_read_all_group_messages'];
        $robot->supports_inline_queries = $data['supports_inline_queries'];
        $robot->saveOrFail();

        // 返回结果
        return success($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Refresh the specified resource.
     */
    public function refresh(string $id)
    {
        // 不存在
        $robot = Robot::where('rid', $id)->first();
        if (empty($robot)) {
            return error('很抱歉、该机器人不存在！');
        }

        // 查询机器人
        $telegramRobot = new TelegramRobot();
        $telegramRobot->token = $robot->token;
        $data = $telegramRobot->getMe();

        // 机器人对象
        $robot->first_name = $data['first_name'];
        $robot->last_name = $data['last_name'] ?? null;
        $robot->username = $data['username'];
        $robot->can_join_groups = $data['can_join_groups'];
        $robot->can_read_all_group_messages = $data['can_read_all_group_messages'];
        $robot->supports_inline_queries = $data['supports_inline_queries'];
        $robot->saveOrFail();

        // 返回结果
        return success($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        // 不存在
        $robot = Robot::where('rid', $id)->first();
        if (empty($robot)) {
            return error('很抱歉、该机器人不存在！');
        }

        // 状态
        if ($request->has('status')) {
            // 设置状态
            $robot->status = $request->status;

            // Hook
            $telegramRobot = new TelegramRobot();
            $telegramRobot->token = $robot->token;

            // 根据状态来调用接口
            if ($robot->status == 1 || $robot->status == '1') {
                // 启用
                $telegramRobot->setWebhook([
                    'url'           =>  route('telegram.hook'),
                    'secret_token'  =>  $robot->username,
                ]);
            } else {
                // 停用
                $telegramRobot->deleteWebhook([
                    'url'           =>  route('telegram.hook'),
                    'secret_token'  =>  $robot->username,
                ]);
            }
        }

        // 更新数据
        $robot->saveOrFail();

        // 返回结果
        return success();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // 不存在
        $robot = Robot::where('rid', $id)->first();
        if (empty($robot)) {
            return error('很抱歉、该机器人不存在！');
        }

        // 删除数据
        $robot->delete();

        // 返回结果
        return success();
    }
}

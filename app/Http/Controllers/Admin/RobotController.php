<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Robot\StoreRequest;
use App\Http\Requests\Admin\Robot\UpdateRequest;
use App\Models\TelegramUser;
use Illuminate\Http\Request;
use App\Telegram\Robot;

class RobotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = TelegramUser::where('is_bot', 1)->orderBy('created_at')->paginate();

        return view('admin.robot.index', [
            'users'    =>  $users
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
        $user = TelegramUser::where('token', $request->token)->first();
        if (!empty($user)) {
            return error('很抱歉、该机器人已经存在！');
        }

        // 查询机器人
        $robot = new Robot();
        $robot->token = $request->token;
        $me = $robot->getMe();

        // 查询聊天
        $chat = $robot->getChat([
            'chat_id'   =>  $me['id'],
        ]);

        // 查询头像
        if (!empty($chat['photo'])) {
            $photo = $robot->getFile([
                'file_id'   =>  $chat['photo']['big_file_id']
            ]);
            // var_dump('https://127.0.0.1:8081/file/bot' . $robot->token . '/' . $photo['file_path']);
        }

        // 获取描述
        $desc = $robot->getMyDescription();
        // 获取描述
        $sdesc = $robot->getMyShortDescription();
        // 获取命令
        $commands = $robot->getMyCommands();
        // 获取HOOK
        $webhook = $robot->getWebhookInfo();

        // 机器人对象
        $user = new TelegramUser($me);
        $user->status = 1;
        $user->token = $request->token;
        if (!empty($photo)) {
            $user->photo = $photo['file_path'];
        }
        $user->description = $desc['description'];
        $user->short_description = $sdesc['short_description'];
        $user->commands = $commands;
        $user->webhook = $webhook;
        $user->saveOrFail();

        // 返回结果
        return success($user);
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
        // $robot = Robot::where('rid', $id)->first();
        // if (empty($robot)) {
        //     return error('很抱歉、该机器人不存在！');
        // }

        // // 查询机器人
        // $telegramRobot = new TelegramRobot();
        // $telegramRobot->token = $robot->token;
        // $data = $telegramRobot->getMe();

        // // 机器人对象
        // $robot->first_name = $data['first_name'];
        // $robot->last_name = $data['last_name'] ?? null;
        // $robot->username = $data['username'];
        // $robot->can_join_groups = $data['can_join_groups'];
        // $robot->can_read_all_group_messages = $data['can_read_all_group_messages'];
        // $robot->supports_inline_queries = $data['supports_inline_queries'];
        // $robot->saveOrFail();

        // // 返回结果
        // return success($data);
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
        $user = TelegramUser::where('id', $id)->where('is_bot', 1)->first();
        if (empty($user)) {
            return error('很抱歉、该机器人不存在！');
        }

        // 状态
        // if ($request->has('status')) {
        //     // 设置状态
        //     $robot->status = $request->status;

        //     // Hook
        //     $telegramRobot = new TelegramRobot();
        //     $telegramRobot->token = $robot->token;

        //     // 根据状态来调用接口
        //     if ($robot->status == 1 || $robot->status == '1') {
        //         // 启用
        //         $telegramRobot->setWebhook([
        //             'url'           =>  route('telegram.hook'),
        //             'secret_token'  =>  $robot->username,
        //         ]);
        //     } else {
        //         // 停用
        //         $telegramRobot->deleteWebhook([
        //             'url'           =>  route('telegram.hook'),
        //             'secret_token'  =>  $robot->username,
        //         ]);
        //     }
        // }

        // 更新数据
        $user->status = $request->status;
        $user->saveOrFail();

        // 返回结果
        return success();
    }

    /**
     * 设置头像
     */
    public function setChatPhoto(Request $request, string $id)
    {
        // 验证参数
        $request->validate([
            'photo'      =>  ['required', 'image'],
        ]);

        // 不存在
        $user = TelegramUser::where('id', $id)->where('is_bot', 1)->first();
        if (empty($user)) {
            return error('很抱歉、该机器人不存在！');
        }

        // 调用接口
        $robot = new Robot($user->token);
        $robot->setChatPhoto([
            'chat_id'       =>  $id,
            'photo'         =>  $request->photo,
        ]);

        // 修改信息
        $user->photo = $request->photo->name;
        $user->saveOrFail();

        // 修改成功
        return success($user);
    }

    /**
     * 设置名称
     */
    public function setMyName(Request $request, string $id)
    {
        // 验证参数
        $request->validate([
            'name'      =>  ['required', 'max:64'],
        ]);

        // 不存在
        $user = TelegramUser::where('id', $id)->where('is_bot', 1)->first();
        if (empty($user)) {
            return error('很抱歉、该机器人不存在！');
        }

        // 调用接口
        $robot = new Robot($user->token);
        $robot->setMyName([
            'name'      =>  $request->name
        ]);

        // 修改信息
        $user->first_name = $request->name;
        $user->saveOrFail();

        // 修改成功
        return success($user);
    }

    /**
     * 设置描述
     */
    public function setMyDescription(Request $request, string $id)
    {
        // 验证参数
        $request->validate([
            'description'      =>  ['required', 'max:512'],
        ]);

        // 不存在
        $user = TelegramUser::where('id', $id)->where('is_bot', 1)->first();
        if (empty($user)) {
            return error('很抱歉、该机器人不存在！');
        }

        // 调用接口
        $robot = new Robot($user->token);
        $robot->setMyDescription([
            'description'      =>  $request->description
        ]);

        // 修改信息
        $user->description = $request->description;
        $user->saveOrFail();

        // 修改成功
        return success($user);
    }

    /**
     * 设置简单描述
     */
    public function setMyShortDescription(Request $request, string $id)
    {
        // 验证参数
        $request->validate([
            'short_description'      =>  ['required', 'max:120'],
        ]);

        // 不存在
        $user = TelegramUser::where('id', $id)->where('is_bot', 1)->first();
        if (empty($user)) {
            return error('很抱歉、该机器人不存在！');
        }

        // 调用接口
        $robot = new Robot($user->token);
        $robot->setMyShortDescription([
            'short_description'      =>  $request->short_description
        ]);

        // 修改信息
        $user->short_description = $request->short_description;
        $user->saveOrFail();

        // 修改成功
        return success($user);
    }

    /**
     * 查看指令
     */
    public function getMyCommands(Request $request, string $id)
    {
        // 不存在
        $user = TelegramUser::where('id', $id)->where('is_bot', 1)->first();
        if (empty($user)) {
            return error('很抱歉、该机器人不存在！');
        }

        // 调用接口
        $robot = new Robot($user->token);
        $res = $robot->getMyCommands();

        // 修改信息
        $user->commands = $res;
        $user->saveOrFail();

        // 修改成功
        return success($res);
    }

    /**
     * 设置指令
     */
    public function setMyCommands(Request $request, string $id)
    {
        // 验证参数
        $request->validate([
            'commands'      =>  ['required'],
        ]);
        // 不存在
        $user = TelegramUser::where('id', $id)->where('is_bot', 1)->first();
        if (empty($user)) {
            return error('很抱歉、该机器人不存在！');
        }

        // 调用接口
        $robot = new Robot($user->token);
        $res = $robot->setMyCommands([
            'commands'      =>  $request->commands,
        ]);

        // 修改成功
        return success($res);
    }

    /**
     * 删除指令
     */
    public function deleteMyCommands(Request $request, string $id)
    {
        // 不存在
        $user = TelegramUser::where('id', $id)->where('is_bot', 1)->first();
        if (empty($user)) {
            return error('很抱歉、该机器人不存在！');
        }

        // 调用接口
        $robot = new Robot($user->token);
        $res = $robot->deleteMyCommands();

        // 修改成功
        return success($res);
    }

    /**
     * 查看Webhook
     */
    public function getWebhookInfo(Request $request, string $id)
    {
        // 不存在
        $user = TelegramUser::where('id', $id)->where('is_bot', 1)->first();
        if (empty($user)) {
            return error('很抱歉、该机器人不存在！');
        }

        // 调用接口
        $robot = new Robot($user->token);
        $res = $robot->getWebhookInfo();

        // 修改信息
        $user->webhook = $res;
        $user->saveOrFail();

        // 修改成功
        return success($res);
    }

    /**
     * 设置Webhook
     */
    public function setWebhook(Request $request, string $id)
    {
        // 参数验证
        $request->validate([
            'url'       =>  ['required'],
        ]);

        // 不存在
        $user = TelegramUser::where('id', $id)->where('is_bot', 1)->first();
        if (empty($user)) {
            return error('很抱歉、该机器人不存在！');
        }

        // 调用接口
        $robot = new Robot($user->token);
        $res = $robot->setWebhook([
            'url'               =>  $request->url,
            'secret_token'      =>  $user->id . '___' . md5($user->token),
        ]);

        // 修改成功
        return success($res);
    }

    /**
     * 删除Webhook
     */
    public function deleteWebhook(Request $request, string $id)
    {
        // 不存在
        $user = TelegramUser::where('id', $id)->where('is_bot', 1)->first();
        if (empty($user)) {
            return error('很抱歉、该机器人不存在！');
        }

        // 调用接口
        $robot = new Robot($user->token);
        $res = $robot->deleteWebhook();

        // 修改成功
        return success($res);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // // 不存在
        // $robot = Robot::where('rid', $id)->first();
        // if (empty($robot)) {
        //     return error('很抱歉、该机器人不存在！');
        // }

        // // 删除数据
        // $robot->delete();

        // // 返回结果
        // return success();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Robot\StoreRequest;
use App\Http\Requests\Admin\Robot\UpdateRequest;
use App\Models\TelegramRobot;
use App\Models\TelegramUser;
use Illuminate\Http\Request;
use App\Telegram\Robot;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RobotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $robots = TelegramRobot::orderBy('created_at')->paginate();

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
        $robot = TelegramRobot::where('token', $request->token)->first();
        if (!empty($robot)) {
            return error('很抱歉、该机器人已经存在！');
        }

        // 查询机器人
        $robotApi = new Robot($request->token);
        $me = $robotApi->getMe();

        // 查询聊天
        $chat = $robotApi->getChat([
            'chat_id'   =>  $me['id'],
        ]);

        // 查询头像
        if (!empty($chat['photo'])) {
            // $photo = $robotApi->getFile([
            //     'file_id'   =>  $chat['photo']['big_file_id']
            // ]);
            // var_dump('https://127.0.0.1:8081/file/bot' . $robot->token . '/' . $photo['file_path']);
            // Storage::ur
        }

        // 获取描述
        $desc = $robotApi->getMyDescription();
        // 获取描述
        $sdesc = $robotApi->getMyShortDescription();
        // 获取命令
        $commands = $robotApi->getMyCommands();
        // 获取HOOK
        $webhook = $robotApi->getWebhookInfo();

        // 事务处理
        DB::transaction(function() use($me, $desc, $sdesc, $request, $commands, $webhook){
            // 用户对象
            $user = new TelegramUser($me);
            $user->description = $desc['description'];
            $user->short_description = $sdesc['short_description'];
            $user->saveOrFail();

            // 机器人对象
            $robot = new TelegramRobot();
            $robot->id = $me['id'];
            $robot->token = $request->token;
            $robot->commands = $commands;
            $robot->webhook = $webhook;
            $robot->saveOrFail();

            // 更新缓存
            Cache::put('telegram:robot:' . $me['id'], $robot);
        });


        // 返回结果
        return success();
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
        // 查询机器人
        $robot = TelegramRobot::where('id', $id)->firstOrFail();

        // 更新数据
        if ($request->has('status')) {
            // 更新字段
            $robot->user->status = $request->status;
            // 保存到数据库
            $robot->user->saveOrFail();
        } else {
            if ($request->has('field')) {
                // 如果是键盘
                if ($request->field == 'private_keyboard') {
                    // 原始数据
                    $callbacks = trim($request->input('callbacks', ''));
                    // 按行分割
                    $rows = explode("\n", $callbacks);
                    // 循环整理
                    $keyboard = [];
                    foreach ($rows as $row) {
                        // 单个切分
                        $cols = explode('###___###', $row);
                        $keyboardRow = [];
                        foreach ($cols as $col) {
                            $col = trim($col);
                            if (strlen($col)) {
                                $keyboardRow[] = ['text' => $col];
                            }
                        }
                        if (!empty($keyboardRow)) {
                            $keyboard[] = $keyboardRow;
                        }
                    }
                    // 保存到模型
                    $robot->private_keyboard = $keyboard;
                } else {
                    // 保存到模型
                    $robot[$request->field] = $request->input('callbacks', []);
                }
            } else {
                // 所有提交都保存
                $robot->fill($request->all());
            }

            // 保存到数据库
            $robot->saveOrFail();
        }

        // 更新缓存
        Cache::put('telegram:robot:' . $id, $robot);

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

        // 查询机器人
        $robot = TelegramRobot::where('id', $id)->firstOrFail();

        // 调用接口
        $robotApi = new Robot($robot->token);
        $robotApi->setChatPhoto([
            'chat_id'       =>  $id,
            'photo'         =>  $request->photo,
        ]);

        // 修改信息
        $robot->user->photo = $request->photo->name;
        $robot->user->saveOrFail();

        // 修改成功
        return success();
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

        // 查询机器人
        $robot = TelegramRobot::where('id', $id)->firstOrFail();

        // 调用接口
        $robotApi = new Robot($robot->token);
        $robotApi->setMyName([
            'name'      =>  $request->name
        ]);

        // 修改信息
        $robot->user->first_name = $request->name;
        $robot->user->saveOrFail();

        // 更新缓存
        Cache::put('telegram:robot:' . $id, $robot);

        // 修改成功
        return success();
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

        // 查询机器人
        $robot = TelegramRobot::where('id', $id)->firstOrFail();

        // 调用接口
        $robotApi = new Robot($robot->token);
        $robotApi->setMyDescription([
            'description'      =>  $request->description
        ]);

        // 修改信息
        $robot->user->description = $request->description;
        $robot->user->saveOrFail();

        // 更新缓存
        Cache::put('telegram:robot:' . $id, $robot);

        // 修改成功
        return success();
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

        // 查询机器人
        $robot = TelegramRobot::where('id', $id)->firstOrFail();

        // 调用接口
        $robotApi = new Robot($robot->token);
        $robotApi->setMyShortDescription([
            'short_description'      =>  $request->short_description
        ]);

        // 修改信息
        $robot->user->short_description = $request->short_description;
        $robot->user->saveOrFail();

        // 更新缓存
        Cache::put('telegram:robot:' . $id, $robot);

        // 修改成功
        return success();
    }

    /**
     * 查看指令
     */
    public function getMyCommands(Request $request, string $id)
    {
        // 查询机器人
        $robot = TelegramRobot::where('id', $id)->firstOrFail();

        // 调用接口
        $robotApi = new Robot($robot->token);
        $res = $robotApi->getMyCommands();

        // 修改信息
        $robot->commands = $res;
        $robot->saveOrFail();

        // 更新缓存
        Cache::put('telegram:robot:' . $id, $robot);

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

        // 查询机器人
        $robot = TelegramRobot::where('id', $id)->firstOrFail();

        // 调用接口
        $robotApi = new Robot($robot->token);
        $res = $robotApi->setMyCommands([
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
        // 查询机器人
        $robot = TelegramRobot::where('id', $id)->firstOrFail();

        // 调用接口
        $robotApi = new Robot($robot->token);
        $res = $robotApi->deleteMyCommands();

        // 修改成功
        return success($res);
    }

    /**
     * 查看Webhook
     */
    public function getWebhookInfo(Request $request, string $id)
    {
        // 查询机器人
        $robot = TelegramRobot::where('id', $id)->firstOrFail();

        // 调用接口
        $robotApi = new Robot($robot->token);
        $res = $robotApi->getWebhookInfo();

        // 修改信息
        $robot->webhook = $res;
        $robot->saveOrFail();

        // 更新缓存
        Cache::put('telegram:robot:' . $id, $robot);

        // 修改成功
        return success($res);
    }

    /**
     * 设置Webhook
     */
    public function setWebhook(Request $request, string $id)
    {
        // 查询机器人
        $robot = TelegramRobot::where('id', $id)->firstOrFail();

        // 调用接口
        $url = $request->input('url', ' ');
        $robotApi = new Robot($robot->token);
        if (empty($url)) {
            $res = $robotApi->setWebhook([
                'url'               =>  '',
            ]);
        } else {
            $res = $robotApi->setWebhook([
                'url'               =>  $url,
                'secret_token'      =>  $robot->id . '___' . md5($robot->token),
            ]);
        }

        // 修改成功
        return success($res);
    }

    /**
     * 删除Webhook
     */
    public function deleteWebhook(Request $request, string $id)
    {
        // 查询机器人
        $robot = TelegramRobot::where('id', $id)->firstOrFail();

        // 调用接口
        $robotApi = new Robot($robot->token);
        $res = $robotApi->deleteWebhook();

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

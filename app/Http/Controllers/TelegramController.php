<?php

namespace App\Http\Controllers;

use App\Models\TelegramRobot;
use App\Models\TelegramUser;
use App\Telegram\Robot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class TelegramController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // 获取密钥
        $secretToken = $request->header('X-Telegram-Bot-Api-Secret-Token', '');
        abort_if(empty($secretToken), 500, 'invalid secret -1');

        // 从密钥中解析账号
        $arr = explode('___', $secretToken);
        abort_if(empty($arr) || count($arr) !== 2, 500, 'invalid secret -2');
        list($id, $hash) = $arr;

        // 查询机器人
        $robot = Cache::rememberForever('telegram:robot:' . $id, function() use($id){
            return TelegramRobot::with(['user'])->find($id);
        });

        // 如果不存在该机器人
        abort_if(empty($robot) || empty($robot['token']), 500, 'invalid robot -4');

        // 密钥不对
        abort_if($hash != md5($robot['token']), 500, 'invalid robot -5');

        // 状态不对
        abort_if(empty($robot->user['status']), 500, 'invalid robot -6');

        // 返回结果
        return (new Robot($robot->token, $robot->user->username, $robot))->handle(json_decode($request->getContent(), true),);
    }
}

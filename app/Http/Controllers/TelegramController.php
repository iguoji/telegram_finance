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
        // 分析机器人
        $secretToken = $request->header('X-Telegram-Bot-Api-Secret-Token', '');
        if (empty($secretToken)) {
            return '-1';
        }
        $arr = explode('___', $secretToken);
        if (empty($arr) || count($arr) !== 2) {
            return '-2';
        }
        list($id, $hash) = $arr;

        // 查询机器人
        $robot = Cache::rememberForever('telegram:robot:' . $id, function() use($id){
            return TelegramRobot::find($id);
        });
        if (empty($robot) || empty($robot['token'])) {
            return '-3';
        }
        if ($hash != md5($robot['token'])) {
            return '-4';
        }

        // 返回结果
        return (new Robot($robot->token, $robot->user->username, $robot))->handle(json_decode($request->getContent(), true),);
    }
}

<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Longman\TelegramBot\Telegram;

class HookController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // 获取配置
        $config = config('telegram.bot');
        // 实例对象
        $telegram = new Telegram($config['api_token'], $config['username']);
        // 命令注入
        $telegram->addCommandsPath(app_path('Commands'));
        // 数据注入
        $telegram->setCustomInput($request->getContent());
        // 数据判断
        if ($telegram->handle()) {

        }
    }
}

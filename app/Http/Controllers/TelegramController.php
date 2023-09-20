<?php

namespace App\Http\Controllers;

use App\Telegram\Robot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;


class TelegramController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // 分析路由
        $routeName = Route::currentRouteName();
        $bot = explode('.', $routeName)[2];

        // 机器人处理
        return (new Robot($bot))
            ->handle(
                $request->header('X-Telegram-Bot-Api-Secret-Token', ''), 
                json_decode($request->getContent(), true),
            );
    }
}

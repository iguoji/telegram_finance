<?php

use App\Http\Controllers\TelegramController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



// 循环注册
$bots = config('telegram.bots');
foreach ($bots as $key => $bot) {
    // 消息句柄
    Route::any('telegram/hook/' . $key, TelegramController::class)->name('telegram.hook.' . $key);
}

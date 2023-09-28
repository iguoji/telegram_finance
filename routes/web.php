<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/**
 * 群组
 */
Route::prefix('group')->name('group.')->controller(\App\Http\Controllers\GroupController::class)->group(function(){
    Route::get('/{group}/bill', 'bill')->name('bill');
    Route::get('/{group}/export/{date?}', 'export')->name('export');
});

/**
 * 管理员
 */
Route::prefix('admin')->name('admin.')->group(function(){
    // 后台首页
    Route::get('/', [App\Http\Controllers\Admin\IndexController::class, 'index'])->name('index');
    Route::get('/index', [App\Http\Controllers\Admin\IndexController::class, 'index'])->name('index');

    // 登录退出
    Route::get('/login', [App\Http\Controllers\Admin\IndexController::class, 'index'])->name('login');
    Route::get('/logout', [App\Http\Controllers\Admin\IndexController::class, 'index'])->name('logout');

    // 订单
    Route::prefix('telegram/user')->name('telegram.user.')->controller(App\Http\Controllers\Admin\TelegramUserController::class)->group(function(){
        // 列表
        Route::get('/', 'index')->name('index');
    });

    // 订单
    Route::prefix('order')->name('order.')->controller(App\Http\Controllers\Admin\OrderController::class)->group(function(){
        // 列表
        Route::get('/', 'index')->name('index');
        // 状态
        Route::post('/{order}/status/{status}', 'changeStatus')->name('changeStatus');
    });

    // 机器人
    Route::resource('robot', App\Http\Controllers\Admin\RobotController::class);
    Route::post('/robot/refresh/{robot}', [App\Http\Controllers\Admin\RobotController::class, 'refresh'])->name('robot.refresh');
    Route::prefix('robot')->name('robot.')->controller(App\Http\Controllers\Admin\RobotController::class)->group(function(){
        // 基本信息
        Route::post('/{robot}/setChatPhoto', 'setChatPhoto')->name('setChatPhoto');
        Route::post('/{robot}/setMyName', 'setMyName')->name('setMyName');
        Route::post('/{robot}/setMyDescription', 'setMyDescription')->name('setMyDescription');
        Route::post('/{robot}/setMyShortDescription', 'setMyShortDescription')->name('setMyShortDescription');

        // Webhook
        Route::post('/{robot}/setWebhook', 'setWebhook')->name('setWebhook');
        Route::delete('/{robot}/deleteWebhook', 'deleteWebhook')->name('deleteWebhook');
        Route::post('/{robot}/getWebhookInfo', 'getWebhookInfo')->name('getWebhookInfo');

        // Commands
        Route::post('/{robot}/setMyCommands', 'setMyCommands')->name('setMyCommands');
        Route::delete('/{robot}/deleteMyCommands', 'deleteMyCommands')->name('deleteMyCommands');
        Route::post('/{robot}/getMyCommands', 'getMyCommands')->name('getMyCommands');
    });

    // 定价
    Route::apiResource('robot.price', App\Http\Controllers\Admin\PriceController::class);

    /**
     * 基础权限
     */
    Route::prefix('bac')->name('bac.')->group(function(){
        // 管理员
        Route::resource('admin', App\Http\Controllers\Admin\IndexController::class);
        // 更新权限
        Route::put('permissions', [App\Http\Controllers\Admin\IndexController::class, 'index'])->name('permissions');
        // 操作日志
        Route::get('logs', [App\Http\Controllers\Admin\IndexController::class, 'index'])->name('logs');
    });
});

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
 * 管理员
 */
Route::prefix('admin')->name('admin.')->group(function(){
    // 后台首页
    Route::get('/', [App\Http\Controllers\Admin\IndexController::class, 'index'])->name('index');
    Route::get('/index', [App\Http\Controllers\Admin\IndexController::class, 'index'])->name('index');

    // 登录退出
    Route::get('/login', [App\Http\Controllers\Admin\IndexController::class, 'index'])->name('login');
    Route::get('/logout', [App\Http\Controllers\Admin\IndexController::class, 'index'])->name('logout');

    // 机器人
    Route::resource('robot', App\Http\Controllers\Admin\RobotController::class);
    Route::post('/robot/refresh/{id}', [App\Http\Controllers\Admin\RobotController::class, 'refresh'])->name('robot.refresh');
    Route::prefix('robot')->name('robot.')->controller(App\Http\Controllers\Admin\RobotController::class)->group(function(){
        Route::post('/{id}/setChatPhoto', 'setChatPhoto')->name('setChatPhoto');
        Route::post('/{id}/setMyName', 'setMyName')->name('setMyName');
        Route::post('/{id}/setMyDescription', 'setMyDescription')->name('setMyDescription');
        Route::post('/{id}/setMyShortDescription', 'setMyShortDescription')->name('setMyShortDescription');

        Route::post('/{id}/setWebhook', 'setWebhook')->name('setWebhook');
        Route::delete('/{id}/deleteWebhook', 'deleteWebhook')->name('deleteWebhook');
        Route::post('/{id}/getWebhookInfo', 'getWebhookInfo')->name('getWebhookInfo');

        Route::post('/{id}/setMyCommands', 'setMyCommands')->name('setMyCommands');
        Route::delete('/{id}/deleteMyCommands', 'deleteMyCommands')->name('deleteMyCommands');
        Route::post('/{id}/getMyCommands', 'getMyCommands')->name('getMyCommands');
    });

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

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

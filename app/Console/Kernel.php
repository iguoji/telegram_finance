<?php

namespace App\Console;

use App\Jobs\CheckOrder;
use App\Jobs\PullTrc20;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // 检查订单
        $schedule->job(new CheckOrder)->everyMinute();
        // 拉取Trc20交易记录
        $schedule->job(new PullTrc20)->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

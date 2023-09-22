<?php

namespace App\Jobs;

use App\Telegram\Robot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Robot $robot, protected array $content = [])
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->robot->call('sendMessage', $this->content);
    }
}

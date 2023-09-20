<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $arr = [
        //     'a' => 1,
        //     'b' => [
        //         'c' => 2,
        //     ],
        // ];

        // var_dump($arr['b']['d'] ?? 'none');


        $text = 'abc';
        list($command, $argument) = explode(' ', $text);
        var_dump([$command, $argument]);


        $this->info('1');
    }
}

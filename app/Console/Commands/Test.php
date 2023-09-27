<?php

namespace App\Console\Commands;

use App\Telegram\Robot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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
        $trc20 = 'TY8jNABVoReroGTuPhyb9RYhfNtyB3bb1p';
        $tronKey = 'f4c13a8b-5ee5-47cd-8f86-379f208ae9d7';
        $startTime = '';


        $robot = new Robot(config('telegram.bots.zidongjizhang_bot.token'), 'zidongjizhang_bot');
        $res = $robot->getChat([
            'chat_id'       =>  -1001963102447
        ]);
        var_dump($res);
        
        // /*$res = Http::timeout(3)->retry(3, 1000)
        //         ->withHeader('TRON-PRO-API-KEY', $tronKey)
        //         ->withUrlParameters([
        //             'start'                     =>  0,
        //             'limit'                     =>  2,
        //             'contract_address'          =>  'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t',
        //             'start_timestamp'           =>  $startTime,
        //             'end_timestamp'             =>  '',
        //             'confirm'                   =>  1,
        //             'relatedAddress'            =>  '',
        //             'fromAddress'               =>  '',
        //             'toAddress'                 =>  $trc20,
        //             'filterTokenValue'          =>  1,
        //         ])
        //         ->get('https://apilist.tronscanapi.com/api/token_trc20/transfers');*/
        
        //     $res = Http::timeout(3)->retry(3, 1000)
        //         ->withHeader('TRON-PRO-API-KEY', $tronKey)
        //         // ->withUrlParameters([
        //         //     'start'                     =>  0,
        //         //     'limit'                     =>  2,
        //         //     'contract_address'          =>  'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t',
        //         //     'start_timestamp'           =>  $startTime,
        //         //     'end_timestamp'             =>  '',
        //         //     'confirm'                   =>  1,
        //         //     'relatedAddress'            =>  '',
        //         //     'fromAddress'               =>  '',
        //         //     'toAddress'                 =>  $trc20,
        //         //     'filterTokenValue'          =>  1,
        //         // ])
        //         ->get('https://apilist.tronscanapi.com/api/token_trc20/transfers?limit=2&contract_address=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t&start_timestamp=' . $startTime . '&confirm=&toAddress=' . $trc20 . '&filterTokenValue=1');

        // var_dump($res->status(), $res->json());


        // $trial_at = '';
        // $year = 0;
        // $month = 1;
        // $day = 0;


        // $start_at = $trial_at ? strtotime($trial_at) : time();
        // $end_at = strtotime($format = "+$year year +$month month +$day days", $start_at);



        // $this->info('用户时间：' . $trial_at);
        // $this->info('格式化:' . $format);
        // $this->info('开始时间：' . date('Y-m-d H:i:s', $start_at));
        // $this->info('结束时间：' . date('Y-m-d H:i:s', $end_at));

        // $number = 1000;

        // $rand = rand(1, 999);

        // var_dump($number + $rand / 1000);

        // $arr = [
        //     'a' => 1,
        //     'b' => [
        //         'c' => 2,
        //     ],
        // ];

        // var_dump($arr['b']['d'] ?? 'none');


        // $text = 'abc';
        // list($command, $argument) = explode(' ', $text);
        // var_dump([$command, $argument]);


        // $this->info('1');
    }
}

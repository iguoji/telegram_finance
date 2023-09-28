<?php

namespace App\Jobs;

use App\Models\Bill;
use App\Models\BillDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class PushBill implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected int $group_id, protected string $date, protected int $type, protected float $number, protected array $from = [])
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $group_id = $this->group_id;
        $date = $this->date;
        $type = $this->type;
        $number = $this->number;
        $from = $this->from;

        DB::transaction(function() use($group_id, $date, $type, $number, $from){
            // 今日账单
            $bill = Bill::where('date', $date)->where('group_id', $group_id)->first();
            if (empty($bill)) {
                $bill = new Bill();
                $bill->group_id = $group_id;
                $bill->date = $date;
                $bill->rate = 100;
                $bill->in = $type == 1 ? $number : 0;
                $bill->out = $type == 2 ? $number : 0;
            } else {
                if ($type == 1) {
                    $bill->in += $number;
                } else {
                    $bill->out += $number;
                }
            }
            $bill->saveOrFail();

            // 流水明细
            $billDetail = new BillDetail();
            $billDetail->bill_id = $bill->id;
            $billDetail->group_id = $bill->group_id;
            $billDetail->type = $type;
            $billDetail->user_id = $from['id'];
            $billDetail->username = $from['username'];
            $billDetail->first_name = $from['first_name'];
            $billDetail->last_name = $from['last_name'] ?? null;
            $billDetail->money = $number;
            $billDetail->saveOrFail();
        });
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $robot_id)
    {
        $prices = Price::where('telegram_robot_id', $robot_id)->orderBy('id')->get();

        return success($prices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $robot_id)
    {
        // 验证参数
        $request->validate([
            'data'      =>  ['required', 'array'],
        ]);

        // 整理数据
        $prices = [];
        foreach ($request->data as $key => $value) {
            if (!empty($value['address']) && !empty($value['label'])) {
                $price = new Price($value);
                $price->telegram_robot_id = $robot_id;
                $prices[] = $price;
            }
        }

        if (!empty($prices)) {
            // 事务处理
            DB::transaction(function() use($prices, $robot_id){
                // 先删除之前的
                Price::where('telegram_robot_id', $robot_id)->delete();
    
                // 再循环添加新的
                foreach ($prices as $price) {
                    $price->saveOrFail();
                }

                // 更新缓存
                Cache::forever('telegram:robot:' . $robot_id . ':prices', $prices);
            });
        }
        
        // 返回结果
        return success();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

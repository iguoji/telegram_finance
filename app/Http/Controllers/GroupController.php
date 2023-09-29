<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class GroupController extends Controller
{
    /**
     * 流水账单
     */
    public function bill(Request $request, string $group_id)
    {
        // 可供查询日期
        $dates = [];
        $dates[] = date('Y-m-d');
        $dates[] = date('Y-m-d', strtotime('-1 days', time()));
        $dates[] = date('Y-m-d', strtotime('-2 days', time()));

        // 具体日期
        $date = $request->input('date', date('Y-m-d'));

        // 查询账单
        $bill = Bill::with(['details'])->where('group_id', $group_id)->where('date', $date)->firstOrFail();

        // 统计数据
        $in = 0;
        $in_count = 0;
        $out = 0;
        $out_count = 0;
        foreach ($bill->details ?? [] as $item) {
            if ($item->type == 1) {
                $in += $item->money;
                $in_count++;
            } else {
                $out += $item->money;
                $out_count++;
            }
        }

        // 返回结果
        return view('group.bill', [
            'bill'      =>  $bill,
            'in'        =>  $in,
            'in_count'  =>  $in_count,
            'out'       =>  $out,
            'out_count' =>  $out_count,
            'dates'     =>  $dates,
        ]);
    }

    /**
     * 导出账单
     */
    public function export(Request $request, string $group_id, string $date = null)
    {
        // 具体日期
        $date = $date ?? date('Y-m-d');

        // 查询账单
        $bill = Bill::with(['details'])->where('group_id', $group_id)->where('date', $date)->firstOrFail();

        // 工作表
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);

        // 统计数据
        $in = 0;
        $in_count = 0;
        $out = 0;
        $out_count = 0;

        // 当前行号
        $row = 2;

        // 入款
        $sheet->getCellByColumnAndRow(1, $row)->setValue('时间');
        $sheet->getCellByColumnAndRow(2, $row)->setValue('金额');
        $sheet->getCellByColumnAndRow(3, $row)->setValue('操作人');
        $sheet->getCellByColumnAndRow(4, $row++)->setValue('回复人');
        foreach ($bill->details ?? [] as $item) {
            if ($item->type == 1) {
                $in += $item->money;
                $in_count++;

                $sheet->getCellByColumnAndRow(1, $row)->setValueExplicit($item->created_at, DataType::TYPE_STRING);
                $sheet->getCellByColumnAndRow(2, $row)->setValue((float) number_format($item->money, 4, '.', ''));
                $sheet->getCellByColumnAndRow(3, $row)->setValue($item->first_name . ($item->last_name ?? ''));
                $sheet->getCellByColumnAndRow(4, $row++)->setValue($item->reply_first_name . ($item->reply_last_name ?? ''));
            }
        }
        $sheet->getCellByColumnAndRow(1, 1)->setValue('入款 共 ' . $in_count . ' 笔');
        $row++;
        $lastRow = $row;
        $row++;

        // 下发
        $sheet->getCellByColumnAndRow(1, $row)->setValue('时间');
        $sheet->getCellByColumnAndRow(2, $row)->setValue('金额');
        $sheet->getCellByColumnAndRow(3, $row)->setValue('操作人');
        $sheet->getCellByColumnAndRow(4, $row++)->setValue('回复人');
        foreach ($bill->details ?? [] as $item) {
            if ($item->type == 2) {
                $out += $item->money;
                $out_count++;

                $sheet->getCellByColumnAndRow(1, $row)->setValueExplicit($item->created_at, DataType::TYPE_STRING);
                $sheet->getCellByColumnAndRow(2, $row)->setValue((float) number_format($item->money, 4, '.', ''));
                $sheet->getCellByColumnAndRow(3, $row)->setValue($item->first_name . ($item->last_name ?? ''));
                $sheet->getCellByColumnAndRow(4, $row++)->setValue($item->reply_first_name . ($item->reply_last_name ?? ''));
            }
        }
        $sheet->getCellByColumnAndRow(1, $lastRow)->setValue('下发 共 ' . $out_count . ' 笔');
        $row++;

        // 总入款：
        $sheet->getCellByColumnAndRow(1, $row)->setValue('总入款：');
        $sheet->getCellByColumnAndRow(2, $row++)->setValue($in);

        // 费率：
        $sheet->getCellByColumnAndRow(1, $row)->setValue('费率：');
        $sheet->getCellByColumnAndRow(2, $row++)->setValue($bill->rate . '%');

        // 应下发：
        $sheet->getCellByColumnAndRow(1, $row)->setValue('应下发：');
        $sheet->getCellByColumnAndRow(2, $row++)->setValue($in * ($bill->rate / 100));

        // 总下发：
        $sheet->getCellByColumnAndRow(1, $row)->setValue('总下发：');
        $sheet->getCellByColumnAndRow(2, $row++)->setValue($out);

        // 未下发：
        $sheet->getCellByColumnAndRow(1, $row)->setValue('未下发：');
        $sheet->getCellByColumnAndRow(2, $row++)->setValue($in * ($bill->rate / 100) - $out);

        // Excel文件
        $writer = new Xlsx($spreadsheet);
        $dir = '/exports/' . $bill->group_id . '/';
        if (!is_dir(storage_path('/app/public' . $dir))) {
            mkdir(storage_path('/app/public' . $dir), 0777, true);
        }
        $name = $dir . date('Y-m-d') . '.xlsx';
        $writer->save(Storage::disk('public')->path($name));

        // 返回结果
        return Storage::disk('public')->download($name);
    }
}

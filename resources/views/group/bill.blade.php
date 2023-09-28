<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>完整账单</title>
    <link href="/assets/css/tabler.min.css?1668287865" rel="stylesheet" />
</head>

<body>
<div class="page">
    <div class="page-wrapper">
        <div class="page-body">
            <div class="container-xl">
                <div class="d-flex mb-4">
                    <select class="form-select" style="width: 200px;" onchange="changeDate(this)">
                        @foreach ($dates as $date)
                            <option value="{{ $date }}" @selected($date==Request::input('date'))>{{ $date }}</option>
                        @endforeach
                    </select>
                    <a href="{{ route('group.export', [$bill->group_id, Request::input('date')]) }}" target="_blank" class="btn btn-primary ms-4">导出Excel</a>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <div class="card-title">入款</div>
                        <div class="card-subtitle ms-3">共 {{ $in_count }} 笔</div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-hover">
                            <thead>
                                <tr>
                                    <th class="w-25">时间</th>
                                    <th class="w-25">金额</th>
                                    <th class="w-25">操作人</th>
                                    <th class="w-25">回复人</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bill->details ?? [] as $item)
                                    @if ($item->type == 1)
                                        <tr>
                                            <td>{{ $item->created_at }}</td>
                                            <td>{{ (float) number_format($item->money, 4, '.', '') }}</td>
                                            <td>{{ $item->first_name . ($item->last_name ?? '') }}</td>
                                            <td></td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <div class="card-title">下发</div>
                        <div class="card-subtitle ms-3">共 {{ $out_count }} 笔</div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-hover">
                            <thead>
                                <tr>
                                    <th class="w-25">时间</th>
                                    <th class="w-25">金额</th>
                                    <th class="w-25">操作人</th>
                                    <th class="w-25">回复人</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bill->details ?? [] as $item)
                                    @if ($item->type == 2)
                                        <tr>
                                            <td>{{ $item->created_at }}</td>
                                            <td>{{ (float) number_format($item->money, 4, '.', '') }}</td>
                                            <td>{{ $item->first_name . ($item->last_name ?? '') }}</td>
                                            <td></td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">总计</div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-hover">
                            <tbody>
                                <tr>
                                    <td class="w-25">总入款：</td>
                                    <td class="w-75">{{ $in }}</td>
                                </tr>
                                <tr>
                                    <td class="w-25">费率：</td>
                                    <td class="w-75">{{ $bill->rate }}%</td>
                                </tr>
                                <tr>
                                    <td class="w-25">应下发：</td>
                                    <td class="w-75">{{ $in * $bill->rate }}</td>
                                </tr>
                                <tr>
                                    <td class="w-25">总下发：</td>
                                    <td class="w-75">{{ $out }}</td>
                                </tr>
                                <tr>
                                    <td class="w-25">未下发：</td>
                                    <td class="w-75">{{ $in * $bill->rate - $out }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeDate(ele) {
    window.location.href = "?date=" + ele.value;
}
</script>
</body>
</html>

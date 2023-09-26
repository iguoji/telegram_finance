<x-layouts.admin title="订单列表">
<div class="card mb-3">
    <form action="" method="GET">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3">
                    <div class="mb-3">
                        <label class="form-label">订单</label>
                        <input type="text" class="form-control" name="id" value="{{ Request::query('id') }}" placeholder="订单号码" />
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="mb-3">
                        <label class="form-label">状态</label>
                        <select name="status" class="form-select">
                            @foreach ($statuses as $status)
                                @if (Request::filled('status') && $status['value'] == Request::query('status'))
                                    <option selected value="{{ $status['value'] }}">{{ $status['text'] }}</option>
                                @else
                                    <option value="{{ $status['value'] }}">{{ $status['text'] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="mb-3">
                        <label class="form-label">机器人</label>
                        <select name="robot_id" class="form-select">
                            <option value="">全部</option>
                            @foreach ($robots as $robot)
                                @if (Request::filled('robot_id') && $robot->id == Request::query('robot_id'))
                                    <option selected value="{{ $robot->id }}">{{ $robot->user->name }}</option>
                                @else
                                    <option value="{{ $robot->id }}">{{ $robot->user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="mb-3">
                        <label class="form-label">用户</label>
                        <input type="text" class="form-control" name="user" placeholder="用户名或昵称" value="{{ Request::query('user') }}" />
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary">筛选</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter table-striped table-hover">
            <thead>
                <tr>
                    <th>订单号码</th>
                    <th>机器人</th>
                    <th>用户</th>
                    <th>定价</th>
                    <th>金额</th>
                    <th>到期时间</th>
                    <th>下单时间</th>
                    <th>到账时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $item)
                    <tr>
                        <td>
                            <div>
                                @if ($item->status == 1)
                                    <span class="badge bg-success me-1">成功</span>
                                @elseif ($item->status == 2)
                                    <span class="badge bg-warning me-1">待支付</span>
                                @else
                                    <span class="badge bg-secondary me-1">失败</span>
                                @endif
                            </div>
                            <div>{{ $item->id }}</div>
                        </td>
                        <td>
                            <div>{{ $item->robot->user->name }}</div>
                            <div class="small text-muted">{{ $item->robot->user->username }}</div>
                        </td>
                        <td>
                            <div>{{ $item->user->name }}</div>
                            <div class="small text-muted">{{ $item->user->username }}</div>
                        </td>
                        <td>
                            <div>{{ $item->price->number }}</div>
                            <div class="small text-muted">{{ $item->price->date }}</div>
                        </td>
                        <td>{{ $item->money }}</td>
                        <td><div class="small">{{ $item->user_trial_at }}</div></td>
                        <td><div class="small">{{ $item->created_at }}</div></td>
                        <td>
                            <div class="small">{{ $item->payment_at }}</div>
                            <div class="small">{{ $item->hash }}</div>
                        </td>
                        <td>
                            <a href="#" class="btn btn-sm">检查</a>
                            @if ($item->status != 1)
                                <a href="#" class="btn btn-sm btn-success" data-bs-toggle="fast-ajax" data-bs-target="{{ route('admin.order.changeStatus', [$item->id, 1]) }}">成功</a>
                            @endif
                            @if ($item->status != 2)
                                <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="fast-ajax" data-bs-target="{{ route('admin.order.changeStatus', [$item->id, 2]) }}">待支付</a>
                            @endif
                            @if ($item->status != 0)
                                <a href="#" class="btn btn-sm btn-secondary" data-bs-toggle="fast-ajax" data-bs-target="{{ route('admin.order.changeStatus', [$item->id, 0]) }}">失败</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="20">{{ $orders->links() }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
</x-layouts.admin>
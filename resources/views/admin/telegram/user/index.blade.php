<x-layouts.admin title="用户列表">
<div class="card mb-3">
    <form action="" method="GET">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3">
                    <div class="mb-3">
                        <label class="form-label">编号</label>
                        <input type="text" class="form-control" name="id" value="{{ Request::query('id') }}" placeholder="用户编号" />
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
                    <th>编号</th>
                    <th>状态</th>
                    <th>名称</th>
                    <th>用户名</th>
                    <th>试用到期时间</th>
                    <th>会员到期时间</th>
                    <th>注册时间</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>
                            <div>
                                @if ($item->status == 1)
                                    <span class="badge bg-success me-1">正常</span>
                                @else
                                    <span class="badge bg-secondary me-1">冻结</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div>{{ $item->name }}</div>
                        </td>
                        <td>
                            <div>{{ $item->username }}</div>
                        </td>
                        <td>
                            @if (strtotime($item->trial_at) > time())
                                <div class="text-green">{{ $item->trial_at }}</div>
                            @else
                                <div class="text-muted">{{ $item->trial_at }}</div>
                            @endif
                        </td>
                        <td>
                            @if (strtotime($item->vip_at) > time())
                                <div class="text-green">{{ $item->vip_at }}</div>
                            @else
                                <div class="text-muted">{{ $item->vip_at }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="">{{ $item->created_at }}</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="20">{{ $users->links() }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
</x-layouts.admin>
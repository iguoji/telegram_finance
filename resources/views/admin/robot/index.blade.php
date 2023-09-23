<x-layouts.admin title="机器人">
    <x-slot:options>
        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"data-bs-target="#modal-create">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
            添加新机器人
        </a>
    </x-slot:options>

    <x-modals.simple id="modal-create" title="添加新的机器人">
        <div class="mb-3">
            <label class="form-label">Token</label>
            <input type="text" class="form-control form-param" name="token" placeholder="请输入机器人密钥" required />
        </div>
    </x-modals.simple>
    
    
    <div class="card">
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                    <tr>
                        <th>名称</th>
                        <th>状态</th>
                        <th>Token</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($robots as $item)
                        <tr>
                            <td>
                                <div>{{ $item->first_name . ($item->last_name ?? '') }}</div>
                                <div>
                                    <a href="https://t.me/{{ $item->username }}" target="_blank">{{ '@' . $item->username }}</a>
                                </div>
                            </td>
                            <td>
                                @if ($item->status)
                                    <span class="badge bg-success me-1"></span>
                                    可用
                                @else
                                    <span class="badge bg-secondary me-1"></span>
                                    停用
                                @endif
                            </td>
                            <td>{{ $item->token }}</td>
                            <td class="text-end">
                                <button class="btn btn-sm ms-1" data-bs-toggle="fast-ajax" data-bs-target="{{ route('admin.robot.refresh', [$item->rid]) }}">刷新</button>
                                @if ($item->status)
                                    <button class="btn btn-sm btn-cyan ms-1" data-bs-toggle="fast-ajax" data-bs-target="{{ route('admin.robot.update', [$item->rid]) }}" data-bs-method="PUT" data-bs-key="status" data-bs-value="0">停用</button>
                                @else
                                    <button class="btn btn-sm btn-green ms-1" data-bs-toggle="fast-ajax" data-bs-target="{{ route('admin.robot.update', [$item->rid]) }}" data-bs-method="PUT" data-bs-key="status" data-bs-value="1">启用</button>
                                @endif
                                <button class="btn btn-sm ms-1 btn-danger" data-bs-toggle="fast-ajax" data-bs-target="{{ route('admin.robot.destroy', [$item->rid]) }}" data-bs-method="DELETE">删除</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>


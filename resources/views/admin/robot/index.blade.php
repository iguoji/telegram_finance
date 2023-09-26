<x-layouts.admin title="机器人">

    <x-slot:options>
        <a href="#" class="btn btn-primary" data-bs-toggle="modal"data-bs-target="#modal-create">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
            添加新机器人
        </a>
    </x-slot:options>
    
    <div class="row">
        @foreach ($robots as $item)
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <div>
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar">
                                        @if ($item->user->status)
                                            <span class="status-dot status-dot-animated status-green"></span>
                                        @else
                                            <span class="status-dot"></span>
                                        @endif
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="card-title">{{ $item->user->name }}</div>
                                    <div class="card-subtitle"><a class="small" href="https://t.me/{{ $item->user->username }}" target="_blank">{{ '@' . $item->user->username }}</a></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-actions">
                            @if ($item->user->status)
                                <a href="#" class="btn-action" data-bs-toggle="fast-ajax" data-bs-target="{{ route('admin.robot.update', [$item->id]) }}" data-bs-method="PUT" data-bs-key="status" data-bs-value="0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler text-danger icon-tabler-player-stop-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M17 4h-10a3 3 0 0 0 -3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3 -3v-10a3 3 0 0 0 -3 -3z" stroke-width="0" fill="currentColor"></path>
                                     </svg>
                                </a>
                            @else
                                <a href="#" class="btn-action btn-green" data-bs-toggle="fast-ajax" data-bs-target="{{ route('admin.robot.update', [$item->id]) }}" data-bs-method="PUT" data-bs-key="status" data-bs-value="1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler text-green icon-tabler-player-play-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M6 4v16a1 1 0 0 0 1.524 .852l13 -8a1 1 0 0 0 0 -1.704l-13 -8a1 1 0 0 0 -1.524 .852z" stroke-width="0" fill="currentColor"></path>
                                     </svg>
                                </a>
                            @endif
                            <a href="#" class="btn-action dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="19" r="1"></circle><circle cx="12" cy="5" r="1"></circle></svg>
                            </a>
                            <div class="dropdown">
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Edit user</a>
                                    <a class="dropdown-item" href="#">Change permissions</a>
                                    <a class="dropdown-item text-danger" href="#">Delete user</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="text-black-50 user-select-none">基本信息</h4>
                                <div class="text-muted">
                                    {{-- 无法更改，Telegram未开放接口 --}}
                                    {{-- <a href="#" data-bs-toggle="modal" data-bs-target="#modal-setChatPhoto" data-bs-action="{{ route('admin.robot.setChatPhoto', [$item->id]) }}">头像</a> --}}
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modal-setMyName" data-bs-action="{{ route('admin.robot.setMyName', [$item->id]) }}" data-bs-fill="{{ Js::encode(['name' => $item->user->name]) }}">名称</a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modal-setMyDescription" data-bs-action="{{ route('admin.robot.setMyDescription', [$item->id]) }}" data-bs-fill="{{ Js::encode(['description' => $item->user->description]) }}">描述</a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modal-setMyShortDescription" data-bs-action="{{ route('admin.robot.setMyShortDescription', [$item->id]) }}" data-bs-fill="{{ Js::encode(['short_description' => $item->user->short_description]) }}">简单描述</a>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-3">
                                <h4 class="text-black-50 user-select-none">价格</h4>
                                <div class="text-muted">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modal-tiralDuration" data-bs-action="{{ route('admin.robot.update', [$item->id]) }}" data-bs-fill="{{ Js::encode(['trial_duration' => $item->trial_duration]) }}">可试用: {{ $item->trial_duration }} 秒</a>
                                    <a href="#" class="btn-prices" data-url="{{ route('admin.robot.price.index', [$item->id]) }}" data-seturl="{{ route('admin.robot.price.store', [$item->id]) }}">定价</a>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <h4 class="text-black-50 user-select-none">Commands</h4>
                                <div>
                                    <a href="#" class="btn-getMyCommands" data-url="{{ route('admin.robot.getMyCommands', [$item->id]) }}" data-seturl="{{ route('admin.robot.setMyCommands', [$item->id]) }}">查看</a>
                                    <a href="#" data-bs-toggle="fast-ajax" data-bs-target="{{ route('admin.robot.deleteMyCommands', [$item->id]) }}" data-bs-method="delete">清除</a>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <h4 class="text-black-50 user-select-none">Webhook</h4>
                                <div>
                                    <a href="#" class="btn-getWebhookInfo" data-url="{{ route('admin.robot.getWebhookInfo', [$item->id]) }}" data-seturl="{{ route('admin.robot.setWebhook', [$item->id]) }}">查看</a>
                                    <a href="#" data-bs-toggle="fast-ajax" data-bs-target="{{ route('admin.robot.deleteWebhook', [$item->id]) }}" data-bs-method="delete">清除</a>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <h4 class="text-black-50 user-select-none">私聊</h4>
                                <div>
                                    <a href="#" class="btn-keyboard" data-callbacks="{{ Js::encode($item->private_keyboard) }}" data-url="{{ route('admin.robot.update', [$item->id]) }}" data-field="private_keyboard">键盘</a>
                                    <a href="#" class="btn-callbacks" data-callbacks="{{ Js::encode($item->private) }}" data-url="{{ route('admin.robot.update', [$item->id]) }}" data-field="private">回调</a>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <h4 class="text-black-50 user-select-none">群聊回调</h4>
                                <div>
                                    <a href="#" class="btn-callbacks" data-callbacks="{{ Js::encode($item->group_default) }}" data-url="{{ route('admin.robot.update', [$item->id]) }}" data-field="group_default">普通人</a>
                                    <a href="#" class="btn-callbacks" data-callbacks="{{ Js::encode($item->group_operator) }}" data-url="{{ route('admin.robot.update', [$item->id]) }}" data-field="group_operator">操作员</a>
                                    <a href="#" class="btn-callbacks" data-callbacks="{{ Js::encode($item->group_administrator) }}" data-url="{{ route('admin.robot.update', [$item->id]) }}" data-field="group_administrator">管理员</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer small">
                        <div class="input-icon">
                            <input type="text" class="form-control disabled" value="{{ $item->token }}" data-bs-toggle="copy" />
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-copy" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M8 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z"></path>
                                    <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"></path>
                                 </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <x-modals.simple id="modal-create" title="添加新的机器人" enctype="multipart/form-data">
        <div>
            <label class="form-label">Token</label>
            <input type="text" class="form-control form-param" name="token" placeholder="请输入机器人密钥" required />
        </div>
    </x-modals.simple>

    <x-modals.simple id="modal-setChatPhoto" title="设置头像">
        <div>
            <div class="form-label">选择一张图片</div>
            <input type="file" class="form-control" name="photo" />
          </div>
    </x-modals.simple>

    <x-modals.simple id="modal-setMyName" title="设置名称">
        <div>
            <label class="form-label">名称</label>
            <input type="text" class="form-control form-param" name="name" placeholder="请输入新的名称" required />
        </div>
    </x-modals.simple>

    <x-modals.simple id="modal-setMyDescription" title="设置描述">
        <div>
            <textarea rows="5" class="form-control form-param" name="description" placeholder="请输入描述"></textarea>
        </div>
    </x-modals.simple>

    <x-modals.simple id="modal-setMyShortDescription" title="设置简单描述">
        <div>
            <textarea rows="5" class="form-control form-param" name="short_description" placeholder="请输入简单的描述"></textarea>
        </div>
    </x-modals.simple>

    <x-modals.simple id="modal-tiralDuration" title="可试用时长" method="PUT">
        <div>
            <div class="input-group">
                <input type="text" class="form-control form-param" name="trial_duration" placeholder="0" required />
                <span class="input-group-text">秒</span>
            </div>
        </div>
    </x-modals.simple>

    <x-modals.simple id="modal-prices" title="产品定价" large="true">
        <table class="table">
            <thead>
                <tr>
                    <th class="w-50">类型/地址</th>
                    <th class="w-50">文本/回调</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0;$i < 4;$i++)
                <tr>
                    <td>
                        <div>
                            <div class="input-group">
                                <select class="form-select form-param" name="data[{{ $i }}][type]" style="width: 50px">
                                    <option value="TRC20">TRC20</option>
                                    <option value="ERC20">ERC20</option>
                                </select>
                                <input type="text" class="form-control form-param" name="data[{{ $i }}][number]" placeholder="0" />
                                <span class="input-group-text">U</span>
                            </div>
                        </div>
                        <div>
                            <input type="text" class="form-control form-param border-top-0" name="data[{{ $i }}][address]" placeholder="地址" />
                        </div>
                    </td>
                    <td>
                        <div>
                            <input type="text" class="form-control form-param" name="data[{{ $i }}][label]" placeholder="文本" />
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="input-group">
                                    <input type="text" class="form-control form-param" name="data[{{ $i }}][year]" placeholder="0"  />
                                    <span class="input-group-text">年</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group">
                                    <input type="text" class="form-control form-param" name="data[{{ $i }}][month]" placeholder="0"  />
                                    <span class="input-group-text">月</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group">
                                    <input type="text" class="form-control form-param" name="data[{{ $i }}][day]" placeholder="0"  />
                                    <span class="input-group-text">日</span>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>
    </x-modals.simple>

    <x-modals.simple id="modal-getMyCommands" title="指令详情">
        <div>
            <textarea rows="10" class="form-control form-param" name="commands" placeholder='[{"command":"指令名称","description":"详细描述"},...]'></textarea>
        </div>
    </x-modals.simple>

    <x-modals.simple id="modal-getWebhookInfo" title="Webhook详情">
        <div>
            <input type="text" list="hooks" class="form-control form-param" name="url" placeholder="url" />
            <datalist id="hooks">
                @foreach (config('telegram.hooks') ?? [] as $hook)
                <option value="{{ $hook }}"></option>
                @endforeach
            </datalist>
        </div>
    </x-modals.simple>

    <x-modals.simple id="modal-keyboard" title="键盘设置" method="PUT">
        <input type="hidden" name="field" />
        <div class="input-group mb-3">
            <select class="form-select">
                @foreach (config('telegram.callbacks') ?? [] as $callKey => $callback)
                <option value="{{ $callKey }}">{{ $callKey }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-keyboard-add">添加</button>
        </div>
        <textarea class="form-control" name="callbacks" rows="10"></textarea>
    </x-modals.simple>

    <x-modals.simple id="modal-callbacks" title="回调列表" method="PUT">
        <input type="hidden" name="field" />
        <div class="form-selectgroup">
            @foreach (config('telegram.callbacks') ?? [] as $callKey => $callback)
                <label class="form-selectgroup-item">
                    <input type="checkbox" name="callbacks[]" value="{{ $callKey }}" class="form-selectgroup-input" />
                    <span class="form-selectgroup-label">{{ $callKey }}</span>
                </label>
            @endforeach
        </div>
    </x-modals.simple>

    <x-slot:script>
        <script>
            let split = '###___###';

            $(function(){
                // 查看指令模态框
                var getMyCommandsModal = new bootstrap.Modal(document.getElementById('modal-getMyCommands'));
                // 查看Webhook模态框
                var getWebhookInfoModal = new bootstrap.Modal(document.getElementById('modal-getWebhookInfo'));
                // 键盘模态框
                var keyboardModal = new bootstrap.Modal(document.getElementById('modal-keyboard'));
                // 回调模态框
                var callbackModal = new bootstrap.Modal(document.getElementById('modal-callbacks'));
                // 定价模态框
                var priceModal = new bootstrap.Modal(document.getElementById('modal-prices'));


                // 查看定价
                $('.btn-prices').on('click', function(){
                    $btn = $(this);
                    let url = $btn.data('url');
                    let seturl = $btn.data('seturl');

                    // 查询定价
                    ajax({
                        method: 'get',
                        url: url,
                        successCallback: function(res){
                            // 整理数据
                            if (res.data) {
                                for (let i = 0; i < res.data.length; i++) {
                                    const price = res.data[i];
                                    $('#modal-prices [name="data[' + i + '][type]"]').val(price.type);
                                    $('#modal-prices [name="data[' + i + '][number]"]').val(price.number);
                                    $('#modal-prices [name="data[' + i + '][address]"]').val(price.address);
                                    $('#modal-prices [name="data[' + i + '][label]"]').val(price.label);
                                    $('#modal-prices [name="data[' + i + '][callback]"]').val(price.callback);
                                    $('#modal-prices [name="data[' + i + '][year]"]').val(price.year);
                                    $('#modal-prices [name="data[' + i + '][month]"]').val(price.month);
                                    $('#modal-prices [name="data[' + i + '][day]"]').val(price.day);
                                }
                            }
                            // 显示模态框
                            $('#modal-prices').parents('form').attr('action', seturl);
                            priceModal.show();
                        }
                    }, $btn);
                });
                // 查看指令
                $('.btn-getMyCommands').on('click', function(){
                    $btn = $(this);
                    ajax({
                        url: $btn.data('url'),
                        successCallback: function(res){
                            $('#modal-getMyCommands textarea').val(JSON.stringify(res.data));
                            $('#modal-getMyCommands').parents('form').attr('action', $btn.data('seturl'));
                            getMyCommandsModal.show();
                        }
                    }, $btn);
                });
                // 查看Webhook
                $('.btn-getWebhookInfo').on('click', function(){
                    $btn = $(this);
                    ajax({
                        url: $btn.data('url'),
                        successCallback: function(res){
                            $('#modal-getWebhookInfo input').val(res.data.url);
                            $('#modal-getWebhookInfo').parents('form').attr('action', $btn.data('seturl'));
                            getWebhookInfoModal.show();
                        }
                    }, $btn);
                });
                // 查看键盘
                $('.btn-keyboard').on('click', function(){
                    $btn = $(this);

                    // 获取参数
                    let url = $btn.data('url');
                    let callbacks = $btn.data('callbacks');
                    let field = $btn.data('field');

                    // 模态框设置
                    $('#modal-keyboard').parents('form').attr('action', url);
                    $('#modal-keyboard input[name="field"]').val(field);

                    // 整理回调
                    console.log(callbacks);
                    if (!callbacks || callbacks == 'null') {
                        callbacks = [];
                    }
                    let val = '';
                    for (let i = 0; i < callbacks.length; i++) {
                        for (let j = 0; j < callbacks[i].length; j++) {
                            val += callbacks[i][j].text + split;
                        }
                        val += "\n";
                    }
                    $('#modal-keyboard textarea').val(val);
                    
                    // 显示模态框
                    keyboardModal.show();
                });
                // 键盘添加
                $('.btn-keyboard-add').on('click', function(){
                    $('#modal-keyboard textarea').val($('#modal-keyboard textarea').val() + split + $('#modal-keyboard select').val());
                });
                // 查看回调
                $('.btn-callbacks').on('click', function(){
                    $btn = $(this);

                    // 获取参数
                    let url = $btn.data('url');
                    let callbacks = $btn.data('callbacks');
                    let field = $btn.data('field');

                    // 模态框设置
                    $('#modal-callbacks').parents('form').attr('action', url);
                    $('#modal-callbacks input[name="field"]').val(field);

                    // 整理回调
                    console.log(callbacks);
                    if (!callbacks || callbacks == 'null') {
                        callbacks = [];
                    }
                    $('#modal-callbacks .form-selectgroup-input').each(function(idx, ele){
                        $(ele).prop('checked', callbacks.indexOf($(ele).val()) != -1);
                    });
                    
                    // 显示模态框
                    callbackModal.show();
                });
            });
        </script>
    </x-slot:script>

</x-layouts.admin>

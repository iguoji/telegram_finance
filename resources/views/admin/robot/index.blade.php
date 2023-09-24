<x-layouts.admin title="机器人">

    <x-slot:options>
        <a href="#" class="btn btn-primary" data-bs-toggle="modal"data-bs-target="#modal-create">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
            添加新机器人
        </a>
    </x-slot:options>
    
    <div class="row">
        @foreach ($users as $item)
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <div>
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar" style="background-image: url(./static/avatars/000m.jpg)"></span>
                                </div>
                                <div class="col">
                                    <div class="card-title">{{ $item->name }}</div>
                                    <div class="card-subtitle"><a href="https://t.me/{{ $item->username }}" target="_blank">{{ '@' . $item->username }}</a></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-actions">
                            @if ($item->status)
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
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modal-setMyName" data-bs-action="{{ route('admin.robot.setMyName', [$item->id]) }}" title="{{ $item->name }}">名称</a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modal-setMyDescription" data-bs-action="{{ route('admin.robot.setMyDescription', [$item->id]) }}" title="{{ $item->description }}">描述</a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modal-setMyShortDescription" data-bs-action="{{ route('admin.robot.setMyShortDescription', [$item->id]) }}" title="{{ $item->short_description }}">简单描述</a>
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
            <textarea class="form-control form-param" name="description" placeholder="请输入描述"></textarea>
        </div>
    </x-modals.simple>

    <x-modals.simple id="modal-setMyShortDescription" title="设置简单描述">
        <div>
            <textarea class="form-control form-param" name="short_description" placeholder="请输入简单的描述"></textarea>
        </div>
    </x-modals.simple>

    <x-modals.simple id="modal-getMyCommands" title="指令详情">
        <div>
            <textarea rows="10" class="form-control form-param" name="commands" placeholder='[{"command":"指令名称","description":"详细描述"},...]'></textarea>
        </div>
    </x-modals.simple>

    <x-modals.simple id="modal-getWebhookInfo" title="Webhook详情">
        <div>
            <input type="text" class="form-control form-param" name="url" placeholder="url" />
        </div>
    </x-modals.simple>

    <x-slot:script>
        <script>
            $(function(){
                // 查看指令模态框
                var getMyCommandsModal = new bootstrap.Modal(document.getElementById('modal-getMyCommands'));
                // 查看Webhook模态框
                var getWebhookInfoModal = new bootstrap.Modal(document.getElementById('modal-getWebhookInfo'));

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
            });
        </script>
    </x-slot:script>

</x-layouts.admin>


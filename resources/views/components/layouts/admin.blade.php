<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ $title ?? '' }} - {{ config('app.name') }}</title>
    <!-- CSS files -->
    <link href="/assets/css/tabler.min.css?1668287865" rel="stylesheet" />
    <link href="/assets/css/tabler-vendors.min.css?1668287865" rel="stylesheet" />
    <link href="/assets/css/demo.min.css?1668287865" rel="stylesheet" />
    <link href="/assets/libs/toastr/toastr.min.css?1668287865" rel="stylesheet" />
</head>

<body>
    <script src="/assets/js/demo-theme.min.js?1668287865"></script>
    <div class="page">
        <!-- 顶部区域 -->
        <header class="navbar navbar-expand-md navbar-light d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                    aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- 站标名称 -->
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="{{ route('admin.index') }}">
                        <img src="/assets/img/logo.svg" width="110" height="32" alt="Tabler" class="navbar-brand-image">
                    </a>
                </h1>
                <!-- 用户信息 -->
                <div class="navbar-nav flex-row order-md-last">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                            <span class="avatar avatar-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path><path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"></path></svg>
                            </span>
                            <div class="d-xl-block ps-2">
                                <div>iGuoji</div>
                                <div class="mt-1 small text-muted">管理员</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="#" class="dropdown-item">修改密码</a>
                            <a href="#" class="dropdown-item">退出登录</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- 导航菜单 -->
        <div class="navbar-expand-md">
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="navbar navbar-light">
                    <div class="container-xl">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="./">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><polyline points="5 12 3 12 12 3 21 12 19 12" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                                    </span>
                                    <span class="nav-link-title">首页</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path><path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path></svg>
                                    </span>
                                    <span class="nav-link-title">用户</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users-group" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1"></path><path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M17 10h2a2 2 0 0 1 2 2v1"></path><path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M3 13v-1a2 2 0 0 1 2 -2h2"></path></svg>
                                    </span>
                                    <span class="nav-link-title">群组</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-cart" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                            <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                            <path d="M17 17h-11v-14h-2"></path>
                                            <path d="M6 5l14 1l-1 7h-13"></path>
                                         </svg>
                                    </span>
                                    <span class="nav-link-title">订单</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.robot.index') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-robot" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M7 7h10a2 2 0 0 1 2 2v1l1 1v3l-1 1v3a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-3l-1 -1v-3l1 -1v-1a2 2 0 0 1 2 -2z"></path><path d="M10 16h4"></path><circle cx="8.5" cy="11.5" r=".5" fill="currentColor"></circle><circle cx="15.5" cy="11.5" r=".5" fill="currentColor"></circle><path d="M9 7l-1 -4"></path><path d="M15 7l1 -4"></path></svg>
                                    </span>
                                    <span class="nav-link-title">机器人</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"></path><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path></svg>
                                    </span>
                                    <span class="nav-link-title">系统</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- 正文区域 -->
        <div class="page-wrapper">
            <!-- 正文页眉 -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="page-pretitle">Overview</div>
                            <h2 class="page-title">{{ $title ?? '' }}</h2>
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            <div class="btn-list">
                                {{ $options ?? '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 正文内容 -->
            <div class="page-body">
                <div class="container-xl">
                    {{ $slot ?? '' }}
                </div>
            </div>

            <!-- 正文底部 -->
            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">技术支持</li>
                                <li class="list-inline-item">
                                    <a href="https://t.me/iguoji" target="_blank" class="link-secondary" rel="noopener">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-telegram text-azure" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M15 10l-4 4l6 6l4 -16l-18 7l4 2l2 6l3 -4"></path></svg>
                                        @iGuoji
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    Copyright &copy; 2022
                                    <a href="{{ route('admin.index') }}" class="link-secondary">{{ config('app.name') }}</a>.
                                    All rights reserved.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!-- Libs JS -->
    <script src="/assets/libs/apexcharts/dist/apexcharts.min.js?1668287865" defer></script>
    <script src="/assets/libs/jsvectormap/dist/js/jsvectormap.min.js?1668287865" defer></script>
    <script src="/assets/libs/jsvectormap/dist/maps/world.js?1668287865" defer></script>
    <script src="/assets/libs/jsvectormap/dist/maps/world-merc.js?1668287865" defer></script>
    <!-- Tabler Core -->
    <script src="/assets/js/tabler.min.js?1668287865" defer></script>
    <script src="/assets/js/demo.min.js?1668287865" defer></script>
    <script src="/assets/libs/jquery/jquery-3.7.1.min.js?1668287865"></script>
    <script src="/assets/libs/toastr/toastr.min.js?1668287865"></script>
    <!-- Framework Script -->
    <script>
        // AJAX
        var ajax = function(options, $element){
            // 按钮禁用
            if ($element) {
                var isA = $element.tagName == 'A';
                if (isA) {
                    $element.addClass('disabled link-btn');
                } else {
                    $element.addClass('btn-loading');
                }
            }

            // 删除确认
            if (options.method == 'delete' || options.method == 'DELETE') {
                let result = confirm('确定要执行吗');
                if (!result && $element) {
                    if (isA) {
                        $btn.removeClass('disabled link-btn');
                    } else {
                        $btn.removeClass('btn-loading');
                    }
                    return false;
                }
            }

            // 参数整理
            if (!options.data) {
                options.data = new FormData();
            }
            if (!options.data.has('_method')) {
                options.data.append('_method', options.method ? options.method : 'POST');
            }
            if (!options.data.has('_token')) {
                options.data.append('_token', '{{ csrf_token() }}');
            }

            console.log(options);
            
            // 执行请求
            $.ajax({
                method: (options.method == 'get' || options.method == 'GET' ? 'GET' : 'POST'),
                url: options.url,
                data: options.data,
                processData: false,
                contentType: false,
                success: options.success ? options.success : function(res){
                    if (res) {
                        if (res.code == 200) {
                            if (options.successCallback) {
                                options.successCallback(res);
                            } else {
                                window.location.reload();
                            }
                        } else {
                            toastr.warning(res.message);
                        }
                    } else {
                        console.log('success', res);
                    }
                },
                error: options.error ? options.error : function(req, status, ex) {
                    if (req.status == 400|| req.status == 422 || req.status == 500) {
                        toastr.warning(req.responseJSON.message);
                    } else {
                        toastr.error(req.status + ' - ' + req.statusText);
                    }
                },
                complete: options.complete ? options.complete : function(){
                    if ($element) {
                        if (isA) {
                            $element.removeClass('disabled link-btn');
                        } else {
                            $element.removeClass('btn-loading');
                        }
                    }
                }
            });
        };

        document.addEventListener("DOMContentLoaded", function () {
            // 模态框
            $('[data-bs-toggle="modal"]').on('click', function(){
                let target = $(this).data('bs-target');
                let action = $(this).data('bs-action');
                console.log(target)
                console.log(action)
                if (action) {
                    $form = $(target).parents('form');
                    console.log($form)
                    if ($form && $form.length) {
                        $form.attr('action', action);
                    }
                }
            });
            // 快速请求
            $('[data-bs-toggle="fast-ajax"]').on('click', function(){
                // 获取参数
                $btn = $(this);
                let url = $btn.data('bs-target');
                let method = $btn.data('bs-method');
                method = method ? method : 'post';
                let key = $btn.data('bs-key');
                let value = $btn.data('bs-value');

                // 整理参数
                let data = new FormData();
                if (key) {
                    data.append(key, value);
                }

                // 执行请求
                ajax({
                    method: method,
                    url: url,
                    data: data,
                }, $btn);
            });

            // 复制内容
            $('[data-bs-toggle="copy"]').on('click', function(){
                navigator.clipboard.writeText($(this).val()).then(() => {
                    $(this).select();
                }).catch(() => {
                    console.log('err');
                });
            });
        });
    </script>
    <!-- User Script -->
    {{ $script ?? '' }}
</body>
</html>
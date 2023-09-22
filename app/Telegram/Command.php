<?php

namespace App\Telegram;


/**
 * 命令类
 */
abstract class Command extends Callback
{
    /**
     * 名称
     */
    public $name = 'start';

    /**
     * 描述
     */
    public $description = 'Start command';

    /**
     * 命令
     */
    public $usage = '/start';

    /**
     * 版本
     */
    public $version = '1.0.0';

    /**
     * 是否需要注册
     */
    public $is_registered = true;
}
